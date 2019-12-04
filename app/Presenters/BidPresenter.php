<?php
namespace App\Presenters;
use Auth;

class BidPresenter extends Presenter {

    public function transform($trip)
    {

    }

    public function bidCollection(\Illuminate\Database\Eloquent\Collection $items)
    {
        return $items->map(function ($item) {
            return $this->bidTransform($item);
        });
    }

    public function bidTransform($bid)
    {
        return [
            'id'                => $bid->id,
            'tripId'            => $bid->trip->tripId,
            'total'             => round($bid->hours * $bid->amount * 100),
            'avatar'            => isset($bid->profile->avatar) ? $bid->profile->avatar : null,
            'firstName'         => isset($bid->profile->firstName) ? $bid->profile->firstName : null,
            'lastName'          => isset($bid->profile->lastName) ? $bid->profile->lastName : null,
            'rating'            => isset($bid->profile->rating) ? $bid->profile->rating : 0,      
            'city'              => isset($bid->profile->city) ? $bid->profile->city : null,
            'state'             => isset($bid->profile->state) ? $bid->profile->state : null,  
            'away'              => (isset($bid->trip->profile) && isset($bid->profile)) ? $this->distance($bid->trip->profile, $bid->profile) : null,           
            'date'              => isset($bid->trip->startTime) ? date('m/d/Y', strtotime($bid->trip->startTime)) : null, 
            'startLocation'     => isset($bid->trip->startLocation) ? $bid->trip->startLocation : null,
            'startTime'         => isset($bid->trip->startTime) ? date('m/d/Y @ hA', strtotime($bid->trip->startTime)) : null,
            'endLocation'       => isset($bid->trip->endLocation) ? $bid->trip->endLocation : null,
            'endTime'           => isset($bid->trip->endTime) ? date('m/d/Y @ hA', strtotime($bid->trip->endTime)) : null,
            'describe'          => isset($bid->trip->describe) ? $bid->trip->describe : null
        ];
    }

    public function bidCaptainCollection(\Illuminate\Database\Eloquent\Collection $items)
    {
        return $items->map(function ($item) {
            return $this->bidCaptainTransform($item);
        });
    }

    public function bidCaptainTransform($bid)
    {
        return [
            'id'                => $bid->id,
            'tripId'            => $bid->trip->tripId,
            'total'             => round($bid->hours * $bid->amount * 100),
            'avatar'            => isset($bid->trip->profile->avatar) ? $bid->trip->profile->avatar : null,
            'firstName'         => isset($bid->trip->profile->firstName) ? $bid->trip->profile->firstName : null,
            'lastName'          => isset($bid->trip->profile->lastName) ? $bid->trip->profile->lastName : null,
            'rating'            => isset($bid->trip->profile->rating) ? $bid->trip->profile->rating : 0,      
            'city'              => isset($bid->trip->profile->city) ? $bid->trip->profile->city : null,
            'state'             => isset($bid->trip->profile->state) ? $bid->trip->profile->state : null,  
            'boatInsurance'     => isset($bid->trip->ownerInfo->boatInsurance) ? $bid->trip->ownerInfo->boatInsurance : null,
            'towCoverage'       => isset($bid->trip->ownerInfo->towCoverage) ? $bid->trip->ownerInfo->towCoverage : null,
            'validSafetyGear'   => isset($bid->trip->ownerInfo->validSafetyGear) ? $bid->trip->ownerInfo->validSafetyGear : null,    
            'away'              => (isset($bid->trip->profile) && isset($bid->profile)) ? $this->distance($bid->trip->profile, $bid->profile) : null,           
            'date'              => isset($bid->trip->startTime) ? date('m/d/Y', strtotime($bid->trip->startTime)) : null, 
            'startLocation'     => isset($bid->trip->startLocation) ? $bid->trip->startLocation : null,
            'startTime'         => isset($bid->trip->startTime) ? date('m/d/Y @ hA', strtotime($bid->trip->startTime)) : null,
            'endLocation'       => isset($bid->trip->endLocation) ? $bid->trip->endLocation : null,
            'endTime'           => isset($bid->trip->endTime) ? date('m/d/Y @ hA', strtotime($bid->trip->endTime)) : null,
            'ownerDescribe'     => isset($bid->trip->describe) ? $bid->trip->describe : null,
            'captDescribe'      => isset($bid->describe) ? $bid->describe : null,
        ];
    }

    public function bidDetailCollection(\Illuminate\Database\Eloquent\Collection $items)
    {
        return $items->map(function ($item) {
            return $this->bidDetailTransform($item);
        });
    }

