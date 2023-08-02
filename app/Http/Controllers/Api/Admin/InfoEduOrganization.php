<?php
namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Http\Resources\Admin\FormsInSpoilerResource;
use App\Http\Resources\Admin\AdminSectionResource;
use App\Http\Resources\Admin\AdminSectionTabResource;

//дерево с чпу
use App\Http\Resources\Admin\SpecialSectionTreeResource;
use App\Http\Resources\Admin\TabTreeResource;
use App\Http\Resources\Admin\SpoilerTreeResource;
use App\Http\Resources\Course\CourseOnlyNameResource;
use App\Models\User;
use App\Models\EduOrganization;
use App\Models\Admin\SpecialSection\AdminSection;
use App\Models\Admin\SpecialSection\AdminSectionTab;
use App\Models\Admin\SpecialSection\SectionSpoiler;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use App\Models\Course\Course;
use App\Models\Admin\SpecialSection\FormDataOrg;
use App\Models\Admin\SpecialSection\DataOrg;
use App\Models\Admin\SpecialSection\DataOrgEmail;
use App\Models\Admin\SpecialSection\DataOrgPhone;
use App\Models\Admin\SpecialSection\FormOrgUnits;
use App\Models\Admin\SpecialSection\OrgUnitEmail;
use App\Models\Admin\SpecialSection\OrgUnitSite;
use App\Models\Admin\SpecialSection\OrgUnitDoc;
use App\Models\Admin\SpecialSection\FormEduProgram;
use App\Models\Admin\SpecialSection\EduProgramDoc;
use App\Models\Admin\SpecialSection\FormMaterialEquipment;
use App\Models\Admin\SpecialSection\FormFellowshipMeasure;
use App\Models\Admin\SpecialSection\FormDataDirector;
use App\Models\Admin\SpecialSection\FormDataDirectorEdu;
use App\Models\Admin\SpecialSection\FormDocument;
use App\Models\Admin\SpecialSection\FormDocumentDoc;
use App\Models\Admin\SpecialSection\FormAccesibleEnv;
use App\Models\Admin\SpecialSection\AccessibleEnvDoc;
use App\Models\Admin\SpecialSection\FormEducation;
use App\Models\Admin\SpecialSection\FormVacantPlace;
use App\Models\Admin\SpecialSection\FormInternationalCooperation;
use App\Models\Admin\SpecialSection\InternationalCooperationImage;
use App\Models\Admin\SpecialSection\FormFinancialSource;
use App\Models\Admin\SpecialSection\FinancialYear;
use App\Models\Admin\SpecialSection\FormEconomicActivityPlan;
use App\Models\Admin\SpecialSection\ActivityPlanYear;
use App\Models\Admin\SpecialSection\SectionYearDocument;
use App\Models\Admin\SpecialSection\SectionYearDoc;
use App\Models\Admin\SpecialSection\FormSpeciality;
use Carbon\Carbon;
use App\Services\MainService;

class InfoEduOrganization extends Controller
{

    public $adminRoles = [
        'superadmin',
        'owner'
    ];


    public function uniqueSlug($title)
    {
        $slug = \Str::slug($title, '-');
        if (AdminSection::where('slug', '=', $slug)->exists() ||
            AdminSectionTab::where('slug', '=', $slug)->exists()  ) {
          return $slug = $slug . '-' . \Str::random(1) . '-' . mt_rand(1,100); 
        }  else {
            return $slug;
        }
    }

    public function createSection(Request $request)
    {   
        $user = User::find(Auth::id());
        $section = new AdminSection;
        $section->title = $request->title;
        $section->slug = $this->uniqueSlug($request->title);
        $section->save();
        return collect($section);
    }


    public function getAllFormInSpoiler(Request $request, $id)
    {   
        $section = AdminSection::with([
            'tabs.spoilers',
            'tabs.spoilers.formsDataOrg',
            'tabs.spoilers.formsDataOrg.emails',
            'tabs.spoilers.formsDataOrg.phones',
            'tabs.spoilers.formsOrgUnit',
            'tabs.spoilers.formsOrgUnit.docs',
            'tabs.spoilers.formsOrgUnit.emails',
            'tabs.spoilers.formsOrgUnit.sites',
            'tabs.spoilers.formsEduProgram',
            'tabs.spoilers.formsEduProgram.docs',
            'tabs.spoilers.formsMaterialEquipment',
            'tabs.spoilers.formsFellowshipMeasure',
            'tabs.spoilers.formsDataDirectorEdu',
            'tabs.spoilers.formsDataDirectorEdu.programms',
            'tabs.spoilers.formsDataDirector',
            'tabs.spoilers.formsDocument',
            'tabs.spoilers.formsDocument.docs', //pdf
            'tabs.spoilers.formsAccesibleEnv',
            'tabs.spoilers.formsAccesibleEnv.docs', //картинки
            'tabs.spoilers.formsEducation',
            'tabs.spoilers.formsInternationalCooperation',
            'tabs.spoilers.formsInternationalCooperation.images',
            'tabs.spoilers.formsFinancialSource',
            'tabs.spoilers.formsFinancialSource.years',
            'tabs.spoilers.formsVacantPlaces',
            'tabs.spoilers.formsEconomicActivityPlan',
            'tabs.spoilers.formsEconomicActivityPlan.years',
            'tabs.spoilers.formsEconomicActivityPlan.years.sections',
            'tabs.spoilers.formsEconomicActivityPlan.years.sections.docs', // pdf файлы
            'tabs.spoilers.formsSpeciality',
            'spoilers.formsDataOrg',
            'spoilers.formsDataOrg.emails',
            'spoilers.formsDataOrg.phones',
            'spoilers.formsOrgUnit',
            'spoilers.formsOrgUnit.docs',
            'spoilers.formsOrgUnit.emails',
            'spoilers.formsOrgUnit.sites',
            'spoilers.formsEduProgram',
            'spoilers.formsEduProgram.docs',
            'spoilers.formsMaterialEquipment',
            'spoilers.formsFellowshipMeasure',
            'spoilers.formsDataDirectorEdu',
            'spoilers.formsDataDirectorEdu.programms',
            'spoilers.formsDataDirector',
            'spoilers.formsDocument',
            'spoilers.formsDocument.docs', //pdf
            'spoilers.formsAccesibleEnv',
            'spoilers.formsAccesibleEnv.docs', //картинки
            'spoilers.formsEducation',
            'spoilers.formsInternationalCooperation',
            'spoilers.formsInternationalCooperation.images',
            'spoilers.formsFinancialSource',
            'spoilers.formsFinancialSource.years',
            'spoilers.formsVacantPlaces',
            'spoilers.formsEconomicActivityPlan',
            'spoilers.formsEconomicActivityPlan.years',
            'spoilers.formsEconomicActivityPlan.years.sections',
            'spoilers.formsEconomicActivityPlan.years.sections.docs', // pdf файлы
            'spoilers.formsSpeciality',
        ])->find($id);
        
        return AdminSectionResource::make($section);
    }


