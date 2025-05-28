<?php

use App\Models\Universal\Bloco;
use App\Models\Universal\Regiao;
use App\Models\Universal\Tipo;
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
        Schema::create('igrejas', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->foreignIdFor(Bloco::class);
            $table->foreignIdFor(Regiao::class);
            $table->foreignIdFor(Tipo::class);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('igrejas');
    }
};
