<?php

namespace App\Http\Controllers;

use App\Models\AttendanceMonthlyRollup;
use App\Models\CompanyCalendar;
use App\Models\Employee;
use App\Models\EmployeeAttendance;
use App\Models\EmployeeLeave;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     * Shows the daily attendance marking sheet for a specific date and department.
     */
    public function index(Request $request)
    {
        $date = $request->input('date', Carbon::today()->toDateString());
        $carbonDate = Carbon::parse($date);
        $calendarEntry = CompanyCalendar::whereDate('date', $date)->first();
        $departments = Employee::whereNotNull('department')->distinct()->pluck('department');

        $query = Employee::query();
        if ($request->filled('department')) {
            $query->where('department', $request->input('department'));
        }
        $employees = $query->get();

        $existingAttendance = EmployeeAttendance::whereDate('date', $date)
            ->pluck('status', 'employee_id')
            ->toArray();

        // Check if employee is officially on leave today
        $onLeaveIds = DB::table('employee_leaves')
            ->whereIn('status', ['ongoing', 'done'])
            ->whereDate('start_date', '<=', $date)
            ->whereDate('end_date', '>=', $date)
            ->pluck('employee_id')
            ->toArray();

        return view('attendances.index', compact(
            'employees',
            'date',
            'departments',
            'existingAttendance',
            'onLeaveIds',
            'calendarEntry',
            'carbonDate'
        ));
    }

    /**
     * Store a newly created resource in storage.
     * Handles the bulk submission of attendance data from the index form.
     */
    public function bulkStore(Request $request)
    {
        $date = $request->input('date');

        // 1. Intercept "Remove All Records" request
        if ($request->has('delete_all')) {
            EmployeeAttendance::whereDate('date', $date)->delete();
            return redirect()
                ->route('attendances.index', ['date' => $date])
                ->with('success', 'All attendance records for ' . Carbon::parse($date)->format('d M Y') . ' have been removed.');
        }

        // 2. Standard Save Logic
        $request->validate([
            'date' => 'required|date',
            'attendance' => 'required|array',
            'attendance.*' => 'in:present,absent,on_duty,leaves', 
        ]);

        $carbonDate = Carbon::parse($date);
        $attendanceData = $request->input('attendance');

        DB::transaction(function () use ($date, $carbonDate, $attendanceData) {
            // Clear existing for this date to prevent duplicates
            EmployeeAttendance::whereDate('date', $date)->delete();

            $toInsert = [];
            $presentCount = 0;
            $onDutyCount = 0;
            $absentCount = 0;

            foreach ($attendanceData as $employeeId => $status) {
                $toInsert[] = [
                    'employee_id' => $employeeId,
                    'date' => $date,
                    'status' => $status,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                // Count for rollup
                if ($status === 'present') $presentCount++;
                if ($status === 'on_duty') $onDutyCount++;
                if ($status === 'absent') $absentCount++;
            }

            EmployeeAttendance::insert($toInsert);

            // Note: In a production environment, you would typically recalculate 
            // the full month's rollup here rather than blindly incrementing it.
            AttendanceMonthlyRollup::updateOrCreate(
                ['month' => $carbonDate->format('Y-m'), 'employee_id' => current(array_keys($attendanceData)) ?? 0], 
                [
                    'total_present' => DB::raw("total_present + $presentCount"),
                    'total_on_duty' => DB::raw("total_on_duty + $onDutyCount"),
                    'total_absent_days' => DB::raw("total_absent_days + $absentCount"),
                ]
            );
        });

        return redirect()
            ->route('attendances.index', ['date' => $date])
            ->with('success', 'Attendance records saved successfully.');
    }

    // --- Company Calendar Management Methods ---

    public function calendarIndex()
    {
        $calendarEntries = CompanyCalendar::orderBy('date')->get();
        return view('calendar.index', compact('calendarEntries'));
    }

    public function calendarStore(Request $request)
    {
        $request->validate([
            'date' => 'required|date|unique:company_calendars,date',
            'name' => 'required|string|max:255',
            'type' => 'required|in:holiday,close_exception,open_exception',
        ]);

        CompanyCalendar::create($request->all());

        return redirect()
            ->route('calendar.index')
            ->with('success', 'Calendar entry added successfully.');
    }

    public function calendarDestroy(CompanyCalendar $calendar)
    {
        $calendar->delete();
        return redirect()
            ->route('calendar.index')
            ->with('success', 'Calendar entry removed.');
    }

    /**
     * API endpoint to fetch calendar events for a specific employee.
     */
    public function employeeEvents(Request $request, Employee $employee)
    {
        $start = $request->input('start');
        $end = $request->input('end');
        $events = [];

        // 1. Fetch Attendance Records
        $attendances = EmployeeAttendance::where('employee_id', $employee->id)
            ->whereBetween('date', [$start, $end])
            ->get();

        foreach ($attendances as $att) {
            $color = '#6c757d'; 
            if ($att->status === 'present') {
                $color = '#28a745'; // Green
            } elseif ($att->status === 'on_duty') {
                $color = '#0d6efd'; // Blue
            } elseif ($att->status === 'absent') {
                $color = '#dc3545'; // Red
            } elseif ($att->status === 'leaves') {
                $color = '#ffc107'; // Yellow
            }

            $events[] = [
                'title' => ucfirst(str_replace('_', ' ', $att->status)),
                'start' => $att->date,
                'color' => $color,
                'allDay' => true,
                'extendedProps' => ['type' => 'attendance']
            ];
        }

        // 2. Fetch Leave Records
        $leaves = EmployeeLeave::with('leaveType')
            ->where('employee_id', $employee->id)
            ->whereIn('status', ['ongoing', 'done'])
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('start_date', [$start, $end])
                    ->orWhereBetween('end_date', [$start, $end])
                    ->orWhere(function ($q) use ($start, $end) {
                        $q->where('start_date', '<', $start)
                            ->where('end_date', '>', $end);
                    });
            })
            ->get();

        foreach ($leaves as $leave) {
            $events[] = [
                'title' => $leave->leaveType ? $leave->leaveType->name : 'Leave',
                'start' => $leave->start_date->format('Y-m-d'),
                'end' => $leave->end_date ? Carbon::parse($leave->end_date)->addDay()->format('Y-m-d') : null,
                'color' => $leave->leaveType ? $leave->leaveType->color : '#ffc107',
                'allDay' => true,
                'extendedProps' => ['type' => 'leave', 'reason' => $leave->reason]
            ];
        }
        
        // 3. Fetch Holidays/Calendar Exceptions
        $holidays = CompanyCalendar::whereBetween('date', [$start, $end])->get();
        foreach ($holidays as $holiday) {
            $events[] = [
                'title' => $holiday->name,
                'start' => $holiday->date->format('Y-m-d'),
                'allDay' => true,
                'extendedProps' => ['type' => $holiday->type]
            ];
        }

        return response()->json($events);
    }
}