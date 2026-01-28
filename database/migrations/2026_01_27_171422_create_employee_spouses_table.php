<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeSpousesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    // database/migrations/xxxx_create_employee_spouses_table.php
public function up()
{
    // Spouse Main Table
    Schema::create('employee_spouses', function (Blueprint $table) {
        $table->id();
        $table->foreignId('employee_id')->constrained()->onDelete('cascade');
        $table->string('name')->nullable();
        $table->string('nationality_religion')->nullable();
        $table->string('hometown')->nullable();
        $table->string('job')->nullable(); 
        $table->string('address')->nullable();
        $table->timestamps();
    });

    // Spouse's Relatives (Siblings, Father's side, Mother's side)
    Schema::create('spouse_relatives', function (Blueprint $table) {
        $table->id();
        $table->foreignId('employee_id')->constrained()->onDelete('cascade');
        $table->enum('type', ['sibling', 'paternal', 'maternal']); // To distinguish side
        $table->string('name')->nullable();
        $table->string('nationality_religion')->nullable();
        $table->string('hometown')->nullable();
        $table->string('job')->nullable(); 
        $table->string('address')->nullable();
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
        Schema::dropIfExists('employee_spouses');
    }
}
