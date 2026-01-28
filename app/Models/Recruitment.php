<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use App\Traits\Loggable;

class Recruitment extends Model
{
    use HasFactory;

    use Loggable;

    protected $fillable = [
        'employee_id',
        'name',
        'dob',
        'nrc',
        'position_applied',
        'nationality',
        'religion',
        'father_name',
        'mother_name',
        'blood_type',
        'status',
        'resume_file_path',
    ];

    protected static function booted()
    {
        static::created(fn($model) => self::logAction("New Recruitment Entry", "Candidate: " . $model->name));
        static::updated(fn($model) => self::logAction("Updated Recruitment Status", "Candidate: " . $model->name . " | Status: " . $model->status));
        static::deleted(fn($model) => self::logAction("Removed Recruitment Record", "Candidate: " . $model->name));
    }

    protected $appends = ['resume_file_url'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Helper to get the full URL of the resume photo
     */
    public function getResumeFileUrlAttribute()
    {
        if (!$this->resume_file_path) {
            return null;
        }

        // Using asset() ensures the URL works in both local and production environments
        return asset('storage/' . $this->resume_file_path);
    }
}