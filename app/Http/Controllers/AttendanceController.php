<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeAttendance;
use App\Models\EmployeeLeave;
use App\Models\Holiday;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->input('date', Carbon::today()->toDateString());
        $holiday = Holiday::where('date', $date)->first();
        $departments = Employee::distinct()->pluck('department');

        $query = Employee::select('id', 'employee_id', 'name', 'department', 'profile_image');
        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }
        $employees = $query->get();

        $existingAttendance = EmployeeAttendance::where('date', $date)->pluck('status', 'employee_id');
        
        // Disable buttons if employee is on official leave
        $onLeaveIds = EmployeeLeave::whereDate('start_date', '<=', $date)
            ->whereDate('end_date', '>=', $date)
            ->pluck('employee_id')
            ->toArray();

        return view('attendances.index', compact('employees', 'date', 'departments', 'existingAttendance', 'onLeaveIds', 'holiday'));
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
                EmployeeAttendance::updateOrCreate(
                    ['employee_id' => $employeeId, 'date' => $date],
                    ['status' => $status] 
                );
            }
        });

        return redirect()->back()->with('success', 'Attendance for ' . $date . ' has been saved.');
    }

    /**
     * Endpoint for Individual Employee Calendar View (Leaves + Absences)
     */
    public function employeeEvents(Request $request, $employeeId)
    {
        $start = Carbon::parse($request->input('start'));
        $end = Carbon::parse($request->input('end'));
        $events = [];

        // 1. Fetch Absences (Show as Red)
        $absences = EmployeeAttendance::where('employee_id', $employeeId)
            ->where('status', 'absent')
            ->whereBetween('date', [$start, $end])
            ->get();

        foreach ($absences as $abs) {
            $events[] = [
                'title' => 'Absent',
                'start' => $abs->date,
                'color' => '#dc3545', // Danger Red
                'allDay' => true
            ];
        }

        // 2. Fetch Approved Leaves (Show by Policy Color)
        $leaves = EmployeeLeave::with('leaveType')
            ->where('employee_id', $employeeId)
            ->whereBetween('start_date', [$start, $end])
            ->get();

        foreach ($leaves as $leave) {
            $events[] = [
                'title' => $leave->leaveType->name,
                'start' => $leave->start_date,
                'end' => $leave->end_date ? Carbon::parse($leave->end_date)->addDay()->toDateString() : null,
                'color' => $leave->leaveType->color ?? '#3788d8',
                'allDay' => true,
                'extendedProps' => ['reason' => $leave->reason]
            ];
        }

        return response()->json($events);
    }
}