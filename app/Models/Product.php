<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Product extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $table = 'products';
    
    protected $fillable = [
        'standard',
        'category_id',
        'product',
        'page_slug',
        'quantity',
        'actual_price',
        'discount',
        'discount_amt',
        'sell_price',
        'gst',
        'gst_amt',
        'product_purprice',
        'sort_order'
    ];

    protected $dates = ['deleted_at'];

}
