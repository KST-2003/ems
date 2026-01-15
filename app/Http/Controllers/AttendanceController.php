<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeAttendance;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index()
    {
        $employees = Employee::select('id', 'name', 'employee_id', 'department')->get();
        return view('attendances.index', compact('employees'));
    }

    public function show(Employee $employee)
    {
        $employee->load(['attendances']);
        return view('attendances.show', compact('employee'));
    }

    // You can expand with store/update/destroy later
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date'        => 'required|date',
            'present'     => 'required|boolean',
        ]);

        EmployeeAttendance::updateOrCreate(
            ['employee_id' => $validated['employee_id'], 'date' => $validated['date']],
            ['present' => $validated['present']]
        );

        return redirect()->back()->with('success', 'Attendance recorded successfully.');
    }
}