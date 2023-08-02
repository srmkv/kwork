<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdminSpecialitiesResource;
use App\Services\AdminService;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    private $adminService;

    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;    
    }

    public function index(Request $request)
    {
        $queryData = $this->adminService->createSpecialityQuery();
        
        $sortParams = $this->adminService->parseSortParams($request->sort);
        $specialities = $this->adminService->getSpecialitiesBySort($sortParams, $queryData->get());
        
        $searchParams = $this->adminService->parseSearchParams($request->search);
        if($searchParams){
            $specialities = $this->adminService->getSpecialitiesBySearch($specialities, $searchParams);
        }

        $paginate = $this->adminService->paginate($request, $specialities->count());

        $specialities->skip($paginate['offset'])->take($paginate['limit'])->get('*');

        return [
            'sort' => $request->sort,
            'page' => $paginate['page'],
            'offset' => $paginate['offset'],
            'totalCount' => $paginate['totalCount'],
            'limit' => $paginate['limit'],
            'per_page' => $paginate['per_page'],
            'data' => AdminSpecialitiesResource::collection($specialities),
        ];
    }
}
