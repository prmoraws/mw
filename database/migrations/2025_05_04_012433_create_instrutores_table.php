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
        Schema::create('instrutores', function (Blueprint $table) {
            $table->unsignedBigInteger('id', true); // bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT
            $table->unsignedBigInteger('bloco_id'); // Foreign key
            $table->unsignedBigInteger('categoria_id'); // Foreign key
            $table->string('foto', 255);
            $table->string('nome', 255);
            $table->string('telefone', 255);
            $table->string('igreja', 255);
            $table->string('profissao', 255);
            $table->longText('batismo')->nullable(); // JSON stored as text
            $table->longText('testemunho')->nullable();
            $table->string('carga', 255)->nullable();
            $table->boolean('certificado')->default(false); // tinyint(1) as boolean
            $table->boolean('inscricao')->default(false); // tinyint(1) as boolean
            $table->timestamps(); // created_at and updated_at

            // Foreign key constraints (assuming related tables exist)
            $table->foreign('bloco_id')->references('id')->on('blocos')->onUpdate('cascade');
            $table->foreign('categoria_id')->references('id')->on('categorias')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instrutores');
    }
};
