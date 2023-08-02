<?php
namespace App\Http\Controllers\Api\Media;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
//auth
use Illuminate\Support\Facades\Auth;
use App\Traits\Company;
use App\Models\User;
use App\Models\SecondaryEdu;
use App\Models\HigherEdu;
use App\Models\AdditionalEdu;
use App\Models\SpecializedSecondaryEdu;
use App\Models\Admin\SpecialSection\FormOrgUnits;
use App\Models\Admin\SpecialSection\OrgUnitDoc;
use App\Models\Admin\SpecialSection\FormEduProgram;
use App\Models\Admin\SpecialSection\EduProgramDoc;
use App\Models\Admin\SpecialSection\FormDataDirectorEdu;
use App\Models\Admin\SpecialSection\FormDocument; // форма с документами
use App\Models\Admin\SpecialSection\FormDocumentDoc; // сам документ
use App\Models\Admin\SpecialSection\FormAccesibleEnv;
use App\Models\Admin\SpecialSection\AccessibleEnvDoc; // это картинки (уу)
use App\Models\Admin\SpecialSection\FormInternationalCooperation;
use App\Models\Admin\SpecialSection\InternationalCooperationImage;
use App\Models\Admin\SpecialSection\PlanYearDocument;
use App\Models\Admin\SpecialSection\SectionYearDocument; //секция
use App\Models\Admin\SpecialSection\SectionYearDoc; // файл в секции
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Conversions\Conversion;
use Spatie\Image\Manipulations;
//services
use App\Services\MainService;
use App\Services\Media\MediaItemService;
//resource
use App\Http\Resources\Media\ListMediaResource;
use Imagick;

class MediaItemController extends Controller
{
    
    public $adminRoles = [
        'superadmin',
        'owner'
    ];


    public function __construct(MediaItemService $mediaitem)
    {
        $this->mediaItem = $mediaitem;
    }

    public function getCollectionMedia(Request $request)
    {
        $collection = $request->collection;
        $model_id = $request->model_id;
        switch ($collection) {
            case 'form_accesible_env_images':
                if(isset($model_id)) {
                    $coops = \DB::table('media')
                        ->where('model_type', 'App\Models\Admin\SpecialSection\FormInternationalCooperation')
                        ->where('model_id', $model_id)
                        ->get(['id', 'model_id']);
                } else {

                    $coops = \DB::table('media')->where('model_type', 'App\Models\Admin\SpecialSection\FormInternationalCooperation')->get(['id', 'model_id']);
                }

                return $coops;
                break;            
            default:
                return response()->json([
                    "message" => "Попросите добавить эту коллекцию в список, чтобы работать с ней..",
                    "code" => 404,                
                ],404);
                break;
        }
    }

    public function deleteMedia(Request $request)
    {  
        $user = Company::currentUserForAction($request);
        return $this->mediaItem->delete($request->media_id, $user);
    }

    public function MediaDeleteForAdmin(Request $request, $media_id)
    {   
        $user = User::find(Auth::id());
        if($user->hasRoleAdmin($this->adminRoles) == 1){
            $media_model = Media::find($media_id);
            if(!isset($media_model)){
                return response()->json([
                    "message" => "Не найден объект с таким id..",
                    "code" => 404,
                ],404);
            }
            $media_model->delete();
            return response()->json([
                "message" => "Медиа файл удален..",
                "code" => 202,
            ],202);
        } 
        else{
            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }
    }

