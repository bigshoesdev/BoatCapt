<?php

namespace App\Http\Controllers\Owner;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Bid;
use App\BidRequest;
use App\Trip;
use App\MerchantTransaction;
use App\Presenters\BidPresenter;
use Auth;
use Illuminate\Support\Facades\Validator;
use App\Merchants\PayPalMerchant;
use App\Merchants\StripeMerchant;
use DB;
use App;

class BidController extends Controller
{
    //
    private $limit = 10;
    
    public function __construct(User $user, Bid $bid, BidRequest $bidRequest, Trip $trip, MerchantTransaction $merchantTransaction, BidPresenter $bidPresenter, PayPalMerchant $paypal, StripeMerchant $stripe)
    {
        $this->user = $user;
        $this->bid = $bid;
        $this->bidRequest = $bidRequest;
        $this->trip = $trip;
        $this->merchantTransaction = $merchantTransaction;
        $this->bidPresenter = $bidPresenter;
        $this->paypal = $paypal;
        $this->stripe = $stripe;
    }

    public function index(Request $request) 
    {
        $param = [
            'avatar'        => Auth::user()['profile'] ? Auth::user()['profile']['avatar'] : null,
            'searchable'    => true,
            'login'         => true
        ];      

        $param = json_encode($param);

        $bidInfo = $this->bidPresenter->bidDetailCollection(
                            $this->bid->with(['trip', 'profile', 'captainInfo'])
                                    ->whereHas('trip', function($q) {
                                        $q->where('user_id',  Auth::user()->id);
                                    }) 
                                    ->get()
                        );
        if(count($bidInfo) == 0)
            return redirect('/');

        $bidInfo = json_encode($bidInfo[0]);
        return view('pages.owner.view-bid', compact('param', 'bidInfo'));
    }

    public function bidList(Request $request) 
    {
        $offset = 0;
        $limit = $this->limit;

        if(isset($request->offset))
            $offset = $request->offset;

        if(isset($request->limit) && $request->limit != 0)
            $limit = $request->limit;

        $bidCount = $this->bid->whereHas('trip', function($q) {
                                $q->where('user_id',  Auth::user()->id);
                            })                             
                            ->count();

        if($bidCount == 0)
        {
            return response()->json([
                'bidCount'    => 0,
                'bidList'      => []
            ]);
        }

        $bids = $this->bid->whereHas('trip', function($q) {
                                $q->where('user_id',  Auth::user()->id);
                            }) 
                            ->orderBy('created_at', 'desc')
                            ->offset($offset)
                            ->limit($limit);

        $bidList = $this->bidPresenter->bidCollection(
            $bids->get()
        );

        return response()->json([
            'bidCount'      => $bidCount,
            'bidList'       => $bidList
        ]);
    }

    public function booking($bidId) 
    {
        $param = [
            'avatar'        => Auth::user()['profile'] ? Auth::user()['profile']['avatar'] : null,
            'searchable'    => true,
            'login'         => true
        ];      

        $param = json_encode($param);

        $bidInfo = $this->bidPresenter->bidBillingCollection(
                            $this->bid->with(['trip', 'trip.paypalEmail', 'trip.stripeDetail', 'trip.profile', 'profile'])
                                    ->where('id', $bidId) 
                                    ->get()
                        );
        if(count($bidInfo) == 0)
            return redirect('/');

        $bidInfo = json_encode($bidInfo[0]);

        return view('pages.owner.booking', compact('param', 'bidInfo'));
    }

    public function acceptBid(Request $request) 
    {
        $validInfo = [
            'merchant_type' => 'required',
            'bidId'         => 'required|integer',
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
                $validInfo['exp_date'] = 'required';
                $validInfo['cc_digits'] = 'required';
            }
            
        }

        $validator = Validator::make($request->all(), $validInfo);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $bidId = $request->bidId;

        $bidInfo = $this->bid->where('id', $bidId)->first();   

        if(is_null($bidInfo))
        {
            return redirect()->back()
                        ->withErrors(['message' => 'There was a problem complete booking.']);
        }

        $trip_id = $bidInfo['trip_id'];    

        $amount = (int)($bidInfo->hours * $bidInfo->amount * 100); 

