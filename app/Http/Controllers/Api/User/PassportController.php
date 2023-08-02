<?php
namespace App\Http\Controllers\Api\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PassportRequest;
use App\Models\Profiles\ProfileIndividual;
use App\Models\User;
use App\Models\Passport;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Buglinjo\LaravelWebp\Facades\Webp;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\Document\PassportResource;
use App\Http\Resources\General\MediaItemRecource;
use App\Http\Resources\General\MediaRecource;
use App\Services\User\BusinessService;
use App\Traits\Company;
use App\Traits\Profile;
use Carbon\Carbon;


use App\Services\UserDocuments\PassportService;
use App\Services\UserDocuments\StatusDocService;

class PassportController extends Controller
{
    public function __construct(Passport $passport, BusinessService $business, PassportService $passportAction)
    {   
        $this->newPassport = new Passport;
        $this->business = $business;
        $this->passportAction = $passportAction;
    }

    public function editPasport(PassportRequest $request)
    {   
        // логика если редактируем паспорт под компанией своему сотруднику
        if(isset($request->type_company) && isset($request->company_id)) 
        {
            if($this->business->canCompanyChangeDocument(
                    $request->type_company,
                    $request->company_id,
                    BusinessService::TYPE_PASSPORT,
                    $request->passport_id,
                    $request->profile_id,
            )){
                return $this->business->editPassportByCompany($request);
            } else {
                return response()->json([
                    "message" => "Вы не можете редактировать этот документ..",
                ], 201);
            }
        }

        $user_id = Auth::id();
        $this->user = User::with('passports')->find($user_id);
        $user_passports = $this->user->passports()->get();
        $form_passport = $request->all();
        //разложим инфу
        foreach ($form_passport as $input_index => $input) {
            $form_info[$input_index] =  $input;
        }

        // нет паспортов
        if(count($user_passports) == 0 ) {
            $this->newPassport->country_id = 1;
            $this->newPassport->user_id = $this->user->id;
            $this->newPassport->save();
            $this->user->passports()->attach($this->newPassport);
        } 
        if($request->passport_id != null) {
            $passport_id = $request->passport_id;
            $passports = Passport::where('user_id', $this->user->id)->get();
            //если паспорт есть в базе апдейтим и сохраняем
            if($passports->contains($passport_id)) {
                $currentPassport = Passport::find($passport_id);
                if(!$currentPassport){
                    return response()->json([
                        "message" => "Паспорт не обнаружен..",
                        "code" => 404,
                    ],404);
                }
                $currentPassport->update([
                    'country_id' => $form_info['country_id'] ?? null,
                    'last_name' => $form_info['last_name'] ?? null,
                    'first_name' => $form_info['first_name'] ?? null,
                    'middle_name' => $form_info['middle_name'] ?? null,
                    'date_of_birth' => Carbon::parse($form_info['date_of_birth']) ?? null,
                    'serial_number' => $form_info['serial_number'] ?? null,
                    'issued_by_whom' => $form_info['issued_by_whom'] ?? null,
                    'date_issue' =>  Carbon::parse($form_info['date_issue']) ?? null,
                    'subdivision_code' => $form_info['subdivision_code'] ?? null,
                    'citizenship' => $form_info['citizenship'] ?? null,
                ]);
                $currentPassport->save();
                return response()->json([
                    "message" => "Зафкиксировано..",
                    "code" => 201,
                    "current_passport_edit" => $currentPassport
                ],201);
            } else {
                return response()->json([
                    "message" => "Паспорта не обнаружены..",
                    "code" => 404,
                ],404);
            }
        }
    }

    public function addPassport(Request $request)
    {   
        // доп. логика для компаний
        if(isset($request->type_company) && isset($request->company_id)) 
        {
            if($this->business->allEmployeesIntoCompany($request->type_company, $request->company_id)->contains('id', $request->profile_id)) {
               return $this->business->addPassportByCompany($request->profile_id);
            } else {
                return response()->json([
                    "message" => "Вы не можете создать документ для этого сотрудника..",
                ], 201);
            }
        }

        $user = User::find(Auth::id());
        $this->newPassport->user_id = $user->id;
        $this->newPassport->country_id = 1;
        // $this->newPassport->status_doc = collect([
        //     StatusDocService::DOC_STATUS_DEFAULT
        // ]);

        $this->newPassport->save();
        $user->passports()->attach($this->newPassport);
        return response()->json([
            "message" => "Добавлен пустой паспорт..",
            "code" => 201,
            "passport" => $this->newPassport
        ],201);
    }

    public function showPassport(Request $request) 
    {   
        if(isset($request->type_company) && isset($request->company_id)){
            if($this->business->isAnEmployee($request->type_company, $request->company_id, $request->profile_id)) {
                $user = User::with('passports')->find(Profile::getUserId($request->profile_id));
            } else {
                return response()->json([
                    "message" => "Вы не можете этого сделать..",
                ], 201);
            }
        } else{
            $user = User::with('passports')->find(Auth::id());
        } 

        $media_id = $request->media_id;
        $media = Media::find($media_id);
        $passport_mime = file_get_contents($media->getPath());
        if($media->getPath()) {
             // PIC
             return response($passport_mime)->withHeaders([
                'Content-Type' => mime_content_type($media->getPath())
            ]);
        } else {
            return response()->json([
                "message" => "Файл не найден на сервере..",
                "code" => 186
            ],404);
        }
    }