    public function getNameCollectionsMedia(Request $request)
    {

        $TypecollectionMedia  = collect([
            //my documents
            'user_passports' => 'Фотографии паспортов пользователй',
            'user_other_documents' => 'Доп. документы пользователей',
            'user_name_replacement' => 'Смена ФИО (доки)',
            'user_snils' => 'СНИЛС',
            'user_workbooks' => 'Трудовые книжки',
            'secondary_school' => 'Среднее образование пользователя ( фотки)',
            'specialized_secondary_school' => 'Среднеспециальное образование пользователей',
            'higher_diplom' => 'Изображения дипломов( высшее образование)',
            'additional_diplom' => 'Доп. образование ( фотки)',
            // admin special section
            'form_org_unit_images' => 'Обложка структурного подразделения ( спец. раздел)', 
            'form_org_unit_docs' => 'pdf файлы в структурном подразделении',
            'form_edu_programm_images' => 'не актуально..',
            'form_edu_programm_pdf' => 'Документ pdf в форме образовательные стандарты',
            'form_edu_director_images' => 'Фото директора( Руководство 2)',
            'form_documents' => 'документы как картинка/ссылка/ПРОСТО ДОКУМЕНТЫ ',
            'form_accesible_env_images'=> 'Обложка(и) для формы доступной среды', //
            'form_accesible_env_pdf'=> 'блять тут только картинки чтоли ( неакутально )', //
            'form_international_cooper_images' => 'Картинки формы международного сотрудничества (УЖЕ НЕ АКТУАЛЬНО)', 
            'form_international_cooper_docs' => 'ДОКУМЕНТЫ формы международного сотрудничества', 
            'form_financial_plan_images' => 'изображение документа в форме с годом (план финансово хоз. деятельности)', 
            'section_year_docs' => 'pdf файлы внутри секции <- год  <- план финансово хоз. деятельности  ', 
        ]);
        return $TypecollectionMedia;
    }



    //получить файл конкретно по media_id, доступно если есть роль админа
    public function imageShow(Request $request)
    {   
        $user = User::find(Auth::id());
        if($user->hasRoleAdmin($this->adminRoles) == 1) {
            if(isset($request->media_id)){
                $media = Media::find($request->media_id);
                if(!isset($media)) {
                    return response()->json([
                        "message" => "Не найден объект с таким id.. или удалён ранее..",
                        "code" => 203,
                    ],203);
                }
                $mime = file_get_contents($media->getPath());
                return response($mime)->withHeaders([
                    'Content-Type' => mime_content_type($media->getPath())
                ]);
            }
        } else {
            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }
    }

    //тест
    public function showUrlMedia(Request $request)
    {   
        $doc_image = PlanYearDocument::find(5);
        $media = $doc_image->addMediaFromRequest('file')->toMediaCollection('form_financial_plan_images', 'public');
        return $media->getUrl();
    }

    public function imageShowForUser(Request $request)
    {   
        $user = Company::currentUserForAction($request);
        if(!isset($request->media_id) || !isset($request->collection_name) ){
            return response()->json([
                'message' => 'Проверьте параметры..'
            ], 403);
        }

        $collection_name = $request->collection_name;
        $document_id = $request->document_id;
        $media_id = $request->media_id;
        $media = Media::find($media_id);
        switch ($collection_name) {
            case 'specialized_secondary_school':
                $spec_edu =  SpecializedSecondaryEdu::find($document_id);
                if(collect($spec_edu->getMedia($collection_name, ['user_id' => $user->id]  ))->contains('id', $media->id ) ) {
                    $mime = file_get_contents($media->getPath());
                    return response($mime)->withHeaders([
                        'Content-Type' => mime_content_type($media->getPath())
                    ]);
                } else {
                    return response()->json([
                        'message' => 'Изображений не найдено..'
                    ], 201);
                }
            case 'secondary_school' :
                $spec_edu =  SecondaryEdu::find($document_id);
                if(collect($spec_edu->getMedia($collection_name, ['user_id' => $user->id]  ))->contains('id', $media->id ) ) {
                    $mime = file_get_contents($media->getPath());
                    return response($mime)->withHeaders([
                        'Content-Type' => mime_content_type($media->getPath())
                    ]);
                }
            case 'higher_diplom' :
                $diplom =  HigherEdu::find($document_id);
                if(collect($diplom->getMedia($collection_name, ['user_id' => $user->id]  ))->contains('id', $media->id ) ) {
                    $mime = file_get_contents($media->getPath());
                    return response($mime)->withHeaders([
                        'Content-Type' => mime_content_type($media->getPath())
                    ]);
                }

            case 'additional_diplom':
                $diplom =  AdditionalEdu::find($document_id);
                if(collect($diplom->getMedia($collection_name, ['user_id' => $user->id]  ))->contains('id', $media->id ) ) {

                    $mime = file_get_contents($media->getPath());
                    return response($mime)->withHeaders([
                        'Content-Type' => mime_content_type($media->getPath())
                    ]);
                }
            break;

            default:
                return response()->json([
                    "message" => "Проверьте имя коллекции..",
                    "code" => 403,
                ],403);
                break;
        }

    }

