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
        // create_oracle_numbers_table
Schema::create('oracle_numbers', function (Blueprint $table) {
    $table->id();
    $table->unsignedTinyInteger('number')->unique();  // 1 al 9
    $table->text('meaning');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oracle_numbers');
    }
};
