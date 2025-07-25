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
        Schema::table('announcements', function (Blueprint $table) {
            // Champs principaux
            if (!Schema::hasColumn('announcements', 'title')) {
                $table->string('title');
            }
            if (!Schema::hasColumn('announcements', 'content')) {
                $table->text('content');
            }
            if (!Schema::hasColumn('announcements', 'attachment')) {
                $table->string('attachment')->nullable();
            }
            if (!Schema::hasColumn('announcements', 'is_published')) {
                $table->boolean('is_published')->default(false);
            }
            if (!Schema::hasColumn('announcements', 'published_at')) {
                $table->timestamp('published_at')->nullable();
            }
            if (!Schema::hasColumn('announcements', 'user_id')) {
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('announcements', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['title', 'content', 'attachment', 'is_published', 'published_at', 'user_id']);
        });
    }
};
