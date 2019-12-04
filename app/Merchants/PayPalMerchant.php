<?php
namespace App\Merchants;

use Validator;

use \PayPal\Api\Amount;
use \PayPal\Api\Details;
use \PayPal\Api\Item;
use \PayPal\Api\ItemList;
use \PayPal\Api\Payer;
use \PayPal\Api\Payment;
use \PayPal\Api\PaymentExecution;
use \PayPal\Api\PaymentDetails;
use \PayPal\Api\RedirectUrls;
use \PayPal\Api\Transaction;
use \PayPal\Auth\OAuthTokenCredential;
use \PayPal\Rest\ApiContext;

class PayPalMerchant {

	private $chargeUrl;
	private $returnUrl;
	private $cancelUrl;
	private $paypalSecret;
	private $paypalId;
	private $mode;
	private $credentials;
	private $token;
	private $chargeData;

	public function __construct()
	{
		$this->chargeData = [];
		$this->chargeUrl = env('PAYPAL_CHARGE_URL');
		$this->returnUrl = env('PAYPAL_RETURN_URL');
		$this->cancelUrl = env('PAYPAL_CANCEL_URL');
		$this->paypalSecret = env('PAYPAL_SECRET');
		$this->paypalId = env('PAYPAL_CLIENT_ID');
		$this->mode = env('PAYPAL_MODE');
	}

	public function setCredentials()
	{
		$this->credentials = new OAuthTokenCredential($this->paypalId, $this->paypalSecret, ['mode' => $this->mode]);
	}

	public function createPayment($returnParam, $cancelParam)
	{
		$this->returnUrl .= '/'.$returnParam;
		$this->cancelUrl .= '/'.$cancelParam;

		$apiContext = new ApiContext($this->credentials, 'Request'.time());
		$apiContext->setConfig(['mode' => $this->mode]);

		$payer = new Payer();
		$payer->setPaymentMethod('paypal');

		$amount = new Amount();
		$amount->setCurrency('USD');
		$amount->setTotal($this->chargeData['amount']);

		$transaction = new Transaction();
		$transaction->setDescription('Trip Payment');
		$transaction->setAmount($amount);

		$redirectUrls = new RedirectUrls();
		$redirectUrls->setReturnUrl($this->returnUrl)->setCancelUrl($this->cancelUrl);

		$payment = new Payment();
		$payment->setIntent('sale')
			->setPayer($payer)
			->setRedirectUrls($redirectUrls)
			->setTransactions([$transaction]);

		$request = clone $payment;

		try {
			$payment->create($apiContext);
		}
		catch (Expection $e) {
			return ['failed' => true, 'msg' => "There was an error processing the payment.", 'error' => $e];
		}

		return [
			'success' => true,
			'approvalLink' => $payment->getApprovalLink(),
			'payment' => $payment
		];
	}

	/**
	 * Execute the payment
	 * @return  [description]
	 */
	public function charge()
	{
		$apiContext = new ApiContext($this->credentials, 'Request'.time());
		$apiContext->setConfig(['mode' => $this->mode]);

		$payment = Payment::get($this->chargeData['paymentId'], $apiContext);

		$execution = new PaymentExecution();
		$execution->setPayerId($this->chargeData['payerId']);

		try {
			$result = $payment->execute($execution, $apiContext);

			try {
				$paymentDetails = Payment::get($this->chargeData['paymentId'], $apiContext);
			}
			catch (Exception $e) {
				return ['failed' => true, 'msg' => 'There was a problem retrieving the payment details', 'error' => $e];
			}
			catch(\PayPal\Exception\PayPalConnectionException $e) {
				return ['failed' => true, 'msg' => 'There was a problem connecting to retrieve the payment details ', 'error' => $e];
			}
		}
		catch (Exception $e) {
			return ['failed' => true, 'msg' => 'There was a error while executing the charge', 'error' => $e];
		}
		catch(\PayPal\Exception\PayPalConnectionException $e) {
			return ['failed' => true, 'msg' => 'There was a problem connecting to execute the payment. This usually occurs when the payment has already been processed.', 'error' => $e];
		}

		return [
			'success' 			=> true,
			'amount' 			=> (int)$paymentDetails->transactions[0]->amount->total,
			'transaction_fee'	=> (int)$paymentDetails->transactions[0]->related_resources[0]->sale->transaction_fee->value,
			'transactionId' 	=> $paymentDetails->transactions[0]->related_resources[0]->sale->id,
			'cc_digits' 		=> null,
			'paymentId' 		=> $result->id
		];
	}

	public function setChargeData(array $input)
	{
		if (isset($input['amount']) ) $this->chargeData['amount'] = (float)$input['amount'];
		if ( isset($input['paymentId']) ) $this->chargeData['paymentId'] = $input['paymentId'];
		if ( isset($input['PayerID']) ) $this->chargeData['payerId'] = $input['PayerID'];
		if ( isset($input['token']) ) $this->chargeData['token'] = $input['token'];
	}
}