<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecruitmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recruitments', function (Blueprint $table) {
            $table->id();
            // Unique ensures one-to-one relationship
            $table->foreignId('employee_id')->nullable()->unique()->constrained()->onDelete('set null');
            $table->string('name');
            $table->date('dob')->nullable(); // Changed to date type
            $table->string('nrc')->nullable();
            $table->string('position_applied')->nullable();
            $table->string('nationality')->nullable();
            $table->string('religion')->nullable();
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('blood_type')->nullable();
            // Use enum for status control
            $table->enum('status', ['pending', 'declined', 'accepted'])->default('pending');
            $table->string('resume_file_path')->nullable();
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
        Schema::dropIfExists('recruitments');
    }
}
