<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeLeave;
use App\Models\EmployeeAbsence;
use App\Models\LeaveType;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class LeaveController extends Controller
{
    /**
     * Display the main leaves page
     */
    public function index()
    {
        $departments = Employee::select('department')->distinct()->pluck('department');
        $leaveTypes = LeaveType::all();
        $employees = Employee::select('id', 'name', 'department')->get();

        return view('leaves.index', compact('departments', 'leaveTypes', 'employees'));
    }

    /**
     * Server-side DataTables for leaves + absences
     */
    public function datatable(Request $request)
    {
        try {
            // 1. Base Leaves Query
            $leaves = EmployeeLeave::query()
                ->select([
                    'employee_leaves.id as id',
                    'employee_leaves.employee_id',
                    'employee_leaves.leave_type_id',
                    'employee_leaves.start_date',
                    'employee_leaves.end_date',
                    'employee_leaves.total_days',
                    'employee_leaves.reason',
                    'employee_leaves.status',
                    'employees.name as employee_name',
                    'employees.department as employee_department',
                    'leave_types.name as leave_type_name',
                    'leave_types.default_days as default_days'
                ])
                ->leftJoin('employees', 'employee_leaves.employee_id', '=', 'employees.id')
                ->leftJoin('leave_types', 'employee_leaves.leave_type_id', '=', 'leave_types.id');

            // 2. Base Absences Query
            $absences = EmployeeAbsence::query()
                ->selectRaw("
                    employee_absences.id as id,
                    employee_absences.employee_id,
                    NULL as leave_type_id,
                    employee_absences.start_date,
                    employee_absences.end_date,
                    NULL as total_days,
                    employee_absences.reason,
                    'ongoing' as status,
                    employees.name as employee_name,
                    employees.department as employee_department,
                    'Absence' as leave_type_name,
                    NULL as default_days
                ")
                ->leftJoin('employees', 'employee_absences.employee_id', '=', 'employees.id');

            // --- APPLY FILTERS ---

            // Filter by Department
            if ($request->filled('department')) {
                $leaves->where('employees.department', $request->department);
                $absences->where('employees.department', $request->department);
            }

            // Filter by Leave Type
            if ($request->filled('leave_type_id')) {
                $leaves->where('employee_leaves.leave_type_id', $request->leave_type_id);
                
                // CRITICAL FIX: If a specific leave type is selected, we must HIDE absences
                // because absences do not belong to any specific leave type.
                $absences->whereRaw('1 = 0'); 
            }

            // Combine queries using Union
            $query = $leaves->union($absences);

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('start_date', fn($row) => $row->start_date ? Carbon::parse($row->start_date)->format('d-m-Y') : '-')
                ->editColumn('end_date', fn($row) => $row->end_date ? Carbon::parse($row->end_date)->format('d-m-Y') : '-')
                ->addColumn('duration', function ($row) {
                    if (!$row->start_date || !$row->end_date) return '-';
                    return Carbon::parse($row->start_date)->diffInDays($row->end_date) + 1;
                })
                ->addColumn('remaining_days', function ($row) use ($request) {
                    if (!$row->leave_type_id || !$row->default_days) {
                        return '<span class="text-muted">-</span>'; 
                    }

                    $excludeId = $request->id ?? 0;

                    $usedDays = EmployeeLeave::where('employee_id', $row->employee_id)
                        ->where('leave_type_id', $row->leave_type_id)
                        ->where('id', '!=', $excludeId)
                        ->sum(\DB::raw("DATEDIFF(COALESCE(end_date, CURDATE()), start_date) + 1"));

                    $remaining = $row->default_days - $usedDays;
                    $class = ($remaining <= 3) ? 'text-danger fw-bold' : '';

                    return "<span class='$class'>{$remaining}/{$row->default_days}</span>";
                })
                ->addColumn('actions', function ($row) {
                    // Only show actions for real leaves (ID exists and has type)
                    if (isset($row->leave_type_id) && $row->leave_type_id) {
                        return '
                        <a href="' . route('leaves.edit', $row->id) . '" class="btn btn-sm btn-primary">Edit</a>
                        <form action="' . route('leaves.destroy', $row->id) . '" method="POST" class="d-inline" 
                              onsubmit="return confirm(\'Are you sure you want to delete this leave?\')">
                            ' . csrf_field() . '
                            ' . method_field('DELETE') . '
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    ';
                    }
                    return '<span class="text-muted">N/A</span>';
                })
                ->rawColumns(['actions', 'remaining_days'])
                ->make(true);
        } catch (\Exception $e) {
            Log::error('DataTables error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Calendar events JSON endpoint
     */
    public function calendarEvents(Request $request)
    {
        try {
            $startStr = $request->input('start');
            $endStr   = $request->input('end');
            
            // Parse dates
            $start = Carbon::parse($startStr)->startOfDay();
            $end   = Carbon::parse($endStr)->endOfDay();

            $events = [];

            // --- 1. Fetch Leaves ---
            $leavesQuery = EmployeeLeave::with(['leaveType', 'employee'])
                ->where(function ($q) use ($start, $end) {
                    $q->whereDate('start_date', '<=', $end->toDateString())
                        ->whereDate('end_date', '>=', $start->toDateString())
                        ->orWhereNull('end_date');
                });

            // FIX: Apply filters to Leaves
            if ($request->filled('department')) {
                $leavesQuery->whereHas('employee', function($q) use ($request) {
                    $q->where('department', $request->department);
                });
            }

            if ($request->filled('leave_type_id')) {
                $leavesQuery->where('leave_type_id', $request->leave_type_id);
            }

            if ($request->filled('employee_id')) {
                $leavesQuery->where('employee_id', $request->employee_id);
            }

            $leaves = $leavesQuery->get();

            foreach ($leaves as $leave) {
                // Ensure valid dates
                $s = $leave->start_date ? Carbon::parse($leave->start_date) : null;
                $e = $leave->end_date ? Carbon::parse($leave->end_date) : null;

                if(!$s) continue;

                $events[] = [
                    'id'    => 'leave-' . $leave->id,
                    'title' => ($leave->employee->name ?? 'Unknown') . ' - ' . ($leave->leaveType->name ?? 'Unknown'),
                    'start' => $s->toDateString(),
                    // FullCalendar end date is exclusive, so add 1 day
                    'end'   => $e ? $e->addDay()->toDateString() : null,
                    'color' => $leave->leaveType->color ?? '#3788d8',
                    'extendedProps' => ['reason' => $leave->reason ?? '']
                ];
            }

            // --- 2. Fetch Absences ---
            // Only fetch absences if NO specific leave type is selected
            if (!$request->filled('leave_type_id')) {
                
                $absencesQuery = EmployeeAbsence::with('employee')
                    ->where(function ($q) use ($start, $end) {
                        $q->whereDate('start_date', '<=', $end->toDateString())
                            ->whereDate('end_date', '>=', $start->toDateString())
                            ->orWhereNull('end_date');
                    });

                // FIX: Apply Department Filter to Absences
                if ($request->filled('department')) {
                    $absencesQuery->whereHas('employee', function($q) use ($request) {
                        $q->where('department', $request->department);
                    });
                }

                if ($request->filled('employee_id')) {
                    $absencesQuery->where('employee_id', $request->employee_id);
                }

                $absences = $absencesQuery->get();

                foreach ($absences as $absence) {
                    $s = $absence->start_date ? Carbon::parse($absence->start_date) : null;
                    $e = $absence->end_date ? Carbon::parse($absence->end_date) : null;

                    if(!$s) continue;

                    $events[] = [
                        'id'    => 'absence-' . $absence->id,
                        'title' => ($absence->employee->name ?? 'Unknown') . ' - Absent',
                        'start' => $s->toDateString(),
                        'end'   => $e ? $e->addDay()->toDateString() : null,
                        'color' => '#ff851b', // Orange for absence
                        'extendedProps' => ['reason' => $absence->reason ?? '']
                    ];
                }
            }

            return response()->json($events);

        } catch (\Exception $e) {
            Log::error('Calendar events error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // ... (Keep create, edit, store, update, destroy methods as they were) ...
    
    public function create()
    {
        $employees = Employee::select('id', 'name')->get();
        $leaveTypes = LeaveType::all();
        return view('leaves.create', compact('employees', 'leaveTypes'));
    }

    public function edit(EmployeeLeave $leave)
    {
        $employees = Employee::select('id', 'name')->get();
        $leaveTypes = LeaveType::all();
        return view('leaves.edit', compact('leave', 'employees', 'leaveTypes'));
    }

    public function store(Request $request)
    {
        // Validation and logic remain the same...
        $validated = $request->validate([
            'employee_id'   => 'required|exists:employees,id',
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date'    => 'required|date',
            'end_date'      => 'nullable|date|after_or_equal:start_date',
            'reason'        => 'nullable|string|max:1000',
            'status'        => 'required|in:ongoing,done',
        ]);

        $employeeId = $validated['employee_id'];
        $leaveTypeId = $validated['leave_type_id'];
        $start = Carbon::parse($validated['start_date']);
        $end = $validated['end_date'] ? Carbon::parse($validated['end_date']) : $start;

        $overlapping = EmployeeLeave::where('employee_id', $employeeId)
            ->where('id', '!=', $request->id ?? 0)
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('start_date', [$start, $end])
                    ->orWhereBetween('end_date', [$start, $end])
                    ->orWhere(function ($sub) use ($start, $end) {
                        $sub->where('start_date', '<=', $start)
                            ->where('end_date', '>=', $end);
                    });
            })
            ->exists();

        if ($overlapping) {
            return back()->withErrors(['start_date' => 'Overlapping leave detected.']);
        }

        EmployeeLeave::create($validated);
        return redirect()->route('leaves.index')->with('success', 'Leave created.');
    }

    public function update(Request $request, EmployeeLeave $leave)
    {
        $validated = $request->validate([
            'employee_id'   => 'required|exists:employees,id',
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date'    => 'required|date',
            'end_date'      => 'nullable|date|after_or_equal:start_date',
            'reason'        => 'nullable|string|max:1000',
            'status'        => 'required|in:ongoing,done',
        ]);
        
        $leave->update($validated);
        return redirect()->route('leaves.index')->with('success', 'Leave updated.');
    }

    public function destroy(EmployeeLeave $leave)
    {
        $leave->delete();
        return redirect()->route('leaves.index')->with('success', 'Leave deleted.');
    }
}