<?php
namespace App\Services\Admin\SettingMainPage;

use App\Models\SettingMainPage\Stuff;
use App\Services\MainService;

class StuffService
{
    public function all()
    {   
        // return Stuff::all();

        return collect([
            'stuffs' => Stuff::all(),
            'subtitle' => \DB::table('stuff_subtitle')->first() ?? null
        ]);
    }

    public function update($data, $id)
    {
        $stuff =  Stuff::findOrFail($id);
        $stuff->fill($data);
        $stuff->save();
        return $stuff;
    }

    public function updateSubtitle($text)
    {   
        // dd($text);
        if(\DB::table('stuff_subtitle')->first() == null) {
            \DB::table('stuff_subtitle')->insert([
                'text' => $text
            ]);
        } else {
            \DB::table('stuff_subtitle')->where('id', 1)->update([
                'text' => $text
            ]);
        }


        return \DB::table('stuff_subtitle')->first();
    }


    public function create($data)
    {
        $stuff = new Stuff;
        $stuff->fill($data);
        $stuff->save();
        return $stuff;
    }

    public function delete($id)
    {
        $stuff =  Stuff::find($id);
        if($stuff == null ) {
            return response()->json([
                'message' => 'Не найдено сотрудника с таким ид..',
                'code' => 404
            ], 404);
        } else {
            (new MainService)->deleteMedia(Stuff::STUFF_PHOTO_PATH, basename($stuff->url));
            $stuff->delete();
            return response()->json([
                'message' => 'Сотрудник успешно удален..',
                'code' => 200
            ], 200);
        }
    }

    public function updatePhoto($img, $id) 
    {
        $stuff = Stuff::findOrFail($id);
        if(!$stuff->url == null){
            (new MainService)->deleteMedia(Stuff::STUFF_PHOTO_PATH, basename($stuff->url));
        }
        $result = (new MainService)->addMedia(Stuff::STUFF_PHOTO_PATH, $img);
        $stuff->url = $result->first()['url'];
        $stuff->save();
        return $stuff;        
    }

    public function deletePhotoStuff($id)
    {
        $stuff = Stuff::findOrFail($id);
        if(!$stuff->url == null){
            (new MainService)->deleteMedia(Stuff::STUFF_PHOTO_PATH, basename($stuff->url));

            $stuff->url = null;
            $stuff->save();
            return $stuff;
        } else {

            return response()->json([
                'message' => 'У сотрудника нет фото..',
                'code' => 403
            ],403);
        }
    }


}