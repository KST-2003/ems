<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'name',
        'phone',
        'gender',
        'profile_image',
        'mm_dob',
        'eng_dob',
        'nationality',
        'religion',
        'father_name',
        'mother_name',
        'nrc',
        'spouse_name',
        'spouse_job',
        'spouse_job_place',
        'current_address',
        'permenant_address',
        'current_position',
        'salary',
        'department',
        'blood_type',
        'lang_proficiency',
        'hobby',
    ];

    protected $appends = ['profile_image_url', 'total_experience_years'];

    // Modern casting for dates
    protected $casts = [
        'mm_dob' => 'date',
        'eng_dob' => 'date',
    ];

    /** --- Relationships --- */

    public function experiences()
    {
        return $this->hasMany(EmployeeExperience::class);
    }

    public function pastExperiences()
    {
        return $this->hasMany(EmployeePastExperience::class);
    }

    public function certificates()
    {
        return $this->hasMany(EmployeeCertificate::class);
    }

    public function criminalRecords()
    {
        return $this->hasMany(EmployeeCriminalRecord::class);
    }

    public function attendances()
    {
        return $this->hasMany(EmployeeAttendance::class);
    }

    public function leaves()
    {
        return $this->hasMany(EmployeeLeave::class);
    }

    public function absences()
    {
        return $this->hasMany(EmployeeAbsence::class);
    }

    public function recruitments()
    {
        return $this->hasMany(Recruitment::class);
    }

    public function educations()
    {
        return $this->hasMany(EmployeeEducation::class);
    }

    public function trainings()
    {
        return $this->hasMany(EmployeeTraining::class);
    }

    public function children()
    {
        return $this->hasMany(EmployeeChild::class);
    }

    public function relatives()
    {
        return $this->hasMany(EmployeeRelative::class);
    }

    public function serviceRecords()
    {
        return $this->hasMany(EmployeeServiceRecord::class);
    }

    /** --- Accessors --- */

    public function getProfileImageUrlAttribute(): ?string
    {
        $disk = Storage::disk('public');
        $path = 'employees/' . $this->profile_image;
        
        if ($this->profile_image && $disk->exists($path)) {
            return '/storage/' . $path;
        }

        return asset('images/' . match ($this->gender) {
            'male' => 'male-default.png',
            'female' => 'female-default.png',
            default => 'no-profile.png',
        });
    }

    public function getTotalExperienceYearsAttribute(): float
    {
        $totalDays = 0;

        // Eager loading protection (optional check)
        if ($this->relationLoaded('experiences')) {
            foreach ($this->experiences as $experience) {
                // Since we cast dates in EmployeeExperience, these are already Carbon or null
                $start = $experience->from_date; 
                
                if (!$start) continue; // Skip if start date is missing

                $end = $experience->is_current 
                    ? Carbon::now() 
                    : ($experience->to_date ?? $start);

                $totalDays += $start->diffInDays($end);
            }
        }

        return round($totalDays / 365, 2);
    }
}