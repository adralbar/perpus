<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttendanceRecordsTable extends Migration
{
    public function up()
    {
        Schema::create('attendance_records', function (Blueprint $table) {
            $table->id();
            $table->string('npk');
            $table->date('tanggal');
            $table->time('waktuci')->nullable(); // Waktu check-in
            $table->time('waktuco')->nullable(); // Waktu check-out
            $table->string('shift1')->nullable(); // Shift karyawan
            $table->string('status')->nullable(); // Status kehadiran
            $table->timestamps(); // created_at dan updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('attendance_records');
    }
}
