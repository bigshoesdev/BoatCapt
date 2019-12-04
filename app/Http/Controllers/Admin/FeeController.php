<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Trip;
use App\Presenters\TripPresenter;
use Auth;

class FeeController extends Controller
{
    //
    private $limit = 10;

    public function __construct(Trip $trip, TripPresenter $tripPresenter)
    {
        $this->trip = $trip;
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
        return view('pages.admin.fees', compact('param'));
    }

    public function feeList(Request $request) 
    {
        $offset = 0;
        $limit = $this->limit;

        if(isset($request->offset))
            $offset = $request->offset;

        if(isset($request->limit) && $request->limit != 0)
            $limit = $request->limit;

        $triptotal = $this->trip->where('bid_id', '<>', null)
                                ->whereRaw('endTime<UTC_TIMESTAMP');

        if(isset($request->startDate))
            $triptotal->where('startTime', '>', date('Y-m-d', strtotime($request->startDate)));
        if(isset($request->endDate))
            $triptotal->where('startTime', '<', date('Y-m-d', strtotime('+1 day', strtotime($request->endDate))));

        $totalCount = $triptotal->count();
        if($totalCount == 0)
        {
            return response()->json([
                'totalCount'    => 0,
                'totalFee'      => 0,
                'tripList'      => []
            ]);
        }

        $tripPrice = $this->trip->selectSub('SUM(merchant_transactions.transaction_fee)*100', 'sum')
                    ->where('bid_id', '<>', null)
                    ->whereRaw('endTime<UTC_TIMESTAMP')
                    ->leftJoin('merchant_transactions', 'trips.merchant_transaction_id', '=', 'merchant_transactions.id');
                    
        if(isset($request->startDate))
            $tripPrice->where('startTime', '>', date('Y-m-d', strtotime($request->startDate)));
        if(isset($request->endDate))
            $tripPrice->where('startTime', '<', date('Y-m-d', strtotime('+1 day', strtotime($request->endDate))));
        $totalFeeFirst = $tripPrice->first();
        $totalFee=0;
        if(!is_null($totalFeeFirst))$totalFee=intval($totalFeeFirst->sum);

        $trips = $this->trip->select('trips.*')
                                    ->selectSub('profiles.firstName', 'firstName')
                                    ->selectSub('profiles.lastName', 'lastName')
                                    ->selectSub('merchant_transactions.amount * 100', 'total')
                                    ->selectSub('merchant_transactions.transaction_fee * 100', 'fee')
                                    ->leftJoin('profiles', 'trips.user_id', '=', 'profiles.user_id')
                                    ->leftJoin('bids', 'trips.id', '=', 'bids.trip_id')
                                    ->leftJoin('merchant_transactions', 'trips.merchant_transaction_id', '=', 'merchant_transactions.id')
                                    ->where('bid_id', '<>', null)
                                    ->whereRaw('endTime<UTC_TIMESTAMP')
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

                case 'fee_asc':
                    $trips->orderBy('fee');
                    break;

                case 'fee_desc':
                    $trips->orderBy('fee', 'desc');
                    break;

                case 'name_asc':
                    $trips->orderBy('firstName')->orderBy('lastName');
                    break;

                case 'name_desc':
                    $trips->orderBy('firstName', 'desc')->orderBy('lastName', 'desc');
                    break;
            }
        }
        else
        {
            $trips->orderBy('startTime');
        }

        $tripList = $this->tripPresenter->tripCollection(
            $trips->get()
        );

        return response()->json([
            'totalCount'    => $totalCount,
            'totalFee'    => $totalFee,
            'tripList'      => $tripList
        ]);
    }
}
