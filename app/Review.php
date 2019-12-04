<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    //
    protected $guarded = [];

    public function profile()
    {
        return $this->hasOne('App\Profile', 'user_id', 'from_user_id');
    }
}
