<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeskripsiToPartiturDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('partitur_detail', function (Blueprint $table) {
            $table->text('deskripsi')->nullable()->after('name'); // Menambahkan kolom deskripsi
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('partitur_detail', function (Blueprint $table) {
            $table->dropColumn('deskripsi'); // Menghapus kolom deskripsi
        });
    }
}