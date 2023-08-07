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
        //  permissions_admin указаны все возможные права которые могут быть у админа
        //  admin_permission - указаны КОНКРЕТНЫЕ права у КОНКРЕТНОГО админа
        //  сравнивать все таблицы с dev, если будут конфликты (фикс от 6.12.22) 
        //  
        Schema::create('permissions_admin', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('slug')->nullable();


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permissions_admin');
    }
};
