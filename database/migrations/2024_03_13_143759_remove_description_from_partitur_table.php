<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveDescriptionFromPartiturTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('partitur', function (Blueprint $table) {
            $table->dropColumn('description'); // Hapus kolom description
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('partitur', function (Blueprint $table) {
            // Menambahkan kembali kolom description jika migration di rollback
            $table->text('description')->nullable()->after('file_image');
        });
    }
}