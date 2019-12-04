<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Trip extends Model
{
    //
    use SoftDeletes;
    protected $guarded = [];

    public function profile()
    {
        return $this->hasOne('App\Profile', 'user_id', 'user_id');
    }

    public function bid()
    {
        return $this->hasOne('App\Bid', 'id', 'bid_id')->withTrashed();
    }

    public function ownerInfo()
    {
        return $this->hasOne('App\OwnerInfo', 'user_id', 'user_id');
    }

    public function merchantTransaction()
    {
        return $this->hasOne('App\MerchantTransaction', 'id', 'merchant_transaction_id');
    }

    public function paypalEmail()
    {
        return $this->hasOne('App\PaypalEmail', 'user_id', 'user_id');
    }

    public function stripeDetail()
    {
        return $this->hasOne('App\StripeDetail', 'user_id', 'user_id');
    }

}
