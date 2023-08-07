<?php
namespace App\Http\Controllers\Api\Payment;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Payment\InstallmentService;
use App\Services\Payment\PaymentService;
use App\Models\Payment;
use App\Models\Payment\InstallmentProcess;
use App\Models\Installment;
use App\Models\User;
use App\Models\Order;

class PayInstallmentController extends Controller
{
    public function __construct(
        InstallmentService $installmentAction
    )
    {
        $this->installmentAction = $installmentAction;
    }

    public function getInstallmentsByType(Request $request)
    {
        switch ($request->type) {
            case $this->installmentAction::TYPE_INSTALLMENT_TINKOFF :
                return $this->installmentAction->getTinkoffInstallments($request->packet_id);
                break;
            
            case $this->installmentAction::TYPE_INSTALLMENT_QUALIFITERRA :
                return $this->installmentAction->getQualifiterraInstallments($request->packet_id);
                break;
            default:
                return 'Укажите верный тип рассрочки';
            break;
        }
    }

    public function firstPayInstallment(Request $request)
    {   
        $user = User::find(Auth::id());
        $profile = $user->individualProfile;
        $installment = Installment::find($request->installment_id);
        $order = Order::find($request->order_id);
        $ip = $request->getClientIp() ?? "1.1.1.1"; // вынести в статик, тут временно
        return $this->installmentAction->newPayInstallmentTinkoff($profile, $installment, $order, $ip);
    }



    // НИЖЕ МЕТОДЫ ДЛЯ РАССРОЧКИ ОТ КВАЛИФИТЕРЫ

    public function firstPayInstallmentQualifiterra(Request $request)
    {   
        $user = User::find(Auth::id());
        $profile = $user->individualProfile;
        $data = $request->all();
        $ip = $request->getClientIp() ?? "1.1.1.1"; // вынести в статик, тут временно
        return $this->installmentAction->newPayInstallmentQualifiterra(
                                        $profile,
                                        $ip,
                                        $data
                                    );

    }

    // ?? 
    public function resendPayInstallmentQualifiterra(Request $request)
    {
        $user = User::find(Auth::id());
        $profile = $user->individualProfile;
        $data = $request->all();
        $ip = $request->getClientIp() ?? "1.1.1.1"; // вынести в статик, тут временно
        return $this->installmentAction->resendPayInstallmentQualifiterra(
                                        $profile,
                                        $ip,
                                        $data
                                    );
    }

    public function notifyHook(Request $request)
    {
        $this->installmentAction->acceptNotify($request->all());
        echo ('OK');
    }


    public function getInstallmentProcessForOrder(Request $request, $order_id)
    {
        $user = User::find(Auth::id());
        $order = Order::find($order_id);
        return $this->installmentAction->currentQualifiterraInstallment($order, $user);
    }

    public function createInstallmentTinkoff(Request $request)
    {
        return $this->installmentAction->newPayInstallmentTinkoff($profile, $installment, $order, $ip);
    }

}
