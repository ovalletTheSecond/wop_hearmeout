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
        Schema::create('votes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('crush_id')->constrained()->onDelete('cascade');
            $table->enum('vote_type', ['oui', 'non', 'non_tare', 'tare_mais_oui']);
            $table->string('ip_address', 45)->nullable();
            $table->string('session_id')->nullable();
            $table->integer('stats_version')->default(1);
            $table->timestamps();
            
            $table->index(['crush_id', 'stats_version']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('votes');
    }
};
