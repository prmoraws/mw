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
        Schema::create('cestas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nome');
            $table->string('terreiro');
            $table->string('contato');
            $table->integer('cestas');
            $table->string('observacao')->nullable();
            $table->string('foto')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cestas');
    }
};