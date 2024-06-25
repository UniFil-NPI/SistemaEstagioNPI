<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrientacaoTable extends Migration
{
    public function up()
    {
        Schema::create('orientacao', function (Blueprint $table) {
            $table->increments('id_orientacao');
            $table->date('data_orientacao');
            $table->integer('grau_satisfacao');
            $table->boolean('comparecimento');
            $table->string('descricao', 300);
            $table->string('email_orientador', 30);
            $table->string('email_aluno', 30);
            $table->foreign('email_orientador')->references('email')->on('users');
            $table->foreign('email_aluno')->references('email_aluno')->on('aluno');
        });
    }

    public function down()
    {
        Schema::dropIfExists('orientacao');
    }
}