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
        Schema::create('form_data_orgs', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('full_title')->nullable();
            $table->string('short_title')->nullable();
            $table->date('date_create_org')->nullable();
            $table->string('founder_org')->nullable();
            $table->string('location')->nullable(); // место нахождения
            $table->string('time_schedule')->nullable(); // график работы
            $table->string('days_working')->nullable(); // дни в которые работает 
            $table->string('time_working')->nullable(); // время в которые работает 
            $table->string('form_type')->default('form_data_org');
            $table->string('address_edu_activity')->nullable(); // адрес осуществления образовательной деятельности
            $table->unsignedBigInteger('admin_section_spoiler_id')->constrained();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('form_data_orgs');
    }
};
