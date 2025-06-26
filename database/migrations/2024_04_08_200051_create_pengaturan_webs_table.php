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
            $table->string('nama');
            $table->string('icon')->nullable();
            $table->string('logo')->default('images/logo.png');
            $table->text('deskripsi');
            $table->string('keywords');
            $table->string('alamat')->nullable();
            $table->string('helpdesk')->nullable();
            $table->string('fb')->nullable();
            $table->string('tiktok')->nullable();
            $table->string('youtube')->nullable();
            $table->string('ig')->nullable();
            $table->string('email')->nullable();
            $table->string('twitter')->nullable();
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
