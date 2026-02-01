<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeSpouse extends Model
{
    protected $fillable = [
        'employee_id', 'name', 'nationality_religion', 'hometown', 'job', 'address'
    ];

    public function employee() {
        return $this->belongsTo(Employee::class);
    }
}
