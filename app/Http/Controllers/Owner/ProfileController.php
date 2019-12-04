<?php

namespace App\Http\Controllers\Owner;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\User;
use App\Profile;
use App\OwnerInfo;
use App\Presenters\OwnerPresenter;
use Illuminate\Support\Facades\Validator;
use DB;

class ProfileController extends Controller
{
    //
    private $user;
    private $profile;
    private $ownerInfo;
    private $ownerPresenter;

	public function __construct(User $user, Profile $profile, OwnerInfo $ownerInfo, OwnerPresenter $ownerPresenter)
	{
		$this->user = $user;
		$this->profile = $profile;
		$this->ownerInfo = $ownerInfo;
		$this->ownerPresenter = $ownerPresenter;
	}

	public function index() 
    {        
        
    	$user = $this->ownerPresenter->transformCollection(
                    $this->user->with(['profile', 'ownerInfo', 'review'])->where('id', Auth::user()->id)->get()
                )[0];
        $userInfo = json_encode($user);        

        $param = json_encode([
            'avatar' => $user['avatar'], 
            'searchable' => true, 
            'login' => true
        ]);
        $userName = json_encode($user['firstName'] ?: explode(' ', $user['fullName'])[0]);

    	return view('pages.owner.profile', compact('param', 'userName', 'userInfo'));
        
    }

    public function update(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'firstName' => 'required|min:3',
            'lastName' => 'required|min:3',
            'email' => $request->email == Auth::user()->email ? 'required|email' : 'required|email|unique:users,email',
            'avatar' => 'mimes:jpg,jpeg,bmp,png,gif|max:10000',
            'fullAddress' => 'required',
            'city' => 'required',
            'state' => 'required',
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
            'user_id'       => Auth::user()->id,
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
            'user_id'           => Auth::user()->id,
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
            $fileName = 'profile-'.Auth::user()->id.'.'.$file->getClientOriginalExtension();
            $profileData['avatar'] = $fileName;

            $check = $file->move(public_path().'/images/avatars/', $fileName);

            if ( ! is_a($check, '\Symfony\Component\HttpFoundation\File\File') ) 
            {
                return redirect()->back()
                            ->withErrors(['avatar' => 'There was a problem uploading the file.'])
                            ->withInput();
            }
        }

        $user = $this->user->where('id', Auth::user()->id)->first();
        $profile = $this->profile->where('user_id', Auth::user()->id)->first();
        $ownerInfo = $this->ownerInfo->where('user_id', Auth::user()->id)->first();

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
                $profile->where('user_id', Auth::user()->id)->update($profileData);
            }

            if(is_null($ownerInfo))
            {
                $this->ownerInfo->create($ownerData);
            }
            else
            {
                $ownerInfo->where('user_id', Auth::user()->id)->update($ownerData);
            }
            DB::commit();
        }
        catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                            ->withErrors(['message' => 'There was a problem updating the account profile.'])
                            ->withInput();
        }

        return redirect()->back()->with('status', 'Account profile was successfully updated.');
    }
}
