<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKomentarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('komentars', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_publikasi')->nullable()->default(null);
            $table->string('nama');
            $table->text('komentar');
            $table->foreignId('konten_id')->nullable();
            $table->foreign('konten_id')->references('id')->on('kontens')->cascadeOnDelete();
            $table->foreignId('file_id')->nullable();
            $table->foreign('file_id')->references('id')->on('files')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable();
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
        Schema::dropIfExists('komentars');
    }
}