    public function getTabOrSectionFromSlug(Request $request, $slug)
    {
        // dd($slug);

        $slugSection = AdminSection::where('slug', '=', $slug)->first();
        $slugTab = AdminSectionTab::where('slug', '=', $slug)->first();

        if($slugSection == null && $slugTab == null) {

           return response()->json([
               'message' => 'Нет раздела с таким слагом..'
           ], 404);
        } else {

            if(!empty($slugSection)) {

                $section = AdminSection::with([
                    'tabs.spoilers',
                    'tabs.spoilers.formsDataOrg',
                    'tabs.spoilers.formsDataOrg.emails',
                    'tabs.spoilers.formsDataOrg.phones',
                    'tabs.spoilers.formsOrgUnit',
                    'tabs.spoilers.formsOrgUnit.docs',
                    'tabs.spoilers.formsOrgUnit.emails',
                    'tabs.spoilers.formsOrgUnit.sites',
                    'tabs.spoilers.formsEduProgram',
                    'tabs.spoilers.formsEduProgram.docs',
                    'tabs.spoilers.formsMaterialEquipment',
                    'tabs.spoilers.formsFellowshipMeasure',
                    'tabs.spoilers.formsDataDirectorEdu',
                    'tabs.spoilers.formsDataDirectorEdu.programms',
                    'tabs.spoilers.formsDataDirector',
                    'tabs.spoilers.formsDocument',
                    'tabs.spoilers.formsDocument.docs', //pdf
                    'tabs.spoilers.formsAccesibleEnv',
                    'tabs.spoilers.formsAccesibleEnv.docs', //картинки
                    'tabs.spoilers.formsEducation',
                    'tabs.spoilers.formsInternationalCooperation',
                    'tabs.spoilers.formsInternationalCooperation.images',
                    'tabs.spoilers.formsFinancialSource',
                    'tabs.spoilers.formsFinancialSource.years',
                    'tabs.spoilers.formsVacantPlaces',
                    'tabs.spoilers.formsEconomicActivityPlan',
                    'tabs.spoilers.formsEconomicActivityPlan.years',
                    'tabs.spoilers.formsEconomicActivityPlan.years.sections',
                    'tabs.spoilers.formsEconomicActivityPlan.years.sections.docs', // pdf файлы
                    'tabs.spoilers.formsSpeciality',
                    'spoilers.formsDataOrg',
                    'spoilers.formsDataOrg.emails',
                    'spoilers.formsDataOrg.phones',
                    'spoilers.formsOrgUnit',
                    'spoilers.formsOrgUnit.docs',
                    'spoilers.formsOrgUnit.emails',
                    'spoilers.formsOrgUnit.sites',
                    'spoilers.formsEduProgram',
                    'spoilers.formsEduProgram.docs',
                    'spoilers.formsMaterialEquipment',
                    'spoilers.formsFellowshipMeasure',
                    'spoilers.formsDataDirectorEdu',
                    'spoilers.formsDataDirectorEdu.programms',
                    'spoilers.formsDataDirector',
                    'spoilers.formsDocument',
                    'spoilers.formsDocument.docs', //pdf
                    'spoilers.formsAccesibleEnv',
                    'spoilers.formsAccesibleEnv.docs', //картинки
                    'spoilers.formsEducation',
                    'spoilers.formsInternationalCooperation',
                    'spoilers.formsInternationalCooperation.images',
                    'spoilers.formsFinancialSource',
                    'spoilers.formsFinancialSource.years',
                    'spoilers.formsVacantPlaces',
                    'spoilers.formsEconomicActivityPlan',
                    'spoilers.formsEconomicActivityPlan.years',
                    'spoilers.formsEconomicActivityPlan.years.sections',
                    'spoilers.formsEconomicActivityPlan.years.sections.docs', // pdf файлы
                    'spoilers.formsSpeciality',
                ])->find($slugSection->id);


                return AdminSectionResource::make($section);
            }

            if(!empty($slugTab)) {

                 
            $tab  = AdminSectionTab::with([
                'spoilers.formsDataOrg',
                'spoilers.formsDataOrg.emails',
                'spoilers.formsDataOrg.phones',
                'spoilers.formsOrgUnit',
                'spoilers.formsOrgUnit.docs',
                'spoilers.formsOrgUnit.emails',
                'spoilers.formsOrgUnit.sites',
                'spoilers.formsEduProgram',
                'spoilers.formsEduProgram.docs',
                'spoilers.formsMaterialEquipment',
                'spoilers.formsFellowshipMeasure',
                'spoilers.formsDataDirectorEdu',
                'spoilers.formsDataDirectorEdu.programms',
                'spoilers.formsDataDirector',
                'spoilers.formsDocument',
                'spoilers.formsDocument.docs', //pdf
                'spoilers.formsAccesibleEnv',
                'spoilers.formsAccesibleEnv.docs', //картинки
                'spoilers.formsEducation',
                'spoilers.formsInternationalCooperation',
                'spoilers.formsInternationalCooperation.images',
                'spoilers.formsFinancialSource',
                'spoilers.formsFinancialSource.years',
                'spoilers.formsVacantPlaces',
                'spoilers.formsEconomicActivityPlan',
                'spoilers.formsEconomicActivityPlan.years',
                'spoilers.formsEconomicActivityPlan.years.sections',
                'spoilers.formsEconomicActivityPlan.years.sections.docs', // pdf файлы
                'spoilers.formsSpeciality',
            ])->find($slugTab->id);

               return AdminSectionTabResource::make($tab);
            }
        }

    }

    public function getSectionAll(Request $request)
    {  
        return AdminSection::orderBy('sort', 'asc')->get();
    }

    public function sectionTree(Request $request)
    {   
        $sections = AdminSection::orderBy('sort', 'asc')->get();
        return SpecialSectionTreeResource::collection($sections);
    }

    public function positionSections(Request $request)
    {
        $user = User::find(Auth::id());
        if ($user->hasRoleAdmin($this->adminRoles) == 1) {
            $section_ids = $request->id;
            foreach ($section_ids as $id => $position) {
                $section = AdminSection::find($id);
                $section->sort = $position;
                $section->save();
            }
            return response()->json([
                'message' => 'Позиции успешно изменены..'
            ], 201);
        } else {
            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }
    }

    public function editSection(Request $request)
    {   
        $section = AdminSection::find($request->section_id);
        $section->update([
            'title' => $request->title,
            'slug' => $this->uniqueSlug($request->title)
        ]);
        return collect($section);
    }


    public function deleteSection(Request $request)
    {   

        // зависимости удалить..
        $section = AdminSection::find($request->section_id);
        $section->delete(); 

        return response()->json([
            'message' => 'Раздел успешно удален..'
        ], 202);
    }


    //ВКЛАДКА ВНУТРИ РАЗДЕЛА
    public function createSectionTab(Request $request)
    {
        $user = User::find(Auth::id());

        if ($user->hasRoleAdmin($this->adminRoles) == 1) {

            $tab = new AdminSectionTab;
            $tab->title = $request->title;
            $tab->slug = $this->uniqueSlug($request->title);
            $tab->admin_section_id = $request->admin_section_id;
            $tab->save();

            return collect($tab);
        } else {

            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }
    }

