<?php

namespace App\Http\Controllers;

use App\Models\Employee;

class EmployeePrintController extends Controller
{
    public function selectTemplate(Employee $employee)
    {
        return view('employees.print.select', compact('employee'));
    }

    public function templateA(Employee $employee)
    {
        $employee->load([
            'children',
            'educations',
            'trainings',
            'serviceRecord',
            'experiences'
        ]);
        Employee::logAction("Printed Template A", "Employee: " . $employee->name);

        return view('employees.print.template-a', compact('employee'));
    }

    public function templateB(Employee $employee)
    {
        $employee->load([
            'children',
            'educations',
            'trainings',
            'pastExperiences',
            'relatives',
            'personnelActions',
            'certificates',
            'criminalRecords',
            'experiences'
        ]);
        Employee::logAction("Printed Template B", "Employee: " . $employee->name);
        return view('employees.print.template-b', compact('employee'));
    }
}
