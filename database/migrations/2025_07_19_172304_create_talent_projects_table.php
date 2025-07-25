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
        Schema::create('talent_projects', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Nom du projet
            $table->text('description'); // Description du projet
            $table->string('owner_name'); // Nom du porteur de projet
            $table->string('contact_email')->nullable();
            $table->string('external_link')->nullable();
            $table->string('attachment_path')->nullable(); // Image, doc, etc.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('talent_projects');
    }
};
