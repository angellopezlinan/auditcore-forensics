<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->cascadeOnDelete();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('internal_id')->index(); // ID interno de empleado para cruce con nóminas
            $table->timestamps();
            
            $table->index(['team_id', 'internal_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
