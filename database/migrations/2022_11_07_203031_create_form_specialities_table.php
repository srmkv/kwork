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
        Schema::create('form_specialities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_section_spoiler_id')->constrained();
            $table->string('title_speciality')->nullable();
            // количество вакантных мест за счет бюджетных ассигнований  федерального бюджета
            $table->integer('vacant_places_federal_budget')->default(0);
            // количество вакантных мест за счет бюджетных ассигнований  субъекта РФ
            $table->integer('vacant_places_subject_rf_budget')->default(0);
            // количество вакантных мест за счет бюджетных ассигнований местного бюджета
            $table->integer('vacant_places_local_budget')->default(0);
            // количество вакантных мест за счет бюджетных ассигнований юр. или физич. лиц
            $table->integer('vacant_places_legal_individ_budget')->default(0);
            $table->timestamps();
            //custom
            $table->string('form_type')->default('form_specialities');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('form_specialities');
    }
};