    public function editSectionTab(Request $request)
    {
        $user = User::find(Auth::id());

        if ($user->hasRoleAdmin($this->adminRoles) == 1) {

            $tab = AdminSectionTab::find($request->id);
            $tab->title = $request->title;
            $tab->slug = $this->uniqueSlug($request->title);
            $tab->save();
            return collect($tab);
        } else {

            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }
    }

    public function deleteSectionTab(Request $request, $tab_id)
    {
        $user = User::find(Auth::id());

        if ($user->hasRoleAdmin($this->adminRoles) == 1) {

            $tab = AdminSectionTab::find($request->tab_id);
            $tab->delete();

            return response()->json([
                'message' => 'Вкладка успешно удалена..',
                'code' => 202
            ], 202);

            // return $tab->spoilers;

        } else {

            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }
    }




    public function createSpoiler(Request $request)
    {   

        $user = User::find(Auth::id());

        if ($user->hasRoleAdmin(['superadmin','owner']) == 1) {

            $spoiler = new SectionSpoiler;
            $spoiler->name = $request->name_spoiler;
            $spoiler->title = $request->title_spoiler;
            $spoiler->description = $request->description_spoiler;
            
            if(isset($request->admin_section_id)) {
                $spoiler->admin_section_id = $request->admin_section_id;

                $spoiler->save();
                return collect($spoiler);

            }
            
            if(isset($request->admin_section_tab_id)) {
                $spoiler->admin_section_tab_id = $request->admin_section_tab_id;
                $spoiler->save();
                return collect($spoiler);

            }
            

           

          
        } else {

            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }

    }


    public function editSpoiler(Request $request)
    {   

        $user = User::find(Auth::id());

        if ($user->hasRoleAdmin(['superadmin','owner']) == 1) {

            $spoiler = SectionSpoiler::find($request->spoiler_id);
            
            $spoiler->name = $request->name_spoiler;
            $spoiler->title = $request->title_spoiler;
            $spoiler->description = $request->description_spoiler;
            // $spoiler->admin_section_id = $request->admin_section_id;

            $spoiler->save();

            return collect($spoiler);
        } else {

            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }

    }

    public function getSpoiler(Request $request, $spoiler_id)
    {   

        $user = User::find(Auth::id());
        if ($user->hasRoleAdmin($this->adminRoles) == 1) {

            $spoiler = SectionSpoiler::find($spoiler_id);
    
            return $spoiler;
        } else {

            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }

    }

    //список спойлеров с позициями
    public function getSpoilers(Request $request)
    {
        $user = User::find(Auth::id());
        if ($user->hasRoleAdmin($this->adminRoles) == 1) {
            $spoilers = SectionSpoiler::all();
            return collect($spoilers);

        } else {

            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }
    }


    public function positionSpoilers(Request $request)
    {
        $user = User::find(Auth::id());
        if ($user->hasRoleAdmin($this->adminRoles) == 1) {

            $sp_ids = $request->id;

            foreach ($sp_ids as $id => $position) {

                $spoiler = SectionSpoiler::find($id);
                $spoiler->position = $position;

                $spoiler->save();
            }


            return response()->json([
                'message' => 'Позиции успешно изменены..'
            ], 201);


            // return collect($spoilers);

        } else {

            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }
    }


    public function deleteSpoiler(Request $request, $spoiler_id)
    {
        $user = User::find(Auth::id());

        if ($user->hasRoleAdmin($this->adminRoles) == 1) {

            $spoiler = SectionSpoiler::find($spoiler_id);
            
            //дополнительно удалять сущности спойлера
            $spoiler->delete();

            return response()->json([
                'message' => 'spoiler удален..'
            ], 202);

        
        } else {

            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }
    }



    public function createForm(Request $request)
    {   

        $user = User::find(Auth::id());

        if ($user->hasRoleAdmin(['superadmin','owner']) == 1) {

            $spoiler = SectionSpoiler::find($request->spoiler_id);

            $form = new FormDataOrg;
            $form->admin_section_spoiler_id = $request->spoiler_id;
            $form->save();

            return collect($form);
        } else {

            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }

    }



    public function deleteForm(Request $request, $form_id)
    {
        $user = User::find(Auth::id());

        if ($user->hasRoleAdmin($this->adminRoles) == 1) {

            // $spoiler = SectionSpoiler::find($request->spoiler_id);

            $form = FormDataOrg::find($form_id);
            $form->delete();
            
            return response()->json([
                'message' => 'Форма удалена',
                'code' => 202
            ], 202);

            return collect($form);
        } else {

            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }
    }


    public function editForm(Request $request)
    {   

        $user = User::find(Auth::id());

        if ($user->hasRoleAdmin(['superadmin','owner']) == 1) {

            $form = FormDataOrg::find($request->form_id);


            $form->full_title = $request->full_title;
            $form->short_title = $request->short_title;
            // $form->date_create_org = date($request->date_create_org);
            // ('2012-10-5 23:26:11.123789');
            $form->date_create_org = Carbon::parse($request->date_create_org);
            $form->founder_org = $request->founder_org;
            $form->location = $request->location;
            $form->time_schedule = $request->time_schedule;
            $form->days_working = $request->days_working;
            $form->time_working = $request->time_working;
            $form->address_edu_activity = $request->address_edu_activity;


            $form->save();

            return collect($form);
        } else {

            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }

    }


    public function createEmail(Request $request)
    {   

        $user = User::find(Auth::id());

        if ($user->hasRoleAdmin(['superadmin','owner']) == 1) {

            $email = new DataOrgEmail;
            $email->form_data_org_id = $request->form_id;
            $email->save();

            return collect($email);
        } else {

            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }

    }

    public function deleteEmail(Request $request, $email_id)
    {   

        $user = User::find(Auth::id());

        if ($user->hasRoleAdmin($this->adminRoles) == 1) {

            $email = DataOrgEmail::find($email_id);

            $email->delete();

            return response()->json([
                'message' => 'Емейл удален..',
                'code' => 202
            ], 202);


        } else {

            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }

    }


    

    public function editEmail(Request $request)
    {   

        $user = User::find(Auth::id());

        if ($user->hasRoleAdmin(['superadmin','owner']) == 1) {

            $email = DataOrgEmail::find($request->email_id);
            $email->email = $request->email;
            $email->save();

            return collect($email);
        } else {

            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }

    }


    public function createPhone(Request $request)
    {   

        $user = User::find(Auth::id());

        if ($user->hasRoleAdmin(['superadmin','owner']) == 1) {

            $phone = new DataOrgPhone;
            $phone->form_data_org_id = $request->form_id;
            $phone->save();

            return collect($phone);
        } else {

            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }

    }


    public function deletePhone(Request $request, $phone_id)
    {   

        $user = User::find(Auth::id());

        if ($user->hasRoleAdmin($this->adminRoles) == 1) {
            $phone = DataOrgPhone::find($phone_id);
            $phone->delete();


            return response()->json([
                'message' => 'Телефон удален..',
                'code' => 202
            ], 202);


        } else {

            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }

    }




