<?php

namespace App\Http\Controllers\Owner;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Trip;
use App\BidRequest;
use App\Presenters\CaptainPresenter;
use App\Presenters\TripPresenter;
use App\Presenters\BidPresenter;
use Auth;
use Illuminate\Support\Facades\Validator;
use DB;

class BookCaptainController extends Controller
{
    //
    public function __construct(User $user, Trip $trip, BidRequest $bidRequest, CaptainPresenter $captainPresenter, BidPresenter $bidPresenter, TripPresenter $tripPresenter)
	{
		$this->user = $user;
        $this->trip = $trip;
        $this->bidRequest = $bidRequest;
		$this->captainPresenter = $captainPresenter;
        $this->tripPresenter = $tripPresenter;
        $this->bidPresenter = $bidPresenter;
	}

    public function index($captainId) 
    {        
        $param = json_encode([
            'avatar' => Auth::user()['profile'] ? Auth::user()['profile']['avatar'] : null,
            'searchable' => true, 
            'login' => true
        ]);

    	$captainInfo = $this->captainPresenter->captainBioCollection(
	                    $this->user->with(['profile', 'captainInfo', 'review'])->where('id', $captainId)->where('role', 1003)->get()
	                );
    	if(count($captainInfo) == 0)
    		return redirect('/');
    	
        $captainInfo = json_encode($captainInfo[0]);  

    	return view('pages.owner.book-captain', compact('param', 'captainInfo'));
        
    }

    public function bidRequestList(Request $request) 
    {
        $bidRequestCount = $this->bidRequest->whereHas('trip', function($q) {
                                $q->where('user_id',  Auth::user()->id);
                            })                       
                            ->count();

        if($bidRequestCount == 0)
        {
            return response()->json([
                'bidRequestCount'    => 0,
                'bidRequestList'     => []
            ]);
        }

        $bidRequests = $this->bidRequest->whereHas('trip', function($q) {
                                $q->where('user_id',  Auth::user()->id);
                            }) 
                            ->orderBy('created_at', 'desc');

        $bidRequestList = $this->bidPresenter->bidCaptRequestCollection(
            $bidRequests->get()
        );

        return response()->json([
            'bidRequestCount'    => $bidRequestCount,
            'bidRequestList'     => $bidRequestList
        ]);
    }

    public function bidRequestDetail($bidRequestId) 
    {
        $param = [
            'avatar'        => Auth::user()['profile'] ? Auth::user()['profile']['avatar'] : null,
            'searchable'    => true,
            'login'         => true
        ];      

        $param = json_encode($param);

        $tripInfo = $this->bidPresenter->bidCaptRequestCollection(
                            $this->bidRequest->where('id', $bidRequestId)->get()
                        );

        if(count($tripInfo) == 0)
            return redirect('/');
        
        $tripInfo = json_encode($tripInfo[0]); 

        return view('pages.owner.trip-detail', compact('param', 'tripInfo'));
        
    }

    public function bookCaptain(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'captainId'         => 'required|integer',
            'startLocation'     => 'required',
            'startTime'         => 'required|date',
            'endLocation'       => 'required',
            'endTime'           => 'required|date',
            'tripNature'        => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $tripId = strtoupper(str_random(6));
        $find = false;
        while (!$find) {
            $count = $this->trip->where('tripId', $tripId)->count();
            if($count > 0)
            {
                $tripId = strtoupper(str_random(6));
            }
            else
            {
                $find = true;
            }
        }

        try {
            DB::beginTransaction();

            $this->trip->create([
                        'tripId'            => $tripId,
                        'user_id'           => Auth::user()->id,
                        'startLocation'     => $request->startLocation,
                        'startTime'         => date('Y-m-d H:i:s', strtotime($request->startTime)),
                        'endLocation'       => $request->endLocation,
                        'endTime'           => date('Y-m-d H:i:s', strtotime($request->endTime)),
                        'tripNature'        => $request->tripNature,
                        'describe'          => $request->describe,
                    ]);
            

            DB::commit();
        }
        catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                            ->withErrors(['message' => 'There was a problem complete booking.']);
        }        

        return redirect('/'.$request->captainId.'/'.$tripId.'/book-confirm');
    }

    public function bookConfirm($captainId, $tripId) 
    {
        $param = json_encode([
            'avatar' => Auth::user()['profile'] ? Auth::user()['profile']['avatar'] : null,
            'searchable' => true, 
            'login' => true
        ]);

        $captainInfo = $this->captainPresenter->captainBioCollection(
                        $this->user->with(['profile', 'captainInfo', 'review'])->where('id', $captainId)->where('role', 1003)->get()
                    );
        if(count($captainInfo) == 0)
            return redirect('/');
        
        $captainInfo = json_encode($captainInfo[0]); 

        $tripInfo = $this->tripPresenter->transformCollection(
                            $this->trip->where('tripId', $tripId)->get()
                        );

        if(count($tripInfo) == 0)
            return redirect('/');
        
        $tripInfo = json_encode($tripInfo[0]); 

        return view('pages.owner.book-captain-confirm', compact('param', 'captainInfo', 'tripInfo'));
    }

    public function requestCaptain(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'captainId'  => 'required|integer',
            'tripId'     => 'required'
        ]);

        if ($validator->fails()) {
            return redirect('/');
        }

        $tripInfo = $this->trip->where('tripId', $request->tripId)->first();
        

        if(is_null($tripInfo))
        {
            return redirect('/');
        }

        $trip_id = $tripInfo['id'];

        $bidRequest = $this->bidRequest->where('trip_id', $trip_id)
                        ->where('user_id', $request->captainId)
                        ->first();

        if(!is_null($bidRequest))
        {
            return redirect('/'.$request->captainId.'/send-request-captain');
        }

        try {
            DB::beginTransaction();

            $this->bidRequest->create([
                        'trip_id'     => $trip_id,
                        'user_id'     => $request->captainId
                    ]);
            

            DB::commit();
        }
        catch (\Exception $e) {
            DB::rollback();
            return redirect('/');
        }        

        return redirect('/'.$request->captainId.'/send-request-captain');
    }

    public function requestSend($captainId) 
    {
        $param = json_encode([
            'avatar' => Auth::user()['profile'] ? Auth::user()['profile']['avatar'] : null,
            'searchable' => true, 
            'login' => true
        ]);

        $captainInfo = $this->captainPresenter->captainBioCollection(
                        $this->user->with(['profile', 'captainInfo', 'review'])->where('id', $captainId)->where('role', 1003)->get()
                    );
        if(count($captainInfo) == 0)
            return redirect('/');
        
        $captainInfo = json_encode($captainInfo[0]); 

        return view('pages.owner.request-captain', compact('param', 'captainInfo'));
    }
}
