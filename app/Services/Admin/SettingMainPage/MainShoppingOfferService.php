<?php
namespace App\Services\Admin\SettingMainPage;
use App\Models\SettingMainPage\MainShoppingOffer;

use App\Http\Resources\Course\TextBlockResource;

class MainShoppingOfferService
{   
    public function __construct()
    {
        // $this->mainScreen = MainScreen::findOrFail(1);
    }

    public function create($data)
    {
        $offer = new MainShoppingOffer;
        $offer->description = $data['description'] ?? '';
        $offer->icon = $data['icon'] ?? '';
        $offer->icon_color = $data['icon_color'] ?? '#fff';
        $offer->save();
        return TextBlockResource::make($offer);
    }

    public function edit($data, $id)
    {   
        $offer =  MainShoppingOffer::findOrFail($id);
        $offer->fill($data);
        $offer->save();
        return TextBlockResource::make($offer);
    }

    public function delete($id)
    {   
        $offer =  MainShoppingOffer::findOrFail($id);
        $offer->delete();

        return response()->json([
            'message' => 'Утп успешно удален..',
            'code' => 200
        ], 200);
    }

    public function all()
    {
        return TextBlockResource::collection(MainShoppingOffer::all());
    }

}
