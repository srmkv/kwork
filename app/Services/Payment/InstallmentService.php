<?php
namespace App\Services\Payment;
use App\Services\Payment\PaymentService;
use App\Models\Payment;
use App\Models\Order;
use App\Models\Installment;
use App\Models\Course\Course;
use App\Models\Payment\InstallmentProcess;
use App\Models\User;

use App\Http\Resources\Payment\InstallmentProcessResource;
use App\Services\Order\OrderCommonService;


class InstallmentService
{	
	// типы рассрочек
	const TYPE_INSTALLMENT_TINKOFF = "tinkoff";
	const TYPE_INSTALLMENT_QUALIFITERRA = "qualifiterra";
	// методы оплаты
	const CACHLESS_TINKOFF = "cashless_pay";
	const INSTALLMENT_QUALIFITERRA = "installment_2";
	const INSTALLMENT_TINKOFF = "installment_3";
	
	// статусы рассрочек(которые в процессе)
	// NEW - создана/попытка оплаты
	// PART_PAID - частично оплачена
	// CONFIRMED - полностью оплачена
	// PAID - полностью оплачена

	// СТАТУСЫ МЕСЯЦЕВ
	const MONTH_PAID = "paid";
	const MONTH_AWAIT = "awaiting";
	const MONTH_UNPAID = "unpaid";

	public function __construct(
	    PaymentService $payment
	)
	{
	    $this->payment = $payment;
	}

	public function getTinkoffInstallments($packet_id)
	{	
		return \DB::table('installments')
			->where('installment_type', $this::TYPE_INSTALLMENT_TINKOFF)
			->where('packet_id', $packet_id)->get();
	}

	public function getQualifiterraInstallments($packet_id)
	{
		return \DB::table('installments')
			->where('installment_type', $this::TYPE_INSTALLMENT_QUALIFITERRA)
			->where('packet_id', $packet_id)->get();
	}

	public function newPayInstallmentTinkoff($profile, $installment, $order, $ip)
	{
	    $course = Course::find($order->course_id);
	    $insallmentBody = [
	        'shopId' => config('tinkoff.shop_id'),
	        'showcaseId' => config('tinkoff.showcase_id'),
	        'sum' => $order->price,
	        'items' => [
	            [
	                'name' => $course->name,
	                'quantity' => 1,
	                'price' => $order->price,
	                'category' => "course_payments_personal",
	                'vendorCode' => $course->id
	            ],
	        ],
	        'orderNumber' =>"personal#" . $order->id,
	        // 'promocode' => $installment->installment_code,
	        // 'promocode' => "installment_0_0_6_6,5",
	        'promocode' => "installment_0_0_24_20,5",
	        // sms, appointment, reject, appointment-reject
	        // 'demoFlow' => "appointment", // тип подписания договора
	        'values' => [
	            "contact" => [
	                "fio" => [
	                    "lastName" =>  $profile->lastname,
	                    "firstName" => $profile->name,
	                    "middleName" => $profile->middle_name
	                ],
	                "mobilePhone" => $profile->phone,
	                "email" => $profile->email
	            ]
	        ]
	    ];

		$resp =  json_decode($this->payment->newInstallment(collect($insallmentBody)->toJson()), true);
		if(!empty($resp['errors'])) {

			return response()->json([
			    'message' => 'Что то пошло не так..',
			    'code' => 403,
			    'errors' => collect($resp['errors'])
			], 200);
		}

		$payment = new Payment;
		$payment->order_id = $order->id;
		$payment->user_id = $profile->user_id;
		$payment->amount = $order->price;
		$payment->description = "Оплата в рассрочку за курс: " . $course->name;
		$payment->user_phone = $profile->phone;
		$payment->user_email  = $profile->email;
		$payment->pay_method_id = $this->payment->getTypePayment($this::INSTALLMENT_TINKOFF);
		$payment->payment_url = $resp['link'];
		$payment->payer_ip = $ip;
		$payment->payment_status = PaymentService::NEW_INSTALLMENT_TINKOFF;
		$payment->tinkoff_payment_id = $resp['id'];
		$payment->save();
		return $payment;
	}

	// ОТ КВАЛИФТЕРРЫ

