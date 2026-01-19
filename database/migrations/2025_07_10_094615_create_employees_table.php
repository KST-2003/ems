<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id')->unique(); // Custom ID (e.g., EMP-001)
            $table->string('name')->nullable();
            $table->string('phone')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->string('profile_image')->nullable();
            
            // New Date Fields
            $table->string('mm_dob')->nullable(); // Myanmar DOB
            $table->date('eng_dob')->nullable(); // English DOB
            
            // Personal Details
            $table->string('nationality')->nullable();
            $table->string('religion')->nullable(); // New Field
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('nrc')->nullable();
            $table->string('blood_type')->nullable();
            
            // Spouse Details
            $table->string('spouse_name')->nullable();
            $table->string('spouse_job')->nullable(); // New Field
            $table->string('spouse_job_place')->nullable(); // New Field
            
            // Address
            $table->text('current_address')->nullable();
            $table->text('permanent_address')->nullable();
            
            // Job Details
            $table->string('current_position')->nullable();
            $table->string('salary')->nullable(); // String to handle formatting or currency
            $table->string('department')->nullable();
            
            // Skills/Extras
            $table->string('lang_proficiency')->nullable(); // New Field
            $table->string('hobby')->nullable(); // New Field
            
            $table->timestamps();
            
            // Indexes
            $table->index('name');
            $table->index('nrc');
        });
    }

    public function down()
    {
        Schema::dropIfExists('employees');
    }
}