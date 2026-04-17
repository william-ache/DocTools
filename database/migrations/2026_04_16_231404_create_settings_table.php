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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('app_name')->default('Santuario Clínico');
            $table->string('primary_color')->default('#00478d');
            $table->json('enabled_modules')->nullable();
            $table->timestamps();
        });

        // Insert default setting
        DB::table('settings')->insert([
            'app_name' => 'Santuario Clínico',
            'primary_color' => '#00478d',
            'enabled_modules' => json_encode(['consultorios', 'servicios', 'finanzas', 'pacientes']),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
