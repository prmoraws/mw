<?php

use App\Models\Cidade;
use App\Models\Estado;
use App\Models\Universal\Bloco;
use App\Models\Universal\Categoria;
use App\Models\Universal\Igreja;
use App\Models\Universal\Regiao;
use App\Models\Unp\Cargo;
use App\Models\Unp\Grupo;
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
        Schema::create('pessoas', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Bloco::class);
            $table->foreignIdFor(Regiao::class);
            $table->foreignIdFor(Igreja::class);
            $table->foreignIdFor(Categoria::class);
            $table->foreignIdFor(Cargo::class);
            $table->foreignIdFor(Grupo::class);
            $table->foreignIdFor(Cidade::class);
            $table->foreignIdFor(Estado::class);
            $table->string('foto');
            $table->string('nome');
            $table->string('celular');
            $table->string('telefone');
            $table->string('email')->unique();
            $table->string('endereco');
            $table->string('bairro');
            $table->string('cep');
            $table->string('profissao');
            $table->text('aptidoes');
            $table->date('conversao')->nullable();     //data de conversão
            $table->date('obra')->nullable();    //data início na obra
            $table->json('trabalho');
            $table->json('batismo'); //
            $table->json('preso');
            $table->text('testemunho');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pessoas');
    }
};
