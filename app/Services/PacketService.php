<?php

namespace App\Services;

use App\Models\Course\CourseSection;
use App\Models\Course\Flow;
use App\Models\Course\Packet;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class PacketService
{
    public function delete(Packet $packet)
    {
        $packet->descriptions()->delete();
        $packet->saleRules()->delete();
        $packet->delete();
    }

    public static function getFlowId($packet_id)
    {
        return \DB::table('packets')->where('id', $packet_id)->first()->flow_id;
    }
}