	public function newPayInstallmentQualifiterra($profile, $ip, $data)
	{	// **********
		// 0. проверим суммы месяцев которые отправляет фронт, и то что в бд - сходятся

		// **********
		$order = Order::find($data['order_id']);
		$course = Course::find($order->course_id);
		$installment = Installment::find($data['installment_id']);
		$user = User::find($profile->user_id);
		$paid_months = $data['paid_months'];
		$checkIssetInst = InstallmentProcess::where('user_id', $user->id)->where('status', "NEW")->where('order_id', $order->id)->count();

		$countMonth = $installment->count_month;
		$i = 1;
		$summPaid = 0; // сумма которую передаем в api
		$monthSumms = collect(); 
		$arrPaidMonths = collect(); // месяцы которые оплачиваем

		while ($countMonth > 0) {
			$summMonth = collect([
				'month' => $i,
				'summ' => $i == 1 ? $installment->price_first_month : $installment->price_month,
				'status' => "unpaid"
			]);
			$monthSumms->push($summMonth);
			$i++;
			$countMonth--;
		}

		foreach ($monthSumms as $indexMonth => $month) {
			if(in_array($month['month'], $paid_months)){
				// вроде не надо
				$arrPaidMonths->push($month);

				$month['status'] = "awaiting";
				$summPaid += $month['summ'];
			}
		}

		if($checkIssetInst > 0 ) {

			return response()->json([
				'message' => 'Вы не можете создать новую рассрочку для оплаты этого курса, так как у вас уже есть активная рассрочка..',
				'code' => 403
			], 403);

			exit();
		}

		// в таком варинат поменять выбор к-ва месяцев не получится , уточнить
		$installmentProcess = new InstallmentProcess;
		$installmentProcess->order_id = $order->id;
		$installmentProcess->user_id = $profile->user_id;
		$installmentProcess->body_installment = $monthSumms;
		$installmentProcess->save();

		// формируем оплату в ti
		$paymentBody = [
		    "Amount" => $summPaid * 100,
		    "OrderId" => "IQ-" . $order->id . \Str::random(5) ,
		    "IP" => $ip,
		    "Description" => "Частичная оплата курса: " . $course->name,
		    "DATA" => [
		            "Phone" => $profile->phone,
		            "Email" => $profile->email,
		        ],
		    "NotificationURL" => url('api/payment/notify-qualifiterra-installment'),
		    "SuccessURL" => "https://qualifiterra.ru/profile/orders/" . $order->id
		];

		if($data['save_card'] == 1) {
		    if($user->paymentCards->count() > 0 ) {
		        $paymentBody["DATA"]["DefaultCard"] = $data['card_id'];
		    } else {
		        $paymentBody["DATA"]["DefaultCard"] = null;
		    }
		    $paymentBody["CustomerKey"] = "userQual" . $user->id;
		}

		$paymentResp = json_decode($this->payment->buildQuery('Init', $paymentBody), true);
		if($paymentResp['Success'] == true) {
		    $payment = new Payment;
		    $payment->order_id = $order->id;
		    $payment->amount = $summPaid * 100;
		    $payment->description = "Частичная оплата курса: " . $course->name;
		    $payment->user_id = $user->id;
		    $payment->user_phone = $profile->phone;
		    $payment->user_email = $profile->email;
		    $payment->pay_method_id = 18;
		    $payment->payment_url = $paymentResp['PaymentURL'] ?? null;
		    $payment->payer_ip = $ip;
		    $payment->error_code = $paymentResp['ErrorCode'];
		    $payment->payment_status = $paymentResp['Status'];
		    $payment->tinkoff_payment_id = $paymentResp['PaymentId'];
		    $payment->save_card = $data['save_card'] == 1 ? 1 : 0;

		    // ид частичной оплаты
		    $payment->installment_process_id = $installmentProcess->id;
		    
		    $payment->save();
		    $order->payment_id = $payment->id;
		    $order->save();
		
		    return $payment;
		} else {

		    return $paymentResp;
		}
	}

