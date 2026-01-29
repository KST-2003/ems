<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeLeave;
use App\Models\LeaveType;
use App\Models\EmployeeLeaveAllocation;
use App\Models\AttendanceMonthlyRollup;
use App\Models\CompanyCalendar; // Added
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class LeaveController extends Controller
{
   public function allocationIndex()
    {
        // Select both columns to ensure the view has access to department and department_place
        $employees = Employee::select('id', 'name', 'employee_id', 'department', 'department_place')->get();
        $leaveTypes = LeaveType::all();
        return view('leaves.allocation', compact('employees', 'leaveTypes'));
    }

    public function allocationStore(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'allocations' => 'required|array',
        ]);

        $year = now()->year;

        foreach ($request->allocations as $leaveTypeId => $data) {
            if (isset($data['enabled'])) {
                EmployeeLeaveAllocation::updateOrCreate(
                    ['employee_id' => $request->employee_id, 'leave_type_id' => $leaveTypeId, 'year' => $year],
                    ['max_allowed_days' => $data['max_days']]
                );
            }
        }

        return redirect()->back()->with('success', 'Leave entitlements allocated successfully.');
    }

    public function index()
    {
        // Use 'department' for the high-level filter dropdown
        $departments = Employee::distinct()->pluck('department');
        $leaveTypes = LeaveType::all();
        // Ensure employees object has both properties
        $employees = Employee::select('id', 'name', 'department', 'department_place')->get();
        return view('leaves.index', compact('departments', 'leaveTypes', 'employees'));
    }

    public function datatable(Request $request)
    {
        try {
            $leaves = EmployeeLeave::query()
                ->select([
                    'employee_leaves.*',
                    'employees.name as employee_name',
                    'employees.department as employee_department',
                    'employees.department_place as employee_department_place', // Added this
                    'leave_types.name as leave_type_name',
                    'leave_types.default_days as default_days'
                ])
                ->leftJoin('employees', 'employee_leaves.employee_id', '=', 'employees.id')
                ->leftJoin('leave_types', 'employee_leaves.leave_type_id', '=', 'leave_types.id');

            // Filter logic remains the same (using department)
            if ($request->filled('department')) {
                $leaves->where('employees.department', $request->department);
            }

            return DataTables::of($leaves)
                ->addIndexColumn()
                ->editColumn('employee_name', function($row) {
                    $url = route('employees.show', $row->employee_id);
                    return '<a href="'.$url.'" class="fw-bold text-primary">'.$row->employee_name.'</a>';
                })
                // Use ?? to prevent "property on null" crash in the duration columns
                ->addColumn('department_info', function($row) {
                    return ($row->employee_department ?? 'N/A') . ' (' . ($row->employee_department_place ?? '-') . ')';
                })
                ->editColumn('start_date', fn($row) => $row->start_date ? Carbon::parse($row->start_date)->format('d-m-Y') : '-')
                ->editColumn('end_date', fn($row) => $row->end_date ? Carbon::parse($row->end_date)->format('d-m-Y') : '-')
                ->addColumn('duration', fn($row) => $row->total_days . ' days')
                ->addColumn('remaining_days', function ($row) {
                    $year = Carbon::parse($row->start_date)->year;
                    $allocation = EmployeeLeaveAllocation::where('employee_id', $row->employee_id)
                        ->where('leave_type_id', $row->leave_type_id)->where('year', $year)->first();

                    $maxDays = $allocation ? $allocation->max_allowed_days : ($row->default_days ?? 0);
                    $usedDays = EmployeeLeave::where('employee_id', $row->employee_id)
                        ->where('leave_type_id', $row->leave_type_id)->where('status', 'done')
                        ->whereYear('start_date', $year)->sum('total_days');

                    return "$usedDays / $maxDays";
                })
                ->addColumn('actions', fn($row) => view('leaves.partials.actions', compact('row'))->render())
                ->rawColumns(['employee_name', 'remaining_days', 'actions'])
                ->make(true);
        } catch (\Exception $e) {
            Log::error('DataTables error: ' . $e->getMessage());
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    public function create()
    {
        $employees = Employee::select('id', 'name', 'employee_id')->get();
        $leaveTypes = LeaveType::all();
        return view('leaves.create', compact('employees', 'leaveTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'reason' => 'nullable|string|max:1000',
            'status' => 'required|in:ongoing,done',
        ]);

        $leaveType = LeaveType::findOrFail($validated['leave_type_id']);
        $totalDays = $this->calculateTotalDays($validated['start_date'], $validated['end_date'], $leaveType);

        $leave = EmployeeLeave::create(array_merge($validated, ['total_days' => $totalDays]));
        
        if ($validated['status'] === 'done') {
            $this->updateRollup($validated['employee_id'], Carbon::parse($validated['start_date'])->format('Y-m'));
        }

        return redirect()->route('leaves.index')->with('success', 'Leave recorded: ' . $totalDays . ' days.');
    }

    public function update(Request $request, EmployeeLeave $leave)
    {
        $validated = $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'reason' => 'nullable|string|max:1000',
            'status' => 'required|in:ongoing,done',
        ]);

        $leaveType = LeaveType::findOrFail($validated['leave_type_id']);
        $totalDays = $this->calculateTotalDays($validated['start_date'], $validated['end_date'], $leaveType);

        $leave->update(array_merge($validated, ['total_days' => $totalDays]));
        $this->updateRollup($leave->employee_id, Carbon::parse($leave->start_date)->format('Y-m'));

        return redirect()->route('leaves.index')->with('success', 'Leave updated.');
    }

    /**
     * Enhanced helper to determine duration based on Company Calendar Exceptions.
     */
    private function calculateTotalDays($start, $end, $leaveType)
    {
        $startDate = Carbon::parse($start);
        $endDate = $end ? Carbon::parse($end) : $startDate;
        $total = 0;

        for ($date = $startDate->copy(); $date->lte($endDate); $date->addDay()) {
            $calendar = CompanyCalendar::where('date', $date->toDateString())->first();

            // 1. If date is in Calendar Exceptions
            if ($calendar) {
                if ($calendar->type === 'open_exception') {
                    $total++; // Forced work day
                }
                // holidays and close_exceptions are skipped
                continue; 
            }

            // 2. Default Weekend Logic
            if ($leaveType->sandwich_rule) {
                $total++; // Count everything
            } else {
                if (!$date->isWeekend()) {
                    $total++; // Count work days only
                }
            }
        }
        return $total;
    }

    public function destroy(EmployeeLeave $leave)
    {
        $empId = $leave->employee_id;
        $month = Carbon::parse($leave->start_date)->format('Y-m');
        $leave->delete();
        $this->updateRollup($empId, $month);
        return redirect()->route('leaves.index')->with('success', 'Leave deleted.');
    }

    private function updateRollup($employeeId, $month)
    {
        if (!$employeeId) return;
        $absentDays = EmployeeLeave::where('employee_id', $employeeId)->where('status', 'done')
            ->where('start_date', 'like', "$month%")->sum('total_days');

        AttendanceMonthlyRollup::updateOrCreate(
            ['employee_id' => $employeeId, 'month' => $month],
            ['total_absent_days' => $absentDays]
        );
    }

    public function calendarEvents(Request $request)
    {
        $query = EmployeeLeave::with(['leaveType', 'employee'])
            ->whereBetween('start_date', [$request->start, $request->end]);

        if ($request->filled('department')) {
            $query->whereHas('employee', fn($q) => $q->where('department', $request->department));
        }

        return response()->json($query->get()->map(fn($leave) => [
            'id' => $leave->id,
            'title' => $leave->employee->name,
            'start' => $leave->start_date,
            'end' => $leave->end_date ? Carbon::parse($leave->end_date)->addDay()->toDateString() : null,
            'color' => $leave->leaveType->color ?? '#3788d8',
            'extendedProps' => ['reason' => $leave->reason]
        ]));
    }

   public function rollupReport(Request $request)
    {
        $month = $request->input('month', now()->format('Y-m'));
        // Fixed: Pluck from 'department' instead of 'department_place' for standard reporting
        $departments = Employee::distinct()->pluck('department'); 
        
        $reports = AttendanceMonthlyRollup::with('employee')
            ->where('month', $month)
            ->when($request->filled('department'), function($q) use ($request) {
                $q->whereHas('employee', function($sq) use ($request) {
                    $sq->where('department', $request->department);
                });
            })
            ->get();

        return view('leaves.reports', compact('reports', 'month', 'departments'));
    } 
}