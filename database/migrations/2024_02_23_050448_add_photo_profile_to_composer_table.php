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
        Schema::table('composer', function (Blueprint $table) {
            $table->text('photo_profile')->after('asal_negara')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('composer', function (Blueprint $table) {
            $table->dropColumn('photo_profile');
        });
    }
};
