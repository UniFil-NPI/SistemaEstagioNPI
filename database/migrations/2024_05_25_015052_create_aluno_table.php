<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAlunoTable extends Migration
{
    public function up()
    {
        Schema::create('aluno', function (Blueprint $table) {
            $table->string('email_aluno', 30)->primary();
            $table->string('nome', 30);
            $table->integer('matricula')->nullable();
            $table->smallInteger('bimestre');
            $table->boolean('ativo');
            $table->string('email_orientador', 30);
            $table->foreign('email_orientador')->references('email')->on('users');
        });
    }

    public function down()
    {
        Schema::dropIfExists('aluno');
    }
}