<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeAttendanceLeavesAbsencesTables extends Migration
{
    public function up()
    {
        // 1. Updated Attendances Table
        Schema::create('employee_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->date('date')->nullable();
            // Changed from boolean 'present' to enum 'status'
            $table->enum('status', ['present', 'on_duty','absent','leave'])->default('present'); 
            $table->timestamps();

            // Optimization: Index for faster daily lookups
            $table->index(['date', 'employee_id']); 
        });

        // 2. Leaves Table (Remains largely the same, but centralized)
        Schema::create('employee_leaves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->foreignId('leave_type_id')->constrained('leave_types')->onDelete('cascade');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('total_days')->nullable(); // Changed to integer for math
            $table->text('reason')->nullable();
            $table->enum('status', ['ongoing', 'done'])->default('ongoing');
            $table->timestamps();
        });

        // REMOVED: employee_absences table block
    }

    public function down()
    {
        Schema::dropIfExists('employee_attendances');
        Schema::dropIfExists('employee_leaves');
    }
}