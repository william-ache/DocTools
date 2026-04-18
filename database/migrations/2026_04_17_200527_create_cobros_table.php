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
        Schema::create('cobros', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('tenant_id')->constrained('tenants')->onDelete('cascade');
            $table->foreignUuid('patient_id')->constrained('patients')->onDelete('cascade');
            $table->foreignUuid('appointment_id')->nullable()->constrained('appointments')->onDelete('set null');
            $table->foreignUuid('metodo_pago_id')->constrained('metodos_pago')->onDelete('cascade');
            
            $table->decimal('amount', 15, 2);
            $table->string('currency')->default('USD');
            $table->decimal('exchange_rate', 15, 4)->default(1.0000);
            $table->string('reference')->nullable();
            $table->text('notes')->nullable();
            $table->string('status')->default('completado');
            $table->dateTime('payment_date');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cobros');
    }
};
