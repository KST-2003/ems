<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeAbroad extends Model
{
   protected $fillable = [
        'employee_id', 'country', 'reason', 'host_name', 'departure_date', 'arrival_date'
    ];

    protected $casts = [
        'departure_date' => 'date',
        'arrival_date' => 'date',
    ];

    public function employee() {
        return $this->belongsTo(Employee::class);
    }
}
