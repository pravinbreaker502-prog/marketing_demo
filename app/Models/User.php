<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'users';
    protected $fillable = [
        'user_id',
        'username',
        'password',
        'user_type',
        'fcm_token'
    ];
    
    public function getAuthPassword()
    {
        return $this->password;
    }

    public function validatePassword($password)
    {
        return md5($password) === $this->password;
    }

    protected $dates = ['deleted_at'];

}
