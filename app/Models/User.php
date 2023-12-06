<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use RexlManu\LaravelTickets\Traits\HasTickets;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasTickets; // important for laravel-tickets

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'balance',
        'email',
        'password',
        'user_status',
        'terms',
        'country',
        'registration_ip',
        'last_login_ip',
        'business_name',
        'paypal_email',
        'firstname',
        'lastname',
        'street',
        'street_number',
        'zip',
        'city',
        'country',
        'vat',
        'notes',
        'subscription_id',
        'balance_seller',
        'kleinunternehmer'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function verifiedEmail(){
      $this->user_status=1;
      $this->save();
    }
}
