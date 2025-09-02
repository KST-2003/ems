<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\RecruitmentController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// Group all authenticated routes under a single middleware group
//leaves/list comes after Route::resource('leaves', ...).
//Laravel matches routes top to bottom, and /leaves/list partially matches /leaves/{leave} (the {leave} wildcard).
//So when you visit /leaves/list, Laravel thinks list is a {leave} parameter and calls the show method. That’s why you get the “View Leave” page instead of JSON.
Route::middleware('auth')->group(function () {
 // Employees
    Route::get('employees/list', [EmployeeController::class, 'list'])->name('employees.list');
    Route::get('employees', [EmployeeController::class, 'webIndex'])->name('employees.index');
    Route::resource('employees', EmployeeController::class)->except(['index']);

    // Attendance
    Route::resource('attendances', AttendanceController::class);

    // Leaves
    Route::get('/leaves/list', [LeaveController::class, 'list'])->name('leaves.list');
    Route::get('/employees/{employee}/leaves', [LeaveController::class, 'employeeLeaves'])->name('employees.leaves');
    Route::get('/employees/{employee}/leaves/filter', [LeaveController::class, 'filterByType'])->name('leaves.filter');
    Route::resource('leaves', LeaveController::class);

    // Recruitment
    Route::resource('recruitments', RecruitmentController::class);

    // Exports
    Route::get('/{employee}/export-excel', [LeaveController::class, 'exportExcel'])->name('export.excel');
    Route::get('/{employee}/export-pdf', [LeaveController::class, 'exportPdf'])->name('export.pdf');
});
