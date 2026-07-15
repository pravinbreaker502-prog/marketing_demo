<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Customer extends Model
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;
    
    protected $table = 'customers';
    
    protected $fillable = [
        'company_name',
        'page_slug',
        'client_name',
        'client_email',
        'client_mobile',
        'client_address',
        'gst_no',
        'sort_order'
    ];

    protected $dates = ['deleted_at'];
    
}
