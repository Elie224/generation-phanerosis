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
            // Champs bancaires manquants
            if (!Schema::hasColumn('support_infos', 'bank_swift')) {
                $table->string('bank_swift')->nullable();
            }
            
            // Mobile Money manquants
            if (!Schema::hasColumn('support_infos', 'mtn_money_name')) {
                $table->string('mtn_money_name')->nullable();
            }
            if (!Schema::hasColumn('support_infos', 'orange_money_name')) {
                $table->string('orange_money_name')->nullable();
            }
            
            // Cryptomonnaies manquantes
            if (!Schema::hasColumn('support_infos', 'usdt_ton_address')) {
                $table->text('usdt_ton_address')->nullable();
            }
            if (!Schema::hasColumn('support_infos', 'usdt_bnb_address')) {
                $table->text('usdt_bnb_address')->nullable();
            }
            if (!Schema::hasColumn('support_infos', 'pi_address')) {
                $table->text('pi_address')->nullable();
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
                'bank_swift',
                'mtn_money_name',
                'orange_money_name',
                'usdt_ton_address',
                'usdt_bnb_address',
                'pi_address'
            ]);
        });
    }
};
