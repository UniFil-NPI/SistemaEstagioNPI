<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->string('email', 30)->primary();
            $table->string('nome', 30);
            $table->boolean('isadmin');
            $table->boolean('ativo');
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}