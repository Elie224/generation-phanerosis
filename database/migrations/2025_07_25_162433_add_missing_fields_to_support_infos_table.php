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
        Schema::table('support_infos', function (Blueprint $table) {
            // Champs manquants pour le formulaire
            if (!Schema::hasColumn('support_infos', 'title')) {
                $table->string('title')->nullable();
            }
            if (!Schema::hasColumn('support_infos', 'description')) {
                $table->text('description')->nullable();
            }
            if (!Schema::hasColumn('support_infos', 'bank_info')) {
                $table->text('bank_info')->nullable();
            }
            if (!Schema::hasColumn('support_infos', 'contact_phone')) {
                $table->string('contact_phone')->nullable();
            }
            if (!Schema::hasColumn('support_infos', 'contact_email')) {
                $table->string('contact_email')->nullable();
            }
            if (!Schema::hasColumn('support_infos', 'contact_address')) {
                $table->string('contact_address')->nullable();
            }
            if (!Schema::hasColumn('support_infos', 'thank_you_message')) {
                $table->text('thank_you_message')->nullable();
            }
            if (!Schema::hasColumn('support_infos', 'is_active')) {
                $table->boolean('is_active')->default(false);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('support_infos', function (Blueprint $table) {
            $table->dropColumn([
                'title',
                'description',
                'bank_info',
                'contact_phone',
                'contact_email',
                'contact_address',
                'thank_you_message',
                'is_active'
            ]);
        });
    }
};
