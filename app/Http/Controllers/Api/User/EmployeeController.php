<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\Profiles\ProfileIndividual;
use App\Models\Employee;
use App\Models\Employee\EmployeeRole;
use App\Models\Profiles\ProfileBuiseness;

use App\Http\Resources\Dashboard\MemberCompaniesResource;

use App\Services\User\EmployeeService;

class EmployeeController extends Controller
{   

    public function __construct(EmployeeService $employee)
    {
        $this->employee = $employee;
    }


    public function changeStatusEmployeer(Request $request, $profile_id)
    {
        $user = User::find(Auth::id());
        $status = $request->status;
        $type = $request->type;
        $company_id = $request->company_id;
        $employee = ProfileIndividual::find($profile_id);

        \DB::table('profilebles')->where('profile_id', $profile_id)
            ->where('profileble_id', $company_id)
            ->where('profiles_individuals_type', $type)
            ->update([
                'status' => $status
            ]);

        return $this->employee->getCompanies($profile_id);
    }


    public function memberCompany(Request $request)
    {
        $user = User::find(Auth::id());
        $employee = ProfileIndividual::where('user_id',$user->id)->first();

        return $this->employee ->getCompanies($employee->id);

    }


    public function employeeStatuses(Request $request)
    {
        $statuses = [

            'accept' => "Сотрудник принял приглашение",
            'cancel' => 'Приглашение отклонено, компания пропадает из списка тех в которые входит сотрудник',
            'waiting' => 'Дефолтное значение статуса, будет до тех пор пока не изменится',
            'left' => 'Покинул организацию',
            'kick' => 'Исключен самой организацией'
        ];


        return collect($statuses);
    }

    

}
