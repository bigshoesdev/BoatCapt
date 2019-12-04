<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Bid;
use App\Trip;
use App\Profile;
use App\Review;
use App\CaptainInfo;
use App\Presenters\CaptainPresenter;
use App\Presenters\ReviewPresenter;
use Auth;
use Illuminate\Support\Facades\Validator;
use DB;

class CaptainController extends Controller
{
    //
    private $limit = 10;

    public function __construct(User $user, Bid $bid, Trip $trip, Profile $profile, Review $review, CaptainInfo $captainInfo, CaptainPresenter $captainPresenter, ReviewPresenter $reviewPresenter)
    {
        $this->user = $user;
        $this->bid = $bid;
        $this->trip = $trip;
        $this->profile = $profile;
        $this->review = $review;
        $this->captainInfo = $captainInfo;
        $this->captainPresenter = $captainPresenter;
        $this->reviewPresenter = $reviewPresenter;
    }

    public function index(Request $request) 
    {
        $param = [
    		'avatar' 		=> Auth::user()['profile'] ? Auth::user()['profile']['avatar'] : null,
    		'searchable'	=> false,
    		'login'			=> true
        ];
        
        $captains = $this->user->select('users.*')
                                    ->selectSub('profiles.created_at', 'date')
                                    ->selectSub('profiles.firstName', 'firstName')
                                    ->selectSub('profiles.lastName', 'lastName')
                                    ->selectSub('profiles.isActive', 'isActive')
                                    ->join('profiles', 'users.id', '=', 'profiles.user_id')
                                    ->where('role', '=', '1003')
                                    ->where('isActive', '=', '0')
                                    ->orderBy('date');

        $newCaptains = $this->captainPresenter->captainsJoinCollection(
            $captains->get()
        );

    	$param = json_encode($param);
        return view('pages.admin.captains', compact('param', 'newCaptains'));
    }
    
    public function captainList(Request $request) 
    {
        $offset = 0;
        $limit = $this->limit;

        if(isset($request->offset))
            $offset = $request->offset;

        if(isset($request->limit) && $request->limit != 0)
            $limit = $request->limit;

        $captainTotal = $this->user->where('role', '=', '1003')
                                    ->join('profiles', 'users.id', '=', 'profiles.user_id');

        if(isset($request->startDate))
            $captainTotal->where('profiles.created_at', '>', date('Y-m-d', strtotime($request->startDate)));
        if(isset($request->endDate))
            $captainTotal->where('profiles.created_at', '<', date('Y-m-d', strtotime('+1 day', strtotime($request->endDate))));
        $totalCount = $captainTotal->count();
        if($totalCount == 0)
        {
            return response()->json([
                'totalCount'    => 0,
                'captainList'   => []
            ]);
        }

        $captains = $this->user->select('users.*')
                                    ->selectSub('profiles.created_at', 'date')
                                    ->selectSub('profiles.rating', 'rating')
                                    ->selectSub('profiles.firstName', 'firstName')
                                    ->selectSub('profiles.lastName', 'lastName')
                                    ->selectSub('profiles.isActive', 'isActive')
                                    ->selectSub('SELECT ROUND(SUM(bids.hours*bids.amount)*100) FROM `bids` INNER JOIN `trips` ON `bids`.`trip_id`=`trips`.`id` WHERE `bids`.`user_id`=users.id AND `bids`.`deleted_at` IS NOT NULL AND `trips`.`endTime`<UTC_TIMESTAMP()', 'total')
                                    ->join('profiles', 'users.id', '=', 'profiles.user_id')
                                    ->where('role', '=', '1003')
                                    ->offset($offset)
                                    ->limit($limit);

        if(isset($request->startDate))
            $captains->where('profiles.created_at', '>', date('Y-m-d', strtotime($request->startDate)));
        if(isset($request->endDate))
            $captains->where('profiles.created_at', '<', date('Y-m-d', strtotime('+1 day', strtotime($request->endDate))));

        if(isset($request->filter))
        {
            $filter = $request->filter;
            switch ($filter) {
                case 'total_asc':
                    $captains->orderBy('total');
                    break;

                case 'total_desc':
                    $captains->orderBy('total', 'desc');
                    break;

                case 'rating_asc':
                    $captains->orderBy('rating');
                    break;

                case 'rating_desc':
                    $captains->orderBy('rating', 'desc');
                    break;

                case 'captain_asc':
                    $captains->orderBy('firstName')->orderBy('lastName');
                    break;

                case 'captain_desc':
                    $captains->orderBy('firstName', 'desc')->orderBy('lastName', 'desc');
                    break;
            }
        }
        else
        {
            $captains->orderBy('date');
        }

        $captainList = $this->captainPresenter->captainsJoinCollection(
            $captains->get()
        );

        return response()->json([
            'totalCount'    => $totalCount,
            'captainList'   => $captainList
        ]);
    }

