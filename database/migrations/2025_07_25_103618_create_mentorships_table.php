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
        Schema::create('mentorships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mentor_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('mentee_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->enum('status', ['pending', 'active', 'completed', 'cancelled'])->default('pending');
            $table->enum('type', ['spiritual', 'professional', 'personal', 'academic'])->default('spiritual');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('duration_weeks')->nullable(); // Durée prévue en semaines
            $table->string('meeting_frequency')->nullable(); // Fréquence des rencontres
            $table->text('goals')->nullable(); // Objectifs du mentorat
            $table->text('expectations')->nullable(); // Attentes mutuelles
            $table->text('notes')->nullable(); // Notes privées du mentor
            $table->boolean('is_public')->default(false); // Si le mentorat peut être visible publiquement
            $table->timestamps();
            
            $table->unique(['mentor_id', 'mentee_id']);
            $table->index(['mentor_id', 'status']);
            $table->index(['mentee_id', 'status']);
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mentorships');
    }
};
