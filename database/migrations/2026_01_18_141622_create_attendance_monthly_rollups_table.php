<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendanceMonthlyRollupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendance_monthly_rollups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->string('month'); // Format: YYYY-MM
            $table->integer('total_present')->default(0);
            $table->integer('total_on_duty')->default(0);
            $table->integer('total_absent_days')->default(0);
            $table->timestamps();

            $table->unique(['employee_id', 'month']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendance_monthly_rollups');
    }
}