    public function editPhone(Request $request)
    {   
        $user = User::find(Auth::id());
        if ($user->hasRoleAdmin(['superadmin','owner']) == 1) {
            $phone = DataOrgPhone::find($request->phone_id);
            $phone->phone = $request->phone;
            $phone->save();
            return collect($phone);
        } else {
            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }
    }


    // структурные подразделения

    public function createUnitForm(Request $request)
    {   
        $user = User::find(Auth::id());

        if ($user->hasRoleAdmin($this->adminRoles) == 1) {

            $form = new FormOrgUnits;
            $form->admin_section_spoiler_id = $request->spoiler_id;
            $form->save();

            return collect($form);


        } else {
            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }
    }


    public function editUnitForm(Request $request)
    {   

        $user = User::find(Auth::id());

        if ($user->hasRoleAdmin($this->adminRoles) == 1) {

            $form = FormOrgUnits::find($request->form_id);
            $form->unit_title = $request->unit_title;
            $form->director = $request->director;
            $form->unit_address = $request->unit_address;
           
            $form->save();

            return collect($form);
        } else {

            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }
    }


    public function docsUnitForm(Request $request, $form_id)
    {
        $user = User::find(Auth::id());

        if ($user->hasRoleAdmin($this->adminRoles) == 1) {

            $form = FormOrgUnits::find($form_id);


            return collect($form->docs);
        } else {

            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }
    }


    public function createEmailUnitForm(Request $request)
    {
        $user = User::find(Auth::id());

        if ($user->hasRoleAdmin($this->adminRoles) == 1) {

            // dd($request);

            $email = new OrgUnitEmail;
            $email->form_org_unit_id = $request->form_id;
            $email->save();

            return collect($email);
        
        } else {

            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }
    }

    public function editEmailUnitForm(Request $request)
    {
        $user = User::find(Auth::id());

        if ($user->hasRoleAdmin($this->adminRoles) == 1) {


            $email = OrgUnitEmail::find($request->email_id);
            $email->email = $request->email;
            $email->save();

            return collect($email);
        
        } else {

            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }

    }

    public function deleteEmailUnitForm(Request $request, $email_id)
    {
        $user = User::find(Auth::id());

        if ($user->hasRoleAdmin($this->adminRoles) == 1) {
            $email = OrgUnitEmail::find($email_id);
            $email->delete();
            return response()->json([
                'message' => 'email успешно удален..'
            ], 202);
        } else {

            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }
    }

    public function createSiteUnitForm(Request $request)
    {
        $user = User::find(Auth::id());
        if ($user->hasRoleAdmin($this->adminRoles) == 1) {
            $site = new OrgUnitSite;
            $site->form_org_unit_id = $request->form_id;
            $site->save();
            return collect($site);
        } else {

            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }
    }


    public function deleteSiteUnitForm(Request $request, $site_id)
    {
        $user = User::find(Auth::id());
        if ($user->hasRoleAdmin($this->adminRoles) == 1) {
            $site = OrgUnitSite::find($site_id);
            $site->delete();
            return response()->json([
                'message' => 'site успешно удален..'
            ], 202);
        } else {
            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }
    }


    public function editSiteUnitForm(Request $request)
    {
        $user = User::find(Auth::id());

        if ($user->hasRoleAdmin($this->adminRoles) == 1) {

            $site = OrgUnitSite::find($request->site_id);
            $site->site = $request->site;
            $site->save();

            return collect($site);
        
        } else {

            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }

    }


    public function getUnitForm(Request $request, $form_id)
    {
        $user = User::find(Auth::id());

        if ($user->hasRoleAdmin($this->adminRoles) == 1) {
            $unit = FormOrgUnits::find($form_id);

            $info['data'] = $unit; // ?? ?? ??

            return $info['data']; // !! !! ??

        } else {
            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }
    }


    public function deleteUnitForm(Request $request, $form_id)
    {

        $user = User::find(Auth::id());

        if ($user->hasRoleAdmin($this->adminRoles) == 1) {
            $unit = FormOrgUnits::find($form_id);
            
            $unit->docs()->delete();
            $unit->emails()->delete();
            $unit->sites()->delete();
            $unit->delete();

            return response()->json([
                'message' => 'Структурноe подразделние успешно удалено..'
            ], 202);

        } else {
            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }

    }

    //удалить документ pdf внутри структурного подразделения
    public function deleteUnitFormDocument(Request $request,  $doc_id){

        $user = User::find(Auth::id());

        if ($user->hasRoleAdmin($this->adminRoles) == 1) {
            $doc = OrgUnitDoc::find($doc_id);

            if(!isset($doc) ) {

                return response()->json([
                    'message' => 'Не найден документ или он был удален ранее..'
                ], 404);
            }

            $media_pdf = Media::find($doc->media_id);

            if(!isset($media_pdf) ) {

                return response()->json([
                    'message' => 'Не найден документ или он был удален ранее..'
                ], 404);
            }

            $media_pdf->delete();
            $doc->delete();

            return response()->json([
                'message' => 'Документ успешно удален из структурного подразделения..',
                'code' => '202'
            ], 202);


        } else {
            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }
    }


    //VACANT PLACES
    public function createVacantPlacesForm(Request $request)
    {   
        $user = User::find(Auth::id());
        if ($user->hasRoleAdmin($this->adminRoles) == 1) {
            
            $vacant = new FormVacantPlace;
            $vacant->admin_section_spoiler_id = $request->spoiler_id;
            $vacant->type_places = $request->type_places;
            // $vacant->course_id = $request->course_id;
            $vacant->save();

            return $vacant;
        }
    }

    public function editVacantPlacesForm(Request $request)
    {   
        $user = User::find(Auth::id());
        if ($user->hasRoleAdmin($this->adminRoles) == 1) {
            
            $vacant = FormVacantPlace::find($request->form_id);
            $vacant->course_id = $request->course_id;
            $vacant->title = $request->title;
            $vacant->save();

            return $vacant;
        }

    }

    public function deleteVacantPlacesForm(Request $request, $form_id)
    {
        $user = User::find(Auth::id());
        if ($user->hasRoleAdmin($this->adminRoles) == 1) {
            $vacant = FormVacantPlace::find($form_id);
            $vacant->delete();

            return response()->json([
                'message' => 'Форма удалена..',
                'code' => '202'
            ], 202);

        }

    }


    // PROGRAMM EDU
    public function createEduProgrammForm(Request $request)
    {
        $user = User::find(Auth::id());

        if ($user->hasRoleAdmin($this->adminRoles) == 1) {
            $programm  = new FormEduProgram;

            $programm->admin_section_spoiler_id = $request->spoiler_id;
            $programm->save();

            return $programm;

        } else {
            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }
    }



    public function editEduProgrammForm(Request $request)
    {
        $user = User::find(Auth::id());

        if ($user->hasRoleAdmin($this->adminRoles) == 1) {
            
            $programm  = FormEduProgram::find($request->form_id);

            $programm->title = $request->title;
            $programm->description = $request->description;
           
            $programm->save();


            return $programm;

        } else {
            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }
    }


