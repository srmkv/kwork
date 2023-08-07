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
        Schema::create('users_employee_roles', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->constrained(); // ид сотрудника
            $table->unsignedBigInteger('employee_role_id')->constrained(); // его роль
            $table->primary(['user_id','employee_role_id']);

        });


    }


    public function down()
    {
        Schema::dropIfExists('users_employee_roles');
    }
};
