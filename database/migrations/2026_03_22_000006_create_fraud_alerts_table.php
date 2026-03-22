<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fraud_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->cascadeOnDelete();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $table->string('type');
            $table->integer('severity_score'); // 1-5
            $table->json('evidence_metadata')->nullable();
            $table->boolean('is_resolved')->default(false);
            $table->timestamps();

            $table->index(['team_id', 'type', 'is_resolved']);
            $table->index(['invoice_id', 'is_resolved']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fraud_alerts');
    }
};
