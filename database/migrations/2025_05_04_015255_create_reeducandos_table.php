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
        Schema::create('reeducandos', function (Blueprint $table) {
            $table->unsignedBigInteger('id', true); // bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT
            $table->string('nome', 255);
            $table->unsignedBigInteger('curso_id'); // Foreign key to cursos table
            $table->string('documento', 255);
            $table->string('carga', 255);
            $table->text('observacoes')->nullable();
            $table->timestamps(); // created_at and updated_at

            // Foreign key constraint
            $table->foreign('curso_id')
                  ->references('id')
                  ->on('cursos')
                  ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reeducandos');
    }
};