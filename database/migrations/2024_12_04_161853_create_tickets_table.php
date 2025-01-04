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
            $table->unsignedBigInteger('client_id')->nullable(); // Associer un client
            $table->unsignedBigInteger('assigned_to')->nullable(); // Technicien assigné
            $table->string('description');
            $table->enum('priority', ['faible', 'moyenne', 'haute']);
            $table->enum('status', ['ouvert', 'en_cours', 'fermé']);
            $table->timestamps();

            $table->foreign('client_id')->references('id')->on('utilisateurs')->onDelete('cascade');
            $table->foreign('assigned_to')->references('id')->on('utilisateurs')->onDelete('set null');
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
