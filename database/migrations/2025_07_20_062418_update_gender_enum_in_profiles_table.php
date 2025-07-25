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
        Schema::table('profiles', function (Blueprint $table) {
            // Supprimer l'ancienne colonne gender
            $table->dropColumn('gender');
        });

        Schema::table('profiles', function (Blueprint $table) {
            // Recréer la colonne gender avec les nouvelles valeurs
            $table->enum('gender', ['male', 'female'])->nullable()->after('birth_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            // Supprimer la nouvelle colonne gender
            $table->dropColumn('gender');
        });

        Schema::table('profiles', function (Blueprint $table) {
            // Recréer l'ancienne colonne gender
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('birth_date');
        });
    }
};
