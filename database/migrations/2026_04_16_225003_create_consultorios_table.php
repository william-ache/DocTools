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
        Schema::create('consultorios', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->text('indications')->nullable();
            $table->decimal('lat', 10, 8)->nullable();
            $table->decimal('lng', 11, 8)->nullable();
            
            // Appointment Configuration
            $table->integer('rest_time_between_appointments')->default(15);
            $table->integer('max_days_anticipation')->default(30);
            $table->integer('standard_appointment_duration')->default(30);
            $table->string('timezone')->default('America/Caracas');
            
            $table->boolean('is_online_booking_enabled')->default(true);
            $table->boolean('whatsapp_reminders')->default(true);
            $table->boolean('accept_bookings')->default(true);
            $table->boolean('booking_notifications')->default(true);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultorios');
    }
};
