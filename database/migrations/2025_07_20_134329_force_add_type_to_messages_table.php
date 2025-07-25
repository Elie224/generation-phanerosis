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
        if (!Schema::hasColumn('messages', 'type')) {
            Schema::table('messages', function (Blueprint $table) {
                $table->string('type')->default('text')->after('id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('messages', 'type')) {
            Schema::table('messages', function (Blueprint $table) {
                $table->dropColumn('type');
            });
        }
    }
};
