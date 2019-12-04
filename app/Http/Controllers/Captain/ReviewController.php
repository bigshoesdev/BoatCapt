<?php

namespace App\Http\Controllers\Captain;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Review;
use App\Trip;
use App\Profile;
use App\Presenters\CaptainPresenter;
use App\Presenters\TripPresenter;
use App\Presenters\ReviewPresenter;
use Illuminate\Support\Facades\Validator;
use Auth;
use DB;

class ReviewController extends Controller
{
    //
    private $limit = 10;

    public function __construct(User $user, Review $review, Trip $trip, Profile $profile, CaptainPresenter $captainPresenter, TripPresenter $tripPresenter, ReviewPresenter $reviewPresenter)
	{
		$this->user = $user;
		$this->review = $review;
        $this->trip = $trip;
        $this->profile = $profile;
		$this->captainPresenter = $captainPresenter;
        $this->tripPresenter = $tripPresenter;
		$this->reviewPresenter = $reviewPresenter;
	}

    public function index() 
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
          
        return view('pages.captain.reviews', compact('param', 'userInfo'));
    }

    public function reviewList(Request $request) 
    {
        $offset = 0;
        $limit = $this->limit;

        if(isset($request->offset))
            $offset = $request->offset;

        if(isset($request->limit) && $request->limit != 0)
            $limit = $request->limit;

        $reviewtotal = $this->review->where('to_user_id', Auth::user()->id);                           
                            

        $reviews = $this->review->where('to_user_id', Auth::user()->id)      
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

    public function detail($tripId) 
    {
        $param = [
            'avatar'        => Auth::user()['profile'] ? Auth::user()['profile']['avatar'] : null,
            'searchable'    => true,
            'login'         => true
        ];      

        $param = json_encode($param);

        $trip = $this->trip->with(['profile', 'bid', 'ownerInfo'])
                            ->where('bid_id', '<>', null)
                            ->where('tripId', $tripId)
                            ->get();

        if(count($trip) == 0)
            return redirect('/');

        $trip_id = $trip[0]['id'];
        $owner_id = $trip[0]['user_id'];

        $tripInfo = $this->tripPresenter->captainDetailCollection(
                            $trip
                        );        
        
        $tripInfo = json_encode($tripInfo[0]); 

        $review = $this->review->where('trip_id', $trip_id)
                            ->where('to_user_id', $owner_id)
                            ->where('from_user_id', Auth::user()->id)
                            ->first();
                             
        $reviewInfo = [
            'rating'    => 0,
            'describe'  => ""
        ];   

        if(!is_null($review))
        {
            $reviewInfo = [
                'rating'    => $review->rating,
                'describe'  => $review->describe
            ];
        }
            
        $reviewInfo = json_encode($reviewInfo);

        return view('pages.captain.leave-review', compact('param', 'tripInfo', 'reviewInfo'));
    }

    public function leaveReview(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'describe' => 'required|min:10',
            'rating' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $trip = $this->trip->with(['profile', 'bid', 'ownerInfo'])
                            ->where('bid_id', '<>', null)
                            ->where('tripId', $request->tripId)
                            ->where('endTime', '<', date('Y-m-d H:i:s'))
                            ->get();

        if(count($trip) == 0)
        {
            return redirect()->back()
                            ->withErrors(['message' => 'There was a problem leave review.']);
        }

        $trip_id = $trip[0]['id'];
        $owner_id = $trip[0]['user_id'];

        $review = $this->review->where('trip_id', $trip_id)
                            ->where('to_user_id', $owner_id)
                            ->where('from_user_id', Auth::user()->id)
                            ->first();    

        $reviewInfo = [
            'trip_id'       => $trip_id,
            'to_user_id'    => $owner_id,
            'from_user_id'  => Auth::user()->id,
            'rating'        => $request->rating,
            'describe'      => $request->describe
        ]; 

        try {
            DB::beginTransaction();

            if(!is_null($review))
            {
                $this->review->where('id', $review->id)
                            ->update($reviewInfo);
            }
            else
            {
                $this->review->create($reviewInfo);
            }

            $reviews = $this->review->where('to_user_id', $owner_id)->get();
            $rating = 0;

            foreach ($reviews as $review) {
                $rating += $review->rating;
            }

            if(count($reviews) > 0)
                $rating = $rating / count($reviews);

            $this->profile->where('user_id', $owner_id)
                    ->update([
                        'rating'    => $rating
                    ]);

            DB::commit();
        }
        catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                            ->withErrors(['message' => 'There was a problem leave review.']);
        }

        return redirect()->back()->with('status', 'Owner review was successfully updated.');
    }
}
