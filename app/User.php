<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use SoftDeletes;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function profile()
    {
        return $this->hasOne('App\Profile');
    }

    public function ownerInfo()
    {
        return $this->hasOne('App\OwnerInfo');
    }

    public function captainInfo()
    {
        return $this->hasOne('App\CaptainInfo');
    }

    public function review()
    {
        return $this->hasMany('App\Review', 'to_user_id');
    }

    public function paypalEmail()
    {
        return $this->hasOne('App\PaypalEmail', 'user_id');
    }

    public function stripeDetail()
    {
        return $this->hasOne('App\StripeDetail', 'user_id');
    }
}
