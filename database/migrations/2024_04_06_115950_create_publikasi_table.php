<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePublikasiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('publikasis', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_publikasi')->nullable()->default(null);
            $table->text('catatan')->nullable();
            // $table->enum('kategori', ['KONTEN', 'FILE'])->default('KONTEN');
            $table->foreignId('konten_id')->nullable()->unique();
            $table->foreign('konten_id')->references('id')->on('kontens')->restrictOnDelete();

            $table->foreignId('file_id')->nullable()->unique();
            $table->foreign('file_id')->references('id')->on('files')->restrictOnDelete();

            $table->foreignId('galeri_id')->nullable()->unique();
            $table->foreign('galeri_id')->references('id')->on('galeris')->restrictOnDelete();

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
        Schema::dropIfExists('publikasi_kontens');
    }
}
