<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;

class CompileAll extends Command
{
    /**
     * El nombre y firma del comando.
     */
    protected $signature = 'app:compile-all {--target=all : El target a compilar (windows, mac, linux, all)}';

    /**
     * Descripción del comando.
     */
    protected $description = 'Compila el sistema para múltiples plataformas y organiza los instaladores en public/downloads';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $target = $this->option('target');
        $this->info("🚀 Iniciando proceso de compilación global para: " . strtoupper($target));

        $basePath = public_path('downloads');
        $platforms = ['windows', 'mac', 'linux', 'android'];

        // 1. Asegurar directorios
        foreach ($platforms as $p) {
            if (!File::exists($basePath . '/' . $p)) {
                File::makeDirectory($basePath . '/' . $p, 0755, true);
            }
        }

        // 2. Compilación de Desktop (NativePHP)
        if ($target === 'all' || in_array($target, ['windows', 'mac', 'linux'])) {
            $this->compileDesktop($target);
        }

        // 3. Compilación de Android (Capacitor Placeholder)
        if ($target === 'all' || $target === 'android') {
            $this->compileAndroid();
        }

        $this->info("✅ Proceso finalizado. Los instaladores están en public/downloads/");
    }

    protected function compileDesktop($target)
    {
        $targets = ($target === 'all') ? ['win', 'mac', 'linux'] : [$this->mapTarget($target)];

        foreach ($targets as $t) {
            $this->line("📦 Compilando para Desktop Target: {$t}...");
            
            // Ejecutar build de NativePHP sin límite de tiempo (timeout 0)
            $process = Process::path(base_path())
                ->timeout(0) // Sin límite de tiempo
                ->run("php artisan native:build {$t}");

            if ($process->successful()) {
                $this->info("✨ Compilación de {$t} exitosa.");
                $this->moveDesktopFiles($t);
            } else {
                $this->error("❌ Error al compilar para {$t}: " . $process->errorOutput());
            }
        }
    }

    protected function mapTarget($target)
    {
        $map = ['windows' => 'win', 'mac' => 'mac', 'linux' => 'linux'];
        return $map[$target] ?? $target;
    }

    protected function mapTargetToName($target)
    {
        $map = ['win' => 'windows', 'mac' => 'mac', 'linux' => 'linux'];
        return $map[$target] ?? $target;
    }

    protected function moveDesktopFiles($os)
    {
        $distPath = base_path('dist');
        if (!File::exists($distPath)) {
            $this->error("No se encontró la carpeta 'dist'.");
            return;
        }

        $targetPath = public_path("downloads/{$os}");
        if (!File::exists($targetPath)) {
            File::makeDirectory($targetPath, 0755, true);
        }

        // Limpiar descargas anteriores para evitar confusión
        File::cleanDirectory($targetPath);

        $files = File::files($distPath);
        $moved = false;

        foreach ($files as $file) {
            $filename = $file->getFilename();
            $extension = $file->getExtension();

            // Lógica inteligente para identificar el INSTALADOR real
            // 1. Debe ser .exe (Windows), .dmg/.pkg (Mac) o .AppImage/deb (Linux)
            // 2. NO debe ser uno de los binarios de soporte internos
            $supportBinaries = ['php.exe', 'windows-kill.exe', 'hiddeninput.exe', 'DocTools.exe', 'consultia.exe'];
            
            $isInstaller = false;
            
            if ($os === 'windows' && $extension === 'exe' && !in_array(strtolower($filename), $supportBinaries)) {
                // En Windows, buscamos el "Setup" o el ejecutable que NO sea el binario interno
                if (str_contains(strtolower($filename), 'setup') || str_contains(strtolower($filename), 'doctools')) {
                    $isInstaller = true;
                }
            } elseif ($os === 'mac' && ($extension === 'dmg' || $extension === 'pkg')) {
                $isInstaller = true;
            } elseif ($os === 'linux' && ($extension === 'AppImage' || $extension === 'deb')) {
                $isInstaller = true;
            }

            if ($isInstaller) {
                File::copy($file->getRealPath(), $targetPath . '/' . $filename);
                $this->info("✅ Instalador copiado: {$filename}");
                $moved = true;
            }
        }

        if (!$moved) {
            $this->warn("⚠️ No se encontró un instalador final en 'dist/'. Asegúrate de que 'npm run build' haya terminado exitosamente.");
        }
    }

    protected function compileAndroid()
    {
        $this->line("📱 Iniciando compilación para Android (Capacitor)...");
        
        // Asumiendo que Capacitor está configurado
        if (File::exists(base_path('android'))) {
            $process = Process::path(base_path())->run("npx cap build android");
            if ($process->successful()) {
                $this->info("✨ Build de Android finalizado.");
                // Aquí se movería el APK generado de android/app/build/outputs/apk/debug/app-debug.apk
            } else {
                $this->warn("⚠️ No se pudo completar el build de Android. Asegúrate de tener Android Studio y el SDK configurado.");
            }
        } else {
            $this->warn("⚠️ Directorio 'android' no encontrado. Ejecuta 'npx cap add android' primero.");
        }
    }
}
