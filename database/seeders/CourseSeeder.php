<?php

namespace Database\Seeders;

use App\Models\Course\Course;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Course::insert([
            [
                'admin_id' => 1,
                'is_published' => 1,
                'name' => 'Курсы кройки и шитья',
                'image' => 'beautiful-paradise-beach-865434-wallpaper-preview.jpg',
                'academic_hours' => 125,
                'academic_days' => 25,
                'study_form_id' => 1,
                'state_id' => 1,
                'days_before_start' => 5,
                'has_document' => 1,
                'description' => 'Учим не шить, а пришивать...',
                'required_docs' => '',
                'speciality_id' => 1,
                'course_category_id' => 13,
                // 'edu_organization_id' => 1, // Говорят что может быть не одна организация
                'price_id' => 1,
            ],

            [
                'admin_id' => 1,
                'is_published' => 0,
                'name' => 'Курсы PHP/Laravel',
                'image' => 'beautiful-paradise-beach-865434-wallpaper-preview.jpg',
                'academic_hours' => 205,
                'academic_days' => 35,
                'study_form_id' => 1,
                'state_id' => 1,
                'days_before_start' => 7,
                'has_document' => 1,
                'description' => 'PHP / Laravel / Vue2.js',
                'required_docs' => '',
                'speciality_id' => 1,
                'course_category_id' => 10,
                // 'edu_organization_id' => 1, // Говорят что может быть не одна организация
                'price_id' => 2,
            ],

            [
                'admin_id' => 1,
                'is_published' => 1,
                'name' => 'Курсы. Наряд для куклы из маминого платья',
                'image' => 'beautiful-paradise-beach-865434-wallpaper-preview.jpg',
                'academic_hours' => 205,
                'academic_days' => 35,
                'study_form_id' => 1,
                'state_id' => 1,
                'days_before_start' => 7,
                'has_document' => 1,
                'description' => 'Описание',
                'required_docs' => '',
                'speciality_id' => 1,
                'course_category_id' => 9,
                // 'edu_organization_id' => 1, // Говорят что может быть не одна организация
                'price_id' => 2,
            ],

            [
                'admin_id' => 1,
                'is_published' => 0,
                'name' => 'Курсы. Кулички из песка',
                'image' => 'beautiful-paradise-beach-865434-wallpaper-preview.jpg',
                'academic_hours' => 205,
                'academic_days' => 35,
                'study_form_id' => 1,
                'state_id' => 1,
                'days_before_start' => 7,
                'has_document' => 1,
                'description' => 'Описание',
                'required_docs' => '',
                'speciality_id' => 1,
                'course_category_id' => 8,
                // 'edu_organization_id' => 1, // Говорят что может быть не одна организация
                'price_id' => 2,
            ],

            [
                'admin_id' => 1,
                'is_published' => 0,
                'name' => 'Курсы. Акушерское дело',
                'image' => 'beautiful-paradise-beach-865434-wallpaper-preview.jpg',
                'academic_hours' => 205,
                'academic_days' => 35,
                'study_form_id' => 1,
                'state_id' => 1,
                'days_before_start' => 7,
                'has_document' => 1,
                'description' => 'Описание',
                'required_docs' => '',
                'speciality_id' => 1,
                'course_category_id' => 11,
                // 'edu_organization_id' => 1, // Говорят что может быть не одна организация
                'price_id' => 2,
            ],

            [
                'admin_id' => 1,
                'is_published' => 1,
                'name' => 'Курсы. Продлёнка',
                'image' => 'beautiful-paradise-beach-865434-wallpaper-preview.jpg',
                'academic_hours' => 205,
                'academic_days' => 35,
                'study_form_id' => 1,
                'state_id' => 1,
                'days_before_start' => 7,
                'has_document' => 1,
                'description' => 'Описание',
                'required_docs' => '',
                'speciality_id' => 1,
                'course_category_id' => 7,
                // 'edu_organization_id' => 1, // Говорят что может быть не одна организация
                'price_id' => 2,
            ],

            [
                'admin_id' => 1,
                'is_published' => 1,
                'name' => 'Курсы. Тренер (индивидуалка)',
                'image' => 'beautiful-paradise-beach-865434-wallpaper-preview.jpg',
                'academic_hours' => 205,
                'academic_days' => 35,
                'study_form_id' => 1,
                'state_id' => 1,
                'days_before_start' => 7,
                'has_document' => 1,
                'description' => 'Описание',
                'required_docs' => '',
                'speciality_id' => 1,
                'course_category_id' => 13,
                // 'edu_organization_id' => 1, // Говорят что может быть не одна организация
                'price_id' => 2,
            ],

            [
                'admin_id' => 1,
                'is_published' => 1,
                'name' => 'Курсы. Тренер (групповые занятия)',
                'image' => 'beautiful-paradise-beach-865434-wallpaper-preview.jpg',
                'academic_hours' => 205,
                'academic_days' => 35,
                'study_form_id' => 1,
                'state_id' => 1,
                'days_before_start' => 7,
                'has_document' => 1,
                'description' => 'Описание',
                'required_docs' => '',
                'speciality_id' => 1,
                'course_category_id' => 13,
                // 'edu_organization_id' => 1, // Говорят что может быть не одна организация
                'price_id' => 2,
            ],

            [
                'is_published' => 0,
                'admin_id' => 1,
                'name' => 'Курсы. Тренер (реабилитация)',
                'image' => 'beautiful-paradise-beach-865434-wallpaper-preview.jpg',
                'academic_hours' => 205,
                'academic_days' => 35,
                'study_form_id' => 1,
                'state_id' => 1,
                'days_before_start' => 7,
                'has_document' => 1,
                'description' => 'Описание',
                'required_docs' => '',
                'speciality_id' => 1,
                'course_category_id' => 13,
                // 'edu_organization_id' => 1, // Говорят что может быть не одна организация
                'price_id' => 2,
            ],

            

            

            

            

            

            

            
        ]);
    }
}
