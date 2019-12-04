<?php
namespace App\Presenters;

class TripPresenter extends Presenter {

    public function transform($trip)
    {
        return [
            'tripId'            => $trip->tripId,
            'startLocation'     => isset($trip->startLocation) ? $trip->startLocation : null,
            'startTime'         => isset($trip->startTime) ? date('m/d/Y @ hA', strtotime($trip->startTime)) : null,
            'endLocation'       => isset($trip->endLocation) ? $trip->endLocation : null,
            'endTime'           => isset($trip->endTime) ? date('m/d/Y @ hA', strtotime($trip->endTime)) : null,
            'describe'          => isset($trip->describe) ? $trip->describe : null
        ];
    }

    public function ownerTripsCollection(\Illuminate\Database\Eloquent\Collection $items)
    {
        return $items->map(function ($item) {
            return $this->ownerTripsTransform($item);
        });
    }

    public function ownerTripsTransform($trip)
    {
        return [
            'tripId'            => $trip->tripId,
            'total'             => isset($trip->merchantTransaction->amount) ? $trip->merchantTransaction->amount * 100 : 0,
            'captainId'         => isset($trip->bid->profile->user_id) ? $trip->bid->profile->user_id : null,
            'avatar'            => isset($trip->bid->profile->avatar) ? $trip->bid->profile->avatar : null,
            'firstName'         => isset($trip->bid->profile->firstName) ? $trip->bid->profile->firstName : null,
            'lastName'          => isset($trip->bid->profile->lastName) ? $trip->bid->profile->lastName : null,
            'rating'            => isset($trip->bid->profile->rating) ? $trip->bid->profile->rating : 0,      
            'city'              => isset($trip->bid->profile->city) ? $trip->bid->profile->city : null,
            'state'             => isset($trip->bid->profile->state) ? $trip->bid->profile->state : null,    
            'away'              => (isset($trip->profile) && isset($trip->bid->profile)) ? $this->distance($trip->profile, $trip->bid->profile) : null,             
            'date'              => isset($trip->startTime) ? date('m/d/Y', strtotime($trip->startTime)) : null, 
            'startLocation'     => isset($trip->startLocation) ? $trip->startLocation : null,
            'startTime'         => isset($trip->startTime) ? date('m/d/Y @ hA', strtotime($trip->startTime)) : null,
            'endLocation'       => isset($trip->endLocation) ? $trip->endLocation : null,
            'endTime'           => isset($trip->endTime) ? date('m/d/Y @ hA', strtotime($trip->endTime)) : null
        ];
    }

    public function ownerDetailCollection(\Illuminate\Database\Eloquent\Collection $items)
    {
        return $items->map(function ($item) {
            return $this->ownerDetailTransform($item);
        });
    }

    public function ownerDetailTransform($trip)
    {
        return [
            'tripId'            => isset($trip->tripId) ? $trip->tripId : null,
            'total'             => isset($trip->merchantTransaction->amount) ? $trip->merchantTransaction->amount * 100 : 0,
            'merchantType'      => isset($trip->merchantTransaction->merchant_type) ? $trip->merchantTransaction->merchant_type  : null,
            'payDate'           => isset($trip->merchantTransaction->created_at) ? date('m/d/Y', strtotime($trip->merchantTransaction->created_at)) : null,
            'ownerId'           => isset($trip->user_id) ? $trip->user_id : null,
            'captainId'         => isset($trip->bid->profile->user_id) ? $trip->bid->profile->user_id : null,
            'captainEmail'      => isset($trip->profile->user->email) ? $trip->profile->user->email : null,
            'captainPhone'      => isset($trip->profile->phone) ? $trip->profile->phone : null,
            'avatar'            => isset($trip->bid->profile->avatar) ? $trip->bid->profile->avatar : null,
            'ownerFirstName'    => isset($trip->profile->firstName) ? $trip->profile->firstName : null,
            'firstName'         => isset($trip->bid->profile->firstName) ? $trip->bid->profile->firstName : null,
            'lastName'          => isset($trip->bid->profile->lastName) ? $trip->bid->profile->lastName : null,
            'rating'            => isset($trip->bid->profile->rating) ? $trip->bid->profile->rating : 0,      
            'city'              => isset($trip->bid->profile->city) ? $trip->bid->profile->city : null,
            'state'             => isset($trip->bid->profile->state) ? $trip->bid->profile->state : null,    
            'firstResponder'    => isset($trip->bid->captainInfo->firstResponder) ? $trip->bid->captainInfo->firstResponder : null,
            'maritimeGrad'      => isset($trip->bid->captainInfo->maritimeGrad) ? $trip->bid->captainInfo->maritimeGrad : null,
            'militaryVeteran'   => isset($trip->bid->captainInfo->militaryVeteran) ? $trip->bid->captainInfo->militaryVeteran : null,
            'drugFree'          => isset($trip->bid->captainInfo->drugFree) ? $trip->bid->captainInfo->drugFree : null,            
            'startLocation'     => isset($trip->startLocation) ? $trip->startLocation : null,
            'startTime'         => isset($trip->startTime) ? date('m/d/Y @ hA', strtotime($trip->startTime)) : null,
            'endLocation'       => isset($trip->endLocation) ? $trip->endLocation : null,
            'endTime'           => isset($trip->endTime) ? date('m/d/Y @ hA', strtotime($trip->endTime)) : null,
            'ownerDescribe'     => isset($trip->describe) ? $trip->describe : null,
            'captDescribe'      => isset($trip->bid->describe) ? $trip->bid->describe : null,
            'isComplete'        => strtotime(date('Y-m-d H:i:s')) < strtotime($trip->endTime) ? 0 : 1
        ];
    }