    //получить файл из однородной коллекции, с указанием номера page 
    public function findMediaInCollection(Request $request)
    {   
        // $user = User::find(Auth::id());
        $user = Company::currentUserForAction($request);
        $collection_name = $request->collection_name;
        $document_id = $request->document_id;

        if($collection_name) {

            switch ($collection_name) {
                case 'secondary_school':
                    $secondarySchool = SecondaryEdu::find($document_id);
                    $media_files = $secondarySchool->getMedia('secondary_school', ['user_id' => $user->id]);
                    return ListMediaResource::collection($media_files);
                case 'specialized_secondary_school':
                    $specializedSecondarySchool = SpecializedSecondaryEdu::find($document_id);
                    $media_files = $specializedSecondarySchool->getMedia('specialized_secondary_school', ['user_id' => $user->id]);

                    if ($media_files->isEmpty()) {

                        return response()->json([
                            "message" => "Изображений еще нет..",
                            "code" => 201,
                        ],201);

                    }
                    return ListMediaResource::collection($media_files);
                case 'higher_diplom':
                    $diplom = HigherEdu::find($document_id);
                    $media_files = $diplom->getMedia('higher_diplom', ['user_id' => $user->id]);
                    return ListMediaResource::collection($media_files);

                case 'additional_diplom':
                    $diplom = AdditionalEdu::find($document_id);
                    $media_files = $diplom->getMedia('additional_diplom', ['user_id' => $user->id]);
                    return ListMediaResource::collection($media_files);
                case 'user_other_documents':
                    $user = User::find(Auth::id());
                    $media_files = $user->getMedia('user_other_documents', ['user_id' => $user->id]);
                    return ListMediaResource::collection($media_files);

                    break;
                
                default:
                    
                    return response()->json([
                        "message" => "Проверьте имя коллекции..",
                        "code" => 403,
                        
                    ],403);

                    break;
            }

            

        }
    }


