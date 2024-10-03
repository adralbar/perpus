<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepartmentTable extends Migration
{
    public function up()
    {
        Schema::create('department', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->unsignedBigInteger('division_id');
            $table->timestamps();

            $table->foreign('division_id')->references('id')->on('division');
        });
    }

    public function down()
    {
        Schema::dropIfExists('department');
    }
}