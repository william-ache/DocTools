@extends('layouts.app')

@section('title', 'Política de Privacidad - Dr. Javier González Pugh')

@section('content')
<section class="py-20 bg-white">
    <div class="max-w-4xl mx-auto px-6">
        <div class="mb-12 flex items-center justify-between">
            <h1 class="text-4xl md:text-5xl font-black text-primary tracking-tighter">Política de Privacidad</h1>
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-primary font-bold hover:underline transition-all">
                <i class="fa-solid fa-arrow-left"></i> Volver al inicio
            </a>
        </div>

        <div class="prose prose-lg max-w-none text-on-surface-variant font-medium leading-relaxed space-y-8">
            <div class="p-8 bg-surface-container-low rounded-[2rem] border border-surface-container">
                <h2 class="text-2xl font-black text-primary mb-4">1. Recopilación de Información</h2>
                <p>Recopilamos información que usted nos proporciona directamente al agendar una cita o contactarnos por WhatsApp. Esto incluye su nombre, número de teléfono, correo electrónico y cualquier información médica relevante para su consulta.</p>
            </div>

            <div class="p-8 bg-surface-container-low rounded-[2rem] border border-surface-container">
                <h2 class="text-2xl font-black text-primary mb-4">2. Uso de la Información</h2>
                <p>La información recopilada se utiliza exclusivamente para:</p>
                <ul class="list-disc pl-6 space-y-2">
                    <li>Gestionar sus citas médicas.</li>
                    <li>Contactarlo para recordatorios o cambios en su agenda.</li>
                    <li>Proveer la mejor atención médica posible basada en sus antecedentes.</li>
                </ul>
            </div>

            <div class="p-8 bg-surface-container-low rounded-[2rem] border border-surface-container">
                <h2 class="text-2xl font-black text-primary mb-4">3. Seguridad de los Datos</h2>
                <p>Implementamos medidas de seguridad técnicas y organizativas para proteger sus datos personales contra el acceso no autorizado, la pérdida o la alteración. Sus datos médicos son tratados con la más estricta confidencialidad profesional.</p>
            </div>

            <div class="p-8 bg-surface-container-low rounded-[2rem] border border-surface-container">
                <h2 class="text-2xl font-black text-primary mb-4">4. Sus Derechos</h2>
                <p>Usted tiene derecho a acceder, corregir o solicitar la eliminación de sus datos personales. Para ejercer estos derechos, puede contactarnos a través de los canales de atención publicados en este sitio.</p>
            </div>
        </div>

        <div class="mt-16 text-center">
            <p class="text-sm text-outline font-bold italic">Última actualización: {{ date('d/m/Y') }}</p>
        </div>
    </div>
</section>
@endsection
