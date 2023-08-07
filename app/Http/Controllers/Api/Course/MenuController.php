<?php

namespace App\Http\Controllers\Api\Course;

use App\Http\Controllers\Controller;
use App\Http\Requests\CatalogSectionRequest;
use App\Http\Resources\Course\MenuResource;
use App\Services\MenuService;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    private $menuService;

    public function __construct(MenuService $menuService)
    {
        $this->menuService = $menuService;
    }

    public function index(Request $request)
    {
        $sectionsMenu = $this->menuService->menu();
        return MenuResource::collection($sectionsMenu->get())->toArray($request);
    }

    public function section(CatalogSectionRequest $request)   
    {
        $result = $this->menuService->menuSection($request->id);     
        
        return response()->json($result, 200);     
    }
}