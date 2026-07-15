<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Orders extends Model
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;
    
    protected $table = 'orders';
    
    protected $fillable = [
        'customer_id',
        'product_id',
        'employee_id',
        ',invoice_id',
        'invoice_no',
        'product_name',
        'quantity',
        'discount',
        'discount_amt',
        'gst_per',
        'gst_amt',
        'actual_amt',
        'total_amt',
        'order_date',
        'delivery_date',
        'status'
    ];

    protected $dates = ['deleted_at'];
}
