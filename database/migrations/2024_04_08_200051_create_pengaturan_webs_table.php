<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePengaturanWebsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pengaturan_webs', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 180);
            $table->string('icon', 180)->nullable();
            $table->string('logo', 180)->default('images/logo.png');
            $table->text('deskripsi');
            $table->string('keywords', 180);
            $table->string('alamat', 180)->nullable();
            $table->string('helpdesk', 180)->nullable();
            $table->string('fb', 180)->nullable();
            $table->string('tiktok', 180)->nullable();
            $table->string('youtube', 180)->nullable();
            $table->string('ig', 180)->nullable();
            $table->string('email', 180)->nullable();
            $table->string('twitter', 180)->nullable();
            $table->float('longitude', 11, 8)->nullable();
            $table->float('latitude', 11, 8)->nullable();
            $table->boolean('confirm_konten')->default(1);
            $table->boolean('confirm_file')->default(1);
            $table->boolean('confirm_komentar')->default(1);
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
        Schema::dropIfExists('pengaturan_webs');
    }
}
