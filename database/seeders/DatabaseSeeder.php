<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\MetodoPago;
use App\Models\Servicio;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // 2. Usuario Administrador Base vinculado al Tenant
        User::updateOrCreate(
            ['email' => 'admin@doctools.com'],
            [
                'name' => 'Dr. Javier Admin',
                'specialty' => 'Otorrinolaringólogo',
                'password' => Hash::make('ServBay.dev'),
            ]
        );

        // 3. Configuraciones Base
        Setting::updateOrCreate(
            ['id' => 1],
            [
                'app_name' => 'Consultia',
                'primary_color' => '#00478d',
                'enabled_modules' => ['consultorios', 'servicios', 'finanzas', 'pacientes'],
            ]
        );

        // 4. Métodos de Pago Premium vinculados al Tenant
        $metodos = [
            ['name' => 'Zelle', 'type' => 'Digital', 'icon' => 'fa-wallet', 'color' => '#632C94', 'details' => 'correo@ejemplo.com'],
            ['name' => 'Binance Pay', 'type' => 'Crypto', 'icon' => 'fa-bitcoin-sign', 'color' => '#F3BA2F', 'details' => 'ID: 12345678'],
            ['name' => 'Efectivo USD', 'type' => 'Cash', 'icon' => 'fa-money-bill-1', 'color' => '#2E7D32', 'details' => 'Pagos en taquilla'],
            ['name' => 'Pago Móvil', 'type' => 'Digital', 'icon' => 'fa-mobile-screen-button', 'color' => '#D32F2F', 'details' => '0414-1234567 / V-12345678 / Banco'],
            ['name' => 'Transferencia Banesco', 'type' => 'Digital', 'icon' => 'fa-building-columns', 'color' => '#0066B3', 'details' => 'Cta: 0134...'],
        ];

        foreach ($metodos as $m) {
            MetodoPago::updateOrCreate(['name' => $m['name']], $m);
        }

        // 5. Servicios Premium vinculados al Tenant
        $servicios = [
            [
                'name' => 'Primera consulta', 
                'duration' => 45, 
                'price' => 50, 
                'is_active' => true, 
                'icon' => 'fa-stethoscope', 
                'color' => '#00478d',
                'description' => 'Evaluación integral inicial, apertura de historia clínica y diagnóstico primario.'
            ],
            [
                'name' => 'Consulta de control', 
                'duration' => 30, 
                'price' => 30, 
                'is_active' => true, 
                'icon' => 'fa-file-waveform', 
                'color' => '#008577',
                'description' => 'Seguimiento de tratamiento y evolución del paciente.'
            ],
            [
                'name' => 'Teleconsulta', 
                'duration' => 30, 
                'price' => 25, 
                'is_active' => true, 
                'icon' => 'fa-headset', 
                'color' => '#6200EE',
                'description' => 'Atención médica remota mediante videollamada segura.'
            ],
        ];

        foreach ($servicios as $s) {
            Servicio::updateOrCreate(['name' => $s['name']], $s);
        }
    }
}
