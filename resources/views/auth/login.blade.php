@extends('layouts.app')

@section('title', 'Iniciar Sesión - Dr. Javier González Pugh')

@section('content')
<div class="min-h-[calc(100vh-80px)] flex items-center justify-center bg-surface py-12 px-4 sm:px-6 lg:px-8 relative overflow-hidden">
    <!-- Decoración de fondo -->
    <div class="absolute top-0 right-0 w-96 h-96 bg-secondary/10 rounded-full blur-3xl -z-10 translate-x-1/2 -translate-y-1/2"></div>
    <div class="absolute bottom-0 left-0 w-96 h-96 bg-primary/10 rounded-full blur-3xl -z-10 -translate-x-1/2 translate-y-1/2"></div>

    <div class="max-w-md w-full space-y-8 bg-white p-10 rounded-[3rem] shadow-2xl ambient-shadow border border-surface-container fade-element">
        <div>
            <div class="flex justify-center mb-6">
                <div class="w-20 h-20 bg-primary/5 rounded-[2rem] flex items-center justify-center text-primary shadow-inner">
                    <i class="fa-solid fa-user-shield text-4xl"></i>
                </div>
            </div>
            <h2 class="text-center text-4xl font-black text-primary tracking-tighter">Acceso Médico</h2>
            <p class="mt-2 text-center text-sm text-on-surface-variant font-medium">
                Gestión administrativa de DocTools
            </p>
        </div>
        
        <form class="mt-10 space-y-8" action="{{ route('login') }}" method="POST">
            @csrf
            <div class="space-y-6">
                <div>
                    <label class="block text-[10px] font-black text-on-surface mb-3 tracking-widest uppercase ml-1">Correo Electrónico</label>
                    <input name="email" type="email" autocomplete="email" required 
                        class="block w-full px-6 py-4 rounded-2xl bg-surface-container-low border-none focus:ring-4 focus:ring-primary/10 transition-all font-bold placeholder:text-outline/50" 
                        placeholder="admin@doctools.com" value="{{ old('email') }}">
                </div>
                <div>
                    <label class="block text-[10px] font-black text-on-surface mb-3 tracking-widest uppercase ml-1">Contraseña</label>
                    <input name="password" type="password" autocomplete="current-password" required 
                        class="block w-full px-6 py-4 rounded-2xl bg-surface-container-low border-none focus:ring-4 focus:ring-primary/10 transition-all font-bold placeholder:text-outline/50" 
                        placeholder="••••••••">
                </div>
            </div>

            @if ($errors->any())
            <div class="bg-red-50 text-red-600 p-5 rounded-2xl text-xs font-bold ring-1 ring-red-100">
                @foreach ($errors->all() as $error)
                    <p class="flex items-center gap-2">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        {{ $error }}
                    </p>
                @endforeach
            </div>
            @endif

            <button type="submit" class="w-full hero-gradient text-white py-5 rounded-2xl font-black text-lg shadow-xl hover:scale-[1.02] transition-all uppercase tracking-widest">
                Entrar al Panel
            </button>
        </form>
        
        <div class="text-center pt-4">
            <a href="/" class="text-xs font-black text-outline hover:text-primary transition-all inline-flex items-center gap-2 uppercase tracking-widest">
                <i class="fa-solid fa-arrow-left text-[10px]"></i>
                Volver al Inicio
            </a>
        </div>
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
