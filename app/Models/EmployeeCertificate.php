<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class EmployeeCertificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'certificate_name',
        'issue_date',
        'issuer',
        'description',
        'file_path',
    ];

    protected $appends = ['file_url'];

    protected $casts = [
        'issue_date' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function getFileUrlAttribute()
    {
        /** @var \Illuminate\Filesystem\FilesystemAdapter $disk */
        $disk = Storage::disk('public');
        $path = 'certificates/' . $this->file_path;
        return $this->file_path && $disk->exists($path) ? '/storage/' . $path : null;
    }
}
