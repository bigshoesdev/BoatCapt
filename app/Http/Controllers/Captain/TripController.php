<?php

namespace App\Http\Controllers\Captain;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Trip;
use App\Bid;
use App\Presenters\CaptainPresenter;
use App\Presenters\TripPresenter;
use Auth;

class TripController extends Controller
{
    //
    private $limit = 10;

    public function __construct(User $user, Trip $trip, CaptainPresenter $captainPresenter, TripPresenter $tripPresenter)
	{
		$this->user = $user;
        $this->trip = $trip;
		$this->captainPresenter = $captainPresenter;
        $this->tripPresenter = $tripPresenter;
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
        return view('pages.captain.trips', compact('param', 'userInfo'));
    }

    public function tripList(Request $request) 
    {
        $offset = 0;
        $limit = $this->limit;

        if(isset($request->offset))
            $offset = $request->offset;

        if(isset($request->limit) && $request->limit != 0)
            $limit = $request->limit;

        $triptotal = $this->trip->whereHas('bid', function($q) {
                                $q->where('user_id',  Auth::user()->id);
                            })
                            ->where('bid_id', '<>', null);                  
                            

        $trips = $this->trip->whereHas('bid', function($q) {
                                $q->where('user_id',  Auth::user()->id);
                            })
                            ->where('bid_id', '<>', null)  
                            ->offset($offset)
                            ->limit($limit);

        if($request->isComplete == 1)
        {
            $triptotal->where('endTime', '<', date('Y-m-d H:i:s'));
            $trips->where('endTime', '<', date('Y-m-d H:i:s'));
        }
        else
        {
            $triptotal->where('endTime', '>', date('Y-m-d H:i:s'));
            $trips->where('endTime', '>', date('Y-m-d H:i:s'));
        }

        $tripCount = $triptotal->count();

        if($tripCount == 0)
        {
            return response()->json([
                'tripCount'    => 0,
                'tripList'      => []
            ]);
        }

        if(isset($request->filter))
        {
            $filter = $request->filter;
            switch ($filter) {
                case 'date_asc':
                    $trips->orderBy('startTime');
                    break;

                case 'date_desc':
                    $trips->orderBy('startTime', 'desc');
                    break;

                case 'total_asc':
                    $trips->select('trips.*')
                            ->selectSub('ROUND(bids.hours * bids.amount * 100)', 'total')
                            ->leftJoin('bids', 'trips.id', '=', 'bids.trip_id')
                            ->orderBy('total');
                    break;

                case 'total_desc':
                    $trips->select('trips.*')
                            ->selectSub('ROUND(bids.hours * bids.amount * 100)', 'total')
                            ->leftJoin('bids', 'trips.id', '=', 'bids.trip_id')
                            ->orderBy('total', 'desc');
                    break;

                case 'name_asc':
                    $trips->select('trips.*')
                            ->selectSub('profiles.firstName', 'firstName')
                            ->selectSub('profiles.lastName', 'lastName')
                            ->leftJoin('bids', 'trips.id', '=', 'bids.trip_id')
                            ->leftJoin('profiles', 'bids.user_id', '=', 'profiles.user_id')
                            ->orderBy('firstName')->orderBy('lastName');
                    break;

                case 'name_desc':
                    $trips->select('trips.*')
                            ->selectSub('profiles.firstName', 'firstName')
                            ->selectSub('profiles.lastName', 'lastName')
                            ->leftJoin('profiles', 'trips.user_id', '=', 'profiles.user_id')
                            ->orderBy('firstName', 'desc')->orderBy('lastName', 'desc');
                    break;
            }
        }
        else
        {
            $trips->orderBy('startTime', 'desc');
        }
        

        $tripList = $this->tripPresenter->captainTripsCollection(
            $trips->get()
        );

        return response()->json([
            'tripCount'      => $tripCount,
            'tripList'       => $tripList
        ]);
    }    

    public function detail($tripId) 
    {
        $param = [
            'avatar'        => Auth::user()['profile'] ? Auth::user()['profile']['avatar'] : null,
            'searchable'    => false,
            'login'         => true
        ];      

        $param = json_encode($param);

        $tripInfo = $this->tripPresenter->captainDetailCollection(
                            $this->trip->with(['profile', 'bid', 'ownerInfo'])
                                    ->where('tripId', $tripId)
                                    ->get()
                        );
        
        if(count($tripInfo) == 0)
            return redirect('/');
        
        $tripInfo = json_encode($tripInfo[0]); 

        $readOnly = 0;

        return view('pages.captain.trip-detail', compact('param', 'tripInfo', 'readOnly'));
        
    }

    public function contactOwner($tripId) 
    {
        $param = [
            'avatar'        => Auth::user()['profile'] ? Auth::user()['profile']['avatar'] : null,
            'searchable'    => false,
            'login'         => true
        ];      

        $param = json_encode($param);

        $tripInfo = $this->tripPresenter->captainDetailCollection(
                            $this->trip->with(['profile', 'bid', 'ownerInfo'])
                                    ->where('bid_id', '<>', null)
                                    ->where('tripId', $tripId)
                                    ->get()
                        );
        
        if(count($tripInfo) == 0)
            return redirect('/');
        
        $tripInfo = json_encode($tripInfo[0]); 
        return view('pages.captain.contact-owner', compact('param', 'tripInfo'));
        
    }
}
