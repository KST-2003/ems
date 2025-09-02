<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id')->unique(); // Admin-entered unique ID
            $table->string('name');
            $table->date('dob')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('phone')->nullable();
            $table->enum('gender',['male','female'])->nullable();
            $table->string('profile_image')->nullable();
            $table->string('nationality')->nullable();
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('nrc')->nullable();
            $table->string('spouse_name')->nullable();
            $table->text('children_names')->nullable(); 
            $table->text('address')->nullable();
            $table->string('education')->nullable();
            $table->string('current_position')->nullable();
            $table->string('salary')->nullable();
            $table->string('department')->nullable();
            $table->string('blood_type')->nullable();
            $table->timestamps();
            $table->index('name'); // Index for faster query searches
            $table->index('nrc');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employees');
    }
}
