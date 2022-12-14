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
        Schema::create('table_sarana_prasarana', function (Blueprint $table) {
            $table->id();

            $table-> integer(destinasi_id);

            $table-> string (nama);

            $table-> string (fungsional & nonfungsional);

            $table-> string (deskripsi);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('table_sarana_prasarana');
    }
};
