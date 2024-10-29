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
        Schema::create('absensici', function (Blueprint $table) {
            $table->id();

            $table->string('npk');
            $table->string('npk_sistem')->nullable();
            $table->date('tanggal');
            $table->time('waktuci');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensici');
    }
};
