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
        Schema::create('status_history', function (Blueprint $table) {
            $table->id();
            $table->enum('status', ['ouvert', 'en_cours', 'fermé', 'prévu', 'terminé']);
            $table->timestamp('changed_at')->useCurrent();
            $table->foreignId('changed_by')->constrained('utilisateurs')->onDelete('cascade');
            $table->unsignedBigInteger('related_id'); // Renommé
            $table->string('related_type'); // Renommé
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('status_histories');
    }
};