    public function captainTripsCollection(\Illuminate\Database\Eloquent\Collection $items)
    {
        return $items->map(function ($item) {
            return $this->captainTripsTransform($item);
        });
    }

    public function captainTripsTransform($trip)
    {
        return [
            'tripId'            => $trip->tripId,
            'total'             => isset($trip->merchantTransaction->amount) ? $trip->merchantTransaction->amount * 100 : 0,
            'avatar'            => isset($trip->profile->avatar) ? $trip->profile->avatar : null,
            'firstName'         => isset($trip->profile->firstName) ? $trip->profile->firstName : null,
            'lastName'          => isset($trip->profile->lastName) ? $trip->profile->lastName : null,
            'rating'            => isset($trip->profile->rating) ? $trip->profile->rating : 0,      
            'city'              => isset($trip->profile->city) ? $trip->profile->city : null,
            'state'             => isset($trip->profile->state) ? $trip->profile->state : null,    
            'away'              => (isset($trip->profile) && isset($trip->bid->profile)) ? $this->distance($trip->profile, $trip->bid->profile) : null,                  
            'date'              => isset($trip->startTime) ? date('m/d/Y', strtotime($trip->startTime)) : null,
            'startLocation'     => isset($trip->startLocation) ? $trip->startLocation : null,
            'startTime'         => isset($trip->startTime) ? date('m/d/Y @ hA', strtotime($trip->startTime)) : null,
            'endLocation'       => isset($trip->endLocation) ? $trip->endLocation : null,
            'endTime'           => isset($trip->endTime) ? date('m/d/Y @ hA', strtotime($trip->endTime)) : null
        ];
    }

    public function captainDetailCollection(\Illuminate\Database\Eloquent\Collection $items)
    {
        return $items->map(function ($item) {
            return $this->captainDetailTransform($item);
        });
    }

    public function captainDetailTransform($trip)
    {
        return [
            'tripId'            => isset($trip->tripId) ? $trip->tripId : null,
            'total'             => isset($trip->merchantTransaction->amount) ? $trip->merchantTransaction->amount * 100 : 0,
            'merchantType'      => isset($trip->merchantTransaction->merchant_type) ? $trip->merchantTransaction->merchant_type  : null,
            'payDate'           => isset($trip->merchantTransaction->created_at) ? date('m/d/Y', strtotime($trip->merchantTransaction->created_at)) : null,
            'ownerId'           => isset($trip->user_id) ? $trip->user_id : null,
            'ownerEmail'        => isset($trip->profile->user->email) ? $trip->profile->user->email : null,
            'ownerPhone'        => isset($trip->profile->phone) ? $trip->profile->phone : null,
            'avatar'            => isset($trip->profile->avatar) ? $trip->profile->avatar : null,
            'firstName'         => isset($trip->profile->firstName) ? $trip->profile->firstName : null,
            'lastName'          => isset($trip->profile->lastName) ? $trip->profile->lastName : null,
            'rating'            => isset($trip->profile->rating) ? $trip->profile->rating : 0,      
            'city'              => isset($trip->profile->city) ? $trip->profile->city : null,
            'state'             => isset($trip->profile->state) ? $trip->profile->state : null,    
            'boatInsurance'     => isset($trip->ownerInfo->boatInsurance) ? $trip->ownerInfo->boatInsurance : null,
            'towCoverage'       => isset($trip->ownerInfo->towCoverage) ? $trip->ownerInfo->towCoverage : null,
            'validSafetyGear'   => isset($trip->ownerInfo->validSafetyGear) ? $trip->ownerInfo->validSafetyGear : null,                      
            'startLocation'     => isset($trip->startLocation) ? $trip->startLocation : null,
            'startTime'         => isset($trip->startTime) ? date('m/d/Y @ hA', strtotime($trip->startTime)) : null,
            'endLocation'       => isset($trip->endLocation) ? $trip->endLocation : null,
            'endTime'           => isset($trip->endTime) ? date('m/d/Y @ hA', strtotime($trip->endTime)) : null,
            'ownerDescribe'     => isset($trip->describe) ? $trip->describe : null,
            'captDescribe'      => isset($trip->bid->describe) ? $trip->bid->describe : null,
            'isComplete'        => strtotime(date('Y-m-d H:i:s')) < strtotime($trip->endTime) ? 0 : 1
        ];
    }