    public function getEduProgrammForm(Request $request, $form_id)
    {
        $user = User::find(Auth::id());

        if ($user->hasRoleAdmin($this->adminRoles) == 1) {
            $programm  = FormEduProgram::find($form_id);
            return $programm;

        } else {
            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }
    }


    public function getEduAllProgrammForm(Request $request)
    {
    
        return FormEduProgram::all();

    }



    public function deleteEduProgrammForm(Request $request, $form_id)
    {
        $user = User::find(Auth::id());
        if ($user->hasRoleAdmin($this->adminRoles) == 1) {
            $programm  = FormEduProgram::find($form_id);
            if($programm == null){
                return response()->json([
                    'message' => 'Образовательная программа не существует или удалена ранее..',
                    'code' => '404'
                ], 404);
            }
            
            $media = Media::all();
            $docs = Media::all();
            //сносим документы в программе
            if(count($programm->docs) > 0) {
                $pdf_ids = [];
                $doc_ids = [];
                foreach ($programm->docs as $doc) {
                    $pdf_ids = array_push($pdf_ids, $doc->media_id);
                    $doc_ids = array_push($doc_ids, $doc->id);
                }
            }
            $media->whereIn('id', $pdf_ids)->delete();
            $programm->docs()->whereIn('id', $doc_ids)->delete();
            //удалим связь c директорами
            $programm->directors()->detach();
            //и в конце саму программу
            $programm->delete();

            return response()->json([
                'message' => 'Образовательная программа удалена..',
                'code' => '202'
            ], 202);

        } else {
            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }
    }

    // удаление дока pdf из программы
    public function deleteDocEduProgramForm(Request $request, $doc_id)
    {
        $user = User::find(Auth::id());
        if ($user->hasRoleAdmin($this->adminRoles) == 1) {

            $doc  = EduProgramDoc::find($doc_id);
            $pdf = Media::find($doc->media_id);

            $pdf->delete();

            $doc->delete();
            

            return response()->json([
                'message' => 'Документ удален..'
            ], 202);


        } else {
            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }
    }



    public function getCourseListEduProgrammForm(Request $request)
    {
        $courses = Course::all();
        return CourseOnlyNameResource::collection($courses);
    }



    // MATERIAL EQUIPMENT FORM

    public function createMatEquipForm(Request $request)
    {
        $user = User::find(Auth::id());

        if ($user->hasRoleAdmin($this->adminRoles) == 1) {
            $equip  = new FormMaterialEquipment;
            $equip->admin_section_spoiler_id = $request->spoiler_id;
            $equip->save();

            return $equip;

        } else {
            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }

    }

    public function editMatEquipForm(Request $request)
    {
        $user = User::find(Auth::id());

        if ($user->hasRoleAdmin($this->adminRoles) == 1) {
            $equip  = FormMaterialEquipment::find($request->form_id);
            $equip->title_block = $request->title_block;
            $equip->title_position = $request->title_position;
            $equip->desc_position = $request->desc_position;
           
            $equip->save();

            return $equip;

        } else {
            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }

    }

    public function deleteMatEquipForm(Request $request, $form_id)
    {
        $user = User::find(Auth::id());

        if ($user->hasRoleAdmin($this->adminRoles) == 1) {
            $equip  = FormMaterialEquipment::find($form_id);

            $equip->delete();

            return response()->json([
                'message' => 'Образовательная программа удалена..'
            ], 202);

        } else {
            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }

    }


    public function getMatEquipForm(Request $request, $form_id)
    {
        $user = User::find(Auth::id());

        if ($user->hasRoleAdmin($this->adminRoles) == 1) {
            $equip  = FormMaterialEquipment::find($form_id);

            return $equip;



        } else {
            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }

    }



    // FELLOWSHIPS AND SUPPORT MEASURES

    public function createFellowMeasureForm(Request $request)
    {
        $user = User::find(Auth::id());

        if ($user->hasRoleAdmin($this->adminRoles) == 1) {
            $fellowship  = new FormFellowshipMeasure;
            $fellowship->admin_section_spoiler_id = $request->spoiler_id;
            $fellowship->save();
            return $fellowship;

        } else {
            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }
    }

    public function editFellowMeasureForm(Request $request)
    {
        $user = User::find(Auth::id());

        if ($user->hasRoleAdmin($this->adminRoles) == 1) {
            $fellowship  =  FormFellowshipMeasure::find($request->form_id);
            $fellowship->title = $request->title ?? null;
            $fellowship->sub_title = $request->sub_title ?? null;
            $fellowship->description = $request->description ?? null;
            $fellowship->address = $request->address ?? null;
            $fellowship->count_seats = $request->count_seats ?? 0;

            $fellowship->save();
            return $fellowship;

        } else {
            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }

    }


    public function deleteFellowMeasureForm(Request $request)
    {
        $user = User::find(Auth::id());

        if ($user->hasRoleAdmin($this->adminRoles) == 1) {
            $fellowship  =  FormFellowshipMeasure::find($request->form_id);

            $fellowship->delete();
            
            return response()->json([
                'message' => 'Стипендия удалена..'
            ], 202);


        } else {
            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }

    }


    public function getFellowMeasureForm(Request $request)
    {
        $user = User::find(Auth::id());

        if ($user->hasRoleAdmin($this->adminRoles) == 1) {
            $fellowship  =  FormFellowshipMeasure::find($request->form_id);
            return $fellowship;
        } else {
            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }
    }


    //DATA DIRECTOR (1)

    public function createDataDirectorForm(Request $request)
    {
        $user = User::find(Auth::id());

        if ($user->hasRoleAdmin($this->adminRoles) == 1) {
            $boss  = new FormDataDirector;
            $boss->admin_section_spoiler_id = $request->spoiler_id;
            $boss->save();
            return $boss;

        } else {
            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }
    }


    public function editDataDirectorForm(Request $request)
    {
        $user = User::find(Auth::id());

        if ($user->hasRoleAdmin($this->adminRoles) == 1) {
            $boss  = FormDataDirector::find($request->form_id);

            $boss->title = $request->title ?? null;
            $boss->director_name = $request->director_name ?? null;
            $boss->director_position = $request->director_position ?? null;
            $boss->contact_phone = $request->contact_phone ?? null;
            $boss->email = $request->email ?? null;
            $boss->save();
            return $boss;

        } else {
            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }
    }


    public function getDataDirectorForm(Request $request, $form_id)
    {
        $user = User::find(Auth::id());

        if ($user->hasRoleAdmin($this->adminRoles) == 1) {
            $boss  = FormDataDirector::find($form_id);

            return $boss;

        } else {
            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }
    }


    public function deleteDataDirectorForm(Request $request, $form_id)
    {
        $user = User::find(Auth::id());

        if ($user->hasRoleAdmin($this->adminRoles) == 1) {
            $boss  = FormDataDirector::find($form_id);
            $boss->delete();
            
            return response()->json([
                'message' => 'Форма руководителя удалена..'
            ], 202);


        } else {
            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }
    }


