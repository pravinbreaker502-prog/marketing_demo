<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Employee extends Model
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;
    
    protected $table = 'employees';

    protected $fillable = [
        'employee_name',
        'page_slug',
        'employee_id',
        'employee_email',
        'employee_mobile',
        'employee_address',
        'employee_profile',
        'employee_dob',
        'employee_blood',
        'employee_adhaar_doc',
        'employee_qualification_doc',
        'employee_experience',
        'employee_resume',
        'employee_passbook_doc',
        'employee_pan_doc',
        'employee_type',
        'vehichle_type',
        'vehichle_license',
        'vehichle_insurance',
        'vehichle_name',
        'vehichle_regno',
        'employee_zone_country',
        'employee_zone_state',
        'employee_zone_city',
        'employee_zone_pincode',
        'username',
        'password',
        'app_token',
        'fcm_token',
        'device_id'
    ];
    
    public function checkPassword($password)
    {
        return md5($password) === $this->password;
    }

    protected $dates = ['deleted_at'];
}
