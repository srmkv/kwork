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
        Schema::create('form_org_units', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->unsignedBigInteger('media_id')->nullable()->constrained();
            $table->string('unit_title')->nullable();
            $table->string('director')->nullable();

            $table->unsignedBigInteger('admin_section_spoiler_id')->constrained();
            $table->string('unit_address')->nullable();
            //custom (!)
            $table->string('url')->nullable();
            $table->string('form_type')->default('form_org_unit');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('form_org_units');
    }
};
