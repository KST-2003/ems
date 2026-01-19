<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyCalendar extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     * * @var string
     */
    protected $table = 'company_calendars';

    /**
     * The attributes that are mass assignable.
     * * @var array
     */
    protected $fillable = [
        'date',
        'name',
        'type',
        'description',
    ];

    /**
     * The attributes that should be cast.
     * * @var array
     */
    protected $casts = [
        'date' => 'date',
    ];

    /**
     * Scope a query to only include holidays or close exceptions.
     * This is useful for blocking the attendance call sheet.
     */
    public function scopeIsClosed($query)
    {
        return $query->whereIn('type', ['holiday', 'close_exception']);
    }

    /**
     * Scope a query to only include open exceptions (working weekends).
     */
    public function scopeIsWorkDay($query)
    {
        return $query->where('type', 'open_exception');
    }
}