	public function resendPayInstallmentQualifiterra($profile, $ip, $data)
	{	
		$letters = 'abcdefghijklmnopqrstuvwxyz';
		$random_string = str_shuffle($letters);
		$random_string = substr($random_string, 0, 7);

		$installmentProcess = InstallmentProcess::find($data['installment_process_id']);
		$order = Order::find($installmentProcess->order_id);
		$course = Course::find($order->course_id);
		$user = User::find($profile->user_id);
		$this->numberPaidMonths = $data['paid_months']; // ключи оплачиваемых месяцев

		$this->summPaid = 0;

		$paidMonths = $installmentProcess->append('body_installment')->body_installment;

		$paidMonths = collect($paidMonths)->map(function ($month) {
	    	if(in_array($month['month'], $this->numberPaidMonths)){
	    		// $month['status'] = "awaiting";
	    		$this->summPaid += $month['summ'];
	    	}
	    	
	    	if(in_array($month['month'], $this->numberPaidMonths)){
	    		$month['status'] = "awaiting";
	    	} 


		    return $month;
		});

		$installmentProcess->body_installment = $paidMonths;

		// ====== ВСЕ ЧТО НИЖЕ ВЫНЕСТИ В ОТДЕЛЬНЫЙ СЛОЙ И ОБЪЕДЕНИТЬ  С МЕТОДОМ newPayInstallmentQualifiterra ===========


		// формируем оплату в ti
		$paymentBody = [
		    "Amount" => $this->summPaid * 100,
		    "OrderId" => "IQ-" . $order->id . "-". $random_string,
		    "IP" => $ip,
		    "Description" => "Частичная оплата курса: " . $course->name,
		    "DATA" => [
		            "Phone" => $profile->phone,
		            "Email" => $profile->email,
		        ],
		    "NotificationURL" => url('api/payment/notify-qualifiterra-installment'),
		    "SuccessURL" => "https://qualifiterra.ru/profile/orders/" . $order->id
		];


		if($data['save_card'] == 1) {
		    if($user->paymentCards->count() > 0 ) {
		        $paymentBody["DATA"]["DefaultCard"] = $data['card_id'];
		    } else {
		        $paymentBody["DATA"]["DefaultCard"] = null;
		    }
		    $paymentBody["CustomerKey"] = "userQual" . $user->id;
		}

		$paymentResp = json_decode($this->payment->buildQuery('Init', $paymentBody), true);
		if($paymentResp['Success'] == true) {
		    $payment = new Payment;
		    $payment->order_id = $order->id;
		    $payment->amount = $this->summPaid * 100;
		    $payment->description = "Частичная оплата курса: " . $course->name;
		    $payment->user_id = $user->id;
		    $payment->user_phone = $profile->phone;
		    $payment->user_email = $profile->email;
		    $payment->pay_method_id = 18;
		    $payment->payment_url = $paymentResp['PaymentURL'] ?? null;
		    $payment->payer_ip = $ip;
		    $payment->error_code = $paymentResp['ErrorCode'];
		    $payment->payment_status = $paymentResp['Status'];
		    $payment->tinkoff_payment_id = $paymentResp['PaymentId'];
		    $payment->save_card = $data['save_card'] == 1 ? 1 : 0;

		    // ид частичной оплаты
		    $payment->installment_process_id = $installmentProcess->id;
		    
		    $payment->save();
		    $order->payment_id = $payment->id;
		    $order->save();
			
			$installmentProcess->save();
		    return $payment;
		} else {

		    return $paymentResp;
		}
	}

	// уведомления для успешных частичных оплат
	public function acceptNotify($data)
	{
		$transId = \DB::table('dev_notify_post')->insertGetId([
		    'request_body' => serialize($data),
		    'created_at' => now(),
		    'desc' => PaymentService::TINKOFF_WEBHOOK_INSTALLMENT
		]);


		$tinkoffResp =  unserialize(\DB::table('dev_notify_post')->where('id', $transId)->get()->first()->request_body);
		$status = $tinkoffResp['Status'];

		switch ($tinkoffResp['Status']) {
		    // Зарезервирован
		    case PaymentService::PAYMENT_AUTHORIZED :
		       // пока ничего не делаем 
		        
		    break;
		    // Подтвержден
		    case PaymentService::PAYMENT_CONFIRMED : 
		    	$order_id = preg_replace("/[^,.0-9]/", '', $tinkoffResp['OrderId']);
		        $order =  Order::find($order_id);
		        $payment = Payment::where('tinkoff_payment_id', $tinkoffResp['PaymentId'])->first();

		        // проставим оплаченные месяцы
		        $installmentProcess = InstallmentProcess::find($payment->installment_process_id);
		        $paidMonths = $installmentProcess->append('body_installment')->body_installment;

		        $paidMonths = collect($paidMonths)->map(function ($month) {
		            if($month['status'] == "awaiting"  ) {
		            	$month['status'] = "paid";
		            }
		            return $month;
		        });

		        $installmentProcess->body_installment = $paidMonths;
		        $installmentProcess->status = "PART_PAID";
		        $installmentProcess->save();

		        $payment->payment_status = PaymentService::PAYMENT_CONFIRMED;


		        if(!$this->checkIsPaidInstallment($installmentProcess)) {
		        	// оплачена частично
		        	$order->order_status_id = OrderCommonService::STATUS_PART_PAID;
		        } else {
		        	// или целиком
		        	$order->order_status_id = OrderCommonService::STATUS_PAID;
		        }

		        if( $payment->save_card == 1) {
		            // сохраним карту, если она еще не была сохранена
		            $args = [
		                'CustomerKey' => "userQual" . $payment->user_id,
		            ];

		            $this->payment->getCardList($args, $payment->user_id);
		        }

		        $order->save();
		        $payment->save();


		    break;

		    default:
		        return '';
		    break;
		}
	}


	public function currentQualifiterraInstallment($order, $user)
	{
		$installmentProcess = InstallmentProcess::where('order_id', $order->id)
								->where('user_id', $user->id)
								->get()->last();
		if(is_null($installmentProcess)) {
			return response()->json([
				'message' => 'Нет активной рассрочки..',
				'code' => 404
			], 200);
		} else {
			return InstallmentProcessResource::make($installmentProcess);
		}
	}

	public function checkIsPaidInstallment($installmentProcess)
	{
		$paidMonths = $installmentProcess->append('body_installment')->body_installment;
		foreach ($paidMonths as $month) {
			if($month['status'] == $this::MONTH_PAID) {
				continue;
			} elseif($month['status'] == $this::MONTH_AWAIT || $month['status'] == $this::MONTH_UNPAID) {

				return false;
			}
		} 
		return true;
	}
}






