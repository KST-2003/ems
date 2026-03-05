<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class EmployeeCriminalRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'name', // New Field
        'start_date', // New Field
        'end_date', // New Field
        'description',
        'file_path',
    ];

    protected $appends = ['file_url'];

    // Cast dates automatically
    protected $casts = ['start_date', 'end_date'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function getFileUrlAttribute()
    {
        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk(config('filesystems.default'));
        $path = 'criminal_records/' . $this->file_path;
        return $this->file_path && $disk->exists($path) ? '/storage/' . $path : null;
    }
}