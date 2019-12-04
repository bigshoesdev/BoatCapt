<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Trip;
use App\User;
use App\Presenters\TripPresenter;
use Auth;

class TripController extends Controller
{
    //
    private $limit = 10;

    public function __construct(Trip $trip, User $user, TripPresenter $tripPresenter)
    {
        $this->trip = $trip;
        $this->user = $user;
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

        $users = $this->user->select('users.last_login')->where('id','=',Auth::user()->id)->first();
        if(is_null($users))
        {
            return redirect('/');
        }
        $lastDate = $users['last_login'];

        $trips = $this->trip
                                    ->where('tripId', '<>', null)
                                    ->where('trips.created_at', '>', $lastDate)
                                    ->join('bid_requests','trips.id','bid_requests.trip_id');
        $newTripsCount=$trips->distinct('trips.id')->count('trips.id');

        return view('pages.admin.trips', compact('param','newTripsCount'));
    }

    public function tripList(Request $request) 
    {
        $offset = 0;
        $limit = $this->limit;

        if(isset($request->offset))
            $offset = $request->offset;

        if(isset($request->limit) && $request->limit != 0)
            $limit = $request->limit;

        $triptotal = $this->trip->where('tripId', '<>', null)
                                ->join('bid_requests','trips.id','bid_requests.trip_id');

        if(isset($request->startDate))
            $triptotal->where('startTime', '>', date('Y-m-d', strtotime($request->startDate)));
        if(isset($request->endDate))
            $triptotal->where('startTime', '<', date('Y-m-d', strtotime('+1 day', strtotime($request->endDate))));

        $totalCount = $triptotal->distinct('trips.id')->count('trips.id');
        if($totalCount == 0)
        {
            return response()->json([
                'totalCount'    => 0,
                'tripList'      => []
            ]);
        }

        $trips = $this->trip->select('trips.*')->distinct('trips.id')
                                    ->selectSub('profiles.firstName', 'firstName')
                                    ->selectSub('profiles.lastName', 'lastName')
                                    ->selectSub('ROUND(bids.hours * bids.amount * 100)', 'total')
                                    ->selectSub('endTime<UTC_TIMESTAMP', 'isCompleted')
                                    ->leftJoin('profiles', 'trips.user_id', '=', 'profiles.user_id')
                                    ->leftJoin('bids', 'trips.id', '=', 'bids.trip_id')
                                    ->join('bid_requests','trips.id','bid_requests.trip_id')
                                    ->where('tripId', '<>', 'null')
                                    ->offset($offset)
                                    ->limit($limit);

        if(isset($request->startDate))
            $trips->where('startTime', '>', date('Y-m-d', strtotime($request->startDate)));
        if(isset($request->endDate))
            $trips->where('startTime', '<', date('Y-m-d', strtotime('+1 day', strtotime($request->endDate))));

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
                    $trips->orderBy('total');
                    break;

                case 'total_desc':
                    $trips->orderBy('total', 'desc');
                    break;

                case 'owner_asc':
                    $trips->orderBy('firstName')->orderBy('lastName');
                    break;

                case 'owner_desc':
                    $trips->orderBy('firstName', 'desc')->orderBy('lastName', 'desc');
                    break;
            }
        }
        else
        {
            $trips->orderBy('startTime', 'desc');
        }

        $tripList = $this->tripPresenter->tripCollection(
            $trips->get()
        );

        return response()->json([
            'totalCount'    => $totalCount,
            'tripList'      => $tripList
        ]);
    }

    public function detail($tripId) 
    {
        $param = [
    		'avatar' 		=> Auth::user()['profile'] ? Auth::user()['profile']['avatar'] : null,
    		'searchable'	=> false,
    		'login'			=> true
    	];    	

    	$param = json_encode($param);

        $tripInfo = $this->tripPresenter->ownerDetailCollection(
            $this->trip->where('bid_id', '<>', null)
                    ->where('tripId', $tripId)
                    ->get()
        );

        if(count($tripInfo) == 0)
            return redirect('/');
        
        $tripInfo = json_encode($tripInfo[0]); 

        $isAdmin = 1;

        return view('pages.owner.trip-detail', compact('param', 'tripInfo', 'isAdmin'));
    	
    }
}
