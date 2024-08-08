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
        Schema::create('order', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->integer('total')->default('0');
            $table->integer('shipment_fee')->default('0');
            $table->unsignedBigInteger('customer_id')->nullable(); 
            $table->unsignedBigInteger('shipment_id')->nullable(); 
            $table->unsignedBigInteger('payment_id')->nullable(); 
            $table->unsignedBigInteger('voucher_id')->nullable(); 
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
        Schema::dropIfExists('order');
    }
};
