<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kategorishift', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('npkSistem');
            $table->string('npk');
            $table->string('divisi');
            $table->string('departement');
            $table->string('section');
            $table->string('shift1');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kategorishift');
    }
};
