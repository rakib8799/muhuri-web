<?php

namespace App\Models\SMS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SMSLog extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'sms_logs';

    protected $fillable = [
        'mobile_number',
        'request_body',
        'response_body',
        'sms_type',
        'message',
        'sms_count',
        'status',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'request_body' => 'array',
        ];
    }
}
