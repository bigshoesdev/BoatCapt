<?php

namespace App\Http\Controllers\Captain;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\User;
use App\Profile;
use App\CaptainInfo;
use App\Presenters\CaptainPresenter;
use Illuminate\Support\Facades\Validator;
use DB;

class ProfileController extends Controller
{
    //
	private $user;
    private $profile;
    private $captainInfo;
    private $captainPresenter;

	public function __construct(User $user, Profile $profile, CaptainInfo $captainInfo, CaptainPresenter $captainPresenter)
	{
		$this->user = $user;
		$this->profile = $profile;
        $this->captainInfo = $captainInfo;
        $this->captainPresenter = $captainPresenter;
	}

	public function index() 
    {
        $user = $this->captainPresenter->transformCollection(
                    $this->user->with(['profile', 'captainInfo'])->where('id', Auth::user()->id)->get()
                )[0];
        $userInfo = json_encode($user);

        $param = json_encode([
            'avatar' => $user['avatar'], 
            'searchable' => false, 
            'login' => true
        ]);
        $userName = json_encode($user['firstName'] ? $user['firstName'] : explode(' ', $user['fullName'])[0]);

        return view('pages.captain.profile', compact('param', 'userInfo', 'userName'));
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

        $captainData = [
            'user_id'           => Auth::user()->id,
            'uscgLicense'       => $request->uscgLicense,
            'licenseTonnage'    => $request->licenseTonnage,
            'firstResponder'    => $request->firstResponder ? 1 : 0,
            'maritimeGrad'      => $request->maritimeGrad ? 1 : 0,
            'militaryVeteran'   => $request->militaryVeteran ? 1 : 0,
            'drugFree'          => $request->drugFree ? 1 : 0,
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
        $captainInfo = $this->captainInfo->where('user_id', Auth::user()->id)->first();

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

            if(is_null($captainInfo))
            {
                $this->captainInfo->create($captainData);
            }
            else
            {
                $captainInfo->where('user_id', Auth::user()->id)->update($captainData);
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
