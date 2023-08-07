<?php
namespace App\Services\Payment;
use App\Models\Payment;
use App\Models\Order;
use App\Models\Payment\PaymentCard;
use Illuminate\Support\Facades\Log;
class PaymentService
{	
	// тип платежа ( удалить)
	const NEW_PAYMENT = "NEW_CASHLESS_PAY";
	const NEW_INSTALLMENT_TINKOFF = "NEW_INSTALLMENT_3";
	const NEW_INSTALLMENT_QUALIFITERRA = "NEW_INSTALLMENT_2";
	
	// типы хуков
	const TINKOFF_WEBHOOK = "webhook_tinkoff_payment";
	const TINKOFF_WEBHOOK_INSTALLMENT = "webhook_tinkoff_installment";

	// статусы платежа ( повторяют тинькофф статусы)
	const PAYMENT_NEW = "NEW";
	const PAYMENT_AUTHORIZED = "AUTHORIZED";
	const PAYMENT_CONFIRMED = "CONFIRMED";

	// уникальные для рассрочки квалифитеры ( сделать для ордера)
	const PAYMENT_PART_PAID = "PART_PAID";

	// методы оплаты
	// 1	Tinkoff, карта	cashless_pay
	// 18	Рассрочка ( Qualifiterra)	installment_2
	// 19	Кредит ( Tinkoff)	installment_3

	private function _genToken($args)
	{	
	    $token = '';
	    $args['Password'] = $this->secretKey;
	    ksort($args);
	    foreach ($args as $arg) {
	        if (!is_array($arg)) {
	            $token .= $arg;
	        }
	    }
	    $token = hash('sha256', $token);
	    return $token;
	}

	public function buildQuery($path, $args)
	{	
		$this->api_url = config('tinkoff.api_url');
		$this->terminalKey = config('tinkoff.terminal_key');
		$this->secretKey = config('tinkoff.secret_key');

	    $url = $this->api_url;
	    if (is_array($args)) {
	        if (!array_key_exists('TerminalKey', $args)) {
	            $args['TerminalKey'] = $this->terminalKey;
	        }
	        if (!array_key_exists('Token', $args)) {
	            $args['Token'] = $this->_genToken($args);
	        }
	    }
	    $url = $this->_combineUrl($url, $path);
	    return $this->_sendRequest($url, $args);
	}

	private function _combineUrl()
	{
	    $args = func_get_args();
	    $url = '';
	    foreach ($args as $arg) {
	        if (is_string($arg)) {
	            if ($arg[strlen($arg) - 1] !== '/') $arg .= '/';
	            $url .= $arg;
	        } else {
	            continue;
	        }
	    }

	    return $url;
	}

	public function getDevOrder($payment_id)
	{	
		$args["PaymentId"] = $payment_id;

	    return $this->buildQuery('GetState', $args);
	}


	public function addCustomer($args)
	{	
	    return $this->buildQuery('AddCustomer', $args);
	}

	public function getCustomer($args)
	{	
	    return $this->buildQuery('GetCustomer', $args);
	}

	public function removeCard($card_id)
	{	
		$customerKey = PaymentCard::where('card_id', $card_id)->first()->customer_key;
		$args = [
			'CardId'      => $card_id,
			'CustomerKey' => $customerKey,
		];

	    $resp = json_decode($this->buildQuery('RemoveCard', $args), 1);

	    if ($resp['Success'] == false) {
	    	return $resp['Message'];
	    } else {

	    	\DB::table('payment_cards')
	    		->where('customer_key', $customerKey)
	    		->where('card_id', $card_id)
	    		->update([
	    			'status' => 'D'
	    		]);
	    }


	}

	public function getCardList($args, $userId)
	{
	   $dataCards = $this->buildQuery('GetCardList', $args);
	   $this->checkUpdateListCards($dataCards, $userId, $args['CustomerKey']);
	}
	
	public function removeCustomer($args)
	{
	    return $this->buildQuery('RemoveCustomer', $args);
	}

