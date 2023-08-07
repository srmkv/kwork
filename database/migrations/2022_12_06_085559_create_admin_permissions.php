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
        Schema::dropIfExists('admin_permissions');
        Schema::create('admin_permissions', function (Blueprint $table) {
            
            $table->unsignedBigInteger('user_id')->constrained();
            $table->unsignedBigInteger('admin_permission_id')->constrained();
            $table->primary(['user_id','admin_permission_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_permissions');
    }
};
