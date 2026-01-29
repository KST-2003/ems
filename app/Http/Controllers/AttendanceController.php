<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeAttendance;
use App\Models\EmployeeLeave;
use App\Models\CompanyCalendar;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->input('date', Carbon::today()->toDateString());
        $departments = Employee::distinct()->pluck('department_place');

        // Check if today is a Holiday or Special Closure in our new system
        $calendarEntry = CompanyCalendar::where('date', $date)
            ->whereIn('type', ['holiday', 'close_exception'])
            ->first();

        $query = Employee::select('id', 'employee_id', 'name', 'department', 'profile_image');
        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }
        $employees = $query->get();

        $existingAttendance = EmployeeAttendance::where('date', $date)->pluck('status', 'employee_id');

        // Disable buttons if employee is on official leave
        $onLeaveIds = EmployeeLeave::whereDate('start_date', '<=', $date)
            ->whereDate('end_date', '>=', $date)
            ->pluck('employee_id')->toArray();

        return view('attendances.index', compact('employees', 'date', 'departments', 'existingAttendance', 'onLeaveIds', 'calendarEntry'));
    }

    public function bulkStore(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'attendance' => 'required|array',
            'attendance.*' => 'in:present,on_duty,absent'
        ]);

        $date = $request->date;
        DB::transaction(function () use ($date, $request) {
            foreach ($request->attendance as $employeeId => $status) {
                // updateOrCreate ensures we can change status and save again
                EmployeeAttendance::updateOrCreate(
                    ['employee_id' => $employeeId, 'date' => $date],
                    ['status' => $status]
                );
            }
        });

        return redirect()->back()->with('success', 'Attendance for ' . $date . ' has been saved.');
    }

    public function employeeEvents(Request $request, $employeeId)
    {
        $start = Carbon::parse($request->input('start'));
        $end = Carbon::parse($request->input('end'));
        $events = [];

        // 1. Fetch Absences (Show as Red)
        $absences = EmployeeAttendance::where('employee_id', $employeeId)
            ->where('status', 'absent')->whereBetween('date', [$start, $end])->get();

        foreach ($absences as $abs) {
            $events[] = ['title' => 'Absent', 'start' => $abs->date, 'color' => '#dc3545', 'allDay' => true];
        }

        // 2. Fetch Approved Leaves (Show by Policy Color)
        $leaves = EmployeeLeave::with('leaveType')->where('employee_id', $employeeId)
            ->whereBetween('start_date', [$start, $end])->get();

        foreach ($leaves as $leave) {
            $events[] = [
                'title' => $leave->leaveType->name,
                'start' => $leave->start_date,
                'end' => $leave->end_date ? Carbon::parse($leave->end_date)->addDay()->toDateString() : null,
                'color' => $leave->leaveType->color ?? '#3788d8',
                'allDay' => true
            ];
        }

        return response()->json($events);
    }

    /**
     * Display the Company Calendar management page.
     */
    public function calendarIndex()
    {
        // Fetch entries ordered by date
        $calendarEntries = CompanyCalendar::orderBy('date', 'asc')->get();
        return view('calendar.index', compact('calendarEntries'));
    }

    /**
     * Store a new holiday or exception in the Company Calendar.
     */
    public function calendarStore(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date|unique:company_calendars,date',
            'name' => 'required|string|max:255',
            'type' => 'required|in:holiday,close_exception,open_exception',
            'description' => 'nullable|string'
        ]);

        CompanyCalendar::create($validated);

        return redirect()->back()->with('success', 'Calendar entry added successfully.');
    }

    /**
     * Remove an entry from the Company Calendar.
     */
    public function calendarDestroy(CompanyCalendar $calendar)
    {
        $calendar->delete();
        return redirect()->back()->with('success', 'Calendar entry removed.');
    }
}
