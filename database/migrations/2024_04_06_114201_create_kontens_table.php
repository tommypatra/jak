<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKontensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kontens', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->dateTime('waktu');
            $table->string('thumbnail')->nullable();
            $table->string('slug')->unique();
            $table->text('isi');
            $table->integer('jumlah_akses')->default(0);
            $table->foreignId('jenis_konten_id');
            $table->foreign('jenis_konten_id')->references('id')->on('jenis_kontens')->restrictOnDelete();
            $table->foreignId('user_id');
            $table->foreign('user_id')->references('id')->on('users')->restrictOnDelete();
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
        Schema::dropIfExists('kontens');
    }
}
