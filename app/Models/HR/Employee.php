<?php

namespace App\Models\HR;

use App\Models\User;
use App\Models\Country;
use App\Models\Branch\Branch;
use App\Models\HR\Leave\Leave;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use App\Models\HR\Leave\LeaveAllocation;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model
{
    use HasFactory, LogsActivity;

    protected $table = "hr_employees";

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'user_id',
        'country_id',
        'is_departed',
        'dob',
        'gender',
        'marital_status',
        'social_security_number',
        'mobile_number',
        'address_line_one',
        'address_line_two',
        'floor',
        'city',
        'state',
        'zip_code',
        'emergency_contact_name',
        'emergency_contact_number',
        'job_positions',
        'department_id',
        'branch_id',
        'manager_id',
        'leave_manager_id',
        'staff_id',
        'salary',
        'hourly_rate',
        'hour_limit_weekly',
        'over_time_rate',
        'over_time_limit',
        'joining_date',
        'is_nfc_card_issued',
        'is_geo_fencing_enabled',
        'is_photo_taking_enabled',
        'job_title',
        'blood_group',
        'etin_number',
        'linkedin_profile',
        'departure_reason_id',
        'departure_description',
        'departure_date',
        'passport_number',
        'work_permit_number',
        'visa_number',
        'visa_expire_date',
        'work_permit_expiration_date',
        'permanent_address',
        'emergency_contact_relation',
        'last_attendance_id',
        'last_check_in',
        'last_check_out',
        'is_active',
        'created_by',
        'updated_by'
    ];

    /**
     * Job positions that belongs to the employee
     */
    public function jobPositions()
    {
        return $this->belongsToMany(JobPosition::class, 'hr_employee_hr_job_position', 'employee_id', 'job_position_id');
    }

    /**
     * Get attendance for the employee
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Get Last Attendance
     */
    public function lastAttendance()
    {
        return $this->belongsTo(Attendance::class, 'last_attendance_id');
    }

    /**
     * Get corresponding country of an employee
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Get corresponding user of an employee
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get corresponding department of an employee
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get corresponding branch of an employee
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function manager()
    {
        return $this->belongsTo(Employee::class, 'manager_id');
    }

    public function leaveManager()
    {
        return $this->belongsTo(Employee::class, 'leave_manager_id');
    }

    /**
     * Get corresponding departure reason of an employee
     */
    public function departureReason()
    {
        return $this->belongsTo(DepartureReason::class, 'departure_reason_id');
    }

    public function getFullName(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function leaveAllocation()
    {
        return $this->hasMany(LeaveAllocation::class);
    }

    public function leaves()
    {
        return $this->hasMany(Leave::class);
    }

    // Cast 'job_positions' attribute to array
    protected $casts = [
        'job_positions' => 'array'
    ];

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
            return "Created new employee - " . $this->getFullName();
        } elseif ($eventName === 'updated') {
            if ($this->isDirty('is_active')) {
                return "Changed the status of the employee - " . $this->getFullName();
            }
            return "Updated the employee - " . $this->getFullName();
        } elseif ($eventName === 'deleted') {
            return "Deleted the employee - " . $this->getFullName();
        }

        // Default description if event is not handled
        return "Employee {$eventName}.";
    }
}
