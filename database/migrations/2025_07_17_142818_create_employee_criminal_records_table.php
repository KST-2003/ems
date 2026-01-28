<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeCriminalRecordsTable extends Migration
{
    public function up()
    {
        Schema::create('employee_criminal_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->string('name')->nullable(); // Title of the record/crime
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->text('description')->nullable();
            $table->string('file_path')->nullable();


            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('employee_criminal_records');
    }
}