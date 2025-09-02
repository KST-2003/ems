<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'name',
        'email',
        'phone',
        'gender',
        'profile_image',
        'dob',
        'nationality',
        'father_name',
        'mother_name',
        'nrc',
        'spouse_name',
        'children_names',
        'address',
        'education',
        'current_position',
        'salary',
        'department',
        'blood_type',
    ];

    protected $appends = ['profile_image_url', 'total_experience_years'];

    public function experiences()
    {
        return $this->hasMany(EmployeeExperience::class);
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
        return $this->hasMany(EmployeeLeave::class, 'employee_id', 'id');
    }

    public function recruitments()
    {
        return $this->hasMany(Recruitment::class);
    }

    public function getProfileImageUrlAttribute()
    {
        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
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

    public function getTotalExperienceYearsAttribute()
    {
        $totalDays = 0;

        foreach ($this->experiences as $experience) {
            $fromDate = \Carbon\Carbon::parse($experience->from_date);
            $toDate = $experience->is_current ? now() : ($experience->to_date ? \Carbon\Carbon::parse($experience->to_date) : $fromDate);
            $totalDays += $fromDate->diffInDays($toDate);
        }

        return round($totalDays / 365, 2);
    }
}