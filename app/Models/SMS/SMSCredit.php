<?php

namespace App\Models\SMS;

use Illuminate\Database\Eloquent\Model;

class SMSCredit extends Model
{
    protected $table = 'sms_credits';
    protected $fillable = [
        'total_credits',
        'used_credits',
        'remaining_credits',
    ];
}
