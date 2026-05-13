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
        Schema::create('doc_ia_interactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type')->nullable(); // transcription, diagnosis_help, etc.
            $table->json('payload')->nullable(); // interaction data
            $table->text('response')->nullable(); // IA response
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doc_ia_interactions');
    }
};
