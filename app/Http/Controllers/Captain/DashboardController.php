<?php

namespace App\Http\Controllers\Captain;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Presenters\CaptainPresenter;
use Auth;

class DashboardController extends Controller
{
    //
    public function __construct(User $user, CaptainPresenter $captainPresenter)
	{
		$this->user = $user;
        $this->captainPresenter = $captainPresenter;
	}

    public function index(Request $request) 
    {
        $param = [
    		'avatar' 		=> Auth::user()['profile'] ? Auth::user()['profile']['avatar'] : null,
    		'searchable'	=> false,
    		'login'			=> true
    	];    	

    	$param = json_encode($param);

        $userInfo = $this->captainPresenter->captainBioCollection(
	                    $this->user->with(['profile', 'captainInfo', 'review'])->where('id', Auth::user()->id)->get()
	                )[0];
        $userInfo = json_encode($userInfo); 

        return view('pages.captain.dashboard', compact('param', 'userInfo'));
    }
}