    public function bidDetailTransform($bid)
    {
        return [
            'id'                => $bid->id,
            'tripId'            => $bid->trip->tripId,
            'captainId'         => isset($bid->user_id) ? $bid->user_id : null,
            'captainEmail'      => isset($bid->profile->user->email) ? $bid->profile->user->email : null,
            'captainPhone'      => isset($bid->profile->phone) ? $bid->profile->phone : null,
            'total'             => round($bid->hours * $bid->amount * 100),
            'avatar'            => isset($bid->profile->avatar) ? $bid->profile->avatar : null,
            'firstName'         => isset($bid->profile->firstName) ? $bid->profile->firstName : null,
            'lastName'          => isset($bid->profile->lastName) ? $bid->profile->lastName : null,
            'rating'            => isset($bid->profile->rating) ? $bid->profile->rating : 0,      
            'city'              => isset($bid->profile->city) ? $bid->profile->city : null,
            'state'             => isset($bid->profile->state) ? $bid->profile->state : null, 
            'firstResponder'    => isset($bid->captainInfo->firstResponder) ? $bid->captainInfo->firstResponder : null,
            'maritimeGrad'      => isset($bid->captainInfo->maritimeGrad) ? $bid->captainInfo->maritimeGrad : null,
            'militaryVeteran'   => isset($bid->captainInfo->militaryVeteran) ? $bid->captainInfo->militaryVeteran : null,
            'drugFree'          => isset($bid->captainInfo->drugFree) ? $bid->captainInfo->drugFree : null,     
            'away'              => (isset($bid->trip->profile) && isset($bid->profile)) ? $this->distance($bid->trip->profile, $bid->profile) : null,            
            'date'              => isset($bid->trip->startTime) ? date('m/d/Y', strtotime($bid->trip->startTime)) : null, 
            'startLocation'     => isset($bid->trip->startLocation) ? $bid->trip->startLocation : null,
            'startTime'         => isset($bid->trip->startTime) ? date('m/d/Y @ hA', strtotime($bid->trip->startTime)) : null,
            'endLocation'       => isset($bid->trip->endLocation) ? $bid->trip->endLocation : null,
            'endTime'           => isset($bid->trip->endTime) ? date('m/d/Y @ hA', strtotime($bid->trip->endTime)) : null,
            'ownerDescribe'     => isset($bid->trip->describe) ? $bid->trip->describe : null,
            'captDescribe'      => isset($bid->describe) ? $bid->describe : null
        ];
    }

    public function bidBillingCollection(\Illuminate\Database\Eloquent\Collection $items)
    {
        return $items->map(function ($item) {
            return $this->bidBillingTransform($item);
        });
    }

    public function bidBillingTransform($bid)
    {
        return [
            'id'            => $bid->id,
            'tripId'        => $bid->trip->tripId,
            'total'         => round($bid->hours * $bid->amount * 100),
            'captain'       => [           
                                'id'            => isset($bid->user_id) ? $bid->user_id : null,                                
                                'avatar'        => isset($bid->profile->avatar) ? $bid->profile->avatar : null,
                                'firstName'     => isset($bid->profile->firstName) ? $bid->profile->firstName : null,
                                'lastName'      => isset($bid->profile->lastName) ? $bid->profile->lastName : null,
                                'rating'        => isset($bid->profile->rating) ? $bid->profile->rating : 0,      
                                'city'          => isset($bid->profile->city) ? $bid->profile->city : null,
                                'state'         => isset($bid->profile->state) ? $bid->profile->state : null,   
                                'away'          => (isset($bid->trip->profile) && isset($bid->profile)) ? $this->distance($bid->trip->profile, $bid->profile) : null,
                            ],
            'merchant_type' => isset($bid->trip->profile->merchant_type) ? $bid->trip->profile->merchant_type : null,
            'paypalEmail'   => isset($bid->trip->paypalEmail->email) ? $bid->trip->paypalEmail->email : null,
            'stripeDetail'  => [
                                'card_number' => isset($bid->trip->stripeDetail->card_number) ? $bid->trip->stripeDetail->card_number : null,
                                'exp_date' => isset($bid->trip->stripeDetail->exp_year) ? $bid->trip->stripeDetail->exp_year.'-'.$bid->trip->stripeDetail->exp_month : null,
                                'cc_digits' => isset($bid->trip->stripeDetail->cc_digits) ? $bid->trip->stripeDetail->cc_digits : null
                            ]     
        ];
    }

    public function bidRequestCollection(\Illuminate\Database\Eloquent\Collection $items)
    {
        return $items->map(function ($item) {
            return $this->bidRequestTransform($item);
        });
    }

