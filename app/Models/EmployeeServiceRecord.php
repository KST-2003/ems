<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeServiceRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'status_type', // ENUM: e.g., 'permanent_appointment', 'transfer', 'dismissal', 'other'
        'recruited_date', // The date the status change took effect
        'remark', // Additional details or context
    ];

    // Cast the date field
    protected $casts = ['recruited_date'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}