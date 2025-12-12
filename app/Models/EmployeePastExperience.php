<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeePastExperience extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'position',
        'salary',
        'location',
        'start_date',
        'end_date',
        'remark',
        'life_insurance', // ENUM: 'yes', 'no'
    ];

    // Cast dates automatically
    protected $casts = ['start_date', 'end_date'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}