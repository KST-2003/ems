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
            'children', 'educations', 'trainings', 'serviceRecord', 'experiences'
        ]);

        return view('employees.print.template-a', compact('employee'));
    }

    public function templateB(Employee $employee)
    {
        $employee->load([
            'children', 'educations', 'trainings',
            'pastExperiences', 'relatives', 'personnelActions',
            'certificates', 'criminalRecords', 'experiences'
        ]);

        return view('employees.print.template-b', compact('employee'));
    }
}