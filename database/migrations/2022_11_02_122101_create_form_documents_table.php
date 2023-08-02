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
        Schema::create('form_documents', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('type_doc_form')->nullable();
            $table->unsignedBigInteger('admin_section_spoiler_id')->constrained();


            $table->string('title')->nullable();
            $table->text('description')->nullable();
            
            $table->string('form_type')->default('form_documents');





        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('form_documents');
    }
};