    public function bidRequestTransform($bidRequest)
    {
        return [
            'tripId'            => $bidRequest->trip->tripId,
            'avatar'            => isset($bidRequest->trip->profile->avatar) ? $bidRequest->trip->profile->avatar : null,
            'firstName'         => isset($bidRequest->trip->profile->firstName) ? $bidRequest->trip->profile->firstName : null,
            'lastName'          => isset($bidRequest->trip->profile->lastName) ? $bidRequest->trip->profile->lastName : null,
            'rating'            => isset($bidRequest->trip->profile->rating) ? $bidRequest->trip->profile->rating : 0,      
            'city'              => isset($bidRequest->trip->profile->city) ? $bidRequest->trip->profile->city : null,
            'state'             => isset($bidRequest->trip->profile->state) ? $bidRequest->trip->profile->state : null, 
            'boatInsurance'     => isset($bidRequest->trip->ownerInfo->boatInsurance) ? $bidRequest->trip->ownerInfo->boatInsurance : null,
            'towCoverage'       => isset($bidRequest->trip->ownerInfo->towCoverage) ? $bidRequest->trip->ownerInfo->towCoverage : null,
            'validSafetyGear'   => isset($bidRequest->trip->ownerInfo->validSafetyGear) ? $bidRequest->trip->ownerInfo->validSafetyGear : null,                         
            'away'              => (isset($bidRequest->trip->profile) && isset($bidRequest->profile)) ? $this->distance($bidRequest->trip->profile, $bidRequest->profile) : null,            
            'date'              => isset($bidRequest->trip->startTime) ? date('m/d/Y', strtotime($bidRequest->trip->startTime)) : null, 
            'startLocation'     => isset($bidRequest->trip->startLocation) ? $bidRequest->trip->startLocation : null,
            'startTime'         => isset($bidRequest->trip->startTime) ? date('m/d/Y @ hA', strtotime($bidRequest->trip->startTime)) : null,
            'endLocation'       => isset($bidRequest->trip->endLocation) ? $bidRequest->trip->endLocation : null,
            'endTime'           => isset($bidRequest->trip->endTime) ? date('m/d/Y @ hA', strtotime($bidRequest->trip->endTime)) : null,
            'describe'          => isset($bidRequest->trip->describe) ? $bidRequest->trip->describe : null
        ];
    }

    public function bidCaptRequestCollection(\Illuminate\Database\Eloquent\Collection $items)
    {
        return $items->map(function ($item) {
            return $this->bidCaptRequestTransform($item);
        });
    }

    public function bidCaptRequestTransform($bidRequest)
    {
        return [
            'id'                => $bidRequest->id,
            'tripId'            => $bidRequest->trip->tripId,
            'avatar'            => isset($bidRequest->profile->avatar) ? $bidRequest->profile->avatar : null,
            'firstName'         => isset($bidRequest->profile->firstName) ? $bidRequest->profile->firstName : null,
            'lastName'          => isset($bidRequest->profile->lastName) ? $bidRequest->profile->lastName : null,
            'rating'            => isset($bidRequest->profile->rating) ? $bidRequest->profile->rating : 0,      
            'city'              => isset($bidRequest->profile->city) ? $bidRequest->profile->city : null,
            'state'             => isset($bidRequest->profile->state) ? $bidRequest->profile->state : null, 
            'firstResponder'    => isset($bidRequest->captainInfo->firstResponder) ? $bidRequest->captainInfo->firstResponder : null,
            'maritimeGrad'      => isset($bidRequest->captainInfo->maritimeGrad) ? $bidRequest->captainInfo->maritimeGrad : null,
            'militaryVeteran'   => isset($bidRequest->captainInfo->militaryVeteran) ? $bidRequest->captainInfo->militaryVeteran : null,
            'drugFree'          => isset($bidRequest->captainInfo->drugFree) ? $bidRequest->captainInfo->drugFree : null,     
            'date'              => isset($bidRequest->trip->startTime) ? date('m/d/Y', strtotime($bidRequest->trip->startTime)) : null, 
            'startLocation'     => isset($bidRequest->trip->startLocation) ? $bidRequest->trip->startLocation : null,
            'startTime'         => isset($bidRequest->trip->startTime) ? date('m/d/Y @ hA', strtotime($bidRequest->trip->startTime)) : null,
            'endLocation'       => isset($bidRequest->trip->endLocation) ? $bidRequest->trip->endLocation : null,
            'endTime'           => isset($bidRequest->trip->endTime) ? date('m/d/Y @ hA', strtotime($bidRequest->trip->endTime)) : null,
            'ownerDescribe'     => isset($bidRequest->trip->describe) ? $bidRequest->trip->describe : null,
            'captDescribe'      => isset($bidRequest->describe) ? $bidRequest->describe : null
        ];
    }

}