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
        'description',
        'file_path',
    ];

    protected $appends = ['file_url'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function getFileUrlAttribute()
    {
        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk('public');
        $path = 'criminal_records/' . $this->file_path;
        return $this->file_path && $disk->exists($path) ? '/storage/' . $path : null;
    }
}