    public function loadMedia(Request $request)
    {   
        $user = Company::currentUserForAction($request);
        $collection_name = $request->collection_name;
        $document_id = $request->document_id;
        if (isset($request->page)){
            $page = $request->page;
        } 
        
        if($request->image != null) {
            switch ($collection_name) {
                case 'secondary_school':
                    $secondarySchool = SecondaryEdu::find($document_id);
                    if($secondarySchool->user_id == $user->id){
                        $secondarySchool->addMediaFromRequest('image')->withCustomProperties([
                            'secondary_school_id' => intval($secondarySchool->id),
                            'user_id' => intval($user->id),
                            'page' => intval($page)
                        ])->toMediaCollection('secondary_school');

                        $media_id = $secondarySchool->getMedia('secondary_school')->last()->id;
                        
                        return response()->json([
                            "message" => "Файл загружен..",
                            "code" => 201,
                            "media_id" => $media_id
                        ],201);

                    } else{
                        return response()->json([
                            "message" => "ууу..",
                            "code" => 403,       
                        ],201);
                    }
                    break;
                
                case 'specialized_secondary_school':
                    $specializedSecondarySchool = SpecializedSecondaryEdu::find($document_id);
                    if(!isset($specializedSecondarySchool)){
                        return response()->json([
                            "message" => "Неправильный id образования",
                            "code" => 404,
                        ],201);
                    }

                    if($specializedSecondarySchool->user_id == $user->id){
                        $specializedSecondarySchool->addMediaFromRequest('image')->withCustomProperties([
                            'specialized_secondary_school_id' => intval($specializedSecondarySchool->id),
                            'user_id' => intval($user->id),
                            'page' => intval($page)
                        ])->toMediaCollection('specialized_secondary_school');
                    
                    $media_id = $specializedSecondarySchool->getMedia('specialized_secondary_school')->last()->id;

                        return response()->json([
                            "message" => "Файл загружен..",
                            "code" => 201,
                            "media_id" => $media_id
                        ],201);

                    } else{
                        return response()->json([
                            "message" => "Неправильный запрос..или недостаточно прав..",
                            "code" => 403,       
                        ],201);
                    }
                case 'higher_diplom' :
                    $diplom = HigherEdu::find($document_id);
                    if($diplom->user_id == $user->id){
                        $diplom->addMediaFromRequest('image')->withCustomProperties([
                            'higher_diplom_id' => intval($diplom->id),
                            'user_id' => intval($user->id),
                            'page' => intval($page)
                        ])->toMediaCollection('higher_diplom');
                    $media_id = $diplom->getMedia('higher_diplom')->last()->id;
                        return response()->json([
                            "message" => "Файл загружен..",
                            "code" => 201,
                            "media_id" => $media_id
                        ],201);
                    } else{
                        return response()->json([
                            "message" => "Неправильный запрос..или недостаточно прав..",
                            "code" => 403,
                        ],201);
                    }
                case 'additional_diplom':
                    $additional_edu = AdditionalEdu::find($document_id);
                    if(!isset($additional_edu)){
                        return response()->json([
                            "message" => "Не найден id образования..",
                            "code" => 404,
                        ],404);
                    }

                    if($additional_edu->user_id == $user->id){
                        $additional_edu->addMediaFromRequest('image')->withCustomProperties([
                            'additional_diplom_id' => intval($additional_edu->id),
                            'user_id' => intval($user->id),
                            'page' => intval($page)
                        ])->toMediaCollection('additional_diplom');

                        $media_id = $additional_edu->getMedia('additional_diplom')->last()->id;

                        return response()->json([
                            "message" => "Файл загружен..",
                            "code" => 201,
                            "media_id" => $media_id
                        ],201);

                    } else{
                        return response()->json([
                            "message" => "Неправильный запрос..или недостаточно прав..",
                            "code" => 403,
                            
                        ],201);
                    }
                
                // админские изображения, могут управлять только админы
                // но смотреть могут все (теперь)
                case 'form_org_unit_images':
                    if($user->hasRoleAdmin($this->adminRoles) == 1){
                        $image_org_unit = FormOrgUnits::find($document_id);

                        $media = $image_org_unit->addMediaFromRequest('image')->withCustomProperties([
                            'image_org_unit_id' => intval($image_org_unit->id),
                            'user_id' => intval($user->id),
                        ])->toMediaCollection('form_org_unit_images', 'public');

                        $media_id = $image_org_unit->getMedia('form_org_unit_images')->last()->id;

                        $image_org_unit->media_id = $media_id;
                        $image_org_unit->url = $media->getUrl();

                        $image_org_unit->save();

                        return response()->json([
                            "message" => "Файл загружен..",
                            "code" => 201,
                            "media_id" => $media_id,
                            "url" => $media->getUrl()
                        ],201);

                    } 
                    else{
                        return response()->json([
                            'message' => 'Вы не можете этого сделать..'
                        ], 422);
                    }

                //картинки внутри формы доступной среды 
                case 'form_accesible_env_images':

                    if($user->hasRoleAdmin($this->adminRoles) == 1){

                        $file = $request->file('image');
                        $result = (new MainService)->addMedia(AccessibleEnvDoc::ACCESIBLE_ENV_IMAGES, $file);

                        $imageDB = new AccessibleEnvDoc;
                        $imageDB->title_doc = $result->first()['name'];
                        $imageDB->image_url = $result->first()['url'];
                        $imageDB->form_accesible_env_id = $document_id;
                        $imageDB->save();

                        return $imageDB;
                    } 
                    else{
                        return response()->json([
                            'message' => 'Вы не можете этого сделать..'
                        ], 422);
                    }

                // правки(оказалось надо несколько картинок в форму, а не 1)
                case 'form_international_cooper_images':
                    if($user->hasRoleAdmin($this->adminRoles) == 1){
                        $file = $request->file('image');
                        $result = (new MainService)->addMedia(FormInternationalCooperation::COOPERATION_INTER_IMAGES, $file);
                        
                        $imageDB = new InternationalCooperationImage;
                        $imageDB->title_image = $result->first()['name'];
                        $imageDB->image_url = $result->first()['url'];
                        $imageDB->form_international_cooperation_id = $document_id;
                        $imageDB->save();

                        return $imageDB;

                    }


                case 'form_org_unit_docs':
                    if($user->hasRoleAdmin($this->adminRoles) == 1){
                        
                        // 1.сначала сохраним pdf 
                        $unit = FormOrgUnits::find($document_id);
                        
                        $media_pdf = $unit->addMediaFromRequest('image')->withCustomProperties([
                            'doc_org_unit_id' => intval($unit->id),
                            'user_id' => intval($user->id),
                        ])->toMediaCollection('form_org_unit_docs', 'public');

                        // 2.получим только что загруженный документ
                        $media = $unit->getMedia('form_org_unit_docs')->last(); 

                        // 3.теперь сформируем превью png
                        $pdf = $media->getPath();
                        $preview = new Imagick($pdf. "[0]"); // первая страница
                        $preview->setImageFormat("png");
                        // отключение прозрачности
                        $preview->setImageAlphaChannel(Imagick::ALPHACHANNEL_REMOVE); 
                        $preview->setImageCompressionQuality(100);
                        $name_png = $media->name . '_' . $media->id . '.png'; 
                        $preview->writeImage(storage_path('app/public/pdf_preview/' . $name_png));
                        $url = asset('storage/pdf_preview/' . $name_png );

                        // 4. сейвим мета дату документа
                        $document = new OrgUnitDoc;
                        $document->media_id = $media->id;
                        $document->title_doc = $media->file_name;
                        $document->form_org_unit_id = $unit->id;
                        $document->preview_pdf = $url;
                        $document->url_pdf = $media_pdf->getUrl();

                        $document->save();

                        return $document;

                    } 
                    else{
                        return response()->json([
                            'message' => 'Вы не можете этого сделать..'
                        ], 422);
                    }

                case 'form_edu_programm_pdf':
                    if($user->hasRoleAdmin($this->adminRoles) == 1){
                        
                        // 1.сначала сохраним pdf 
                        $form_edu = FormEduProgram::find($document_id);
                        $form_edu->addMediaFromRequest('image')->withCustomProperties([
                            'form_edu_program_id' => intval($form_edu->id),
                            'user_id' => intval($user->id),
                        ])->toMediaCollection($collection_name, 'public');

                        // 2.получим только что загруженный документ
                        $media = $form_edu->getMedia($collection_name)->last(); 

                        // 3.теперь сформируем превью png
                        $pdf = $media->getPath();
                        $preview = new Imagick($pdf. "[0]"); // первая страница
                        $preview->setImageFormat("png");
                        // отключение прозрачности
                        $preview->setImageAlphaChannel(Imagick::ALPHACHANNEL_REMOVE); 
                        $preview->setImageCompressionQuality(100);
                        $name_png = $media->name . '_' . $media->id . '.png'; 
                        $preview->writeImage(storage_path('app/public/pdf_preview/' . $name_png));
                        $url = asset('storage/pdf_preview/' . $name_png );

                        // 4. сейвим мета дату документа
                        $document = new EduProgramDoc;
                        $document->media_id = $media->id;
                        $document->title_doc = $media->file_name;
                        $document->form_edu_program_id = $form_edu->id;
                        $document->preview_pdf = $url;
                        $document->url_pdf = $media->getUrl();
                        $document->save();

                        return $document;

                    } 
                    else{
                        return response()->json([
                            'message' => 'Вы не можете этого сделать..'
                        ], 422);
                    }

                // Сохранение пдф файлов с формированием превью в конкретных моделях 
                // вынести в отдельный сервис когда нибудь.. todo #100001
                case 'section_year_docs':
                    if($user->hasRoleAdmin($this->adminRoles) == 1){
                        
                        // 1.сначала сохраним pdf 
                        $year = SectionYearDocument::find($document_id);
                        // dd($year);

                        $year->addMediaFromRequest('image')->withCustomProperties([
                            'doc_section_year_doc_id' => intval($year->id),
                            'user_id' => intval($user->id),
                        ])->toMediaCollection($collection_name, 'public');

                        // 2.получим только что загруженный документ
                        $media = $year->getMedia($collection_name)->last(); 

                        // 3.теперь сформируем превью png
                        $pdf = $media->getPath();
                        $preview = new Imagick($pdf. "[0]"); // первая страница
                        $preview->setImageFormat("png");
                        // отключение прозрачности
                        $preview->setImageAlphaChannel(Imagick::ALPHACHANNEL_REMOVE); 
                        $preview->setImageCompressionQuality(100);
                        $name_png = $media->name . '_' . $media->id . '.png'; 
                        $preview->writeImage(storage_path('app/public/pdf_preview/' . $name_png));
                        $url = asset('storage/pdf_preview/' . $name_png );

                        // 4. сейвим мета дату документа
                        $document = new SectionYearDoc;
                        $document->media_id = $media->id;
                        $document->title_doc = $media->file_name;
                        $document->section_year_document_id = $year->id;
                        $document->preview_pdf = $url;
                        $document->url_pdf = $media->getUrl();

                        $document->save();
                        return $document;

                    }

                    else{
                        return response()->json([
                            'message' => 'Вы не можете этого сделать..'
                        ], 422);
                    }

                case 'form_international_cooper_docs':

                    if($user->hasRoleAdmin($this->adminRoles) == 1){
                        // 1.сначала сохраним pdf 
                        $coop = FormInternationalCooperation::find($document_id);
                        $coop->addMediaFromRequest('image')->withCustomProperties([
                            'coop_doc_id' => intval($coop->id),
                            'user_id' => intval($user->id),
                        ])->toMediaCollection($collection_name, 'public');

                        // 2.получим только что загруженный документ
                        $media = $coop->getMedia($collection_name)->last(); 

                        // 3.теперь сформируем превью png
                        $pdf = $media->getPath();
                        $preview = new Imagick($pdf. "[0]"); // первая страница
                        $preview->setImageFormat("png");
                        // отключение прозрачности
                        $preview->setImageAlphaChannel(Imagick::ALPHACHANNEL_REMOVE); 
                        $preview->setImageCompressionQuality(100);
                        $name_png = $media->name . '_' . $media->id . '.png'; 
                        $preview->writeImage(storage_path('app/public/pdf_preview/' . $name_png));
                        $url = asset('storage/pdf_preview/' . $name_png );

                        // 4. сейвим мета дату документа
                        $document = new InternationalCooperationImage;
                        $document->media_id = $media->id;
                        $document->title_doc = $media->file_name;
                        $document->form_international_cooperation_id = $coop->id;
                        $document->preview_pdf = $url;
                        $document->url_pdf = $media->getUrl();

                        $document->save();
                        return $document;

                    }

                    else{
                        return response()->json([
                            'message' => 'Вы не можете этого сделать..'
                        ], 422);
                    }


                // документы как ссылка/картинка
                case 'form_documents':
                    if($user->hasRoleAdmin($this->adminRoles) == 1){
                        
                        // 1.сначала сохраним pdf 
                        $document = FormDocument::find($document_id);
                        // dd($year);

                        $document->addMediaFromRequest('image')->withCustomProperties([
                            'doc_section_year_doc_id' => intval($document->id),
                            'user_id' => intval($user->id),
                        ])->toMediaCollection($collection_name, 'public');

                        // 2.получим только что загруженный документ
                        $media = $document->getMedia($collection_name)->last(); 

                        // 3.теперь сформируем превью png
                        $pdf = $media->getPath();
                        $preview = new Imagick($pdf. "[0]"); // первая страница
                        $preview->setImageFormat("png");
                        // отключение прозрачности
                        $preview->setImageAlphaChannel(Imagick::ALPHACHANNEL_REMOVE); 
                        $preview->setImageCompressionQuality(100);
                        $name_png = $media->name . '_' . $media->id . '.png'; 
                        $preview->writeImage(storage_path('app/public/pdf_preview/' . $name_png));
                        $url = asset('storage/pdf_preview/' . $name_png );

                        // 4. сейвим мета дату документа
                        $doc = new FormDocumentDoc;
                        $doc->media_id = $media->id;
                        $doc->title_doc = $media->file_name;
                        $doc->form_document_id = $document->id;
                        $doc->preview_pdf = $url;
                        $doc->url_pdf = $media->getUrl();
                        $doc->save();

                        return $doc;

                    } 
                    else{
                        return response()->json([
                            'message' => 'Вы не можете этого сделать..'
                        ], 422);
                    }


                case 'form_edu_programm_images':
                    if($user->hasRoleAdmin($this->adminRoles) == 1){
                        $form_edu = FormEduProgram::find($document_id);

                        $media = $form_edu->addMediaFromRequest('image')->withCustomProperties([
                            'image_edu_programm_id' => intval($form_edu->id),
                            'user_id' => intval($user->id),

                        ])->toMediaCollection('form_edu_programm_images' ,'public');

                        $media_id = $form_edu->getMedia('form_edu_programm_images')->last()->id;

                        $form_edu->media_id = $media_id;
                        $form_edu->url = $media->getUrl();

                        $form_edu->save();

                        return response()->json([
                            "message" => "Файл загружен..",
                            "code" => 201,
                            "media_id" => $media_id,
                            "url" => $media->getUrl()
                        ],201);

                    } 
                    else{
                        return response()->json([
                            'message' => 'Вы не можете этого сделать..'
                        ], 422);
                    }

                case 'form_edu_director_images':
                    if($user->hasRoleAdmin($this->adminRoles) == 1){
                        $form_director = FormDataDirectorEdu::find($document_id);

                        $media = $form_director->addMediaFromRequest('image')->withCustomProperties([
                            'image_director_id' => intval($form_director->id),
                            'user_id' => intval($user->id),

                        ])->toMediaCollection('form_edu_director_images', 'public');

                        $media_id = $form_director->getMedia('form_edu_director_images')->last()->id;

                        $form_director->media_id = $media_id;
                        $form_director->url = $media->getUrl();
                        $form_director->save();

                        return response()->json([
                            "message" => "Файл загружен..",
                            "code" => 201,
                            "media_id" => $media_id,
                            "url" => $media->getUrl()
                        ],201);

                    } 
                    else{
                        return response()->json([
                            'message' => 'Вы не можете этого сделать..'
                        ], 422);
                    }

                case 'form_financial_plan_images':
                    if($user->hasRoleAdmin($this->adminRoles) == 1){

                        $doc_image = PlanYearDocument::find($document_id);
                        $doc_image->addMediaFromRequest('image')->withCustomProperties([
                            'image_id' => intval($doc_image->id),
                            'user_id' => intval($doc_image->id),
                        ])->toMediaCollection($collection_name, 'public');

                        $media = $doc_image->getMedia($collection_name)->last(); 
                        $media_id = $media->id;

                        $doc_image->media_id = $media_id;
                        $doc_image->url = $media->getUrl();
                        $doc_image->save();

                        return response()->json([
                            "message" => "Изображение загружено..",
                            "code" => 201,
                            "media_id" => $media_id,
                            "url" => $media->getUrl()
                        ],201);

                    } 
                    else{
                        return response()->json([
                            'message' => 'Вы не можете этого сделать..'
                        ], 422);
                    }


                default:
                    
                    return response()->json([
                        "message" => "Проверьте имя коллекции..",
                        "code" => 403,
                    ],403);



                    break;
            }

        } else {

            return response()->json([
                "message" => "А где само изображение/ файл?!",
                "code" => 403,
                
            ],201);
        }



    }








}


