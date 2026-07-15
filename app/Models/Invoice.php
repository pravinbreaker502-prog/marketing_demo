<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Invoice extends Model
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;
    
    protected $table = 'invoice_history';
    
    protected $fillable = [
        'customer_id',
        'order_id',
        'invoice_no',
        'invoice',
        'total_amount',
        'paid_amount',
        'pending_amount',
        'payment_status'
    ];

    protected $dates = ['deleted_at'];
}
