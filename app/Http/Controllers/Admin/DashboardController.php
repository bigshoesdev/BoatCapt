<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Trip;
use App\Review;
use Auth;

class DashboardController extends Controller
{
    //
    private $limit = 10;

    public function __construct(User $user, Trip $trip, Review $review)
    {
        $this->user = $user;
        $this->trip = $trip;
        $this->review = $review;
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

        return view('pages.admin.dashboard', compact('param','newTripsCount'));
    }

    public function infoList(Request $request) 
    {
        $cTrip = $this->trip->where('bid_id', '<>', null)->join('bid_requests','trips.id','bid_requests.trip_id');
        if(isset($request->startDate))
            $cTrip->where('startTime', '>', date('Y-m-d', strtotime($request->startDate)));
        if(isset($request->endDate))
            $cTrip->where('startTime', '<', date('Y-m-d', strtotime('+1 day', strtotime($request->endDate))));
        $trip = $cTrip->distinct('trips.id')->count('trips.id');

        $cRevenue = $this->trip->selectSub('SUM(merchant_transactions.amount) * 100', 'sum')
                    ->where('bid_id', '<>', null)
                    ->whereRaw('endTime<UTC_TIMESTAMP')
                    ->leftJoin('merchant_transactions', 'trips.merchant_transaction_id', '=', 'merchant_transactions.id');
        if(isset($request->startDate))
            $cRevenue->where('startTime', '>', date('Y-m-d', strtotime($request->startDate)));
        if(isset($request->endDate))
            $cRevenue->where('startTime', '<', date('Y-m-d', strtotime('+1 day', strtotime($request->endDate))));
        $fRevenue = $cRevenue->first();
        $revenue=0;
        if(!is_null($fRevenue))$revenue=intval($fRevenue->sum);

        $cCaptain = $this->user->where('role', '=', '1003')
                                    ->join('profiles', 'users.id', '=', 'profiles.user_id');
        if(isset($request->startDate))
            $cCaptain->where('profiles.created_at', '>', date('Y-m-d', strtotime($request->startDate)));
        if(isset($request->endDate))
            $cCaptain->where('profiles.created_at', '<', date('Y-m-d', strtotime('+1 day', strtotime($request->endDate))));
        $captain = $cCaptain->count();

        $cOwner = $this->user->where('role', '=', '1002')
                                    ->join('profiles', 'users.id', '=', 'profiles.user_id');
        if(isset($request->startDate))
            $cOwner->where('profiles.created_at', '>', date('Y-m-d', strtotime($request->startDate)));
        if(isset($request->endDate))
            $cOwner->where('profiles.created_at', '<', date('Y-m-d', strtotime('+1 day', strtotime($request->endDate))));
        $owner = $cOwner->count();

        $cReview = $this->review;
        if(isset($request->startDate))
            $cReview->where('created_at', '>', date('Y-m-d', strtotime($request->startDate)));
        if(isset($request->endDate))
            $cReview->where('created_at', '<', date('Y-m-d', strtotime('+1 day', strtotime($request->endDate))));
        $review = $cReview->count();

        $cPayment = $this->trip->selectSub('SUM(merchant_transactions.amount) * 90', 'sum')
                    ->where('bid_id', '<>', null)
                    ->whereRaw('endTime<UTC_TIMESTAMP')
                    ->leftJoin('merchant_transactions', 'trips.merchant_transaction_id', '=', 'merchant_transactions.id');
        if(isset($request->startDate))
            $cPayment->where('startTime', '>', date('Y-m-d', strtotime($request->startDate)));
        if(isset($request->endDate))
            $cPayment->where('startTime', '<', date('Y-m-d', strtotime('+1 day', strtotime($request->endDate))));
        $fPayment = $cPayment->first();
        $payment=0;
        if(!is_null($fPayment))$payment=intval($fPayment->sum);

        $cFee = $this->trip->selectSub('SUM(merchant_transactions.transaction_fee)*100', 'sum')
                    ->where('bid_id', '<>', null)
                    ->whereRaw('endTime<UTC_TIMESTAMP')
                    ->leftJoin('merchant_transactions', 'trips.merchant_transaction_id', '=', 'merchant_transactions.id');
        if(isset($request->startDate))
            $cFee->where('startTime', '>', date('Y-m-d', strtotime($request->startDate)));
        if(isset($request->endDate))
            $cFee->where('startTime', '<', date('Y-m-d', strtotime('+1 day', strtotime($request->endDate))));
        $fFee = $cFee->first();
        $fee=0;
        if(!is_null($fFee))$fee=intval($fFee->sum);

        $cNet = $this->trip->selectSub('SUM(merchant_transactions.amount * 10 - merchant_transactions.transaction_fee * 100)', 'sum')
                    ->where('bid_id', '<>', null)
                    ->whereRaw('endTime<UTC_TIMESTAMP')
                    ->leftJoin('merchant_transactions', 'trips.merchant_transaction_id', '=', 'merchant_transactions.id');
        if(isset($request->startDate))
            $cNet->where('startTime', '>', date('Y-m-d', strtotime($request->startDate)));
        if(isset($request->endDate))
            $cNet->where('startTime', '<', date('Y-m-d', strtotime('+1 day', strtotime($request->endDate))));
        $fNet = $cNet->first();
        $net=0;
        if(!is_null($fNet))$net=intval($fNet->sum);

        return response()->json([
            'trip'    => $trip,
            'revenue'    => $revenue,
            'captain'    => $captain,
            'owner'    => $owner,
            'review'    => $review,
            'payment'    => $payment,
            'fee'    => $fee,
            'net'    => $net
        ]);
    }
}
