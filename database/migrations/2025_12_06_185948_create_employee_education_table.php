<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeEducationTable extends Migration
{
    public function up()
    {
        Schema::create('employee_education', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['school', 'uni', 'other'])->default('other');
            $table->string('institution_name')->nullable();
            $table->string('degree_certificate')->nullable();
            $table->string('field_of_study')->nullable();
            $table->date('date')->nullable(); // Graduation/Completion date
            $table->text('remark')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('employee_education');
    }
}