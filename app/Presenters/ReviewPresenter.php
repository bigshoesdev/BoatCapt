<?php
namespace App\Presenters;

class ReviewPresenter extends Presenter {

    public function transform($review)
    {
        return [
            'avatar'            => isset($review->profile->avatar) ? $review->profile->avatar : null,
            'firstName'         => isset($review->profile->firstName) ? $review->profile->firstName : null,
            'lastName'          => isset($review->profile->lastName) ? $review->profile->lastName : null,
            'rating'            => isset($review->rating) ? $review->rating : 0,
            'describe'          => isset($review->describe) ? $review->describe : null,
            'date'              => isset($review->created_at) ? date('F j, Y', strtotime($review->created_at)) : null
        ];
    }

    public function reviewJoinCollection(\Illuminate\Database\Eloquent\Collection $items)
    {
        return $items->map(function ($item) {
            return $this->reviewJoinTransform($item);
        });
    }

    public function reviewJoinTransform($review)
    {
        return [
            'id'                => $review->id,
            'captainId'         => $review->captainId,
            'date'              => date('m/d/Y', strtotime($review->date)), 
            'firstName'         => isset($review->firstName) ? $review->firstName : null,
            'lastName'          => isset($review->lastName) ? $review->lastName : null,
            'rating'            => isset($review->rating) ? $review->rating : 0,
            'tripId'            => isset($review->tripId) ? $review->tripId : null
        ];
    }
}