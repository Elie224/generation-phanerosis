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
        Schema::create('notification_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Types de notifications
            $table->boolean('email_enabled')->default(true);
            $table->boolean('sms_enabled')->default(false);
            $table->boolean('push_enabled')->default(true);
            $table->boolean('whatsapp_enabled')->default(false);
            
            // Préférences par type d'événement
            $table->boolean('events_email')->default(true);
            $table->boolean('events_sms')->default(false);
            $table->boolean('events_push')->default(true);
            $table->boolean('events_whatsapp')->default(false);
            
            $table->boolean('prayer_requests_email')->default(true);
            $table->boolean('prayer_requests_sms')->default(false);
            $table->boolean('prayer_requests_push')->default(true);
            $table->boolean('prayer_requests_whatsapp')->default(false);
            
            $table->boolean('announcements_email')->default(true);
            $table->boolean('announcements_sms')->default(false);
            $table->boolean('announcements_push')->default(true);
            $table->boolean('announcements_whatsapp')->default(false);
            
            $table->boolean('messages_email')->default(true);
            $table->boolean('messages_sms')->default(false);
            $table->boolean('messages_push')->default(true);
            $table->boolean('messages_whatsapp')->default(false);
            
            $table->boolean('ministry_updates_email')->default(true);
            $table->boolean('ministry_updates_sms')->default(false);
            $table->boolean('ministry_updates_push')->default(true);
            $table->boolean('ministry_updates_whatsapp')->default(false);
            
            $table->boolean('birthday_reminders_email')->default(true);
            $table->boolean('birthday_reminders_sms')->default(false);
            $table->boolean('birthday_reminders_push')->default(true);
            $table->boolean('birthday_reminders_whatsapp')->default(false);
            
            $table->boolean('spiritual_reminders_email')->default(true);
            $table->boolean('spiritual_reminders_sms')->default(false);
            $table->boolean('spiritual_reminders_push')->default(true);
            $table->boolean('spiritual_reminders_whatsapp')->default(false);
            
            // Informations de contact
            $table->string('whatsapp_number')->nullable();
            $table->string('sms_number')->nullable();
            $table->string('push_token')->nullable();
            
            // Paramètres de fréquence
            $table->enum('email_frequency', ['immediate', 'daily', 'weekly'])->default('immediate');
            $table->enum('sms_frequency', ['immediate', 'daily', 'weekly'])->default('immediate');
            $table->enum('whatsapp_frequency', ['immediate', 'daily', 'weekly'])->default('immediate');
            
            // Heures de réception
            $table->time('quiet_hours_start')->default('22:00:00');
            $table->time('quiet_hours_end')->default('08:00:00');
            $table->boolean('respect_quiet_hours')->default(true);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_preferences');
    }
};
