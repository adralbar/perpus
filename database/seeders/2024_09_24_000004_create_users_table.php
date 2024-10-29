<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('npk_sistem')->unique()->nullable()->default(null);
            $table->string('npk')->unique();
            $table->string('nama');
            $table->string('password');
            $table->string('no_telp')->nullable();
            $table->unsignedBigInteger('section_id');
            $table->unsignedBigInteger('department_id');
            $table->unsignedBigInteger('division_id');
            $table->unsignedBigInteger('role_id')->nullable();
            $table->timestamps();

            $table->foreign('section_id')->references('id')->on('section');
            $table->foreign('department_id')->references('id')->on('department');
            $table->foreign('division_id')->references('id')->on('division');
            $table->foreign('role_id')->references('id')->on('role');
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}