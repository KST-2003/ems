<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeRelative extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'name',
        'relation', // How they are related (e.g., "Brother", "Uncle")
        'job',
        'location',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}