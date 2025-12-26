<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('personnel_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['recruit', 'promote', 'demote', 'transfer', 'punishment', 'partnership'])->default('recruit'); // Your ENUM types
            $table->string('position')->nullable();
            $table->string('department')->nullable();
            $table->string('location')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->text('reason')->nullable();
            $table->text('remark')->nullable(); // Optional extra for details
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('personnel_actions');
    }
};