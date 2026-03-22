<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->cascadeOnDelete();
            $table->foreignId('vendor_id')->constrained()->cascadeOnDelete();
            $table->string('invoice_number')->index();
            $table->date('issue_date');
            $table->decimal('total_amount', 15, 2);
            $table->string('pdf_vault_path')->nullable(); // Ruta en Bóveda Privada
            $table->string('status')->default('pending');
            $table->timestamps();

            $table->index(['team_id', 'invoice_number']);
            $table->index(['team_id', 'vendor_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