        if ( App::environment() === 'local' )
        {       

            if($request->merchant_type == 1)
            {
                $chargeData = [
                    'amount'    => $amount / 100
                ];

                $this->paypal->setChargeData($chargeData);
                $this->paypal->setCredentials();

                try {
                    $paymentResult = $this->paypal->createPayment($request->bidId, $request->bidId);
                }
                catch (\Exception $e) {
                    return redirect()->back()
                                    ->withErrors(['message' => 'There was a problem complete booking.']);
                }

                if ( isset($paymentResult['failed']) )
                {
                    return redirect('/'.$bidId.'/owner-booking')->withErrors(['message' => $paymentResult['msg']]);
                } 

                return redirect($paymentResult['approvalLink']);
            }
            else
            {                
                $exp_date = explode('-', $request->exp_date);
                $cardInfo = [
                    'cardNumber' => $request->card_number,
                    'expMonth'   => $exp_date[1],
                    'expYear'    => $exp_date[0],
                    'cvc'        => $request->cc_digits
                ];

                $this->stripe->setCardData($cardInfo);
                $tokenResponse = $this->stripe->getToken();

                if ( isset($tokenResponse['failed']) ) 
                    return redirect('/'.$request->bidId.'/owner-booking')->withErrors(['message' => $tokenResponse['msg']]);

                $chargeInfo = [
                    'stripeToken' => $tokenResponse['id'],
                    'amount'      => $amount / 100,
                    'description' => 'Trip payment'
                ];

                $this->stripe->setChargeData($chargeInfo);

                $chargeResponse = $this->stripe->charge();

                if ( isset($chargeResponse['failed']) ) 
                    return redirect('/'.$bidId.'/owner-booking')->withErrors(['message' => $chargeResponse['msg']]);
            }
        }
        else
        {   
            $transactionId = strtoupper(str_random(17));
            $rate = 0.04;
            $payment_id = 'PAY-'.strtoupper(str_random(24));
            $cc_digits = null;

            if($request->merchant_type == 2)
            {
                $transactionId = 'ch_'.str_random(14);
                $rate = 0.029;
                $payment_id = null;
                $cc_digits = $request->cc_digits;
            }

            $chargeResponse = [
                'transactionId'     => $transactionId,
                'amount'            => $amount / 100,
                'transaction_fee'   => $amount / 100 * $rate,
                'cc_digits'         => $cc_digits,
                'paymentId'         => $payment_id
            ];
        }    
        
        try {
            DB::beginTransaction();

            $merchant_transaction = $this->merchantTransaction->create([
                'merchant_type'     => $request->merchant_type,
                'transaction_id'    => $chargeResponse['transactionId'],
                'amount'            => $chargeResponse['amount'],
                'transaction_fee'   => $chargeResponse['transaction_fee'],
                'cc_digits'         => $chargeResponse['cc_digits'],
                'payment_id'        => $chargeResponse['paymentId']
            ]);

            $this->trip->where('id', $trip_id)
                        ->update([
                            'bid_id'                   => $bidId,
                            'merchant_transaction_id'  => $merchant_transaction->id
                        ]);
            
            $this->bidRequest->where('trip_id', $trip_id)->delete();
            $this->bid->where('trip_id', $trip_id)->delete();

            DB::commit();
        }
        catch (\Exception $e) {
            DB::rollback();
            
            return redirect()->back()
                            ->withErrors(['message' => 'There was a problem complete booking.']);
        }

        return redirect('/'.$bidId.'/owner-booking-complete');
    }

    public function completePaymentWithPayPal(Request $request, $bidId) {        

        $input = $request->input();

        $this->paypal->setCredentials();
        $this->paypal->setChargeData($request->input());
        $chargeResponse = $this->paypal->charge();

        if ( isset($chargeResponse['failed']) ) 
        {
            return redirect('/'.$bidId.'/owner-booking')->withErrors(['message' => $chargeResponse['msg']]);
        }

        $bidInfo = $this->bid->where('id', $bidId)->first();   

        if(is_null($bidInfo))
        {
            return redirect()->back()
                        ->withErrors(['message' => 'There was a problem complete booking.']);
        }

        $trip_id = $bidInfo['trip_id'];

        try {
            DB::beginTransaction();

            $merchant_transaction_id = $this->merchantTransaction->create([
                'merchant_type'     => 1,
                'transaction_id'    => $chargeResponse['transactionId'],
                'amount'            => $chargeResponse['amount'],
                'transaction_fee'   => $chargeResponse['transaction_fee'],
                'cc_digits'         => $chargeResponse['cc_digits'],
                'payment_id'        => $chargeResponse['paymentId']
            ]);

            $this->trip->where('id', $trip_id)
                        ->update([
                            'bid_id'                   => $bidId,
                            'merchant_transaction_id'  => $merchant_transaction_id
                        ]);
            
            $this->bidRequest->where('trip_id', $trip_id)->delete();
            $this->bid->where('trip_id', $trip_id)->delete();

            DB::commit();
        }
        catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                            ->withErrors(['message' => 'There was a problem complete booking.']);
        }

        return redirect('/'.$bidId.'/owner-booking-complete');
    }

    public function paypalPaymentRejected($bidId) {
        return redirect('/'.$bidId.'/owner-booking')->withErrors(['message' => 'The paypal payment rejected.']);
    }

    public function rejectBid($bidId) 
    {
        $this->bid->where('id', $bidId)->delete();
        return redirect('/dashboard');
    }

    public function completeBooking($bidId) 
    {
        $param = [
            'avatar'        => Auth::user()['profile'] ? Auth::user()['profile']['avatar'] : null,
            'searchable'    => true,
            'login'         => true
        ];      

        $param = json_encode($param);

        $bidInfo = $this->bidPresenter->bidDetailCollection(
                            $this->bid->with(['trip', 'profile', 'captainInfo'])
                                    ->where('id', $bidId)
                                    ->withTrashed()
                                    ->get()
                        );
        if(count($bidInfo) == 0)
            return redirect('/');

        $bidInfo = json_encode($bidInfo[0]);

        $email = json_encode(Auth::user()->email);

        return view('pages.owner.booking-complete', compact('param', 'bidInfo', 'email'));
    }
}
