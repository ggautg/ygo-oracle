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
        Schema::create('oracle_postures', function (Blueprint $table) {
            $table->id();
            $table->string('posture')->unique(); // 'ofensiva', 'equilibrada', 'defensiva'
            $table->string('label');
            $table->string('icon'); // nombre del ícono de Lucide
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oracle_postures');
    }
};
