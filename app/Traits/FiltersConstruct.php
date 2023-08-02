<?php

namespace App\Traits;

use App\Models\Course\Course;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Api Responser Trait
|--------------------------------------------------------------------------
|
| This trait will be used for any response we sent to clients.
|
*/
/**
        Redis::setBit($key_bit_map, $offer_id, 1);
        Redis::hmSet('all_filters', $category_rewrite, $category_rewrite);

        Redis::pipeline(function ($pipe) {
            for ($i = 0; $i < 105000; $i++) {
                $pipe->setBit("color-green",$i, rand(0,1));
            }
          });

        Redis::bitOp($oper, $name_result, $arr_keys_for_redis_operation[$i][$j]);


        private static function bitmap_good($bitmap){
        $bytes = unpack('C*', $bitmap);
        $bin = join(array_map(function($byte){
            return sprintf("%08b", $byte);
        }, $bytes));
        return $bin;


        Redis::hGetAll('all_filters')


        //берём колличество позиций в сформированном выше битмапе со значениемм "1", это и есть колличество товаров для текущего перебираемого фильтра
        $count = Redis::bitCount('count_' . $final_result_name);

        Redis::keys('*');
        Redis::keys('*result_*');
        Redis::del($filter);
        Redis::exists($data['key'])

        public static function get_all_cache_keys()
        {
            $redis = Redis::connection('cache');
            return $redis->keys('*');
        }

    }

     * 
     */
trait FiltersConstruct
{
    protected function createBitMap(string $propertySlug, array $propertyValueSlugs, int $course_id)
    {
        // Redis::flushall();
        if($propertySlug && $propertyValueSlugs && $course_id){
            foreach($propertyValueSlugs as $propValSlug){
                $key = \Str::slug($propertySlug) . '_' . $propValSlug;
                Redis::setBit($key, $course_id, 1);
            }   
        }
    }
}