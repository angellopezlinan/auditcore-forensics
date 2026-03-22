<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->cascadeOnDelete();
            $table->string('legal_name');
            $table->string('vat_number')->index(); // CIF/NIF indexado para búsquedas rápidas
            $table->integer('risk_score')->default(0); // 0-100 score de riesgo
            $table->timestamps();

            $table->index(['team_id', 'vat_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};
