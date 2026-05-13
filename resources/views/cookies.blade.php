@extends('layouts.app')

@section('title', 'Política de Cookies - Dr. Javier González Pugh')

@section('content')
<section class="py-20 bg-white">
    <div class="max-w-4xl mx-auto px-6">
        <div class="mb-12 flex items-center justify-between">
            <h1 class="text-4xl md:text-5xl font-black text-primary tracking-tighter">Política de Cookies</h1>
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-primary font-bold hover:underline transition-all">
                <i class="fa-solid fa-arrow-left"></i> Volver al inicio
            </a>
        </div>

        <div class="prose prose-lg max-w-none text-on-surface-variant font-medium leading-relaxed space-y-8">
            <div class="p-8 bg-surface-container-low rounded-[2rem] border border-surface-container">
                <h2 class="text-2xl font-black text-primary mb-4">¿Qué son las Cookies?</h2>
                <p>Las cookies son pequeños archivos de texto que los sitios web almacenan en su navegador para recordar información sobre su visita, como sus preferencias o su estado de sesión.</p>
            </div>

            <div class="p-8 bg-surface-container-low rounded-[2rem] border border-surface-container">
                <h2 class="text-2xl font-black text-primary mb-4">Uso de Cookies en este sitio</h2>
                <p>Utilizamos cookies por los siguientes motivos:</p>
                <ul class="list-disc pl-6 space-y-2">
                    <li><strong>Cookies Esenciales:</strong> Necesarias para el funcionamiento básico del sistema de citas y autenticación.</li>
                    <li><strong>Preferencias:</strong> Para recordar sus ajustes de visualización.</li>
                    <li><strong>Análisis:</strong> Para entender cómo los usuarios interactúan con nuestra página y mejorar nuestros servicios.</li>
                </ul>
            </div>

            <div class="p-8 bg-surface-container-low rounded-[2rem] border border-surface-container">
                <h2 class="text-2xl font-black text-primary mb-4">Cómo controlar las Cookies</h2>
                <p>Usted puede desactivar o eliminar las cookies en cualquier momento a través de la configuración de su navegador. Tenga en cuenta que esto podría afectar la funcionalidad de algunas partes del sitio.</p>
            </div>
        </div>

        <div class="mt-16 text-center">
            <p class="text-sm text-outline font-bold italic">Última actualización: {{ date('d/m/Y') }}</p>
        </div>
    </div>
</section>
@endsection
