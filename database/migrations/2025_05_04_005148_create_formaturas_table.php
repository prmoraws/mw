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
        Schema::create('formaturas', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->string('curso');
            $table->string('unidade');
            $table->date('data');
            $table->string('hora');
            $table->text('checklist')->nullable();
            $table->timestamps(); // Equivalent to your created_at and updated_at defaults
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('formaturas');
    }
};