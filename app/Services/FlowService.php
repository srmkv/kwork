<?php

namespace App\Services;

use App\Models\Course\CourseSection;
use App\Models\Course\Flow;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class FlowService
{
    private $courseSectionService;
    private $packetService;

    public function __construct(CourseSectionService $courseSectionService, PacketService $packetService)
    {
        $this->courseSectionService = $courseSectionService;
        $this->packetService = $packetService;
    }

    /**
     * Ближайший поток(группа)
     */
    public static function nearestFlow($flows)
    {
        if(!$flows){
            return null;
        }
        return $flows
        ->sortBy('start')
        ->filter(fn ($item) => isset($item['start']) ? Carbon::createFromFormat('Y-m-d', $item['start'])->isFuture() : null)
        ;
    }

    public static function infoForOrders($flows)
    {
        return $flows->get(['id', 'start', 'end', 'title', 'type_id']);
    }

    public function delete(Flow $flow)
    {
        foreach($flow->sections as $section){
            $this->courseSectionService->delete($section);
        }

        foreach($flow->packets as $packet){
            $this->packetService->delete($packet);
        }

        $flow->delete();
    }


    public static function getCourseId($flow_id)
    {
        return \DB::table('flows')->where('id', $flow_id)->first()->course_id;
    }
}