    //EDU DIRECTOR(2)

    public function createEduDirectorForm(Request $request)
    {
        $user = User::find(Auth::id());

        if ($user->hasRoleAdmin($this->adminRoles) == 1) {
            $boss  = new FormDataDirectorEdu;
            $boss->admin_section_spoiler_id = $request->spoiler_id;
            $boss->save();
            return $boss;

        } else {
            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }
    }


    public function editEduDirectorForm(Request $request)
    {
        $user = User::find(Auth::id());

        if ($user->hasRoleAdmin($this->adminRoles) == 1) {
            $boss  = FormDataDirectorEdu::find($request->form_id);

            $boss->title_fio = $request->title_fio ?? null;
            $boss->current_position = $request->current_position ?? null;
            $boss->level_education = $request->level_education ?? null;
            $boss->total_work_experience = $request->total_work_experience ?? null;
            $boss->professional_experience = $request->professional_experience ?? null;
            $boss->direction_or_speciality = $request->direction_or_speciality ?? null;
            $boss->academic_degree = $request->academic_degree ?? null;
            $boss->refresher_vocational_training = $request->refresher_vocational_training ?? null;
            $boss->description_director = $request->description_director ?? null;


            $boss->save();
            return $boss;

        } else {
            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }
    }


    public function getEduDirectorForm(Request $request, $form_id)
    {
        $user = User::find(Auth::id());

        if ($user->hasRoleAdmin($this->adminRoles) == 1) {
            $boss  = FormDataDirectorEdu::find($form_id);

            return $boss;

        } else {
            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }
    }


    public function deleteEduDirectorForm(Request $request, $form_id)
    {
        $user = User::find(Auth::id());
        if ($user->hasRoleAdmin($this->adminRoles) == 1) {
            $boss  = FormDataDirectorEdu::find($form_id);
            if($boss == null){
                return response()->json([
                    'message' => 'Форма директора не существует или удалена ранее..',
                    'code' => '404'
                ], 404);
            }

            //уберем программы для этого руководства
            $boss->programms()->detach();

            $boss->delete();

            return response()->json([
                'message' => 'Форма руководителя удалена..',
                'code' => '202'
            ], 202);


        } else {
            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }
    }

    public function getProgrammsEduDirectorForm(Request $request, $form_id)
    {
        $user = User::find(Auth::id());
        if ($user->hasRoleAdmin($this->adminRoles) == 1) {
            $form  = FormDataDirectorEdu::find($form_id);
            // $form->delete();
            

            return $form->programms;


        } else {
            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }
    }



    public function attachProgrammsEduDirectorForm(Request $request)
    {
        $user = User::find(Auth::id());
        if ($user->hasRoleAdmin($this->adminRoles) == 1) {
            $form  = FormDataDirectorEdu::find($request->form_id);
            $pr = $request->progs;
            $progs = explode(",", $pr);   
            // dd($progs);
            $form->programms()->sync($progs);
            return $form->programms;

        } else {
            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }
    }


    public function crateDocForm(Request $request)
    {
        $user = User::find(Auth::id());
        if ($user->hasRoleAdmin($this->adminRoles) == 1) {
            $form  = new FormDocument;
            $form->admin_section_spoiler_id = $request->spoiler_id;
            $form->type_doc_form = $request->type_form;
            $form->save(); 


            return $form;

        } else {
            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }
    }

    public function editDocForm(Request $request)
    {
        $user = User::find(Auth::id());
        if ($user->hasRoleAdmin($this->adminRoles) == 1) {

            $form  = FormDocument::find($request->form_id);


            $form->title = $request->title;
            $form->description = $request->description;
            $form->save(); 

            return $form;

        } else {
            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }
    }



    public function getDocForm(Request $request, $form_id)
    {
        $user = User::find(Auth::id());
        if ($user->hasRoleAdmin($this->adminRoles) == 1) {

            $form  = FormDocument::find($form_id); 

            return $form;

        } else {
            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }
    }


    public function deleteDocForm(Request $request, $form_id)
    {
        $user = User::find(Auth::id());
        if ($user->hasRoleAdmin($this->adminRoles) == 1) {
            $form  = FormDocument::find($form_id); 
            $form->delete();
            return response()->json([
                'message' => 'Форма с документом удалена..'
            ], 202);
        } else {
            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }
    }

    

    public function deletePdfDocForm(Request $request, $doc_id)
    {

        $user = User::find(Auth::id());

        if ($user->hasRoleAdmin($this->adminRoles) == 1) {
            $doc = FormDocumentDoc::find($doc_id);

            if(!isset($doc)) {
                return response()->json([
                    'message' => 'Не найден документ или он был удален ранее..'
                ], 404);
            }

            $media_pdf = Media::find($doc->media_id);

            if(!isset($media_pdf) ) {

                return response()->json([
                    'message' => 'Не найден документ или он был удален ранее..'
                ], 404);
            }

            $media_pdf->delete();
            $doc->delete();

            return response()->json([
                'message' => 'Документ успешно удален из формы с годом..',
                'code' => '202'
            ], 202);


        } else {
            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }
    }


    // ENVIRONMENTS

    public function createEnvForm(Request $request)
    {
        $user = User::find(Auth::id());
        if ($user->hasRoleAdmin($this->adminRoles) == 1) {

            $env  = new FormAccesibleEnv;
            $env->admin_section_spoiler_id = $request->spoiler_id;
            $env->save();
            return $env;

        } else {
            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }
    }
    public function editEnvForm(Request $request)
    {
        $user = User::find(Auth::id());
        if ($user->hasRoleAdmin($this->adminRoles) == 1) {
            $env  = FormAccesibleEnv::find($request->form_id);
            $env->title = $request->title;
            $env->save();
            return $env;
        } else {
            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }
    }

    public function getEnvForm(Request $request, $form_id)
    {
        $user = User::find(Auth::id());
        if ($user->hasRoleAdmin($this->adminRoles) == 1) {

            $env  = FormAccesibleEnv::find($form_id);

            return $env;

        } else {
            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }
    }

    public function deleteEnvForm(Request $request, $form_id)
    {
        $user = User::find(Auth::id());
        if ($user->hasRoleAdmin($this->adminRoles) == 1) {

            $env  = FormAccesibleEnv::find($form_id);
            $env->delete();

            return response()->json([
                'message' => 'Форма доступной среды удалена..'
            ], 202);

        } else {
            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }
    }

    public function editImageDescEnvForm(Request $request)
    {   
        $user = User::find(Auth::id());
        if ($user->hasRoleAdmin($this->adminRoles) == 1) {

            $imageDB  = AccessibleEnvDoc::find($request->image_id);
            $imageDB->image_description = $request->image_description;
            $imageDB->save();

            return response()->json([
                'message' => 'Изменено описание для картинки..',
                'code' => '202'
            ], 202);

        } else {
            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }

    }

