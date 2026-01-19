<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeLeave;
use App\Models\LeaveType;
use App\Models\EmployeeLeaveAllocation;
use App\Models\AttendanceMonthlyRollup;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class LeaveController extends Controller
{
    /**
     * Display the leave allocation management page.
     */
    public function allocationIndex()
    {
        $employees = Employee::select('id', 'name', 'employee_id', 'department')->get();
        $leaveTypes = LeaveType::all();
        return view('leaves.allocation', compact('employees', 'leaveTypes'));
    }

    /**
     * Store or update leave allocations for an employee.
     */
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
                    [
                        'employee_id' => $request->employee_id,
                        'leave_type_id' => $leaveTypeId,
                        'year' => $year
                    ],
                    ['max_allowed_days' => $data['max_days']]
                );
            }
        }

        return redirect()->back()->with('success', 'Leave entitlements allocated successfully.');
    }

    /**
     * Display the main leaves index.
     */
    public function index()
    {
        $departments = Employee::select('department')->distinct()->pluck('department');
        $leaveTypes = LeaveType::all();
        $employees = Employee::select('id', 'name', 'department')->get();
        return view('leaves.index', compact('departments', 'leaveTypes', 'employees'));
    }

    /**
     * Server-side DataTables logic.
     */
    public function datatable(Request $request)
    {
        try {
            $leaves = EmployeeLeave::query()
                ->select([
                    'employee_leaves.*',
                    'employees.name as employee_name',
                    'employees.department as employee_department',
                    'leave_types.name as leave_type_name',
                    'leave_types.default_days as default_days'
                ])
                ->leftJoin('employees', 'employee_leaves.employee_id', '=', 'employees.id')
                ->leftJoin('leave_types', 'employee_leaves.leave_type_id', '=', 'leave_types.id');

            if ($request->filled('department')) {
                $leaves->where('employees.department', $request->department);
            }

            if ($request->filled('leave_type_id')) {
                $leaves->where('employee_leaves.leave_type_id', $request->leave_type_id);
            }

            return DataTables::of($leaves)
                ->addIndexColumn()
                ->editColumn('employee_name', function($row) {
                    $url = route('employees.show', $row->employee_id);
                    return '<a href="'.$url.'" class="fw-bold text-primary">'.$row->employee_name.'</a>';
                })
                ->editColumn('start_date', fn($row) => $row->start_date ? Carbon::parse($row->start_date)->format('d-m-Y') : '-')
                ->editColumn('end_date', fn($row) => $row->end_date ? Carbon::parse($row->end_date)->format('d-m-Y') : '-')
                ->addColumn('duration', fn($row) => $row->total_days . ' days')
                ->addColumn('remaining_days', function ($row) {
                    $year = Carbon::parse($row->start_date)->year;
                    
                    $allocation = EmployeeLeaveAllocation::where('employee_id', $row->employee_id)
                        ->where('leave_type_id', $row->leave_type_id)
                        ->where('year', $year)
                        ->first();

                    $maxDays = $allocation ? $allocation->max_allowed_days : ($row->default_days ?? 0);
                    
                    // Sum used days ONLY for the specific year of the record and ONLY if status is 'done'
                    $usedDays = EmployeeLeave::where('employee_id', $row->employee_id)
                        ->where('leave_type_id', $row->leave_type_id)
                        ->where('status', 'done')
                        ->whereYear('start_date', $year)
                        ->sum('total_days');

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

    /**
     * Show form to create new leave.
     */
    public function create()
    {
        $employees = Employee::select('id', 'name', 'employee_id')->get();
        $leaveTypes = LeaveType::all();
        return view('leaves.create', compact('employees', 'leaveTypes'));
    }

    /**
     * Store new leave and calculate duration.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id'   => 'required|exists:employees,id',
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date'    => 'required|date',
            'end_date'      => 'nullable|date|after_or_equal:start_date',
            'reason'        => 'nullable|string|max:1000',
            'status'        => 'required|in:ongoing,done',
        ]);

        $leaveType = LeaveType::findOrFail($validated['leave_type_id']);
        $totalDays = $this->calculateTotalDays($validated['start_date'], $validated['end_date'], $leaveType);

        if ($leaveType->max_continuous_days && $totalDays > $leaveType->max_continuous_days) {
            return back()->withErrors(['end_date' => "This leave type allows a maximum of {$leaveType->max_continuous_days} continuous days."]);
        }

        $leave = EmployeeLeave::create(array_merge($validated, ['total_days' => $totalDays]));
        
        // Update rollup if marked as 'done'
        if ($validated['status'] === 'done') {
            $this->updateRollup($validated['employee_id'], Carbon::parse($validated['start_date'])->format('Y-m'));
        }

        return redirect()->route('leaves.index')->with('success', 'Leave recorded with duration: ' . $totalDays . ' days.');
    }

    public function edit(EmployeeLeave $leave)
    {
        $employees = Employee::select('id', 'name', 'employee_id')->get();
        $leaveTypes = LeaveType::all();
        return view('leaves.edit', compact('leave', 'employees', 'leaveTypes'));
    }

    /**
     * Update leave and RECALCULATE duration.
     */
    public function update(Request $request, EmployeeLeave $leave)
    {
        $validated = $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date'    => 'required|date',
            'end_date'      => 'nullable|date|after_or_equal:start_date',
            'reason'        => 'nullable|string|max:1000',
            'status'        => 'required|in:ongoing,done',
        ]);

        $leaveType = LeaveType::findOrFail($validated['leave_type_id']);
        $totalDays = $this->calculateTotalDays($validated['start_date'], $validated['end_date'], $leaveType);

        $leave->update(array_merge($validated, ['total_days' => $totalDays]));
        
        // Refresh rollup calculation
        $this->updateRollup($leave->employee_id, Carbon::parse($leave->start_date)->format('Y-m'));

        return redirect()->route('leaves.index')->with('success', 'Leave updated. Duration recalculated to: ' . $totalDays . ' days.');
    }

    /**
     * Delete leave and refresh rollup.
     */
    public function destroy(EmployeeLeave $leave)
    {
        $empId = $leave->employee_id;
        $month = Carbon::parse($leave->start_date)->format('Y-m');

        $leave->delete();
        $this->updateRollup($empId, $month);

        return redirect()->route('leaves.index')->with('success', 'Leave deleted.');
    }

    /**
     * Helper to determine duration based on Policy (Sandwich vs Standard).
     */
    private function calculateTotalDays($start, $end, $leaveType)
    {
        $startDate = Carbon::parse($start);
        $endDate = $end ? Carbon::parse($end) : $startDate;

        if ($leaveType->sandwich_rule) {
            return $startDate->diffInDays($endDate) + 1;
        }

        return $startDate->diffInDaysFiltered(fn(Carbon $date) => !$date->isWeekend(), $endDate) + 1;
    }

    /**
     * Update the BOD Monthly Report stats.
     */
    private function updateRollup($employeeId, $month)
    {
        if (!$employeeId) return;

        $absentDays = EmployeeLeave::where('employee_id', $employeeId)
            ->where('status', 'done')
            ->where('start_date', 'like', "$month%")
            ->sum('total_days');

        AttendanceMonthlyRollup::updateOrCreate(
            ['employee_id' => $employeeId, 'month' => $month],
            ['total_absent_days' => $absentDays]
        );
    }

    /**
     * FullCalendar JSON endpoint.
     */
    public function calendarEvents(Request $request)
    {
        $start = Carbon::parse($request->input('start'));
        $end = Carbon::parse($request->input('end'));

        $query = EmployeeLeave::with(['leaveType', 'employee'])
            ->whereBetween('start_date', [$start, $end]);

        if ($request->filled('department')) {
            $query->whereHas('employee', fn($q) => $q->where('department', $request->department));
        }

        return response()->json($query->get()->map(function ($leave) {
            return [
                'id' => $leave->id,
                'title' => $leave->employee->name,
                'start' => $leave->start_date,
                'end' => $leave->end_date ? Carbon::parse($leave->end_date)->addDay()->toDateString() : null,
                'color' => $leave->leaveType->color ?? '#3788d8',
                'extendedProps' => [
                    'reason' => $leave->reason ?? 'No remarks',
                    'type' => $leave->leaveType->name
                ]
            ];
        }));
    }

    /**
     * BOD Monthly Rollup Report.
     */
    public function rollupReport(Request $request)
    {
        $month = $request->input('month', now()->format('Y-m'));
        $departments = Employee::distinct()->pluck('department');
        $reports = AttendanceMonthlyRollup::with('employee')->where('month', $month)
            ->when($request->filled('department'), fn($q) => $q->whereHas('employee', fn($sq) => $sq->where('department', $request->department)))
            ->get();

        return view('leaves.reports', compact('reports', 'month', 'departments'));
    }
}