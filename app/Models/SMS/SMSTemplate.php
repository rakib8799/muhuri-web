<?php

namespace App\Models\SMS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SMSTemplate extends Model
{
    use HasFactory;
    protected $table = 'sms_templates';

    protected $fillable = [
        'name',
        'slug',
        'sms_template_en',
        'sms_template_bn',
        'is_active',
        'central_id'
    ];
}
