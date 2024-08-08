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
        Schema::create('shipment', function (Blueprint $table) {
            $table->id();
            $table->string('nama_penerima');
            $table->string('phone_number')->nullable();
            $table->string('negara')->nullable();
            $table->string('provinsi')->nullable();
            $table->string('kota')->nullable();
            $table->string('kecamatan')->nullable();
            $table->string('kode_pos')->nullable();
            $table->text('informasi_tambahan')->nullable();
            $table->text('detail_informasi_tambahan')->nullable();
            $table->integer('is_default')->default('0');
            $table->unsignedBigInteger('customer_id')->nullable(); 
            $table->unsignedBigInteger('order_id')->nullable(); 
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
        Schema::dropIfExists('shipment');
    }
};
