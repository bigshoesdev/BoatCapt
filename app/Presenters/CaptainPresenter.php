<?php
namespace App\Presenters;
use Auth;

class CaptainPresenter extends Presenter {

    public function billingCollection(\Illuminate\Database\Eloquent\Collection $items)
    {
        return $items->map(function ($item) {
            return $this->billingTransform($item);
        });
    }

    public function captainsCollection(\Illuminate\Database\Eloquent\Collection $items)
    {
        return $items->map(function ($item) {
            return $this->captainsTransform($item);
        });
    }

    public function captainsJoinCollection(\Illuminate\Database\Eloquent\Collection $items)
    {
        return $items->map(function ($item) {
            return $this->captainsJoinTransform($item);
        });
    }

    public function captainBioCollection(\Illuminate\Database\Eloquent\Collection $items)
    {
        return $items->map(function ($item) {
            return $this->captainBioTransform($item);
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
            'uscgLicense'       => isset($user->captainInfo->uscgLicense) ? $user->captainInfo->uscgLicense : null,
            'licenseTonnage'    => isset($user->captainInfo->licenseTonnage) ? $user->captainInfo->licenseTonnage : null,
            'firstResponder'    => isset($user->captainInfo->firstResponder) ? $user->captainInfo->firstResponder : null,
            'maritimeGrad'      => isset($user->captainInfo->maritimeGrad) ? $user->captainInfo->maritimeGrad : null,
            'militaryVeteran'   => isset($user->captainInfo->militaryVeteran) ? $user->captainInfo->militaryVeteran : null,
            'drugFree'          => isset($user->captainInfo->drugFree) ? $user->captainInfo->drugFree : null,
            'describe'          => isset($user->captainInfo->describe) ? $user->captainInfo->describe : null,
            'rating'            => isset($user->profile->rating) ? $user->profile->rating : 0,
            'isActive'          => isset($user->profile->isActive) ? $user->profile->isActive : 0,
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
            'firstResponder'    => isset($user->captainInfo->firstResponder) ? $user->captainInfo->firstResponder : null,
            'maritimeGrad'      => isset($user->captainInfo->maritimeGrad) ? $user->captainInfo->maritimeGrad : null,
            'militaryVeteran'   => isset($user->captainInfo->militaryVeteran) ? $user->captainInfo->militaryVeteran : null,
            'drugFree'          => isset($user->captainInfo->drugFree) ? $user->captainInfo->drugFree : null,
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

    public function captainsTransform($user)
    {
        return [
            'id'                => $user->id,
            'avatar'            => isset($user->profile->avatar) ? $user->profile->avatar : null,
            'fullName'          => $user->name,
            'firstName'         => isset($user->profile->firstName) ? $user->profile->firstName : explode(' ', $user->name)[0],
            'city'              => isset($user->profile->city) ? $user->profile->city : null,
            'state'             => isset($user->profile->state) ? $user->profile->state : null,
            'away'              => isset($user->away) ? round(sqrt($user->away), 2) : null,//(Auth::check() && isset(Auth::user()->profile) && isset($user->profile)) ? $this->distance(Auth::user()->profile, $user->profile) : null,
            'rating'            => isset($user->profile->rating) ? $user->profile->rating : 0,
            'reviews'           => isset($user->review) ? count($user->review) : 0
        ];
    }

    public function captainsJoinTransform($user)
    {
        return [
            'id'                => $user->id,
            'date'              => date('m/d/Y', strtotime($user->date)), 
            'firstName'         => isset($user->firstName) ? $user->firstName : null,
            'lastName'          => isset($user->lastName) ? $user->lastName : null,
            'rating'            => isset($user->rating) ? $user->rating : 0,
            'isActive'          => isset($user->isActive) ? $user->isActive : 0,
            'total'             => $user->total
        ];
    }

    public function captainBioTransform($user)
    {
        return [
            'id'                => $user->id,
            'avatar'            => isset($user->profile->avatar) ? $user->profile->avatar : null,
            'fullName'          => $user->name,
            'firstName'         => isset($user->profile->firstName) ? $user->profile->firstName : explode(' ', $user->name)[0],
            'city'              => isset($user->profile->city) ? $user->profile->city : null,
            'state'             => isset($user->profile->state) ? $user->profile->state : null,            
            'firstResponder'    => isset($user->captainInfo->firstResponder) ? $user->captainInfo->firstResponder : null,
            'maritimeGrad'      => isset($user->captainInfo->maritimeGrad) ? $user->captainInfo->maritimeGrad : null,
            'militaryVeteran'   => isset($user->captainInfo->militaryVeteran) ? $user->captainInfo->militaryVeteran : null,
            'drugFree'          => isset($user->captainInfo->drugFree) ? $user->captainInfo->drugFree : null,
            'describe'          => isset($user->captainInfo->describe) ? $user->captainInfo->describe : null,
            'rating'            => isset($user->profile->rating) ? $user->profile->rating : 0,
            'reviews'           => isset($user->review) ? count($user->review) : 0            
        ];
    }

}