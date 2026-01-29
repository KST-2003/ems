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
        $table->string('employee_id')->unique();
        $table->string('name')->nullable();
        $table->string('phone')->nullable();
        $table->enum('gender', ['male', 'female'])->nullable();
        $table->string('profile_image')->nullable();
        $table->string('mm_dob')->nullable();
        $table->date('eng_dob')->nullable();
        
        // Personal Details
        $table->string('nationality')->nullable();
        $table->string('religion')->nullable();
        $table->string('nrc')->nullable();
        $table->string('blood_type')->nullable();
        $table->string('height')->nullable();
        $table->string('hair_color')->nullable();
        $table->string('notable_trade')->nullable();
        $table->string('skin_color')->nullable();
        $table->string('weight')->nullable();
        $table->string('pob')->nullable();

        // Parents Info
        $table->string('father_name')->nullable();
        $table->string('father_nationality')->nullable();
        $table->string('father_religion')->nullable();
        $table->string('father_pob')->nullable();
        $table->string('father_job')->nullable();
        $table->string('father_address')->nullable();
        $table->string('mother_name')->nullable();
        $table->string('mother_nationality')->nullable();
        $table->string('mother_religion')->nullable();
        $table->string('mother_pob')->nullable();
        $table->string('mother_job')->nullable();
        $table->string('mother_address')->nullable();
        $table->string('is_parent_citizen')->nullable();
        
        // Political/Election Info
        $table->boolean('isin_election')->nullable();
        $table->text('election_description')->nullable();

        // Address
        $table->text('current_address')->nullable();
        $table->text('permanent_address')->nullable();
        $table->text('old_address')->nullable();
        
        // Job Details
        $table->string('current_position')->nullable();
        $table->date('current_position_date')->nullable();
        $table->string('department')->nullable();
        $table->string('position_acquire')->nullable();
        $table->string('acquire_type')->nullable();
        $table->string('salary')->nullable();
        $table->string('department_place')->nullable();
        $table->string('job_refer')->nullable();
        
        $table->string('lang_proficiency')->nullable();
        $table->string('hobby')->nullable();
        $table->timestamps();

        $table->index('name');
        $table->index('nrc');
    });
}

    public function down()
    {
        Schema::dropIfExists('employees');
    }
}