<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->morphs('bankable'); // Employee o Vendor
            $table->string('iban_hash')->index(); // Hash del IBAN para detección de duplicados/fraude
            $table->string('swift')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['bankable_id', 'bankable_type', 'is_active'], 'idx_bank_active');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bank_accounts');
    }
};
