<?php
namespace App\Http\Controllers\Api\Payment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Course\Course;
use App\Models\Order;
use App\Models\Order\BusinessOrder;
use App\Models\Payment;
use App\Models\User;
use App\Services\Payment\PaymentService;
use App\Services\Payment\InstallmentService;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{   

    public function __construct(
        PaymentService $payment, 
        InstallmentService $installmentAction
    )
    {
        $this->payment = $payment;
        $this->installmentAction = $installmentAction;
    }

    public function newCustomerMerchant(Request $request)
    {   
        $user = User::find(Auth::id());
        $args = [
            'CustomerKey' => 'VoinCustomer',
            'Email'       => $user->individualProfile->email,
            'Phone'       => $user->individualProfile->phone,
        ];

        return $this->payment->addCustomer($args);
    }

    public function getUserCards(Request $request)
    {   
        $user = User::find(Auth::id());
        return $user->paymentCards;

    }

    public function removePaymentCard(Request $request)
    {
        return $this->payment->removeCard($request->card_id);
    }

    public function getOrder(Request $request)
    {
        return $this->payment->getOrder($request->order_id);
    }

    public function notifyHook(Request $request)
    {
        $this->payment->acceptNotify($request->all());
        echo ('OK');
    }

    public function removeCustomerMerchant(Request $request, $customerKey)
    {  
        $args = [
            'CustomerKey' => $customerKey
        ];

        return $this->payment->removeCustomer($args);
    }

    public function resendHook(Request $request)
    {
        $paymentResp = json_decode($this->payment->buildQuery('Resend', []), true);
        return $paymentResp;
    }

    // прибраться todo #404
    public function createOrder(Request $request)
    {
        $pay_method_id = $request->pay_method_id;
        $type_order = $request->type_order;
        $user = User::find(Auth::id());
        switch ($type_order) {
            case 'personal':
                $order = Order::find($request->order_id);
                $course = Course::find($order->course_id);
                $paymentBody = [
                    "Amount" => $order->price * 100,
                    "OrderId" => $order->id,
                    "IP" =>  $request->getClientIp() ?? "1.1.1.1",
                    "Description" => $course->name,
                    "DATA" => [
                            "Phone" => $user->phone,
                            "Email" => $user->email,
                        ],
                    // "NotificationURL" => "https://back.qualifiterra.ru/api/payment/notify",
                    "NotificationURL" => url("/api/payment/notify"),

                    "SuccessURL" => "https://qualifiterra.ru/profile/orders/" . $order->id
                ];
                if($request->save_card == 1) {
                    if($user->paymentCards->count() > 0 ) {
                        $paymentBody["DATA"]["DefaultCard"] = $request->card_id;
                    } else {
                        $paymentBody["DATA"]["DefaultCard"] = null;
                    }
                    $paymentBody["CustomerKey"] = "userQual" . $user->id;
                }

                $paymentResp = json_decode($this->payment->buildQuery('Init', $paymentBody), true);
                if($paymentResp['Success'] == true) {
                    $payment = new Payment;
                    $payment->order_id = $order->id;
                    $payment->amount = $order->price * 100;
                    $payment->description = "Оплата курса: " . $course->name;
                    $payment->user_id = $user->id;
                    $payment->user_phone = isset($user->individualProfile->phone) ? $user->individualProfile->phone : null ;
                    $payment->user_email = isset($user->individualProfile->email) ? $user->individualProfile->email : null ;
                    $payment->pay_method_id = $pay_method_id ?? 1;
                    $payment->payment_url = $paymentResp['PaymentURL'] ?? null;
                    $payment->payer_ip = $request->getClientIp() ?? "1.1.1.1";
                    $payment->error_code = $paymentResp['ErrorCode'];
                    $payment->payment_status = $paymentResp['Status'];
                    $payment->tinkoff_payment_id = $paymentResp['PaymentId'];

                    $payment->save_card = $request->save_card == 1 ? 1 : 0;


                    $payment->save();
                    $order->payment_id = $payment->id;

                    $order->save();
   
                    return $payment;
                } else {

                    return $paymentResp;
                }
                break;
            case 'business' :
                $order = BusinessOrder::find($request->order_id);
                $paymentBody = [
                    "Amount" => $order->price * 100,
                    "OrderId" => $order->id,
                    "IP" =>  $request->getClientIp() ?? "1.1.1.1",
                    "Description" =>"оплата курсов в соответствии с заявкой #" . $order->id,
                    "DATA" => [
                            "Phone" => $user->phone,
                            "Email" => $user->email
                        ],
                    // "NotificationURL" => "https://back.qualifiterra.ru/api/payment/notify"
                ];
                $paymentResp = json_decode($this->payment->buildQuery('Init', $paymentBody), true);
                if($paymentResp['Success'] == true) {
                    $payment = new Payment;
                    $payment->business_order_id = $order->id;
                    $payment->amount = $order->price * 100;
                    $payment->description = "оплата курсов в соответствии с заявкой #" . $order->id;
                    $payment->user_id = $user->id;
                    $payment->user_phone = $user->phone;
                    $payment->user_email = $user->email;
                    $payment->pay_method_id = $pay_method_id ?? 1;
                    $payment->payment_url = $paymentResp['PaymentURL'] ?? null;
                    $payment->payer_ip = $request->getClientIp() ?? "1.1.1.1";
                    $payment->error_code = $paymentResp['ErrorCode'];
                    $payment->payment_status = $paymentResp['Status'];
                    $payment->tinkoff_payment_id = $paymentResp['PaymentId'];
                    $payment->save();
                    return $payment;
                } else {
                    return $paymentResp['ErrorCode'];
                }
            break;
            default:
                return 'Не правильный тип заявки..';
            break;
        }
    }

    public function typePaymentsAll(Request $request)
    {
        return \DB::table('pay_methods')->get();
    }

    public function getPaymentById(Request $request, $payment_id)
    {
        return Payment::find($payment_id);
    }

    // dev
    public function checkNotify(Request $request, $id)
    {   
        $tinkoffResp =  unserialize(\DB::table('dev_notify_post')->where('id', $id)->get()->first()->request_body);
        $status = $tinkoffResp['Status'];
        return $tinkoffResp;
    }


    // dev
    public function getCheckOrder(Request $request)
    {
        return $this->payment->getDevOrder($request->payment_id);
    }

    // dev
    public function devUserCards(Request $request, $customerKey)
    {   
        $args = [
            'CustomerKey' => $customerKey,
        ];
        return $this->payment->buildQuery('GetCardList', $args);
    }

    //dev
    public function getCustomerMerchant(Request $request, $customerKey)
    {   
        $args = [
            'CustomerKey' => $customerKey,
        ];
        
        return $this->payment->getCustomer($args);
    }

}



 