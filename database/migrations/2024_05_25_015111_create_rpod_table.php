<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRpodTable extends Migration
{
    public function up()
    {
        Schema::create('rpod', function (Blueprint $table) {
            $table->increments('id_rpod');
            $table->date('data_entrega');
            $table->string('email_aluno', 30);
            $table->foreign('email_aluno')->references('email_aluno')->on('aluno');
        });
    }

    public function down()
    {
        Schema::dropIfExists('rpod');
    }
}