	public function checkUpdateListCards($cards, $userId, $customerKey)
	{	
		$cards = json_decode($cards, 1);
		foreach ($cards as $card) {
			if($card != false) {
				if (\DB::table('payment_cards')
						->where('user_id', $userId)
						->where('card_id', $card['CardId'])
						->count() > 0 ) {
					continue;
				} else {
					$saveCard = new PaymentCard;
					$saveCard->card_id =  $card['CardId'];
					$saveCard->pan =  $card['Pan'];
					$saveCard->status =  $card['Status'];
					$saveCard->rebill_id =  $card['RebillId'];
					$saveCard->card_type =  $card['CardType'];
					$saveCard->exp_date =  $card['ExpDate'];
					$saveCard->user_id =  $userId;
					$saveCard->customer_key = $customerKey;
					$saveCard->save();
				}
			}
		}
	}

	public function getOrder($OrderId)
	{	
		$args["OrderId"] = $OrderId;
	    return $this->buildQuery('CheckOrder', $args);
	}

	private function _sendRequest($api_url, $args)
	{
	    $this->error = '';
	    if (is_array($args)) {
	        $args = json_encode($args);
	    }

	    if ($curl = curl_init()) {
	        curl_setopt($curl, CURLOPT_URL, $api_url);
	        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
	        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	        curl_setopt($curl, CURLOPT_POST, true);
	        curl_setopt($curl, CURLOPT_POSTFIELDS, $args);
	        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
	            'Content-Type: application/json',
	        ));

	        $out = curl_exec($curl);
	        $this->response = $out;
	        $json = json_decode($out);

	        if ($json) {
	            if (@$json->ErrorCode !== "0") {
	                $this->error = @$json->Details;
	            } else {
	                $this->paymentUrl = @$json->PaymentURL;
	                $this->paymentId = @$json->PaymentId;
	                $this->status = @$json->Status;
	            }
	        }
	        curl_close($curl);
	        return $out;
	    } else {
	        throw new HttpException('Can not create connection to ' . $api_url . ' with args ' . $args, 404);
	    }
	}


	public function newInstallment($insallmentBody)
	{	

		$api_url = config('tinkoff.api_installment_form');
		// dd($api_url);
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, $api_url);
	    curl_setopt($ch, CURLOPT_POST, 1);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $insallmentBody);
	    curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	    $server_output = curl_exec($ch);
	    curl_close($ch);
	    
	    return $server_output;
	}

	public function getTypePayment($slug)
	{
		return \DB::table('pay_methods')->where('slug', $slug)->first()->id;
	}

	public function acceptNotify($data)
	{
		$transId = \DB::table('dev_notify_post')->insertGetId([
		    'request_body' => serialize($data),
		    'created_at' => now(),
		    'desc' => $this::TINKOFF_WEBHOOK
		]);


		$tinkoffResp =  unserialize(\DB::table('dev_notify_post')->where('id', $transId)->get()->first()->request_body);
		$status = $tinkoffResp['Status'];

		switch ($tinkoffResp['Status']) {
		    // Зарезервирован
		    case $this::PAYMENT_AUTHORIZED :
		       // пока ничего не делаем 
		        
		    break;
		    // Подтвержден
		    case $this::PAYMENT_CONFIRMED : 
		        $order =  Order::find($tinkoffResp['OrderId']);
		        $payment = Payment::where('tinkoff_payment_id', $tinkoffResp['PaymentId'])->first();
		        $payment->payment_status = $this::PAYMENT_CONFIRMED;
		        // оплачено
		        $order->order_status_id = 3;
		        if( $payment->save_card == 1) {
		            // сохраним карту, если она еще не была сохранена
		            $args = [
		                'CustomerKey' => "userQual" . $payment->user_id,
		            ];

		            $this->getCardList($args, $payment->user_id);
		        }

		        $order->save();
		        $payment->save();

		    break;

		    default:
		        return '';
		    break;
		}
	}

}
