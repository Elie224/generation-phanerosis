<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Créer un trigger pour empêcher la modification des champs critiques de l'admin principal
        DB::unprepared('
            CREATE TRIGGER prevent_main_admin_modification 
            BEFORE UPDATE ON users 
            FOR EACH ROW 
            BEGIN
                IF OLD.email = "kouroumaelisee@gmail.com" AND (
                    OLD.name != NEW.name OR 
                    OLD.email != NEW.email OR 
                    OLD.role != NEW.role OR 
                    OLD.is_active != NEW.is_active
                ) THEN
                    SIGNAL SQLSTATE "45000" 
                    SET MESSAGE_TEXT = "L\'administrateur principal ne peut pas être modifié";
                END IF;
            END
        ');

        // Créer un trigger pour empêcher la suppression de l'admin principal
        DB::unprepared('
            CREATE TRIGGER prevent_main_admin_deletion 
            BEFORE DELETE ON users 
            FOR EACH ROW 
            BEGIN
                IF OLD.email = "kouroumaelisee@gmail.com" THEN
                    SIGNAL SQLSTATE "45000" 
                    SET MESSAGE_TEXT = "L\'administrateur principal ne peut pas être supprimé";
                END IF;
            END
        ');

        // Créer un trigger pour empêcher la désactivation de l'admin principal
        DB::unprepared('
            CREATE TRIGGER prevent_main_admin_deactivation 
            BEFORE UPDATE ON users 
            FOR EACH ROW 
            BEGIN
                IF OLD.email = "kouroumaelisee@gmail.com" AND NEW.is_active = 0 THEN
                    SIGNAL SQLSTATE "45000" 
                    SET MESSAGE_TEXT = "L\'administrateur principal ne peut pas être désactivé";
                END IF;
            END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS prevent_main_admin_modification');
        DB::unprepared('DROP TRIGGER IF EXISTS prevent_main_admin_deletion');
        DB::unprepared('DROP TRIGGER IF EXISTS prevent_main_admin_deactivation');
    }
};
