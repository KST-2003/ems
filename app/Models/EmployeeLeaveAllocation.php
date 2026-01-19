<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class EmployeeLeaveAllocation extends Model {
    protected $fillable = ['employee_id', 'leave_type_id', 'max_allowed_days', 'year'];

    public function leaveType() {
        return $this->belongsTo(LeaveType::class);
    }
}