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
    Schema::create('ygo_cards', function (Blueprint $table) {
        $table->id();
        $table->unsignedInteger('ygo_id')->unique();
        $table->string('name');
        $table->string('type');
        $table->string('frame_type');
        $table->string('race')->nullable();
        $table->string('attribute')->nullable();
        $table->unsignedTinyInteger('level')->nullable();
        $table->unsignedTinyInteger('linkval')->nullable();
        $table->json('link_markers')->nullable();
        $table->unsignedTinyInteger('pendulum_scale')->nullable();
        $table->integer('atk')->nullable();
        $table->integer('def')->nullable();
        $table->text('description');
        $table->string('archetype')->nullable();
        $table->string('banlist_status')->nullable();
        $table->string('image_url')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ygo_cards');
    }
};
