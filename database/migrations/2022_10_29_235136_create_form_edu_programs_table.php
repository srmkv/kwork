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
        Schema::create('form_edu_programs', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('media_id')->nullable()->constrained();
            $table->unsignedBigInteger('admin_section_spoiler_id')->constrained();

            $table->string('form_type')->default('form_edu_programs');
            $table->string('url')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('form_edu_programs');
    }
};
