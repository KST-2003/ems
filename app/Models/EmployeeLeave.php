<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

class EmployeeLeave extends Model
{
    use HasFactory;

    use Loggable;

    protected static function booted()
    {
        static::created(fn($model) => self::logAction("Created Leave Request", "Employee ID: " . $model->employee_id . " | Type: " . $model->leave_type_id));
        static::updated(fn($model) => self::logAction("Updated Leave Status", "ID: " . $model->id . " | Status: " . $model->status));
        static::deleted(fn($model) => self::logAction("Deleted Leave Record", "ID: " . $model->id));
    }

    protected $fillable = [
        'employee_id',
        'leave_type_id',
        'start_date',
        'end_date',
        'total_days',
        'reason',
        'status', //ongoing,done
    ];

    protected $casts = [
        'start_date' => 'date:Y-m-d',  // or 'date' for Carbon
        'end_date'   => 'date:Y-m-d',
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