    public function tripCollection(\Illuminate\Database\Eloquent\Collection $items)
    {
        return $items->map(function ($item) {
            return $this->tripTransform($item);
        });
    }

    public function tripTransform($trip)
    {
        $payment_method='Unknown';
        if(isset($trip->merchant_type)){
            if($trip->merchant_type==1)
                $payment_method='PayPal';
            else if($trip->merchant_type==2)
                $payment_method='Credit Card';
        }
        return [
            'id'                => $trip->id,
            'tripId'            => $trip->tripId,
            'ownerId'           => $trip->user_id,
            'bidId'             => $trip->bid_id,
            'captainId'         => isset($trip->captainId) ? $trip->captainId : null,
            'firstName'         => isset($trip->firstName) ? $trip->firstName : null,
            'lastName'          => isset($trip->lastName) ? $trip->lastName : null, 
            'date'              => isset($trip->startTime) ? date('m/d/Y', strtotime($trip->startTime)) : null,
            'total'             => isset($trip->total) ? intval($trip->total) : 0,
            'payment'           => isset($trip->payment) ? intval($trip->payment) : 0,
            'fee'               => isset($trip->fee) ? intval($trip->fee) : 0,
            'net'               => isset($trip->net) ? intval($trip->net) : 0,
            'payment_method'    => $payment_method,
            'isCompleted'       => isset($trip->isCompleted) ? $trip->isCompleted : false,
        ];
    }

    public function adminBookCollection(\Illuminate\Database\Eloquent\Collection $items)
    {
        return $items->map(function ($item) {
            return $this->adminBookTransform($item);
        });
    }

    public function adminBookTransform($trip)
    {
        return [
            'tripId'            => isset($trip->tripId) ? $trip->tripId : null,
            'ownerId'           => isset($trip->user_id) ? $trip->user_id : null,
            'captainId'         => isset($trip->bid->profile->user_id) ? $trip->bid->profile->user_id : null,
            'captainEmail'      => isset($trip->profile->user->email) ? $trip->profile->user->email : null,
            'captainPhone'      => isset($trip->profile->phone) ? $trip->profile->phone : null,
            'avatar'            => isset($trip->bid->profile->avatar) ? $trip->bid->profile->avatar : null,
            'ownerFirstName'    => isset($trip->profile->firstName) ? $trip->profile->firstName : null,
            'firstName'         => isset($trip->bid->profile->firstName) ? $trip->bid->profile->firstName : null,
            'lastName'          => isset($trip->bid->profile->lastName) ? $trip->bid->profile->lastName : null,
            'rating'            => isset($trip->bid->profile->rating) ? $trip->bid->profile->rating : 0,      
            'city'              => isset($trip->bid->profile->city) ? $trip->bid->profile->city : null,
            'state'             => isset($trip->bid->profile->state) ? $trip->bid->profile->state : null,    
            'firstResponder'    => isset($trip->bid->captainInfo->firstResponder) ? $trip->bid->captainInfo->firstResponder : null,
            'maritimeGrad'      => isset($trip->bid->captainInfo->maritimeGrad) ? $trip->bid->captainInfo->maritimeGrad : null,
            'militaryVeteran'   => isset($trip->bid->captainInfo->militaryVeteran) ? $trip->bid->captainInfo->militaryVeteran : null,
            'drugFree'          => isset($trip->bid->captainInfo->drugFree) ? $trip->bid->captainInfo->drugFree : null,            
            'isComplete'        => strtotime(date('Y-m-d H:i:s')) < strtotime($trip->endTime) ? 0 : 1
        ];
    }
}