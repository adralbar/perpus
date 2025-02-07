<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('daftarreadlist', function (Blueprint $table) {
            $table->id();
            $table->string('buku_id');
            $table->string('email');
            $table->string('nama');
            $table->string('judul');
            $table->string('penulis');
            $table->string('penerbit');
            $table->date('tanggal');
            $table->string('nomorisbn');
            $table->string('bahasa');
            $table->string('kategori');
            $table->text('ringkasan');
            $table->string('foto'); // Menyimpan path foto
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('buku');
    }
};
