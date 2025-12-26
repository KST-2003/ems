<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeServiceRecordsTable extends Migration
{
    public function up()
    {
        Schema::create('employee_service_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->string('grade')->nullable(); // junior, senior, selection, higher
            $table->date('recruited_date')->nullable();
            $table->text('remark')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('employee_service_records');
    }
}

