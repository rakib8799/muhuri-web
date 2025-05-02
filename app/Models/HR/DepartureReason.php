<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;

class DepartureReason extends Model
{
    use HasFactory;

    protected $table = "hr_departure_reasons";

    protected $fillable = [
        'name',
        'slug'
    ];

    /**
     * Add Activity to activityLogs table
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['*']);
    }

    /**
     * Changed the activity description of events
     */
    public function getDescriptionForEvent(string $eventName): string
    {
        if ($eventName === 'updated') {
            return "Updated the departure reason - $this->name";
        }

        // Default description if event is not handled
        return "Departure reason {$eventName}.";
    }
}
