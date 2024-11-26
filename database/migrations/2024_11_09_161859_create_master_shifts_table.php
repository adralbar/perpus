<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasterShiftsTable extends Migration
{
    public function up()
    {
        Schema::create('master_shift', function (Blueprint $table) {
            $table->id();
            $table->string('shift_name');
            $table->string('waktu');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('master_shift');
    }
}
