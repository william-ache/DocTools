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
        Schema::create('appointments', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('consultorio_id')->nullable();
            $table->uuid('patient_id')->nullable();
            $table->datetime('start_time');
            $table->datetime('end_time');
            $table->string('status')->default('agendada'); // agendada, confirmada, completada, cancelada
            $table->text('notes')->nullable();
            $table->timestamps();

            // Foreign keys (optional but recommended)
            // You might want to add them depending on if consultorios and patients use UUIDs too.
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
