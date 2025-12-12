<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeEducation extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'type', // ENUM: 'school', 'uni', 'other'
        'institution_name',
        'degree_certificate',
        'field_of_study',
        'date', // Graduation or completion date
        'remark',
    ];
    
    // Cast 'date' to Carbon instance
    protected $casts = ['date'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}