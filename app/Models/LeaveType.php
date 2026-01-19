<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'default_days',
        'color',
        'max_continuous_days',
        'sandwich_rule'
    ];

    public function leaves()
    {
        return $this->hasMany(EmployeeLeave::class, 'leave_type_id', 'id');
    }

    public function allocations()
    {
        return $this->hasMany(EmployeeLeaveAllocation::class);
    }
}
