<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeePersonalRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    // database/migrations/xxxx_create_employee_personal_records_table.php
public function up()
{
    Schema::create('employee_personal_records', function (Blueprint $table) {
        $table->id();
        $table->foreignId('employee_id')->constrained()->onDelete('cascade');
        $table->text('schools')->nullable(); 
        $table->text('latest_school')->nullable();
        $table->text('school_voluntary')->nullable();
        $table->text('hobbies')->nullable();
        $table->text('jobs_dept')->nullable();
        $table->text('refugee')->nullable();
        $table->text('jobtransfer_desc')->nullable();
        $table->string('citizen_duties')->nullable();
        $table->string('relatives_officials')->nullable();
        $table->text('foreign_friends_desc')->nullable();
        $table->text('referal_officials')->nullable();
        $table->boolean('has_criminal_rec')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee_personal_records');
    }
}
