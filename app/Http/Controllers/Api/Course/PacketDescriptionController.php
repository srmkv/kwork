<?php

namespace App\Http\Controllers\Api\Course;

use App\Http\Controllers\Controller;
use App\Http\Requests\PacketDescriptionRequest;
use App\Models\Course\PacketDescription;
use Illuminate\Http\Request;

class PacketDescriptionController extends Controller
{
    public function delete(PacketDescriptionRequest $request)
    {
        $packet = PacketDescription::findOrFail($request->id);
        $packet->delete();
        return response()->json('Пункт описания удалён из раздела', 200);
    }
}
