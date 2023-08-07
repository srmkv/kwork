<?php
namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use App\Models\Course\CategoryCourse;
use App\Models\Course\Cource;
use App\Models\Course\Teacher;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        // Способы оплаты (модель Заявка)
        $this->call([
            ChatRoomSeeder::class,
            AcademicDegreeSeeder::class,
            BannerTypeSeeder::class,
            BidStateSeeder::class,
            // CourseCategoriesSeeder::class,
            // CourseCommentSeeder::class,
            CourseDocSeeder::class,
            // CourseEduDocsSeeder::class,
            // CourseEduOrganizationSeeder::class,
            // CourseLevelEducationSeeder::class,
            // CourseRatingSeeder::class,
            CourseRefinementSeeder::class,
            // CourseSeeder::class,
            CourseStateSeeder::class,
            DirectionSeeder::class,
            DocEduDirectionSeeder::class,
            // EduOrganizationSeeder::class,
            // FaqAnswerSeeder::class,
            // FaqQuestionSeeder::class,
            // FaqSeeder::class,
            FilterCategoryTagSeeder::class,
            // FlowSeeder::class,
            FlowTypeSeeder::class,
            // LevelEducationSeeder::class,
            // PasportSeeder::class,
            // PayMethodSeeder::class,
            PricesSeeder::class,
            SnilsSeeder::class,
            // SpecialitySeeder::class,
            TagRefinementSeeder::class,
            TagSearchCourseSeeder::class,
            TeacherSeeder::class,
            TeacherStateSeeder::class,
            UseTechnologySeeder::class,
            // UserTestSeeder::class,
            DocSeeder::class,
            BannerContentTypeSeeder::class,
            CourseProcessTypeSeeder::class,
            VideoTypeSeeder::class,
            StudyFormSeeder::class,
            SqlDumpSeeder::class,
        ]);


    }
}
