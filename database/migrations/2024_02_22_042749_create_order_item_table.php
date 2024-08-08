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
        Schema::create('order_item', function (Blueprint $table) {
            $table->id();
            $table->integer('quantity')->default('0');
            $table->integer('for_competition')->default('0');
            $table->integer('competition_fee')->default('0');
            $table->unsignedBigInteger('choir_id')->nullable(); 
            $table->unsignedBigInteger('order_id')->nullable(); 
            $table->unsignedBigInteger('partitur_id')->nullable(); 
            $table->unsignedBigInteger('merchandise_id')->nullable(); 
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
        Schema::dropIfExists('order_item');
    }
};
