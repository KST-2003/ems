<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonnelAction extends Model
{
    use HasFactory;

    // ဝန်ထမ်းဆိုင်ရာ ဆောင်ရွက်ချက်များ - Template B (big bottom table)
    protected $fillable = [
        'employee_id',
        'type', // enum - recruit, promote, demote, transfer, punishment, partnership
        'position',
        'department',
        'location',
        'start_date',
        'end_date',
        'reason',
        'remark',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}