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
            $table->string('npk');
            $table->string('divisi');
            $table->string('departement');
            $table->string('section');
            $table->string('shift1')->nullable(); // Nullable jika tidak selalu ada data
            $table->string('shift2')->nullable();
            $table->string('shift3')->nullable();
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
