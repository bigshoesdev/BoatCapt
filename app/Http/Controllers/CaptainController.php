<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Review;
use App\Presenters\CaptainPresenter;
use App\Presenters\ReviewPresenter;
use Auth;
use App;

class CaptainController extends Controller
{
    //
    private $limit = 10;

    public function __construct(User $user, Review $review, CaptainPresenter $captainPresenter, ReviewPresenter $reviewPresenter)
	{
		$this->user = $user;
		$this->review = $review;
		$this->captainPresenter = $captainPresenter;
		$this->reviewPresenter = $reviewPresenter;
	}

    public function findCaptains(Request $request) 
    {    
    	$param = [
    		'avatar' 		=> null,
    		'search'		=> $request->search,
    		'searchable'	=> true,
    		'login'			=> false
    	];

        $info = [
            'city'          => null,
            'state'         => null,
            'search'        => $request->search
        ];

        if(isset($request->search) && (App::environment() === 'local')){
            //Formatted address
            $formattedAddr = str_replace(' ','+',$request->search);
            try {
                //Send request and receive json data by address
                $geocodeFromAddr = @file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$formattedAddr.'&sensor=true_or_false&key='.env('GOOGLE_API_KEY'));
            
                $output = json_decode($geocodeFromAddr);

                if(isset($output->results[0]->address_components))
                {
                    $address_components = $output->results[0]->address_components;
                    foreach ($address_components as $address_component) {
                        $addressType = $address_component->types[0];

                        if ($addressType == 'locality') {
                            $info['city'] = $address_component->long_name;
                        }

                        if ($addressType == 'administrative_area_level_1') {
                            $info['state'] = $address_component->short_name;
                        }
                    }
                }   
            } catch (Exception $e) {
            }  

        }

    	if(Auth::check())
    	{
    		$param['login'] = true;
    		$param['avatar'] = isset(Auth::user()->profile->avatar) ? Auth::user()->profile->avatar : null;
            // $info = [
            //     'city'  => (isset(Auth::user()->profile->city) && Auth::user()->profile->city != "") ? Auth::user()->profile->city : null,
            //     'state' => (isset(Auth::user()->profile->state) && Auth::user()->profile->city != "") ? Auth::user()->profile->state : null,
            //     'search'  => $request->search
            // ];

    		if(Auth::user()->role == '1003' || Auth::user()->role == '1001')
    		{
    			$param['searchable'] = false;    			
    		}
    	}

    	$param = json_encode($param);

        $info = json_encode($info);

