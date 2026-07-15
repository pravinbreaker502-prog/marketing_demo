<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class FollowupHistory extends Model
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;
    
    protected $table = 'customers_follows_history';

    protected $fillable = [
        'employee_id',
        'customer_id',
        'sample_ids',
        'order_ids',
        'in_time',
        'out_time',
        'accept_status',
        'followup_date',
        'training'
    ];

    protected $dates = ['deleted_at'];
}
