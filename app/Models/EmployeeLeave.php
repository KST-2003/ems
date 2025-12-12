<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeLeave extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'leave_type_id',
        'start_date',
        'end_date',
        'reason',
        'status', // e.g., 'pending', 'approved', 'rejected'
    ];

    protected $casts = [
        'start_date',
        'end_date',
    ];
    
    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
    
    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class, 'leave_type_id', 'id');
    }
}