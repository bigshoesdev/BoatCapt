<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class LanderController extends Controller
{
    //
    public function index() 
    { 
    	$param = [
    		'avatar' 		=> null,
    		'searchable'	=> true,
    		'login'			=> false,
    		'hide'			=> true
    	];

        $info = [
            'city'  => null,
            'state' => null
        ];

    	if(Auth::check())
    	{
            $info = [
                'city'  => (isset(Auth::user()->profile->city) && Auth::user()->profile->city != "") ? Auth::user()->profile->city : null,
                'state' => (isset(Auth::user()->profile->state) && Auth::user()->profile->city != "") ? Auth::user()->profile->state : null
            ];

    		$param['login'] = true;
    		$param['avatar'] = Auth::user()['profile'] ? Auth::user()['profile']['avatar'] : null;
    		if(Auth::user()->role == '1003')
    		{
    			$param['searchable'] = false;    			
    		}
    	}

    	$param = json_encode($param);

        $info = json_encode($info);
    	return view('pages.lander', compact('param', 'info'));
    }

    public function about() 
    { 
    	$param = [
    		'avatar' 		=> null,
    		'searchable'	=> true,
    		'login'			=> false
    	];

    	if(Auth::check())
    	{
    		$param['login'] = true;
    		$param['avatar'] = Auth::user()['profile'] ? Auth::user()['profile']['avatar'] : null;
    		if(Auth::user()->role == '1003')
    		{
    			$param['searchable'] = false;    			
    		}
    	}

    	$param = json_encode($param);
    	return view('pages.about', compact('param'));
    }

    public function contact() 
    { 
    	$param = [
    		'avatar' 		=> null,
    		'searchable'	=> true,
    		'login'			=> false
    	];

    	if(Auth::check())
    	{
    		$param['login'] = true;
    		$param['avatar'] = Auth::user()['profile'] ? Auth::user()['profile']['avatar'] : null;
    		if(Auth::user()->role == '1003')
    		{
    			$param['searchable'] = false;    			
    		}
    	}

    	$param = json_encode($param);
    	return view('pages.contact', compact('param'));
    }

    public function terms() 
    { 
    	$param = [
    		'avatar' 		=> null,
    		'searchable'	=> true,
    		'login'			=> false
    	];

    	if(Auth::check())
    	{
    		$param['login'] = true;
    		$param['avatar'] = Auth::user()['profile'] ? Auth::user()['profile']['avatar'] : null;
    		if(Auth::user()->role == '1003')
    		{
    			$param['searchable'] = false;    			
    		}
    	}

    	$param = json_encode($param);
    	return view('pages.terms', compact('param'));
    }
}
