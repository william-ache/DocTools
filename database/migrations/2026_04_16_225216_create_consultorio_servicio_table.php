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
        Schema::create('consultorio_servicio', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('consultorio_id')->constrained('consultorios')->onDelete('cascade');
            $table->foreignUuid('servicio_id')->constrained('servicios')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultorio_servicio');
    }
};
