<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Informations personnelles de base
            $table->string('avatar')->nullable();
            $table->string('banner')->nullable();
            $table->text('bio')->nullable();
            $table->string('phone')->nullable();
            $table->string('city')->nullable();
            $table->string('country')->nullable();
            $table->string('address')->nullable();
            $table->date('birth_date')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            
            // Informations spirituelles
            $table->date('conversion_date')->nullable();
            $table->date('baptism_date')->nullable();
            $table->string('ministry_role')->nullable();
            $table->text('spiritual_gifts')->nullable();
            $table->text('testimony')->nullable();
            
            // Informations d'église
            $table->date('member_since')->nullable();
            $table->string('age_group')->nullable(); // jeunesse, adultes, seniors
            $table->json('ministries')->nullable(); // participation aux ministères
            $table->string('small_group')->nullable();
            
            // Réseaux sociaux
            $table->string('facebook_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('twitter_url')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->string('youtube_url')->nullable();
            
            // Préférences et paramètres
            $table->json('notification_preferences')->nullable();
            $table->enum('privacy_level', ['public', 'members', 'friends', 'private'])->default('members');
            $table->string('language')->default('fr');
            $table->enum('theme', ['light', 'dark', 'auto'])->default('light');
            $table->boolean('is_online')->default(false);
            $table->timestamp('last_seen_at')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
