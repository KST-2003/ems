<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeExperience extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'company_name',
        'position',
        'department',
        'from_date',
        'to_date',
        'is_current',
        'location',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}