    public function LoadImg(Request $request)
    {    
        $user = Company::currentUserForAction($request);
        $passport_id = intval($request->passport_id);
        $passport = Passport::find($passport_id);
        $passport__1 = $passport->getMedia('user_passports', ['passport_page' => 1, 'passport_id' => $passport_id  ]);
        $passport__2 = $passport->getMedia('user_passports', ['passport_page' => 2, 'passport_id' => $passport_id ]);

        if($request->passport__1 != null) {
            if( $passport__1->count() == 0) {
                $media = $passport->addMediaFromRequest('passport__1')->withCustomProperties([
                    'passport_id' => $passport_id,
                    'passport_page' => 1,
                    'user_id' => $user->id
                ])->toMediaCollection('user_passports');
            }
            if( $passport__1->count() > 0) {
                $passport__1->first()->delete();
                $media = $passport->addMediaFromRequest('passport__1')->withCustomProperties([
                    'passport_id' => $passport_id,
                    'passport_page' => 1,
                    'user_id' => $user->id
                ])->toMediaCollection('user_passports');
            }
        }

        if($request->passport__2 != null) {
            if( $passport__2->count() == 0) {
                $media = $passport->addMediaFromRequest('passport__2')->withCustomProperties([
                    'passport_id' => $passport_id,
                    'passport_page' => 2,
                    'user_id' => $user->id
                ])->toMediaCollection('user_passports');
            }
            if( $passport__2->count() > 0) {
                $passport__2->first()->delete();
                
                $media = $passport->addMediaFromRequest('passport__2')->withCustomProperties([
                    'passport_id' => $passport_id,
                    'passport_page' => 2,
                    'user_id' => $user->id
                ])->toMediaCollection('user_passports');
            }
        }

        return response()->json([
            "message" => "fixed..",
            "code" => 201,
            "media_id" => $media['id']
        ],202);
    }


    // media id
    public function MediaInfoPassports(Request $request)
    {   

        if(isset($request->type_company) && isset($request->company_id)){

            // ТАКАЯ ПРОВЕРКА ВЫЗЫВАЕТ БАГ ЛАРАВЕЛ ОТПРАВИТЬ ОТЧЕТ
            // $this->business->allEmployeesIntoCompany($request->type_company, $request->company_id)->contains('id', $request->profile_id)

            if($this->business->isAnEmployee($request->type_company, $request->company_id, $request->profile_id)) {
                $user = User::with('passports')->find(Profile::getUserId($request->profile_id));
            } else {
                return response()->json([
                    "message" => "Вы не можете этого сделать..",
                ], 201);
            }
        } else{
            $user = User::with('passports')->find(Auth::id());
        } 

        $passports = $user->passports;
        foreach ($passports as $index => $passport) {
            if($passport->getMedia('user_passports') != null ) {
                $arrPassport[$index] = $passport->getMedia('user_passports')->toArray();  
            } else {
                $arrPassport[$index] = [];
            }   
        }
        return isset($arrPassport) ? MediaRecource::collection($arrPassport) : [];
    }

    public function deletePasport(Request $request)
    {   
        if(isset($request->type_company) && isset($request->company_id)){
            if($this->business->isAnEmployee($request->type_company, $request->company_id, $request->profile_id)) {
                $user = User::with('passports')->find(Profile::getUserId($request->profile_id));
            } else {
                return response()->json([
                    "message" => "Вы не можете этого сделать..",
                ], 201);
            }
        } else{
            $user = User::with('passports')->find(Auth::id());
        } 
        
        if(Passport::find($request->passport_id) != null){

            $passport = Passport::find($request->passport_id);

            $passports = \DB::table('passports')->where('user_id', $user->id)
                // ->whereJsonContains('status_doc', StatusDocService::DOC_STATUS_DEFAULT )
                ->get();

            // Провери что паспорт пренадлежит клиенту и не участвует в заявках 
            if($passports->where('id', $passport->id)->count() > 0 ) {
                $user->passports()->detach($passport->id);
                $this->passportAction->delete($passport);
                return response()->json([
                    "message" => "Паспорт успешно удален..",
                    "code" => 301,
                ],301);
            } elseif($passport->status_doc == StatusDocService::DOC_STATUS_RESERVE) {
                $user->passports()->detach($passport->id);
            } else {
                return response()->json([
                    "message" => "Вы не можете удалить чужой документ",
                    "code" => 403,
                ],403);
            }
        } else {

            return response()->json([
                "message" => "Документа не существует, либо он был удален ранее..",
                "code" => 404,
            ],404);

        }

    }

    public function mainInfoPassports(Request $request)
    {   
        if(isset($request->type_company) && isset($request->company_id)){
            if($this->business->isAnEmployee($request->type_company, $request->company_id, $request->profile_id)) {
                $user = User::with('passports')->find(Profile::getUserId($request->profile_id));
            } else {
                return response()->json([
                    "message" => "Вы не можете этого сделать..",
                ], 201);
            }
        } else{
            $user = User::with('passports')->find(Auth::id());
        }

        $passports = \DB::table('passports')->where('user_id', $user->id)
            // ->whereJsonContains('status_doc', StatusDocService::DOC_STATUS_DEFAULT )
            ->get();
        return PassportResource::collection($passports);
    }

    public function passportById(Request $request, $passport_id)
    {
        $passport = Passport::find($request->passport_id);
        return PassportResource::make($passport);
    }
}

