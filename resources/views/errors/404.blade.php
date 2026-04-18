@extends('layouts.admin')

@section('content')
<div class="flex flex-col items-center justify-center min-h-[70vh] text-center space-y-8 fade-element">
    <div class="relative">
        <div class="w-40 h-40 bg-primary/5 rounded-[3rem] flex items-center justify-center border border-primary/10">
            <i class="fa-solid fa-file-circle-exclamation text-primary text-6xl opacity-20"></i>
        </div>
        <div class="absolute -top-4 -right-4 bg-error text-white px-4 py-2 rounded-2xl font-black text-xl shadow-lg">
            404
        </div>
    </div>
    
    <div class="max-w-md">
        <h1 class="text-3xl font-black text-primary tracking-tight mb-4">Página no encontrada</h1>
        <p class="text-on-surface-variant font-medium text-sm md:text-base leading-relaxed">
            Lo sentimos, el expediente o la sección que buscas no está disponible en este momento. Puede que haya sido movida o ya no exista.
        </p>
    </div>

    <div class="flex items-center gap-4">
        <a href="{{ route('admin.dashboard') }}" class="bg-primary text-white px-8 py-4 rounded-2xl font-black text-sm uppercase tracking-widest shadow-lg hover:scale-105 transition-all flex items-center gap-2">
            <i class="fa-solid fa-house"></i>
            Volver al Inicio
        </a>
    </div>
</div>

<style>
    .fade-element {
        opacity: 0;
        transform: translateY(20px);
        animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    @keyframes fadeInUp {
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endsection
