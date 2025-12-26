<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\RecruitmentController;
use App\Http\Controllers\EmployeePrintController; // ← New controller
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/phpinfo', function() {
    phpinfo();
});

Route::middleware('auth')->group(function () {

    // Employees
    Route::get('employees/list', [EmployeeController::class, 'list'])->name('employees.list');
    Route::get('employees', [EmployeeController::class, 'webIndex'])->name('employees.index');
    Route::resource('employees', EmployeeController::class)->except(['index']);

    // Print Templates (new)
    Route::prefix('employees/{employee}/print')->name('employees.print.')->group(function () {
        Route::get('select', [EmployeePrintController::class, 'selectTemplate'])->name('select');
        Route::get('template-a', [EmployeePrintController::class, 'templateA'])->name('template-a');
        Route::get('template-b', [EmployeePrintController::class, 'templateB'])->name('template-b');
    });

    // Attendance
    Route::resource('attendances', AttendanceController::class);

    // Leaves
    Route::get('/leaves/list', [LeaveController::class, 'list'])->name('leaves.list');
    Route::get('/employees/{employee}/leaves', [LeaveController::class, 'employeeLeaves'])->name('employees.leaves');
    Route::get('/employees/{employee}/leaves/filter', [LeaveController::class, 'filterByType'])->name('leaves.filter');
    Route::resource('leaves', LeaveController::class);

    // Recruitment
    Route::resource('recruitments', RecruitmentController::class);

    // Exports (if you still use them)
    Route::get('/{employee}/export-excel', [LeaveController::class, 'exportExcel'])->name('export.excel');
    Route::get('/{employee}/export-pdf', [LeaveController::class, 'exportPdf'])->name('export.pdf');
});