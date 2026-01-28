<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

class EmployeeLeaveAllocation extends Model {

    use Loggable;

    protected static function booted()
    {
        static::created(fn($model) => self::logAction("Created Leave Allocation", "Employee ID: " . $model->employee_id . " | Type: " . $model->leave_type_id));
        static::updated(fn($model) => self::logAction("Updated Leave Allocation", "ID: " . $model->id . " | Status: " . $model->status));
        static::deleted(fn($model) => self::logAction("Deleted Leave Allocation", "ID: " . $model->id));
    }

    protected $fillable = ['employee_id', 'leave_type_id', 'max_allowed_days', 'year'];

    public function leaveType() {
        return $this->belongsTo(LeaveType::class);
    }
}