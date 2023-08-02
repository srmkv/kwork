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
        Schema::create('employee_roles_permissions', function (Blueprint $table) {

            $table->unsignedBigInteger('employee_role_id')->constrained();
            $table->unsignedBigInteger('employee_permission_id')->constrained();
            // $table->primary(['user_id','permission_employee_id']);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee_roles_permissions');
    }
};
