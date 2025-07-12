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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();

            // Clé étrangère vers l'utilisateur
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Titre et description
            $table->string('title');
            $table->text('description')->nullable();

            // Statut : En attente, en cours, terminé
            $table->enum('status', ['en_attente', 'en_cours', 'termine'])->default('en_attente');

            // Priorité : faible, moyenne, élevée
            $table->enum('priority', ['faible', 'moyenne', 'elevee'])->default('moyenne');

            // Date d'échéance
            $table->date('due_date')->nullable();

            // Progression de la tâche (0 à 100 %)
            $table->integer('progress')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
