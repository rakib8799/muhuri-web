<?php

namespace App\Models\HR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class JobPosition extends Model
{
    use HasFactory, LogsActivity;

    use SoftDeletes;

    protected $table = "hr_job_positions";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'central_id',
        'is_active',
        'created_by',
        'updated_by'
    ];

    /**
     * Employee that belongs to the job position
     */
    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'hr_employee_hr_job_position', 'job_position_id', 'employee_id');
    }

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
        if ($eventName === 'created') {
            return "Created new job position - $this->name";
        } elseif ($eventName === 'updated') {
            if ($this->isDirty('is_active')) {
                return "Changed the status of the job position - $this->name";
            }
            return "Updated the job position - $this->name";
        } elseif ($eventName === 'deleted') {
            return "Deleted the job position - $this->name";
        }

        // Default description if event is not handled
        return "Job Position {$eventName}.";
    }
}
