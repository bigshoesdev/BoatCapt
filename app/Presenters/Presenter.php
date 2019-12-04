<?php
namespace App\Presenters;

abstract class Presenter {

	public function transformCollection(\Illuminate\Database\Eloquent\Collection $items)
    {
    	return $items->map(function ($item) {
    		return $this->transform($item);
    	});
    }

    public abstract function transform($item);

    public function distance($profile1, $profile2)
    {
        if(isset($profile1->lat) && isset($profile1->lon) && isset($profile2->lat) && isset($profile2->lon))
        {
        	$lat1 = $profile1->lat;
            $lon1 = $profile1->lon;
            $lat2 = $profile2->lat;
            $lon2 = $profile2->lon;
        	$distance = round(sqrt(pow((69.1*($lon1-$lon2)*cos($lat1/57.3)), 2)+pow((69.1*($lat1-$lat2)), 2)), 2);
        	return $distance;
        }
        else
            return null;
    }
}