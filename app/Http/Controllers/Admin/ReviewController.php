<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Review;
use App\Presenters\ReviewPresenter;
use Auth;

class ReviewController extends Controller
{
    //
    private $limit = 10;

    public function __construct(Review $review, ReviewPresenter $reviewPresenter)
    {
        $this->review = $review;
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
        return view('pages.admin.reviews', compact('param'));
    }

    public function reveiwList(Request $request) 
    {
        $offset = 0;
        $limit = $this->limit;

        if(isset($request->offset))
            $offset = $request->offset;

        if(isset($request->limit) && $request->limit != 0)
            $limit = $request->limit;

        $reviewtotal = $this->review;

        if(isset($request->startDate))
            $reviewtotal->where('created_at', '>', date('Y-m-d', strtotime($request->startDate)));
        if(isset($request->endDate))
            $reviewtotal->where('created_at', '<', date('Y-m-d', strtotime('+1 day', strtotime($request->endDate))));

        $totalCount = $reviewtotal->count();
        if($totalCount == 0)
        {
            return response()->json([
                'totalCount'    => 0,
                'reviewList'      => []
            ]);
        }

        $reviews = $this->review->select('reviews.id')
                                    ->selectSub('reviews.created_at', 'date')
                                    ->selectSub('reviews.to_user_id', 'captainId')
                                    ->selectSub('profiles.firstName', 'firstName')
                                    ->selectSub('profiles.lastName', 'lastName')
                                    ->selectSub('profiles.rating', 'rating')
                                    ->selectSub('trips.tripId', 'tripId')
                                    ->join('profiles', 'reviews.to_user_id', '=', 'profiles.user_id')
                                    ->join('trips', 'reviews.trip_id', '=', 'trips.id')
                                    ->offset($offset)
                                    ->limit($limit);

        if(isset($request->startDate))
            $reviews->where('reviews.created_at', '>', date('Y-m-d', strtotime($request->startDate)));
        if(isset($request->endDate))
            $reviews->where('reviews.created_at', '<', date('Y-m-d', strtotime('+1 day', strtotime($request->endDate))));

        if(isset($request->filter))
        {
            $filter = $request->filter;
            switch ($filter) {
                case 'date_asc':
                    $reviews->orderBy('date');
                    break;

                case 'date_desc':
                    $reviews->orderBy('date', 'desc');
                    break;

                case 'rating_asc':
                    $reviews->orderBy('rating');
                    break;

                case 'rating_desc':
                    $reviews->orderBy('rating', 'desc');
                    break;

                case 'captain_asc':
                    $reviews->orderBy('firstName')->orderBy('lastName');
                    break;

                case 'captain_desc':
                    $reviews->orderBy('firstName', 'desc')->orderBy('lastName', 'desc');
                    break;
            }
        }
        else
        {
            $reviews->orderBy('date');
        }

        $reviewList = $this->reviewPresenter->reviewJoinCollection(
            $reviews->get()
        );

        return response()->json([
            'totalCount'    => $totalCount,
            'reviewList'      => $reviewList
        ]);
    }
}