    public function deleteImageEnvForm(Request $request, $image_id)
    {
        $user = User::find(Auth::id());

        if ($user->hasRoleAdmin($this->adminRoles) == 1) {

            $imageDB  = AccessibleEnvDoc::find($image_id);
            if(Media::find($imageDB->media_id) !== null  )  {

                 Media::find($imageDB->media_id)->delete();
            }
            $imageDB->delete();

            return response()->json([
                'message' => 'Изображение удалено..',
                'code' => '202'
            ], 202);


        } else {
            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }
    }

    //EDUCATION

    public function crateEduForm(Request $request)
    {
        $user = User::find(Auth::id());
        if ($user->hasRoleAdmin($this->adminRoles) == 1) {

            $edu  = new FormEducation;
            $edu->admin_section_spoiler_id = $request->spoiler_id;
            $edu->save();

            return $edu;

        } else {
            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }
    }


    public function editEduForm(Request $request)
    {
        $user = User::find(Auth::id());
        if ($user->hasRoleAdmin($this->adminRoles) == 1) {
            $edu  = FormEducation::find($request->form_id);

            // $edu->title_speciality = $request->title_speciality;
            // $edu->direction_training = $request->direction_training;
            // $edu->title_programm = $request->title_programm;
            // $edu->level_education = $request->level_education;
            // $edu->implemented_forms_education = $request->implemented_forms_education;
            // $edu->description_programm = $request->description_programm;
            // $edu->academic_plan = $request->academic_plan;
            // $edu->calendar_training_schedule = $request->calendar_training_schedule;
            // $edu->description = $request->description;
            // $edu->teachers = $request->teachers;
            
            $edu->course_id = $request->course_id;
            $edu->save();

            return $edu;

        } else {
            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }
    }


    public function getEduForm(Request $request, $form_id)
    {
        $user = User::find(Auth::id());
        if ($user->hasRoleAdmin($this->adminRoles) == 1) {

            $edu  = FormEducation::find($form_id);

            return $edu;

        } else {
            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }
    }

    public function deleteEduForm(Request $request, $form_id)
    {
        $user = User::find(Auth::id());
        if ($user->hasRoleAdmin($this->adminRoles) == 1) {

            $edu  = FormEducation::find($form_id);
            $edu->delete();

            return response()->json([
                'message' => 'Форма обучения удалена..'
            ], 202);

        } else {
            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }
    }


    
    //INTERNATIONAL COOPERATION

    public function crateCoopForm(Request $request)
    {
        $user = User::find(Auth::id());
        if ($user->hasRoleAdmin($this->adminRoles) == 1) {

            $coop  = new FormInternationalCooperation;
            $coop->admin_section_spoiler_id = $request->spoiler_id;
            $coop->save();

            return $coop;

        } else {
            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }
    }

    public function editCoopForm(Request $request)
    {
        $user = User::find(Auth::id());
        if ($user->hasRoleAdmin($this->adminRoles) == 1) {

            $coop  = FormInternationalCooperation::find($request->form_id);
            $coop->title_form = $request->title_form;
            $coop->name = $request->name;
            $coop->save();
            return $coop;
        } else {
            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }
    }

    public function getCoopForm(Request $request, $form_id)
    {
        $user = User::find(Auth::id());
        if ($user->hasRoleAdmin($this->adminRoles) == 1) {

            $coop  = FormInternationalCooperation::find($form_id);

            return $coop;

        } else {
            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }
    }


    public function deleteCoopForm(Request $request, $form_id)
    {
        $user = User::find(Auth::id());
        if ($user->hasRoleAdmin($this->adminRoles) == 1) {

            $coop  = FormInternationalCooperation::find($form_id);
            $coop->delete();

            return response()->json([
                'message' => 'Форма международного сотрудничества удалена..'
            ], 202);

        } else {
            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }
    }

    public function deleteImageCoopForm(Request $request, $image_id)
    {
        $user = User::find(Auth::id());
        if ($user->hasRoleAdmin($this->adminRoles) == 1) {

            $doc = InternationalCooperationImage::find($image_id);

            $media = Media::find($doc->media_id);

            $media->delete();
            $doc->delete();


            return response()->json([
                'message' => 'Удалено..'
            ], 202);

            // if((new MainService)->deleteMedia(FormInternationalCooperation::COOPERATION_INTER_IMAGES, $image->title_image) ) {

            //     $image->delete();

            //     return response()->json([
            //         'message' => 'удалено..'
            //     ], 201);
            // }

        } else {
            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }
    }

    //FINANCIAL SOURCE
    public function crateFinancialSourceForm(Request $request)
    {
        $user = User::find(Auth::id());
        if ($user->hasRoleAdmin($this->adminRoles) == 1) {

            $fsource  = new FormFinancialSource;
            $fsource->admin_section_spoiler_id = $request->spoiler_id;
            $fsource->save();

            return $fsource;

        } else {
            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }
    }


    public function editFinancialSourceForm(Request $request)
    {
        $user = User::find(Auth::id());
        if ($user->hasRoleAdmin($this->adminRoles) == 1) {

            $fsource  = FormFinancialSource::find($request->form_id);
            $fsource->title_form = $request->title_form;

            $fsource->save();
            return $fsource;
        } else {
            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }
    }

    public function getFinancialSourceForm(Request $request, $form_id)
    {
        $user = User::find(Auth::id());
        if ($user->hasRoleAdmin($this->adminRoles) == 1) {

            $fsource  = FormFinancialSource::with('years')->find($form_id);
            // dd($fsource);

            return $fsource;

        } else {
            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }

    }

    
    public function deleteFinancialSourceForm(Request $request, $form_id){
        $user = User::find(Auth::id());
        if ($user->hasRoleAdmin($this->adminRoles) == 1) {

            $form  = FormFinancialSource::find($form_id);
            $form->delete();
            
            return response()->json([
                'message' => 'Источник финансирования удален..'
            ], 202);

        } else {
            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }
    }





    // YEAR
    public function crateYearFinancialSourceForm(Request $request)
    {
        $user = User::find(Auth::id());
        if ($user->hasRoleAdmin($this->adminRoles) == 1) {

            $year  = new FinancialYear;
            $year->form_financial_source_id = $request->form_id;
            $year->save();

            return $year;

        } else {
            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }
    }

    public function editYearFinancialSourceForm(Request $request)
    {
        $user = User::find(Auth::id());
        if ($user->hasRoleAdmin($this->adminRoles) == 1) {

            $year  = FinancialYear::find($request->year_id);
            $year->year_start = date($request->year_start);
            $year->financial_summ = $request->financial_summ;


            $year->save();
            
            return $year;

        } else {
            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }
    }


    public function deleteYearFinancialSourceForm(Request $request, $fsource_id)
    {
        $user = User::find(Auth::id());
        if ($user->hasRoleAdmin($this->adminRoles) == 1) {

            $year  = FinancialYear::find($fsource_id);
            $year->delete();
            
            return response()->json([
                'message' => 'Год удален..'
            ], 202);

        } else {
            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }
    }


    // FINANCIAL ECONOMIC ACTIVITY PLAN 

