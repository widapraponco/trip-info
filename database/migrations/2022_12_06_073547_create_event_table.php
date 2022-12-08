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
        Schema::create('event', function (Blueprint $table) {
            $table->id();

            $table->integer('destinasi_id');
            $table->string('nama');
            $table->date('tanggal_pelaksanaan');
            $table->integer('jam_mulai');
            $table->integer('jam_berakhir');
            $table->date('tanggal_selesai');
            $table->string('contact_person');
            $table->integer('rating');

            $table->destinasi_id();

            $table->nama();
            
            $table->tanggal_pelaksanaan();

            $table->jam_mulai();

            $table->jam_berakhir();

            $table->tanggal_selesai();

            $table->contact_person();

            $table->rating();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('event');
    }
};
