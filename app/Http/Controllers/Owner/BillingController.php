<?php

namespace App\Http\Controllers\Owner;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\User;
use App\Profile;
use App\PaypalEmail;
use App\StripeDetail;
use App\Presenters\OwnerPresenter;
use Illuminate\Support\Facades\Validator;
use DB;

class BillingController extends Controller
{
    //
    private $user;
    private $profile;
    private $ownerPresenter;
    private $paypalEmail;
    private $stripeDetail;

	public function __construct(User $user, Profile $profile, OwnerPresenter $ownerPresenter, PaypalEmail $paypalEmail, StripeDetail $stripeDetail)
	{
		$this->user = $user;
		$this->profile = $profile;
		$this->ownerPresenter = $ownerPresenter;
        $this->paypalEmail = $paypalEmail;
        $this->stripeDetail = $stripeDetail;
	}

	public function index(Request $request, $ownerId=null) 
    {
        $userId = $ownerId==null?Auth::user()->id:$ownerId;
        $user = $this->ownerPresenter->billingCollection(
                    $this->user->with(['profile', 'ownerInfo', 'review', 'paypalEmail', 'stripeDetail'])->where('id', $userId)->get()
                )[0];
        $userInfo = json_encode($user);

        $param = json_encode([
            'avatar' => $user['avatar'], 
            'searchable' => $ownerId==null,
            'login' => true
        ]);
        $userName = json_encode($user['firstName'] ? $user['firstName'] : explode(' ', $user['fullName'])[0]);

        $ownerId = json_encode($ownerId);

        return view('pages.owner.billing', compact('param', 'userInfo', 'userName', 'ownerId'));
    }

    public function update(Request $request, $ownerId=null) 
    {
        $userId = $ownerId==null?Auth::user()->id:$ownerId;
        $validInfo = [
            'merchant_type' => 'required'
        ];

        if(isset($request->merchant_type) && $request->merchant_type != null)
        {
            if($request->merchant_type == 1)
            {
                $validInfo['paypalEmail'] = 'required|email';
            }
            else
            {
                $validInfo['card_number'] = 'required';
                $validInfo['exp_date'] = 'required|date';
                $validInfo['cc_digits'] = 'required';
            }
            
        }

        $validator = Validator::make($request->all(), $validInfo);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }
            

        try {
            DB::beginTransaction();

            $profileData = [
                'user_id'           => $userId,
                'merchant_type'     => $request->merchant_type
            ];               

            $profile = $this->profile->where('user_id', $userId)->first();

            if(is_null($profile))
            {
                $this->profile->create($profileData);
            }
            else
            {
                $profile->where('user_id', $userId)->update($profileData);
            }

            if($request->merchant_type == 1)
            {
                $paypalData = [
                    'user_id'           => $userId,
                    'email'             => $request->paypalEmail
                ];

                $paypalEmail = $this->paypalEmail->where('user_id', $userId)->first();

                if(is_null($paypalEmail))
                {
                    $this->paypalEmail->create($paypalData);
                }
                else
                {
                    $paypalEmail->where('user_id', $userId)->update($paypalData);
                }
            }
            else
            {
                $stripeData = [
                    'user_id'           => $userId,
                    'card_number'       => $request->card_number,
                    'exp_month'         => date('m', strtotime($request->exp_date)),
                    'exp_year'          => date('Y', strtotime($request->exp_date)),
                    'cc_digits'         => $request->cc_digits
                ];

                $stripeDetail = $this->stripeDetail->where('user_id', $userId)->first();

                if(is_null($stripeDetail))
                {
                    $this->stripeDetail->create($stripeData);
                }
                else
                {
                    $stripeDetail->where('user_id', $userId)->update($stripeData);
                }
            }
            
            DB::commit();
        }
        catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                            ->withErrors(['message' => 'There was a problem updating the account billing profile.']);
        }

        return redirect()->back()->with('status', 'Account billing profile was successfully updated.');
    }
}
