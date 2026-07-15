<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class LeaveRequests extends Model
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;
    
    protected $table = 'leave_requests';
    
    protected $fillable = [
        'employee_id',
        'leave_type',
        'from_date',
        'to_date',
        'leave_reason',
        'reject_reason',
        'status'
    ];

    protected $dates = ['deleted_at'];
}
