<?php
namespace App\Presenters;

class OwnerPresenter extends Presenter {

    public function billingCollection(\Illuminate\Database\Eloquent\Collection $items)
    {
        return $items->map(function ($item) {
            return $this->billingTransform($item);
        });
    }

    public function ownerCollection(\Illuminate\Database\Eloquent\Collection $items)
    {
        return $items->map(function ($item) {
            return $this->ownerTransform($item);
        });
    }

    public function ownerJoinCollection(\Illuminate\Database\Eloquent\Collection $items)
    {
        return $items->map(function ($item) {
            return $this->ownerJoinTransform($item);
        });
    }

	public function transform($user)
    {
        return [
            'id'                => $user->id,
            'avatar'            => isset($user->profile->avatar) ? $user->profile->avatar : null,
            'fullName'          => $user->name,
            'firstName'         => isset($user->profile->firstName) ? $user->profile->firstName : null,
            'lastName'          => isset($user->profile->lastName) ? $user->profile->lastName : null,
            'email'             => $user->email,
            'phone'             => isset($user->profile->phone) ? $user->profile->phone : null,
            'fullAddress'       => isset($user->profile->fullAddress) ? $user->profile->fullAddress : null,
            'address'           => isset($user->profile->address) ? $user->profile->address : null,
            'address2'          => isset($user->profile->address2) ? $user->profile->address2 : null,
            'city'              => isset($user->profile->city) ? $user->profile->city : null,
            'state'             => isset($user->profile->state) ? $user->profile->state : null,
            'country'           => isset($user->profile->country) ? $user->profile->country : null,
            'zipcode'           => isset($user->profile->zipcode) ? $user->profile->zipcode : null,
            'lat'               => isset($user->profile->lat) ? $user->profile->lat : null,
            'lon'               => isset($user->profile->lon) ? $user->profile->lon : null,
            'boatClass'         => isset($user->ownerInfo->boatClass) ? $user->ownerInfo->boatClass : null,
            'boatModel'         => isset($user->ownerInfo->boatModel) ? $user->ownerInfo->boatModel : null,
            'boatYear'          => isset($user->ownerInfo->boatYear) ? $user->ownerInfo->boatYear : null,
            'draft'             => isset($user->ownerInfo->draft) ? $user->ownerInfo->draft : null,
            'length'            => isset($user->ownerInfo->length) ? $user->ownerInfo->length : null,
            'beam'              => isset($user->ownerInfo->beam) ? $user->ownerInfo->beam : null,
            'boatInsurance'     => isset($user->ownerInfo->boatInsurance) ? $user->ownerInfo->boatInsurance : null,
            'towCoverage'       => isset($user->ownerInfo->towCoverage) ? $user->ownerInfo->towCoverage : null,
            'validSafetyGear'   => isset($user->ownerInfo->validSafetyGear) ? $user->ownerInfo->validSafetyGear : null,
            'describe'          => isset($user->ownerInfo->describe) ? $user->ownerInfo->describe : null,
            'rating'            => isset($user->profile->rating) ? $user->profile->rating : 0,
            'reviews'           => isset($user->review) ? count($user->review) : 0
        ];
    }

    public function billingTransform($user)
    {
        return [
            'avatar'            => isset($user->profile->avatar) ? $user->profile->avatar : null,
            'firstName'         => isset($user->profile->firstName) ? $user->profile->firstName : explode(' ', $user->name)[0],
            'city'              => isset($user->profile->city) ? $user->profile->city : null,
            'state'             => isset($user->profile->state) ? $user->profile->state : null,
            'boatInsurance'     => isset($user->ownerInfo->boatInsurance) ? $user->ownerInfo->boatInsurance : null,
            'towCoverage'       => isset($user->ownerInfo->towCoverage) ? $user->ownerInfo->towCoverage : null,
            'validSafetyGear'   => isset($user->ownerInfo->validSafetyGear) ? $user->ownerInfo->validSafetyGear : null,
            'rating'            => isset($user->profile->rating) ? $user->profile->rating : 0,
            'reviews'           => isset($user->review) ? count($user->review) : 0,
            'merchant_type'     => isset($user->profile->merchant_type) ? $user->profile->merchant_type : null,
            'paypalEmail'       => isset($user->paypalEmail->email) ? $user->paypalEmail->email : null,
            'stripeDetail'      => [
                                    'card_number' => isset($user->stripeDetail->card_number) ? $user->stripeDetail->card_number : null,
                                    'exp_date' => isset($user->stripeDetail->exp_year) ? $user->stripeDetail->exp_year.'-'.($user->stripeDetail->exp_month < 10 ? '0'.$user->stripeDetail->exp_month : $user->stripeDetail->exp_month) : null,
                                    'cc_digits' => isset($user->stripeDetail->cc_digits) ? $user->stripeDetail->cc_digits : null
                                ]       
        ];
    }

    public function ownerTransform($user)
    {
        return [
            'id'                => $user->id,
            'avatar'            => isset($user->profile->avatar) ? $user->profile->avatar : null,
            'fullName'          => $user->name,
            'firstName'         => isset($user->profile->firstName) ? $user->profile->firstName : explode(' ', $user->name)[0],
            'city'              => isset($user->profile->city) ? $user->profile->city : null,
            'state'             => isset($user->profile->state) ? $user->profile->state : null,            
            'boatInsurance'     => isset($user->ownerInfo->boatInsurance) ? $user->ownerInfo->boatInsurance : null,
            'towCoverage'       => isset($user->ownerInfo->towCoverage) ? $user->ownerInfo->towCoverage : null,
            'validSafetyGear'   => isset($user->ownerInfo->validSafetyGear) ? $user->ownerInfo->validSafetyGear : null,
            'describe'          => isset($user->ownerInfo->describe) ? $user->ownerInfo->describe : null,
            'rating'            => isset($user->profile->rating) ? $user->profile->rating : 0,
            'reviews'           => isset($user->review) ? count($user->review) : 0            
        ];
    }

    public function ownerJoinTransform($user)
    {
        return [
            'id'                => $user->id,
            'date'              => date('m/d/Y', strtotime($user->date)), 
            'firstName'         => isset($user->firstName) ? $user->firstName : null,
            'lastName'          => isset($user->lastName) ? $user->lastName : null,
            'rating'            => isset($user->rating) ? $user->rating : 0,
            'total'             => $user->total
        ];
    }
}