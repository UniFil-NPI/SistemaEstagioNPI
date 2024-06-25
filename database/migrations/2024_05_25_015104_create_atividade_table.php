<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAtividadeTable extends Migration
{
    public function up()
    {
        Schema::create('atividade', function (Blueprint $table) {
            $table->increments('id_atividade');
            $table->date('data_postagem');
            $table->date('data_entrega')->nullable();
            $table->integer('nota');
            $table->string('nome_atividade', 30);
            $table->string('email_aluno', 30);
            $table->foreign('email_aluno')->references('email_aluno')->on('aluno');
        });
    }

    public function down()
    {
        Schema::dropIfExists('atividade');
    }
}