    public function profile($captainId) 
    {
        $user = $this->captainPresenter->transformCollection(
                    $this->user->with(['profile', 'captainInfo'])->where('id', $captainId)->get()
                )[0];
        $userInfo = json_encode($user);

        $param = [
    		'avatar' 		=> Auth::user()['profile'] ? Auth::user()['profile']['avatar'] : null,
    		'searchable'	=> false,
    		'login'			=> true
        ];
        
    	$param = json_encode($param);
        $userName = json_encode($user['firstName'] ? $user['firstName'] : explode(' ', $user['fullName'])[0]);

        return view('pages.admin.captain-profile', compact('param', 'userInfo', 'userName'));
    }

    public function updateProfile(Request $request) 
    {
        $userId=0;
        $user_email=null;
        if(isset($request->id)){
            $userId=$request->id;
            $user_email_first=$this->user->select('email')->where('id', $userId)->first();
            if(!is_null($user_email_first))$user_email=$user_email_first->email;
        }
        if($userId==0 || $user_email==null){
            return redirect()->back()
                        ->withErrors(['user_id' => 'There was a problem updating the profile.'])
                        ->withInput();
        }

        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
            'firstName' => 'required|min:3',
            'lastName' => 'required|min:3',
            'email' => $request->email == $user_email ? 'required|email' : 'required|email|unique:users,email',
            'avatar' => 'mimes:jpg,jpeg,bmp,png,gif|max:10000',
            'fullAddress' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zipcode' => 'required',
            'lat' => 'required',
            'lon' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $file = $request->file('avatar');
        $profileData = [
            'user_id'       => $userId,
            'firstName'     => $request->firstName,
            'lastName'      => $request->lastName,
            'phone'         => $request->phone,
            'fullAddress'   => $request->fullAddress,
            'address'       => $request->address,
            'address2'      => $request->address2,
            'city'          => $request->city,
            'state'         => $request->state,
            'country'       => $request->country,
            'zipcode'       => $request->zipcode,
            'lat'           => $request->lat,
            'lon'           => $request->lon
        ];

        $captainData = [
            'user_id'           => $userId,
            'uscgLicense'       => $request->uscgLicense,
            'licenseTonnage'    => $request->licenseTonnage,
            'firstResponder'    => $request->firstResponder ? 1 : 0,
            'maritimeGrad'      => $request->maritimeGrad ? 1 : 0,
            'militaryVeteran'   => $request->militaryVeteran ? 1 : 0,
            'drugFree'          => $request->drugFree ? 1 : 0,
            'describe'          => $request->describe
        ];

        if($file)
        {
            $fileName = 'profile-'.$userId.'.'.$file->getClientOriginalExtension();
            $profileData['avatar'] = $fileName;

            $check = $file->move(public_path().'/images/avatars/', $fileName);

            if ( ! is_a($check, '\Symfony\Component\HttpFoundation\File\File') ) 
            {
                return redirect()->back()
                            ->withErrors(['avatar' => 'There was a problem uploading the file.']);
            }
        }

        $user = $this->user->where('id', $userId)->first();
        $profile = $this->profile->where('user_id', $userId)->first();
        $captainInfo = $this->captainInfo->where('user_id', $userId)->first();

        try {
            DB::beginTransaction();

            $user->update([
                'email' => $request->email,
                'name'  => $request->firstName.' '. $request->lastName
            ]);

            if(is_null($profile))
            {
                $this->profile->create($profileData);
            }
            else
            {
                $profile->where('user_id', $userId)->update($profileData);
            }

            if(is_null($captainInfo))
            {
                $this->captainInfo->create($captainData);
            }
            else
            {
                $captainInfo->where('user_id', $userId)->update($captainData);
            }
            DB::commit();
        }
        catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                            ->withErrors(['message' => 'There was a problem updating the account profile.']);
        }

        return redirect()->back()->with('status', 'Account profile was successfully updated.');
    }

    public function approve($captainId) 
    {
        $this->profile->where('user_id', $captainId)->update([
            'isActive' => 1
        ]);

        return redirect()->back()->with('status', 'Account profile was successfully approved.');
    }

    public function reject($captainId) 
    {
        $this->profile->where('user_id', $captainId)->update([
            'isActive' => 2
        ]);

        return redirect()->back()->with('status', 'Account profile was successfully rejected.');
    }

    public function reviews($captainId) 
    {
        $param = [
    		'avatar' 		=> Auth::user()['profile'] ? Auth::user()['profile']['avatar'] : null,
    		'searchable'	=> false,
    		'login'			=> true
        ];

    	$param = json_encode($param);

        $userInfo = $this->captainPresenter->captainBioCollection(
	                    $this->user->with(['profile', 'captainInfo', 'review'])->where('id', $captainId)->get()
	                )[0];
        $userInfo = json_encode($userInfo); 
          
        return view('pages.admin.captain-reviews', compact('param', 'userInfo'));
    }

    public function reviewsList(Request $request, $captainId) 
    {
        $offset = 0;
        $limit = $this->limit;

        if(isset($request->offset))
            $offset = $request->offset;

        if(isset($request->limit) && $request->limit != 0)
            $limit = $request->limit;

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
