<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeParentSibling extends Model
{
    protected $fillable = [
        'employee_id',
        'side',
        'name',
        'nationality_religion',
        'hometown',
        'relation',
        'job',
        'location'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
