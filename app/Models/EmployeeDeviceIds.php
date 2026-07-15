<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class EmployeeDeviceIds extends Model
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;
    
    protected $table = 'employee_device_ids';

    protected $fillable = [
        'employee_id',
        'device_id'
    ];

    protected $dates = ['deleted_at'];
}
