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
        Schema::create('user_admin_roles', function (Blueprint $table) {

            $table->unsignedBigInteger('user_id')->constrained();
            $table->unsignedBigInteger('admin_role_id')->constrained();


            $table->primary(['user_id','admin_role_id']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_admin_roles');
    }
};
