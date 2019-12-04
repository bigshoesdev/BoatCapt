<?php
namespace App\Merchants;

use Validator;

use \Stripe\Stripe as Stripe;
use \Stripe\Customer as Stripe_Customer;
use \Stripe\Charge as Stripe_Charge;
use \Stripe\Error\Card as Stripe_CardError;
use \Stripe\Error\InvalidRequest as Stripe_InvalidRequestError;
use \Stripe\Error\Authentication as Stripe_AuthenticationError;
use \Stripe\Error\ApiConnection as Stripe_ApiConnectionError;
use \Stripe\Error as Stripe_Error;
use \Stripe\Token as Stripe_Token;

class StripeMerchant {

	// the data required to process a charge
	public $chargeData;
	public $cardData;

	private $apiKey;
	private $newCustomer;

	public function __construct()
	{
		$this->newCustomer      = false;
		$this->chargeData       = [];
		$this->cardData         = [];
		$this->apiKey           = env('STRIPE_SECRET');
	}

	public function setCardData(array $input)
	{
		$this->cardData = [
			'card' => [
				'number'        => $input['cardNumber'],
				'exp_month'     => $input['expMonth'],
				'exp_year'      => $input['expYear'],
				'cvc'           => $input['cvc']
			],
		];
	}

	public function setChargeData(array $input)
	{
		$this->chargeData = [
			'amount'      => $input['amount'],
			'currency'    => 'USD',
			'description' => $input['description'],
		];

		if ( isset($input['stripeToken']) ) $this->chargeData['source'] = $input['stripeToken'];

		if ( isset($input['customer']) ) $this->chargeData['customer'] = $input['customer'];

		$this->newCustomer = ( isset($input['setupNewCustomer']) );
	}

	public function getToken()
	{
		Stripe::setApiKey($this->apiKey);

		if ( ! empty($this->cardData) ) {
			try {				
				$tokenResponse = Stripe_Token::create($this->cardData);
			}
			catch (Stripe_CardError $e) {
				return [
					'failed' => true,
					'msg' => $e->jsonBody['error']['message'],
					'error' => $e,
				];
			}
			catch (Stripe_InvalidRequestError $e) {				
			 	return [
						'failed' => true,
						'msg'    => 'Invalid parameters were supplied to Stripe\'s API',
						'error'  => $e,
			 	];

			}
			catch (Stripe_AuthenticationError $e) {
			  	return [
						'failed' => true,
						'msg'    => 'Authentication with Stripe\'s API failed (maybe you changed API keys recently)',
						'error'  => $e,
			  	];

			}
			catch (Stripe_ApiConnectionError $e) {
			  	return [
						'failed' => true,
						'msg'    => 'Network communication with Stripe failed',
						'error'  => $e,
			  	];

			}
			catch (Stripe_Error $e) {
			  	return [
						'failed' =>true,
						'msg'    =>'There was an error processing your request',
						'error'  => $e,
			  	];

			  	// fire an event to send email to administrator
			}
			catch (Exception $e) {
				return [
					'failed' => true,
					'msg' => 'There was an error while attempting to get the card token',
					'error' => $e,
				];
			}

			return $tokenResponse;
		}
		else {
			return [
				'failed' => true,
				'msg'    => 'Card data was not set',
				'error'  => 'Card data was not set',
			];
		}
	}

	public function charge()
	{
		Stripe::setApiKey($this->apiKey);

		try {

			if ( ! empty($this->chargeData) ) {				
				$charge = Stripe_Charge::create($this->chargeData);
			}
			else {
				return [
					'failed' => true,
					'msg' => 'Charge data was not set'
				];
			}

		}
		catch(Stripe_CardError $e) {
	  		return [
					'failed' => true,
					'msg'    => $e->jsonBody['error']['message'],
					'error'  => $e
	  		];

		}
		catch (Stripe_InvalidRequestError $e) {
		 	return [
					'failed' => true,
					'msg'    => 'Invalid parameters were supplied to Stripe\'s API',
					'error'  => $e,
		 	];

		}
		catch (Stripe_AuthenticationError $e) {
		  	return [
					'failed' => true,
					'msg'    => 'Authentication with Stripe\'s API failed (maybe you changed API keys recently)',
					'error'  => $e,
		  	];

		}
		catch (Stripe_ApiConnectionError $e) {
		  	return [
					'failed' => true,
					'msg'    => 'Network communication with Stripe failed',
					'error'  => $e,
		  	];

		}
		catch (Stripe_Error $e) {
		  	return [
					'failed' =>true,
					'msg'    =>'Display a very generic error to the user, and maybe send yourself an email',
					'error'  => $e,
		  	];

		}
		catch (Exception $e) {
		  	return [
					'failed' => true,
					'msg'    => 'Something else happened, completely unrelated to Stripe',
					'error'  => $e,
		  	];

		}

		if ( isset($charge) ) {
			return [
				'success' 			=> true,
				'amount'			=> $charge->amount,
				'transaction_fee'	=> $charge->fee,
				'transactionId' 	=> $charge->id,
				'cc_digits' 		=> $charge->source->last4,
				'paymentId'			=> null
			];
		}

		return [
				'failed' => true,
				'msg'    => 'There was a problem payment with Stripe.',
				'error'  => $e,
	  	];
	}

	public function createCustomer(array $input)
	{
		Stripe::setApiKey($this->apiKey);

		$customer = Stripe_Customer::create($input);

		return $customer->id;
	}

	public function updateCustomer(array $input)
	{
		Stripe::setApiKey($this->apiKey);

		$customer = Stripe_Customer::retrieve($input['customer']);
		$customer->source = $input['source'];
		$customer->save();
	}
}