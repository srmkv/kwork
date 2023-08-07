<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_data_director_edus', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->unsignedBigInteger('admin_section_spoiler_id')->constrained();
            $table->unsignedBigInteger('media_id')->nullable()->constrained(); 

            $table->string('title_fio')->nullable();
            $table->string('current_position')->nullable();
            $table->string('level_education')->nullable();
            $table->string('total_work_experience')->nullable();
            $table->string('professional_experience')->nullable();
            $table->string('direction_or_speciality')->nullable();
            $table->string('academic_degree')->nullable();
            // повышение квалификации или профессиональная подгтотовка
            $table->string('refresher_vocational_training')->nullable(); 
            $table->text('description_director')->nullable(); 
            $table->string('url')->nullable();

            //custom 
            $table->string('form_type')->default('form_data_director_edus');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('form_data_director_edus');
    }
};
