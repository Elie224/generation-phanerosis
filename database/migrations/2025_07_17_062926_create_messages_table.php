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
    Schema::create('messages', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('from_id');
        $table->unsignedBigInteger('to_id');
        $table->text('content');
        $table->boolean('is_read')->default(false);
        $table->timestamps();

        $table->foreign('from_id')->references('id')->on('users')->onDelete('cascade');
        $table->foreign('to_id')->references('id')->on('users')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
