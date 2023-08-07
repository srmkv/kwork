<?php
namespace App\Http\Controllers\Api\Course;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Installment;
use App\Models\Course\Packet;

class PacketInstallmentController extends Controller
{
    public function createInstallment(Request $request)
    {
        $user = $user = User::find(Auth::id());
        $installment = new Installment;
        switch ($request->installment_type) {
            case 'tinkoff':
                // code...
                // return dd(11);

                $installment->installment_type = $request->installment_type;
                $installment->packet_id = $request->packet_id;
                $installment->save();
                return $installment;


                break;
            case 'qualifiterra':
                
                $installment->installment_type = $request->installment_type;
                $installment->packet_id = $request->packet_id;
                $installment->save();
                return $installment;
                
                break;
            default:
                return response()->json([
                    'message' => 'Не верный тип рассрочки',
                    'code' => 403
                ],403);
                break;
        }
    }

    // public function createInstallTinkoff(Request)


    public function editInstallment(Request $request)
    {
        $user = $user = User::find(Auth::id());
        $installment = Installment::find($request->installment_id);
        switch ($installment->installment_type ?? $request->installment_type) {
            case 'tinkoff':
                $installment->total_price = $request->total_price ?? $installment->total_price;
                $installment->installment_code = $request->installment_code ?? $installment->installment_code;
                $countMonth = \DB::table('tinkoff_type_installments')
                    ->where('installment_code', $installment->installment_code)
                    ->first()->count_month;
                // $priceMonth = $installment->total_price /  $countMonth;
                
                // $installment->price_month = round($priceMonth);
                // $installment->price_first_month = 0;
                $installment->count_month = $countMonth;
                $installment->save();
                return $installment;
            break;
            
            case 'qualifiterra' :

                $installment->total_price = $request->total_price ?? $installment->total_price;
                $installment->count_month = $request->count_month ?? $installment->count_month;
                $installment->price_first_month = $request->price_first_month ?? $installment->price_first_month;
                if($installment->price_first_month > 0)
                {
                    $priceMonth =  ($installment->total_price - $installment->price_first_month) /  ( $installment->count_month - 1 );
                } else {
                    $priceMonth =   ($installment->total_price /  $installment->count_month);
                }
                $installment->price_month = round($priceMonth);
                $installment->save();
                return $installment;
            break;

            default:
                return 'Такого типа рассрочки не существует..';    
            break;
        }
    }


    public function getInstallments(Request $request, $packet_id)
    {
        return Installment::where('packet_id', $packet_id)->get();
    }



    public function getTinkoffInstallments(Request $request)
    {
        return \DB::table('tinkoff_type_installments')->get();
    }


    public function deleteInstallment(Request $request, $installment_id)
    {
        $user = $user = User::find(Auth::id());
        $installment = Installment::find($installment_id);
        if($installment != null ) {
            $installment->delete();
            return response()->json([
                "message" => "Рассрочка удалена..",
                "code" => 403,
            ],201);
        } else {
            return response()->json([
                "message" => "Такой рассрочки не существует.. или она удалена ранее..",
                "code" => 403,
            ],201);
        }
    }

    public function editPacketSplitForBusiness(Request $request, $packet_id)
    {
        $user = User::find(Auth::id());
        $packet = Packet::find($packet_id);

        $packet->split_months = $request->counts_months;
        $packet->save();

        return collect($packet->append('split_months')->split_months);

    }

    public function  getPacketSplitForBusiness(Request $request, $packet_id)
    {
        $user = User::find(Auth::id());
        $packet = Packet::find($packet_id);

        return collect($packet->append('split_months')->split_months);
    }



    public function editStateTinkoffInstallment(Request $request, $packet_id, $state)
    {    
        $packet = Packet::find($packet_id);
        $packet->tinkoff_installment = $state;
        $packet->save();
        return $packet;
    }  
}
