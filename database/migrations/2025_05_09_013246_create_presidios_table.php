<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('presidios', function (Blueprint $table) {
            $table->id();
            $table->string('nome')->index();
            $table->string('diretor');
            $table->string('contato_diretor');
            $table->string('adjunto');
            $table->string('contato_adjunto')->nullable();
            $table->string('laborativa')->nullable();
            $table->string('contato_laborativa')->nullable();
            $table->text('visita')->nullable();
            $table->text('interno')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presidios');
    }
};