<?php

namespace Database\Seeders;

use App\Models\EduOrganization;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EduOrganizationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EduOrganization::insert([
            [
                'user_id' => 1,
                'name' => 'ООО "Квалифитерра"',
                'legal_address' => '125124, РОССИЯ, МОСКВА Г., МУНИЦИПАЛЬНЫЙ ОКРУГ БЕГОВОЙ ВН.ТЕР.Г., 3-Я ЯМСКОГО ПОЛЯ УЛ., Д. 2, К. 13, ЭТАЖ 4, ПОМЕЩ./КОМ. XII/21',
                'actual_address' => '125124, РОССИЯ, МОСКВА Г., МУНИЦИПАЛЬНЫЙ ОКРУГ БЕГОВОЙ ВН.ТЕР.Г., 3-Я ЯМСКОГО ПОЛЯ УЛ., Д. 2, К. 13, ЭТАЖ 4, ПОМЕЩ./КОМ. XII/21',
                'INN' => '7714436684',
                'KPP' => '772801001',
                'OGRN' => '5187746031260',
                'bank' => 'АО "АЛЬФА-БАНК"',
                'BIK' => '044525593',
                'corr_account' => '30101810200000000593',
                'сhecking_account' => '40702810602160002736',
                'general_director' => 'Овсянников Константин Олегович',
                'phone' => '8-495-032-21-12',
                'email' => 'info@q-terra.ru',
                'image' => 'beautiful-paradise-beach-865434-wallpaper-preview.jpg',
                'description' => 'Курсы от Квалифиттеры ваще ааагонь!!!',
            ],
            [
                'user_id' => 2,
                'name' => 'Яндекс',
                'legal_address' => '125124, РОССИЯ, МОСКВА Г., МУНИЦИПАЛЬНЫЙ ОКРУГ БЕГОВОЙ ВН.ТЕР.Г., 3-Я ЯМСКОГО ПОЛЯ УЛ., Д. 2, К. 13, ЭТАЖ 4, ПОМЕЩ./КОМ. XII/21',
                'actual_address' => '125124, РОССИЯ, МОСКВА Г., МУНИЦИПАЛЬНЫЙ ОКРУГ БЕГОВОЙ ВН.ТЕР.Г., 3-Я ЯМСКОГО ПОЛЯ УЛ., Д. 2, К. 13, ЭТАЖ 4, ПОМЕЩ./КОМ. XII/21',
                'INN' => '7714436684',
                'KPP' => '772801001',
                'OGRN' => '5187746031260',
                'bank' => 'АО "АЛЬФА-БАНК"',
                'BIK' => '044525593',
                'corr_account' => '30101810200000000593',
                'сhecking_account' => '40702810602160002736',
                'general_director' => 'Овсянников Константин Олегович',
                'phone' => '8-495-032-21-12',
                'email' => 'info@dsa.ru',
                'image' => 'beautiful-paradise-beach-865434-wallpaper-preview.jpg',
                'description' => 'Курсы для мажоров либо гиков',
            ],
            
        ]);
    }
}
