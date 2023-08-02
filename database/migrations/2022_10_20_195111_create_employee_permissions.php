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
        // сводная табл. между сотрудниками и правами 
        Schema::create('employee_permissions', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->constrained();
            $table->unsignedBigInteger('employee_permission_id')->constrained();

            $table->primary(['user_id','employee_permission_id']);


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee_permissions');
    }
};
