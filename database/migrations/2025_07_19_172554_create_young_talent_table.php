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
        Schema::create('young_talent', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nom du jeune
            $table->string('domain'); // Domaine d'excellence
            $table->text('description')->nullable(); // Description ou bio
            $table->string('photo_path')->nullable(); // Photo du jeune
            $table->string('cv_path')->nullable(); // CV ou document
            $table->string('external_link')->nullable(); // Lien externe (portfolio, réseaux)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('young_talent');
    }
};
