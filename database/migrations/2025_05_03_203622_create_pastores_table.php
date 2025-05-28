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
        Schema::create('pastores', function (Blueprint $table) {
            $table->id();
            $table->string('sede');
            $table->string('pastor');
            $table->string('telefone');
            $table->string('esposa');
            $table->string('tel_epos');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pastores');
    }
};
