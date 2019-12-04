<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BidRequest extends Model
{
    //
    use SoftDeletes;

    protected $guarded = [];

    public function trip()
    {
        return $this->hasOne('App\Trip', 'id', 'trip_id');
    }

    public function profile()
 	{
 		return $this->belongsTo('App\Profile', 'user_id', 'user_id');
 	}
}
