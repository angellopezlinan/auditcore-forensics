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
        Schema::create('incidents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->comment('Responsable o auditor que reporta')->constrained()->cascadeOnDelete();
            
            $table->string('title');
            $table->date('date');
            
            $table->enum('category', [
                'accident', 
                'incident', 
                'near_miss', 
                'hazard'
            ])->default('incident');
            
            $table->enum('severity', [
                'low', 
                'medium', 
                'high', 
                'critical'
            ])->default('low');
            
            $table->enum('status', [
                'open', 
                'investigating', 
                'closed'
            ])->default('open');

            $table->text('description')->nullable();
            $table->text('root_cause')->nullable();
            $table->text('mitigation_actions')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incidents');
    }
};
