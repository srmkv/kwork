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
        /**
         * Таблица где собираются все профиля пользователя
         */
        Schema::create('user_types', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique();
            $table->unsignedBigInteger('profile_profiles_id')->unique(); //есть у всех пользователей
//            $table->unsignedBigInteger('profile_businesses_id')->unique()->nullable();
            $table->timestamps();

            // $table->foreign('profile_profiles_id')
            //     ->references('id')
            //     ->on('users');

//            $table->foreign('profile_businesses_id')
//                ->references('id')
//                ->on('user_profile');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_types');
    }
};
