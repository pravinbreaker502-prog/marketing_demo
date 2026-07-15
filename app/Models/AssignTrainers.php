<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class AssignTrainers extends Model
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;
    
    protected $table = 'assign_trainers_toschool';
    
    protected $fillable = [
        'customer_id',
        'trainer_id',
        'no_of_teachers',
        'assigned_from',
        'assigned_end',
        'started_from',
        'training_end',
        'process_status',
        'reason'
    ];

    protected $dates = ['deleted_at'];
}
