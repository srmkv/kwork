<?php

namespace App\Http\Controllers\Api\Course;

use App\Http\Controllers\Controller;
use App\Http\Requests\PacketSaleRuleRequest;
use App\Models\Course\PacketSaleRule;
use Illuminate\Http\Request;

class PacketSaleRuleController extends Controller
{
    public function delete(PacketSaleRuleRequest $request)
    {
        $rule = PacketSaleRule::findOrFail($request->id);
        $rule->delete();
        return response()->json('Пункт правил продажи удалён из раздела', 200);
    }
}
