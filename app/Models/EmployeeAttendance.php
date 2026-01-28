<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

class EmployeeAttendance extends Model
{
    use HasFactory;

    use Loggable;

    protected static function booted()
    {
        static::created(fn($model) => self::logAction("Recorded Attendance", "Employee ID: " . $model->employee_id));
        static::deleted(fn($model) => self::logAction("Removed Attendance Record", "ID: " . $model->id));
    }

    protected $fillable = [
        'employee_id',
        'date',
        'status', // Boolean or similar status
    ];

    protected $casts = ['date'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}