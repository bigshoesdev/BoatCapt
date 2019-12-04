<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Bid;
use App\Trip;
use App\Profile;
use App\Review;
use App\OwnerInfo;
use App\Presenters\OwnerPresenter;
use App\Presenters\ReviewPresenter;
use Auth;
use Illuminate\Support\Facades\Validator;
use DB;


class OwnerController extends Controller
{
    //
    private $limit = 10;

    public function __construct(User $user, Bid $bid, Trip $trip, Profile $profile, Review $review, OwnerInfo $ownerInfo, OwnerPresenter $ownerPresenter, ReviewPresenter $reviewPresenter)
    {
        $this->user = $user;
        $this->bid = $bid;
        $this->trip = $trip;
        $this->profile = $profile;
        $this->review = $review;
        $this->ownerInfo = $ownerInfo;
        $this->ownerPresenter = $ownerPresenter;
        $this->reviewPresenter = $reviewPresenter;
    }

    public function index(Request $request) 
    {
        $param = [
    		'avatar' 		=> Auth::user()['profile'] ? Auth::user()['profile']['avatar'] : null,
    		'searchable'	=> false,
    		'login'			=> true
    	];    	

    	$param = json_encode($param);
        return view('pages.admin.owners', compact('param'));
    }

    public function ownerList(Request $request) 
    {
        $offset = 0;
        $limit = $this->limit;

        if(isset($request->offset))
            $offset = $request->offset;

        if(isset($request->limit) && $request->limit != 0)
            $limit = $request->limit;

        $ownerTotal = $this->user->where('role', '=', '1002')
                                    ->join('profiles', 'users.id', '=', 'profiles.user_id');

        if(isset($request->startDate))
            $ownerTotal->where('profiles.created_at', '>', date('Y-m-d', strtotime($request->startDate)));
        if(isset($request->endDate))
            $ownerTotal->where('profiles.created_at', '<', date('Y-m-d', strtotime('+1 day', strtotime($request->endDate))));
        $totalCount = $ownerTotal->count();
        if($totalCount == 0)
        {
            return response()->json([
                'totalCount'    => 0,
                'ownerList'   => []
            ]);
        }

        $owners = $this->user->select('users.*')
                                    ->selectSub('profiles.created_at', 'date')
                                    ->selectSub('profiles.rating', 'rating')
                                    ->selectSub('profiles.firstName', 'firstName')
                                    ->selectSub('profiles.lastName', 'lastName')
                                    ->selectSub('SELECT ROUND(SUM(bids.hours*bids.amount)*100) FROM `bids` INNER JOIN `trips` ON `bids`.`trip_id`=`trips`.`id` WHERE `trips`.`user_id`=users.id AND `bids`.`deleted_at` IS NOT NULL AND `trips`.`endTime`<UTC_TIMESTAMP()', 'total')
                                    ->join('profiles', 'users.id', '=', 'profiles.user_id')
                                    ->where('role', '=', '1002')
                                    ->offset($offset)
                                    ->limit($limit);

        if(isset($request->startDate))
            $owners->where('profiles.created_at', '>', date('Y-m-d', strtotime($request->startDate)));
        if(isset($request->endDate))
            $owners->where('profiles.created_at', '<', date('Y-m-d', strtotime('+1 day', strtotime($request->endDate))));

        if(isset($request->filter))
        {
            $filter = $request->filter;
            switch ($filter) {
                case 'total_asc':
                    $owners->orderBy('total');
                    break;

                case 'total_desc':
                    $owners->orderBy('total', 'desc');
                    break;

                case 'rating_asc':
                    $owners->orderBy('rating');
                    break;

                case 'rating_desc':
                    $owners->orderBy('rating', 'desc');
                    break;

                case 'owner_asc':
                    $owners->orderBy('firstName')->orderBy('lastName');
                    break;

                case 'owner_desc':
                    $owners->orderBy('firstName', 'desc')->orderBy('lastName', 'desc');
                    break;
            }
        }
        else
        {
            $owners->orderBy('date');
        }

        $ownerList = $this->ownerPresenter->ownerJoinCollection(
            $owners->get()
        );

        return response()->json([
            'totalCount'    => $totalCount,
            'ownerList'   => $ownerList
        ]);
    }

    public function profile($ownerId) 
    {        
    	$user = $this->ownerPresenter->transformCollection(
                    $this->user->with(['profile', 'ownerInfo', 'review'])->where('id', $ownerId)->get()
                )[0];
        $userInfo = json_encode($user);        

        $param = json_encode([
            'avatar' => $user['avatar'], 
            'searchable' => true, 
            'login' => true
        ]);
        $userName = json_encode($user['firstName'] ?: explode(' ', $user['fullName'])[0]);

    	return view('pages.admin.owner-profile', compact('param', 'userName', 'userInfo'));
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

        $ownerData = [
            'user_id'           => $userId,
            'boatClass'         => $request->boatClass,
            'boatModel'         => $request->boatModel,
            'boatYear'          => $request->boatYear,
            'draft'             => $request->draft,
            'length'            => $request->length,
            'beam'              => $request->beam,
            'boatInsurance'     => $request->boatInsurance ? 1 : 0,
            'towCoverage'       => $request->towCoverage ? 1 : 0,
            'validSafetyGear'   => $request->validSafetyGear ? 1 : 0,
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
        $ownerInfo = $this->ownerInfo->where('user_id', $userId)->first();

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

            if(is_null($ownerInfo))
            {
                $this->ownerInfo->create($ownerData);
            }
            else
            {
                $ownerInfo->where('user_id', $userId)->update($ownerData);
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

    public function reviews($ownerId) 
    {
        $param = [
    		'avatar' 		=> Auth::user()['profile'] ? Auth::user()['profile']['avatar'] : null,
    		'searchable'	=> false,
    		'login'			=> true
    	];    	

    	$param = json_encode($param);

        $userInfo = $this->ownerPresenter->ownerCollection(
	                    $this->user->with(['profile', 'ownerInfo', 'review'])->where('id', $ownerId)->get()
	                )[0];
        $userInfo = json_encode($userInfo); 

        return view('pages.admin.owner-reviews', compact('param', 'userInfo'));
    }

    public function reviewsList(Request $request, $ownerId) 
    {
        $offset = 0;
        $limit = $this->limit;

        if(isset($request->offset))
            $offset = $request->offset;

        if(isset($request->limit) && $request->limit != 0)
            $limit = $request->limit;

        $reviewtotal = $this->review->where('to_user_id', $ownerId);                           
                            

        $reviews = $this->review->where('to_user_id', $ownerId)      
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
