<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\LeaveTypeController;
use App\Http\Controllers\RecruitmentController;
use App\Http\Controllers\EmployeePrintController;

Route::get('/', function () {
    return redirect()->route('login');
});


Auth::routes();

Route::middleware('auth')->group(function () {

    // Employees
    // IMPORTANT: Define this BEFORE the resource route to prevent 'list' being treated as an ID.
    Route::get('employees/list', [EmployeeController::class, 'list'])->name('employees.list');
    
    Route::get('employees', [EmployeeController::class, 'webIndex'])->name('employees.index');
    Route::resource('employees', EmployeeController::class)->except(['index']);

    // Print templates
    Route::prefix('employees/{employee}/print')->name('employees.print.')->group(function () {
        Route::get('select', [EmployeePrintController::class, 'selectTemplate'])->name('select');
        Route::get('template-a', [EmployeePrintController::class, 'templateA'])->name('template-a');
        Route::get('template-b', [EmployeePrintController::class, 'templateB'])->name('template-b');
    });

    // Attendance
    Route::resource('attendances', AttendanceController::class);

    // ────────────────────────────────────────────────────────────────
    // Leaves - IMPORTANT ROUTES (FORCE STANDARD {leave} PARAMETER)
    // ────────────────────────────────────────────────────────────────
    Route::get('/leaves', [LeaveController::class, 'index'])->name('leaves.index');
    Route::get('/leaves/datatable', [LeaveController::class, 'datatable'])->name('leaves.datatable');
    Route::get('/leaves/calendar-events', [LeaveController::class, 'calendarEvents'])->name('leaves.calendar-events');

    // Explicitly define CRUD with correct {leave} parameter (overrides any weird cache)
    Route::get('leaves/create', [LeaveController::class, 'create'])->name('leaves.create');
    Route::post('leaves', [LeaveController::class, 'store'])->name('leaves.store');
    Route::get('leaves/{leave}', [LeaveController::class, 'show'])->name('leaves.show');
    Route::get('leaves/{leave}/edit', [LeaveController::class, 'edit'])->name('leaves.edit');
    Route::put('leaves/{leave}', [LeaveController::class, 'update'])->name('leaves.update');
    Route::patch('leaves/{leave}', [LeaveController::class, 'update'])->name('leaves.update');
    Route::delete('leaves/{leave}', [LeaveController::class, 'destroy'])->name('leaves.destroy');




    // Leave types
    Route::resource('leave-types', LeaveTypeController::class);

    // Recruitment
    Route::resource('recruitments', RecruitmentController::class);
});
