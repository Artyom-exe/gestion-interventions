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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->text('description');
            $table->enum('status', ['ouvert', 'en_cours', 'fermé'])->default('ouvert');
            $table->enum('priority', ['faible', 'moyenne', 'haute'])->default('moyenne');
            $table->foreignId('assigned_to')->nullable()->constrained('utilisateurs')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
