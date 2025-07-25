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
        Schema::create('mentorship_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mentorship_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->datetime('scheduled_at');
            $table->datetime('started_at')->nullable();
            $table->datetime('ended_at')->nullable();
            $table->enum('status', ['scheduled', 'in_progress', 'completed', 'cancelled', 'no_show'])->default('scheduled');
            $table->enum('format', ['in_person', 'video_call', 'phone_call', 'chat'])->default('in_person');
            $table->string('location')->nullable(); // Lieu ou lien de la session
            $table->text('agenda')->nullable(); // Ordre du jour
            $table->text('notes')->nullable(); // Notes de la session
            $table->text('homework')->nullable(); // Devoirs/actions à faire
            $table->integer('rating')->nullable(); // Note de satisfaction (1-5)
            $table->text('feedback')->nullable(); // Retour d'expérience
            $table->timestamps();
            
            $table->index(['mentorship_id', 'scheduled_at']);
            $table->index(['mentorship_id', 'status']);
            $table->index('scheduled_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mentorship_sessions');
    }
};
