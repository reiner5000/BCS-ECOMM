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
        Schema::create('partitur_detail', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('file_type');
            $table->integer('harga')->default('0');
            $table->integer('minimum_order')->default('0');
            $table->string('preview_audio')->nullable();
            $table->string('preview_video')->nullable();
            $table->string('preview_partitur')->nullable();
            $table->unsignedBigInteger('partitur_id')->nullable(); 
            $table->unsignedBigInteger('category_detail_id')->nullable(); 
            $table->timestamps();
            $table->softDeletes(); 
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('partitur_details');
    }
};
