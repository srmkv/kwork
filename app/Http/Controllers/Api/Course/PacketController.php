<?php

namespace App\Http\Controllers\Api\Course;

use App\Http\Controllers\Controller;
use App\Http\Requests\PacketRequest;
use App\Models\Course\Packet;
use App\Services\PacketService;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Http\Request;

class PacketController extends Controller
{
    use ApiResponser;

    private $packetService;

    public function __construct(PacketService $packetService)
    {
        $this->packetService = $packetService;
    }
    
    public function delete(PacketRequest $request)
    {
        try{
            $packet = Packet::findOrFail($request->id);
            $this->packetService->delete($packet);
            return $this->success('Пакет удалён из потока');
        }catch(Exception $e){
            return $this->error(404, 'Пакет не удалён', null, $e);
        }
    }
}
