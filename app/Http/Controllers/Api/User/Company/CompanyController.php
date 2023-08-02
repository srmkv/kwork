<?php

namespace App\Http\Controllers\Api\User\Company;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

use App\Models\User;
use App\Models\Employee\EmployeePermission;

use App\Models\Profiles\ProfileBuiseness;
use App\Models\Profiles\ProfileIndividual;
use App\Models\Profiles\ProfileSelfEmployed;

use App\Models\Employee;

use App\Http\Resources\User\EmployeesListResource;
use App\Http\Resources\User\EmploeeWithinCompanyResource;

use Spatie\MediaLibrary\MediaCollections\Models\Media;

use App\Services\User\BusinessService;


class CompanyController extends Controller
{
    public function testCompany(Request $request)
    {
        // return 600;




        $profileBusiness = ProfileBuiseness::find(30);
        $profileIp = ProfileSelfEmployed::find(1);
        $employee = Employee::find(23);

        // $employee = Employee::find(14);
         
        // foreach ($profileBusiness->employeesBusinsess as $emoloyee) {
        //     //
        // }

        //create

        // $employee = $profileIp->employees()->firstOrCreate([
        //         'phone' => '79923081201',
        //     ],
        //     [
        //         'employee_role_id' => 26,
        //         'individual_profile_id' => 30,
        //     ]
        // );

        // return $profileBusiness->employeesBusinsess->makeHidden('pivot');
        // return $profileIp->employees;



        return $employee->companiesBusiness->merge($employee->companiesIp);
        // return $employee->companiesIp;

    }
}
