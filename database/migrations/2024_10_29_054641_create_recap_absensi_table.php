<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecapAbsensiTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('recap_absensi', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('npk', 10);
            $table->date('tanggal');
            $table->time('waktuci')->nullable(); 
            $table->string('waktuco')->default('NO OUT');  
            $table->string('shift1')->nullable(); 
            $table->string('section_nama'); 
            $table->string('department_nama');  
            $table->string('division_nama');  
            $table->string('status'); 
            $table->string('npk_sistem', 10); 
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recap_absensi');
    }
};
