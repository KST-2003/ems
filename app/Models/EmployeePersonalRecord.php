<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeePersonalRecord extends Model
{
    protected $fillable = [
        'employee_id', 'schools', 'latest_school', 'school_voluntary', 
        'hobbies', 'jobs_dept', 'refugee', 'jobtransfer_desc', 
        'citizen_duties', 'relatives_officials', 'foreign_friends_desc', 
        'referal_officials', 'has_criminal_rec'
    ];

    public function employee() {
        return $this->belongsTo(Employee::class);
    }
}
