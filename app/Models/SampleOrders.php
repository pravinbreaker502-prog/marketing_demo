<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class SampleOrders extends Model
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;
    
    protected $table = 'sample_orders';
    
    protected $fillable = [
        'customer_id',
        'product_id',
        'employee_id',
        'product_name',
        'quantity',
    ];

    protected $dates = ['deleted_at'];
}
