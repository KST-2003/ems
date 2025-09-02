<?php

namespace App\Http\Controllers;

use App\Models\EmployeeLeave;
use App\Models\LeaveType;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Log;
use App\Models\Employee;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EmployeeLeavesExport;
use Str;
use PDF;

class LeaveController extends Controller
{
    public function index()
    {
        $departments = Employee::select('department')->distinct()->pluck('department');
        $leaveTypes = LeaveType::all();

        return view('leaves.index', compact('departments', 'leaveTypes'));
    }

    public function create()
    {
        $leaveTypes = LeaveType::all();
        $employees = Employee::all();
        return view('leaves.create', compact('leaveTypes', 'employees'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateRequest($request);
        EmployeeLeave::create($validated);

        return redirect()->route('leaves.index')->with('success', __('messages.leave_created'));
    }

    public function employeeLeaves(Employee $employee)
    {
        $leaveTypes = LeaveType::all();
        return view('leaves.show', compact('employee', 'leaveTypes'));
    }

    public function show(EmployeeLeave $leave)
    {
        $leave->load('employee', 'leaveType');
        return view('leaves.view', compact('leave'));
    }

    public function edit($id)
    {
        $leave = EmployeeLeave::with(['employee', 'leaveType'])->find($id);

        if (!$leave) {
            return redirect()->route('leaves.index')->with('error', 'Leave record not found for ID: ' . $id);
        }

        if (!$leave->employee) {
            return redirect()->route('leaves.index')->with('error', 'Associated employee not found for leave ID: ' . $id);
        }

        $leaveTypes = LeaveType::all();
        return view('leaves.edit', compact('leave', 'leaveTypes'));
    }

    public function update(Request $request, $id)
    {
        $leave = EmployeeLeave::with('employee')->find($id);

        if (!$leave) {
            return redirect()->route('leaves.index')->with('error', 'Leave record not found for ID: ' . $id);
        }

        $validated = $this->validateRequest($request);
        $leave->update($validated);

        if (!$leave->employee_id || !$leave->employee) {
            return redirect()->route('leaves.index')->with('error', 'Associated employee not found for leave ID: ' . $id);
        }

        return redirect()->route('employees.leaves', $leave->employee_id)
            ->with('success', __('messages.leave_updated'));
    }

    public function destroy($id)
    {
        try {
            $leave = EmployeeLeave::find($id);

            if (!$leave) {
                Log::error('Leave record not found for ID: ' . $id);
                return response()->json(['success' => false, 'message' => 'Leave record not found'], 404);
            }

            Log::info('Attempting to delete leave ID: ' . $leave->id, [
                'employee_id' => $leave->employee_id,
                'leave_type_id' => $leave->leave_type_id,
            ]);

            $leave->delete();

            Log::info('Successfully deleted leave ID: ' . $leave->id);
            return response()->json(['success' => true, 'message' => 'Leave deleted successfully']);
        } catch (\Exception $e) {
            Log::error('Failed to delete leave ID: ' . $id . ' - Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['success' => false, 'message' => 'Failed to delete leave: ' . $e->getMessage()], 500);
        }
    }

    public function list(Request $request)
    {
        $employees = Employee::with(['leaves.leaveType']);

        return DataTables::of($employees)
            ->addIndexColumn()
            ->addColumn('department', fn($row) => $row->department)
            ->addColumn('phone', fn($row) => $row->phone)
            ->addColumn('number_of_leaves', function ($row) {
                return $row->leaves->sum(function ($leave) {
                    return $leave->start_date->diffInDays($leave->end_date) + 1;
                });
            })
            ->addColumn('leave_types', function ($row) {
                return $row->leaves->pluck('leaveType.name')->unique()->implode(', ');
            })
            ->addColumn('action', function ($row) {
                $view = route('employees.leaves', $row->id);
                return '<a href="' . $view . '" class="btn btn-sm btn-info">' . __('messages.view') . '</a>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function filterByType(Request $request, Employee $employee)
    {
        $typeId = $request->get('leave_type_id');
        $leaves = $employee->leaves()->with('leaveType');

        if ($typeId) {
            $leaves->where('leave_type_id', $typeId);
        }

        return DataTables::of($leaves)
            ->addIndexColumn()
            ->addColumn('duration', fn($leave) => $leave->start_date->diffInDays($leave->end_date) + 1)
            ->editColumn('start_date', fn($leave) => $leave->start_date->format('d-m-Y'))
            ->editColumn('end_date', fn($leave) => $leave->end_date->format('d-m-Y'))
            ->editColumn('leaveType.name', fn($leave) => $leave->leaveType->name ?? '-')
            ->addColumn('status', function ($leave) {
                if ($leave->status === 'Pending') {
                    return '<span class="badge bg-warning text-dark">Pending</span>';
                } elseif ($leave->status === 'Approved') {
                    return '<span class="badge bg-success">Approved</span>';
                } elseif ($leave->status === 'Rejected') {
                    return '<span class="badge bg-danger">Rejected</span>';
                }
                return '<span class="badge bg-secondary">-</span>';
            })
            ->addColumn('action', function ($leave) {
                $edit = route('leaves.edit', $leave->id);
                return '
                    <a href="' . $edit . '" class="btn btn-sm btn-warning">'. __('messages.edit') . '</a>
                    <button class="btn btn-sm btn-danger delete-leave" data-id="' . $leave->id . '">'. __('messages.delete') .'   </button>
                ';
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }

    private function validateRequest(Request $request)
    {
        return $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string',
            'status' => 'required|in:Pending,Approved,Rejected',
        ]);
    }

    public function exportExcel(Employee $employee)
    {
        return Excel::download(new EmployeeLeavesExport($employee), 'leaves.xlsx');
    }

    public function exportPdf(Employee $employee)
    {
        try {
            $leaves = $employee->leaves()->with('leaveType')->get();
            // This line is from your old Dompdf setup; you can remove it.
            // \Log::info('Font file check: ' . (file_exists(storage_path('fonts/NotoSerifMyanmar-Regular.ttf')) ? 'Font found' : 'Font missing'));
            
            // This line is from your old Dompdf setup; you can remove it.
            // \Log::info('DOMPDF Font Config: ' . json_encode(config('dompdf.options.font_data')));

            // Load view with the mPDF facade
            $pdf = PDF::loadView('leaves.pdf', compact('employee', 'leaves'));
            
            $employeeName = Str::slug($employee->name);
            $date = now()->format('d-m-Y');
            $filename = "{$employeeName}_leaves_{$date}.pdf";
            
            return $pdf->download($filename);
        } catch (\Exception $e) {
            \Log::error('PDF Export Error: ' . $e->getMessage(), [
                'employee_id' => $employee->id,
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()->with('error', 'Failed to generate PDF: ' . $e->getMessage());
        }
    }
}