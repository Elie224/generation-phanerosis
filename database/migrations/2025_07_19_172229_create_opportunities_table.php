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
        Schema::create('opportunities', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Intitulé du poste ou de l'opportunité
            $table->text('description'); // Détail de l'offre
            $table->string('company')->nullable(); // Employeur ou organisme
            $table->string('location')->nullable(); // Lieu
            $table->string('contact_email')->nullable(); // Contact
            $table->string('external_link')->nullable(); // Lien externe (site, offre)
            $table->string('attachment_path')->nullable(); // CV, fiche PDF, etc.
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('opportunities');
    }
};
