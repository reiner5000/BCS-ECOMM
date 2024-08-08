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
        // tambah kolom size dan color di table cart
        Schema::table('cart', function (Blueprint $table) {
            $table->string('size')->nullable();
            $table->string('color')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //down migration
        Schema::table('cart', function (Blueprint $table) {
            $table->dropColumn('size');
            $table->dropColumn('color');
        });
    }
};