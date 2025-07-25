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
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('resource_categories')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->string('slug')->unique();
            $table->enum('type', ['document', 'video', 'audio', 'link', 'image'])->default('document');
            $table->string('file_path')->nullable(); // Pour les fichiers uploadés
            $table->string('external_url')->nullable(); // Pour les liens externes
            $table->string('thumbnail_path')->nullable(); // Image de prévisualisation
            $table->string('file_size')->nullable(); // Taille du fichier
            $table->string('file_type')->nullable(); // Type MIME
            $table->integer('downloads_count')->default(0);
            $table->integer('views_count')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_public')->default(true);
            $table->text('tags')->nullable(); // Tags séparés par des virgules
            $table->string('author')->nullable();
            $table->string('publisher')->nullable();
            $table->date('publication_date')->nullable();
            $table->string('language')->default('fr');
            $table->integer('duration')->nullable(); // Pour les vidéos/audio (en secondes)
            $table->timestamps();
            
            $table->index(['category_id', 'created_at']);
            $table->index(['user_id', 'created_at']);
            $table->index('slug');
            $table->index('type');
            $table->index('is_featured');
            $table->index('is_public');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resources');
    }
};
