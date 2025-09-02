<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Recruitment extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'name',
        'email',
        'position',
        'status',
        'resume',
        'resume_file_path',
    ];

    protected $appends = ['resume_file_url'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function getResumeFileUrlAttribute()
    {
        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk('public');
        $path = 'recruitments/' . $this->resume_file_path;
        return $this->resume_file_path && $disk->exists($path) ? '/storage/' . $path : null;
    }
}