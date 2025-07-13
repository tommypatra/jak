<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJabatanUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jabatan_users', function (Blueprint $table) {
            $table->id();
            $table->date('tmt')->nullable();
            $table->foreignId('user_id');
            $table->foreign('user_id')->references('id')->on('users')->restrictOnDelete();
            $table->foreignId('jabatan_id');
            $table->foreign('jabatan_id')->references('id')->on('jabatans')->restrictOnDelete();
            $table->foreignId('unit_kerja_id');
            $table->foreign('unit_kerja_id')->references('id')->on('unit_kerjas')->restrictOnDelete();
            $table->boolean('is_aktif')->default(1);
            $table->unique(['user_id', 'unit_kerja_id', 'jabatan_id', 'tmt']);
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
        Schema::dropIfExists('jabatan_users');
    }
}