    public function crateActivityPlanForm(Request $request)
    {
        $user = User::find(Auth::id());
        if ($user->hasRoleAdmin($this->adminRoles) == 1) {

            $plan  = new FormEconomicActivityPlan;
            $plan->admin_section_spoiler_id = $request->spoiler_id;
            $plan->save();

            return $plan;

        } else {
            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }
    }

    public function editActivityPlanForm(Request $request)
    {
        $user = User::find(Auth::id());
        if ($user->hasRoleAdmin($this->adminRoles) == 1) {

            $plan  = FormEconomicActivityPlan::find($request->form_id);
            $plan->title_form = $request->title_form;
            $plan->description = $request->description;
            $plan->display_as = $request->display_as;
            $plan->save();
              
            return $plan;

        } else {
            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }
    }

    public function deleteActivityPlanForm(Request $request, $form_id)
    {
        $user = User::find(Auth::id());
        if ($user->hasRoleAdmin($this->adminRoles) == 1) {

            $plan  = FormEconomicActivityPlan::find($form_id);
            $plan->delete();

            return response()->json([
                'message' => 'Форма удалена..'
            ], 202);

        } else {
            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }
    }

    public function getActivityPlanForm(Request $request, $plan_id)
    {
        $user = User::find(Auth::id());
        if ($user->hasRoleAdmin($this->adminRoles) == 1) {

            $plan  = FormEconomicActivityPlan::with('years')->find($plan_id);

            return $plan;

        } else {
            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }

    }


    //PLAN YEAR 
    public function getPlanYearForm(Request $request, $year_id)
    {
        $user = User::find(Auth::id());
        if ($user->hasRoleAdmin($this->adminRoles) == 1) {
            $year  = ActivityPlanYear::with('document_in_year')->find($year_id);
            return $year;
        } else {
            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }

    }

    public function createPlanYearForm(Request $request)
    {
        $user = User::find(Auth::id());
        if ($user->hasRoleAdmin($this->adminRoles) == 1) {
            $year  = new ActivityPlanYear;
            $year->form_economic_activity_plan_id = $request->form_id;
            // $year->year = $request->year;
            $year->save();
            return $year;

        } else {
            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }
    }

    public function editPlanYearForm(Request $request)
    {
        $user = User::find(Auth::id());
        if ($user->hasRoleAdmin($this->adminRoles) == 1) {
            $year  = ActivityPlanYear::find($request->year_id);
            // $year->form_economic_activity_plan_id = $request->form_id;
            $year->year = $request->year;
            $year->save();
            return $year;
        } else {
            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }

    }



    public function deletePlanYearForm(Request $request, $year_id)
    {
        $user = User::find(Auth::id());
        if ($user->hasRoleAdmin($this->adminRoles) == 1) {
            $year  = ActivityPlanYear::find($year_id);
            $year->delete();
            
            return response()->json([
                'message' => 'Форма с годом удалена..'
            ], 202);


        } else {
            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }
    }


    // СЕКЦИЯ ДОКУМЕНТОВ В ГОДУ (ПЛАН ФИНАНСОВО ХОЗ. ДЕЯТЕЛЬНОСТИ)
    public function createSectionYearDocumentForm(Request $request)
    {
        $user = User::find(Auth::id());
        if ($user->hasRoleAdmin($this->adminRoles) == 1) {
            $section  = new SectionYearDocument;
            $section->activity_plan_year_id = $request->year_id; 
            $section->save();
            return $section;

        } else {
            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }
    }


    public function editSectionYearDocumentForm(Request $request)
    {
        $user = User::find(Auth::id());
        if ($user->hasRoleAdmin($this->adminRoles) == 1) {
            $section  = SectionYearDocument::find($request->section_id);
            $section->section_title = $request->title; 
            $section->save();
            return $section;

        } else {
            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }
    }


    public function deleteSectionYearDocumentForm(Request $request, $section_id)
    {
        $user = User::find(Auth::id());
        if ($user->hasRoleAdmin($this->adminRoles) == 1) {
            $section  = SectionYearDocument::find($section_id);
            $section->delete();
            
            return response()->json([
                'message' => 'Секция с документами удалена..'
            ], 202);


        } else {
            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }
    }

    //удалить документ из секции
    public function deleteDocInSectionYear(Request $request, $doc_id)
    {

        $user = User::find(Auth::id());

        if ($user->hasRoleAdmin($this->adminRoles) == 1) {
            $doc = SectionYearDoc::find($doc_id);

            if(!isset($doc) ) {
                return response()->json([
                    'message' => 'Не найден документ или он был удален ранее..'
                ], 404);
            }

            $media_pdf = Media::find($doc->media_id);

            if(!isset($media_pdf) ) {

                return response()->json([
                    'message' => 'Не найден документ или он был удален ранее..'
                ], 404);
            }

            $media_pdf->delete();
            $doc->delete();

            return response()->json([
                'message' => 'Документ успешно удален из формы с годом..',
                'code' => '202'
            ], 202);


        } else {
            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }
    }


    public function editDocInSectionYear(Request $request)
    {
        $user = User::find(Auth::id());

        if ($user->hasRoleAdmin($this->adminRoles) == 1) {
            $doc = SectionYearDoc::find($request->doc_id);

            $doc->year_doc = $request->year;
            $doc->save();
        
            return $doc;


        } else {
            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }
    }




    //FORM ADMIN SPECIALITY
    public function createSpecialityForm(Request $request)
    {
        $user = User::find(Auth::id());
        if ($user->hasRoleAdmin($this->adminRoles) == 1) {
            $speciality  = new FormSpeciality;
            $speciality->admin_section_spoiler_id = $request->spoiler_id;
            $speciality->save();
            return $speciality;


        } else {
            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }
    }

    public function editSpecialityForm(Request $request)
    {
        $user = User::find(Auth::id());
        if ($user->hasRoleAdmin($this->adminRoles) == 1) {
            $speciality  = FormSpeciality::find($request->form_id);

            $speciality->title_speciality = $request->title;
            $speciality->vacant_places_federal_budget = $request->vacant_places_federal_budget;
            $speciality->vacant_places_subject_rf_budget = $request->vacant_places_subject_rf_budget;
            $speciality->vacant_places_local_budget = $request->vacant_places_local_budget;
            $speciality->vacant_places_legal_individ_budget = $request->vacant_places_legal_individ_budget;

            $speciality->save();
            return $speciality;


        } else {
            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }
    }



    public function getSpecialityForm(Request $request, $speciality_id)
    {
        $user = User::find(Auth::id());
        if ($user->hasRoleAdmin($this->adminRoles) == 1) {
            $spec  = FormSpeciality::find($speciality_id);

            return $spec;

        } else {
            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }
    }


    public function deleteSpecialityForm(Request $request, $speciality_id)
    {
        $user = User::find(Auth::id());
        if ($user->hasRoleAdmin($this->adminRoles) == 1) {
            $spec  = FormSpeciality::find($speciality_id);

            $spec->delete();


            return response()->json([
                'message' => 'Форма специальности удалена..'
            ], 202);



        } else {
            return response()->json([
                'message' => 'Вы не можете этого сделать..'
            ], 422);
        }
    }

    



    



}
