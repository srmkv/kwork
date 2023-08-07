<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::create('profilebles', function (Blueprint $table) {
            $table->integer('profile_id');
            $table->integer('profileble_id');
            $table->string('profiles_individuals_type');
            $table->string('role_id')->nullable();
            $table->string('status')->default('waitng');
            $table->string('profile_who_invited')->nullable();
            $table->timestamp('invitation_date')->nullable();
            $table->string('job_position_who')->nullable();
            $table->string('job_position_employee')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profilebles');
    }
};
