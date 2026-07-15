<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class PaymentHistory extends Model
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;
    
    protected $table = 'payment_history';
    
    protected $fillable = [
        'customer_id',
        'invoice_id',
        'invoice_no',
        'amount',
        'payment_status'
    ];

    protected $dates = ['deleted_at'];
}
