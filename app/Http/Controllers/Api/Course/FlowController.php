<?php

namespace App\Http\Controllers\Api\Course;

use App\Http\Controllers\Controller;
use App\Http\Requests\CourseAddFlowRequest;
use App\Http\Requests\FlowCRUDRequest;
use App\Models\Course\Flow;
use App\Services\FlowService;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Http\Request;

class FlowController extends Controller
{
    use ApiResponser;

    private $flowService;

    public function __construct(FlowService $flowService)
    {
        $this->flowService = $flowService;
    }

    public function delete(FlowCRUDRequest $request)
    {
        try{
            $flow = Flow::findOrFail($request->id);
            $this->flowService->delete($flow);
            return $this->success('Поток удалён');
        }catch(Exception $e){
            return $this->error(404, 'Поток не удалён', null, $e);
        }
    }
}
