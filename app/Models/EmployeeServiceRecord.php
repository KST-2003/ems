<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeServiceRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'grade', // junior, senior, selection, higher
        'recruited_date', 
        'remark', 
    ];

    // Cast the date field
    protected $casts = [
        'recruited_date' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}