    	return view('pages.find-captains', compact('param', 'info'));        
    }

    public function captainList(Request $request) 
    {
        $offset = 0;
        $limit = $this->limit;

        if(isset($request->offset))
            $offset = $request->offset;

        if(isset($request->limit) && $request->limit != 0)
            $limit = $request->limit;

        $captaintotal = $this->user->where('role', '1003')
                                ->whereHas('profile', function($q) {
                                    $q->where('isActive', '1');
                                });  

        $captains = $this->user->select('users.*')
                            ->leftJoin('profiles', 'profiles.user_id', '=', 'users.id')
                            ->where('role', '1003')
                            ->whereHas('profile', function($q) {
                                $q->where('isActive', '1');
                            })
                            ->offset($offset)
                            ->limit($limit);

        $lat = isset($request->latlng['lat']) ? $request->latlng['lat'] : null;
        $lon = isset($request->latlng['lng']) ? $request->latlng['lng'] : null;
        $radius = (App::environment() === 'local') ? 50 : 2500;

        if(isset($request->search) && (App::environment() === 'local')){
            //Formatted address
            $formattedAddr = str_replace(' ','+',$request->search);            
            //Send request and receive json data by address

            try {                
                $geocodeFromAddr = @file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$formattedAddr.'&sensor=true_or_false&key='.env('GOOGLE_API_KEY'));
                $output = json_decode($geocodeFromAddr);
                $lat = isset($output->results[0]->geometry->location->lat) ? $output->results[0]->geometry->location->lat : $lat;
                $lon = isset($output->results[0]->geometry->location->lng) ? $output->results[0]->geometry->location->lng : $lon;
                
            } catch (Exception $e) {
            }
            
        }

        // $lat = isset(Auth::user()->profile->lat) ? Auth::user()->profile->lat : $lat;
        // $lon = isset(Auth::user()->profile->lon) ? Auth::user()->profile->lon : $lon;

        // if(isset($request->search))
        // {
        //     $search = $request->search;
        //     $captaintotal->whereHas('profile', function($q) use ($search) {
        //                     $q->where('address', 'like', '%'.$search.'%')
        //                         ->orWhere('address2', 'like', '%'.$search.'%')
        //                         ->orWhere('city', 'like', '%'.$search.'%')
        //                         ->orWhere('state', 'like', '%'.$search.'%')
        //                         ->orWhere('zipcode', 'like', '%'.$search.'%');
        //                 });  

        //     $captains->whereHas('profile', function($q) use ($search) {
        //                     $q->where('address', 'like', '%'.$search.'%')
        //                         ->orWhere('address2', 'like', '%'.$search.'%')
        //                         ->orWhere('city', 'like', '%'.$search.'%')
        //                         ->orWhere('state', 'like', '%'.$search.'%')
        //                         ->orWhere('zipcode', 'like', '%'.$search.'%');
        //                 });  
        // }

        if($lat && $lon)
        {
            $captaintotal->whereHas('profile', function($q) use ($lat, $lon, $radius) {
                            $q->whereRaw('(POW((69.1*(lon-'.$lon.')*COS('.$lat.'/57.3)), 2)+POW((69.1*(lat-'.$lat.')), 2)) < '.($radius*$radius));
                        });

            $captains->selectSub('(POW((69.1*(lon-'.$lon.')*COS('.$lat.'/57.3)), 2)+POW((69.1*(lat-'.$lat.')), 2))', 'away')
                        ->whereHas('profile', function($q) use ($lat, $lon, $radius) {
                            $q->whereRaw('(POW((69.1*(lon-'.$lon.')*COS('.$lat.'/57.3)), 2)+POW((69.1*(lat-'.$lat.')), 2)) < '.($radius*$radius));
                        });
        }

        $totalCount = $captaintotal->count();
        if($totalCount == 0)
        {
            return response()->json([
                'totalCount'    => 0,
                'captainList'      => []
            ]);
        }

        if(isset($request->filter))
        {
            $filter = $request->filter;
            switch ($filter) {
                case 'rating_asc':
                    $captains->orderBy('profiles.rating');
                    break;

                case 'rating_desc':
                    $captains->orderBy('profiles.rating', 'desc');
                    break;

                case 'distance_asc':
                    if($lat && $lon)
                    {
                        $captains->orderBy('away');
                    }
                    break;

                case 'distance_desc':
                    if($lat && $lon)
                    {
                        $captains->orderBy('away', 'desc');
                    }
                    break;

                case 'name_asc':
                    $captains->orderBy('name');
                    break;

                case 'name_desc':
                    $captains->orderBy('name', 'desc');
                    break;
            }
        }
        else
        {
            $captains->orderBy('name');
        }

        $captainList = $this->captainPresenter->captainsCollection(
            $captains->get()
        );

        return response()->json([
            'totalCount'        => $totalCount,
            'captainList'       => $captainList
        ]);
    }

    public function captainBio(Request $request, $captainId) 
    {   
    	$captainInfo = $this->captainPresenter->captainBioCollection(
	                    $this->user->with(['profile', 'captainInfo', 'review'])->where('id', $captainId)->where('role', 1003)->get()
	                );

    	if(count($captainInfo) == 0)
    		return redirect('/');

    	$param = [
    		'avatar' 		=> null,
    		'searchable'	=> true,
    		'login'			=> false
    	];

    	$hire = 0;

    	if(Auth::check())
    	{
    		$param['login'] = true;
    		$param['avatar'] = isset(Auth::user()->profile->avatar) ? Auth::user()->profile->avatar : null;
    		$hire = 1;
    		if(Auth::user()->role == '1003' || Auth::user()->role == '1001')
    		{
    			$param['searchable'] = false;  
    			$hire = 2;  			
    		}
    	}

    	$param = json_encode($param);

        $captainInfo = json_encode($captainInfo[0]);    
    	return view('pages.captain-bio', compact('param', 'captainInfo', 'hire'));    
    }

    public function captainBioReviews(Request $request, $captainId) 
    {   
    	$captainInfo = $this->captainPresenter->captainBioCollection(
	                    $this->user->with(['profile', 'captainInfo', 'review'])->where('id', $captainId)->where('role', 1003)->get()
	                );

    	if(count($captainInfo) == 0)
    		return redirect('/');

    	$param = [
    		'avatar' 		=> null,
    		'searchable'	=> true,
    		'login'			=> false
    	];

    	$hire = 0;

    	if(Auth::check())
    	{
    		$param['login'] = true;
    		$param['avatar'] = isset(Auth::user()->profile->avatar) ? Auth::user()->profile->avatar : null;
    		$hire = 1;
    		if(Auth::user()->role == '1003' || Auth::user()->role == '1001')
    		{
    			$param['searchable'] = false;  
    			$hire = 2;  			
    		}
    	}

    	$param = json_encode($param);

        $captainInfo = json_encode($captainInfo[0]); 

        $reviews = $this->reviewPresenter->transformCollection(
	                    $this->review->with(['profile'])->where('to_user_id', $captainId)->get()
	                );
        $reviews = json_encode($reviews);   
    	return view('pages.captain-bio-reviews', compact('param', 'captainInfo', 'hire', 'reviews'));    
    }

    public function reviewlist(Request $request) 
    {
        $offset = 0;
        $limit = $this->limit;

        if(isset($request->offset))
            $offset = $request->offset;

        if(isset($request->limit) && $request->limit != 0)
            $limit = $request->limit;

        if(!isset($request->captainId))
        {
            return response()->json([
                'totalCount'    => 0,
                'reviewList'     => []
            ]);
        }

        $captainId = $request->captainId;

        $reviewtotal = $this->review->where('to_user_id', $captainId);                           
                            

        $reviews = $this->review->where('to_user_id', $captainId)      
                                ->offset($offset)
                                ->limit($limit);

        $totalCount = $reviewtotal->count();

        if($totalCount == 0)
        {
            return response()->json([
                'totalCount'    => 0,
                'reviewList'     => []
            ]);
        }

        if(isset($request->filter))
        {
            $filter = $request->filter;
            switch ($filter) {

                case 'date_asc':
                    $reviews->orderBy('created_at');
                    break;

                case 'date_desc':
                    $reviews->orderBy('created_at', 'desc');
                    break;    

                case 'rating_asc':
                    $reviews->orderBy('rating');
                    break;

                case 'rating_desc':
                    $reviews->orderBy('rating', 'desc');
                    break;            

                case 'name_asc':
                    $reviews->select('reviews.*')
                            ->selectSub('profiles.firstName', 'firstName')
                            ->selectSub('profiles.lastName', 'lastName')
                            ->leftJoin('profiles', 'reviews.from_user_id', '=', 'profiles.user_id')
                            ->orderBy('firstName')->orderBy('lastName');
                    break;

                case 'name_desc':
                    $reviews->select('reviews.*')
                            ->selectSub('profiles.firstName', 'firstName')
                            ->selectSub('profiles.lastName', 'lastName')
                            ->leftJoin('profiles', 'reviews.from_user_id', '=', 'profiles.user_id')
                            ->orderBy('firstName', 'desc')->orderBy('lastName', 'desc');
                    break;
            }
        }
        else
        {
            $reviews->orderBy('created_at', 'desc');
        }
        

        $reviewList = $this->reviewPresenter->transformCollection(
            $reviews->get()
        );

        return response()->json([
            'totalCount'      => $totalCount,
            'reviewList'      => $reviewList
        ]);
    }

}
