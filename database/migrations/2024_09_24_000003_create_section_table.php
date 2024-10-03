<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSectionTable extends Migration
{
    public function up()
    {
        Schema::create('section', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->unsignedBigInteger('department_id');
            $table->timestamps();

            $table->foreign('department_id')->references('id')->on('department');
        });
    }

    public function down()
    {
        Schema::dropIfExists('section');
    }
}
