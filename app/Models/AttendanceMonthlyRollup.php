<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AttendanceMonthlyRollup extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'month',
        'total_present',
        'total_on_duty',
        'total_absent_days'
    ];

    /**
     * Define the relationship to the Employee model.
     * This fixes the "undefined method employee()" error.
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}