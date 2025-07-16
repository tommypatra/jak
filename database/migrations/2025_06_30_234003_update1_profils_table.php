<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Update1ProfilsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('profils', function (Blueprint $table) {
            $table->string('gelar_depan', 50)->nullable()->after('id');
            $table->string('gelar_belakang', 50)->nullable()->after('gelar_depan');
            $table->string('tempat_lahir', 100)->nullable()->after('alamat');
            $table->date('tanggal_lahir')->nullable()->after('tempat_lahir');
            $table->string('nomor_pegawai', 100)->nullable()->after('tanggal_lahir');
            $table->string('nidn', 50)->nullable()->after('nomor_pegawai');

            $table->foreignId('jabatan_id')->nullable();
            $table->foreign('jabatan_id')->references('id')->on('jabatans')->restrictOnDelete();
            $table->foreignId('unit_kerja_id')->nullable();
            $table->foreign('unit_kerja_id')->references('id')->on('unit_kerjas')->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('profils', function (Blueprint $table) {
            //
        });
    }
}
