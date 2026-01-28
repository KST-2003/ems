<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Loggable;

class Holiday extends Model
{

    use Loggable;
    protected $fillable = ['date', 'name', 'type'];
    protected static function booted()
    {
        // Logs when a new holiday is added via the "Save to Calendar" form
        static::created(fn($model) => self::logAction("Created Holiday", "Name: " . $model->name . " | Date: " . $model->date));

        // Logs when a holiday is deleted using the red trash icon
        static::deleted(fn($model) => self::logAction("Deleted Holiday", "Name: " . $model->name));
    }
}
