<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKategorishiftTable extends Migration
{
    public function up()
    {
        Schema::create('kategorishift', function (Blueprint $table) {
            $table->id();

            $table->string('npk');

            $table->string('shift1');
            $table->date('start_date')->nullable(); // Simpan start_date
            $table->date('end_date')->nullable();   // Simpan end_date
            $table->date('date');       // Simpan setiap tanggal antara start_date dan end_date

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kategorishift');
    }
}
