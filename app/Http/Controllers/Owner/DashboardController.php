<?php

namespace App\Http\Controllers\Owner;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Bid;
use App\Presenters\OwnerPresenter;
use App\Presenters\BidPresenter;
use Auth;

class DashboardController extends Controller
{
    //
    public function __construct(User $user, OwnerPresenter $ownerPresenter, Bid $bid, BidPresenter $bidPresenter)
	{
		$this->user = $user;
        $this->bid = $bid;
		$this->ownerPresenter = $ownerPresenter;
        $this->bidPresenter = $bidPresenter;
	}

    public function index(Request $request) 
    {
        $param = [
    		'avatar' 		=> isset(Auth::user()->profile->avatar) ? Auth::user()->profile->avatar : null,
    		'searchable'	=> true,
    		'login'			=> true
    	];    	

    	$param = json_encode($param);

        $userInfo = $this->ownerPresenter->ownerCollection(
	                    $this->user->with(['profile', 'ownerInfo', 'review'])->where('id', Auth::user()->id)->get()
	                )[0];
        $userInfo = json_encode($userInfo); 

        return view('pages.owner.dashboard', compact('param', 'userInfo'));
    }
}
