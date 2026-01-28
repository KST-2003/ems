<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

class LeaveType extends Model
{
    use HasFactory;

    use Loggable;

    protected static function booted()
    {
        static::created(fn($model) => self::logAction("Created Holiday", "Name: " . $model->name . " | Date: " . $model->date));
        static::updated(fn($model) => self::logAction("Updated Holiday", "Name: " . $model->name . " | Type: " . $model->type));
        static::deleted(fn($model) => self::logAction("Deleted Holiday", "Name: " . $model->name));
    }
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
