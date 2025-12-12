<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeChild extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'name',
        'date_of_birth',
    ];

    // Cast date automatically
    protected $casts = ['date_of_birth'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}