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
    Route::get('employees/list', [EmployeeController::class, 'list'])->name('employees.list');
    Route::get('employees', [EmployeeController::class, 'webIndex'])->name('employees.index');
    Route::resource('employees', EmployeeController::class)->except(['index']);

    // Print templates
    Route::prefix('employees/{employee}/print')->name('employees.print.')->group(function () {
        Route::get('select', [EmployeePrintController::class, 'selectTemplate'])->name('select');
        Route::get('template-a', [EmployeePrintController::class, 'templateA'])->name('template-a');
        Route::get('template-b', [EmployeePrintController::class, 'templateB'])->name('template-b');
    });

    // Attendance - Added Bulk Store for 200+ employees
    Route::post('attendances/bulk', [AttendanceController::class, 'bulkStore'])->name('attendances.bulk-store');
    Route::resource('attendances', AttendanceController::class);

    //Employees Individual Calendar route
    Route::get('/attendances/employee-events/{employee}', [AttendanceController::class, 'employeeEvents'])
        ->name('attendances.employee-events');

    // Leaves
    Route::get('/leaves/datatable', [LeaveController::class, 'datatable'])->name('leaves.datatable');
    Route::get('/leaves/calendar-events', [LeaveController::class, 'calendarEvents'])->name('leaves.calendar-events');
    Route::get('/leaves/reports', [LeaveController::class, 'rollupReport'])->name('leaves.reports');
    // Leave Allocations - FIX: Added missing routes
    Route::get('/leaves/allocations', [LeaveController::class, 'allocationIndex'])->name('leave-allocations.index');
    Route::post('/leaves/allocations', [LeaveController::class, 'allocationStore'])->name('leave-allocations.store');

    Route::resource('leaves', LeaveController::class)->parameters([
        'leaves' => 'leave'
    ]);

    // Leave types
    Route::resource('leave-types', LeaveTypeController::class);

    // Company Calendar Routes
    Route::get('/calendar/manage', [AttendanceController::class, 'calendarIndex'])->name('calendar.index');
    Route::post('/calendar/manage', [AttendanceController::class, 'calendarStore'])->name('calendar.store');
    Route::delete('/calendar/{calendar}', [AttendanceController::class, 'calendarDestroy'])->name('calendar.destroy');

    // Recruitment
    Route::get('recruitments/list', [RecruitmentController::class, 'list'])->name('recruitments.list');
    Route::get('recruitments/download/{recruitment}', [RecruitmentController::class, 'downloadResume'])->name('recruitments.download');
    Route::patch('recruitments/{recruitment}/status', [RecruitmentController::class, 'updateStatus'])->name('recruitments.updateStatus');
    Route::resource('recruitments', RecruitmentController::class);

    Route::get('security', [EmployeeController::class, 'securityIndex'])->name('security.index');
});
