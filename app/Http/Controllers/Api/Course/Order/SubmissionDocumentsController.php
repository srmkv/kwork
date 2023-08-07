<?php
namespace App\Http\Controllers\Api\Course\Order;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Order;
use App\Models\Order\AdmissionDocument;
use App\Services\Order\OrderSubmissionDocumentsService;
use App\Http\Resources\Order\AdmissionDocumentResource;

class SubmissionDocumentsController extends Controller
{   
    public function __construct(OrderSubmissionDocumentsService $orderSubmission)
    {
        $this->orderSubmission = $orderSubmission;
    }

    public function getAdmissionDocuments(Request $request, $order_id)
    {   
        $user = User::find(Auth::id());
        $docs = \DB::table('admission_documents')->where('order_id', $order_id)->pluck('id');
        return AdmissionDocumentResource::collection(AdmissionDocument::find($docs));
    }

    // при редактироавнии добавилась механика клонирования документов для админа..
    // 
    public function editAdmissionDocuments(Request $request, $order_id)
    {
        $user = User::find(Auth::id());
        $admission_id = $request->document["id"];
        $admission = AdmissionDocument::find($admission_id);

        $admission->user_documents = $request->document["user_documents"];
        $admission->save();

        // после изменения адмишена также скорректируем его статус
        $this->orderSubmission->formationAdmissionStatus($admission);

        // клонируем..
        // $this->orderSubmission->documentsCloning($admission);

        return AdmissionDocumentResource::make($admission);
    }

    public function getAdmissionById(Request $request, $admission_id)
    {   
        $admission = AdmissionDocument::find($admission_id);
        // вернуть
        // return AdmissionDocumentResource::make($admission);
        // тесты ..
        return $this->orderSubmission->documentsCloning($admission);

    }


}

