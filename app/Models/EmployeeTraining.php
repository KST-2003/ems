<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeTraining extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'training_type', // ENUM: 'domestic', 'foreign', 'other'
        'course_name',
        'location',
        'start_date',
        'end_date',
    ];

    // Cast dates automatically
    protected $casts = [
        'start_date' => 'date',
        'end_date'   => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}