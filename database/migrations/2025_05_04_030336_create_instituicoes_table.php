<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('instituicoes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nome');
            $table->string('contato');
            $table->string('bairro');
            $table->string('convidados');
            $table->string('onibus');
            $table->string('bloco');
            $table->string('iurd');
            $table->string('pastor');
            $table->string('telefone');
            $table->string('endereco');
            $table->string('localização');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instituicoes');
    }
};
