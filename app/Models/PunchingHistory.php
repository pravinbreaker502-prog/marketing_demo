<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class PunchingHistory extends Model
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;
    
    protected $table = 'punching_history';
    
    protected $fillable = [
        'in_id',
        'employee_id',
        'punch_type',
        'faceimage_path',
        'odoimage_path',
        'odometer_km'
    ];

    protected $dates = ['deleted_at'];
}
