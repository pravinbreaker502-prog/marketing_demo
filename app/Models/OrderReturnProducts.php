<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class OrderReturnProducts extends Model
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;
    
    protected $table = 'order_return_products';
    
    protected $fillable = [
        'customer_id',
        'employee_id',
        'product_id',
        'order_id',
        'invoice_id',
        'quantity',
        'discount',
        'discount_amount',
        'gst_per',
        'gst_amount',
        'actual_amount',
        'total_amount',
        'compensated_amount',
        'returned_amount'
    ];

    protected $dates = ['deleted_at'];
}
