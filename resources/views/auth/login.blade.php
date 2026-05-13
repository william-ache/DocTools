@extends('layouts.app')

@section('title', 'Acceso Médico - DocTools')

@section('content')
<div class="min-h-screen flex items-stretch bg-white">
    <!-- Lado Izquierdo: Visual Cinematográfico -->
    <div class="hidden lg:flex w-1/2 relative overflow-hidden h-screen sticky top-0">
        <img src="{{ asset('img/consultorio.png') }}" class="absolute inset-0 w-full h-full object-cover transition-transform duration-[10s] hover:scale-110" alt="Consultorio Premium">
        <!-- Overlay de Gradient -->
        <div class="absolute inset-0 bg-gradient-to-t from-primary/80 via-transparent to-transparent"></div>
        
        <!-- Texto sobre la imagen -->
        <div class="absolute bottom-16 left-16 right-16 text-white reveal">
            <h2 class="text-5xl font-black tracking-tighter leading-none mb-6 italic">Tu gestión médica, <br>elevada al siguiente nivel.</h2>
            <div class="w-20 h-2 bg-secondary rounded-full"></div>
            <p class="mt-8 text-lg font-medium opacity-90 leading-relaxed max-w-sm">DocTools ofrece eficiencia y tecnología para que tú te enfoques en lo más importante: la salud.</p>
        </div>
        
        <!-- Badge de Seguridad Glassmorphism -->
        <div class="absolute top-10 left-10 backdrop-blur-xl bg-white/10 p-4 rounded-2xl border border-white/20 flex items-center gap-3">
            <div class="w-10 h-10 bg-green-500 rounded-xl flex items-center justify-center text-white shadow-[0_0_15px_rgba(34,197,94,0.5)]">
                <i class="fa-solid fa-shield-halved"></i>
            </div>
            <span class="text-white text-[10px] font-black uppercase tracking-widest leading-none">Acceso<br>Encriptado</span>
        </div>
    </div>

    <!-- Lado Derecho: Formulario Estilizado -->
    <div class="flex-1 flex flex-col items-center justify-center p-8 md:p-16 relative bg-surface">
        <div class="w-full max-w-md space-y-12 fade-element">
            <div class="text-center lg:text-left">
                <div class="flex justify-center lg:justify-start mb-8">
                    <div class="w-16 h-16 bg-primary/10 rounded-2xl flex items-center justify-center text-primary shadow-sm">
                        <i class="fa-solid fa-square-person-confined text-2xl"></i>
                    </div>
                </div>
                <h1 class="text-4xl font-black text-primary tracking-tighter mb-2">Panel Administrativo</h1>
                <p class="text-on-surface-variant font-medium opacity-70">Ingresa tus credenciales para continuar</p>
            </div>

            <form class="space-y-8" action="{{ route('login') }}" method="POST">
                @csrf
                <div class="space-y-6">
                    <div class="group">
                        <label class="block text-[9px] font-black text-outline uppercase tracking-[0.2em] mb-3 ml-1 group-focus-within:text-primary transition-colors">Usuario</label>
                        <div class="relative">
                             <div class="absolute inset-y-0 left-6 flex items-center pointer-events-none text-outline/50 group-focus-within:text-primary transition-colors">
                                <i class="fa-solid fa-envelope"></i>
                            </div>
                            <input name="email" type="email" required 
                                class="block w-full pl-14 pr-6 py-5 rounded-2xl bg-white border border-surface-container focus:border-primary/30 focus:ring-4 focus:ring-primary/5 transition-all font-bold placeholder:text-outline/30 placeholder:font-normal" 
                                placeholder="Escribe tu correo..." value="{{ old('email', 'admin@doctools.com') }}">
                        </div>
                    </div>

                    <div class="group">
                        <label class="block text-[9px] font-black text-outline uppercase tracking-[0.2em] mb-3 ml-1 group-focus-within:text-primary transition-colors">Contraseña</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-6 flex items-center pointer-events-none text-outline/50 group-focus-within:text-primary transition-colors">
                                <i class="fa-solid fa-lock"></i>
                            </div>
                            <input name="password" type="password" required 
                                class="block w-full pl-14 pr-6 py-5 rounded-2xl bg-white border border-surface-container focus:border-primary/30 focus:ring-4 focus:ring-primary/5 transition-all font-bold placeholder:text-outline/30 placeholder:font-normal" 
                                placeholder="••••••••" value="ServBay.dev">
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between px-1">
                        <label class="flex items-center gap-2 cursor-pointer group">
                            <input type="checkbox" name="remember" class="w-4 h-4 rounded-md border-surface-container text-primary focus:ring-primary/50 transition-all">
                            <span class="text-[11px] font-bold text-outline group-hover:text-primary transition-colors">Recordarme</span>
                        </label>
                        <a href="#" class="text-[11px] font-black text-primary hover:underline uppercase tracking-tighter">¿Olvidaste tu acceso?</a>
                    </div>
                </div>

                @if ($errors->any())
                <div class="bg-red-50 text-red-600 p-5 rounded-3xl text-[11px] font-bold ring-1 ring-red-100 animate-shake">
                    @foreach ($errors->all() as $error)
                        <p class="flex items-center gap-2"><i class="fa-solid fa-circle-exclamation"></i> {{ $error }}</p>
                    @endforeach
                </div>
                @endif

                <button type="submit" class="w-full hero-gradient text-white py-6 rounded-2xl font-black text-lg shadow-[0_20px_40px_rgba(var(--primary-rgb),0.2)] hover:shadow-[0_20px_40px_rgba(var(--primary-rgb),0.4)] hover:-translate-y-1 active:scale-95 transition-all uppercase tracking-[0.1em]">
                    Iniciar Sesión
                </button>
            </form>

            <div class="text-center pt-4">
                <a href="/" class="group text-xs font-black text-outline hover:text-primary transition-all inline-flex items-center gap-2 uppercase tracking-widest">
                    <i class="fa-solid fa-arrow-left text-[10px] group-hover:-translate-x-1 transition-transform"></i>
                    Regresar al sitio principal
                </a>
            </div>
            
            <p class="text-center text-[10px] font-bold text-outline/40 uppercase tracking-widest">DocTools v{{ env('NATIVEPHP_APP_VERSION', '1.0.0') }} • &copy; 2026</p>
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
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        25% { transform: translateX(-5px); }
        75% { transform: translateX(5px); }
    }
    .animate-shake { animation: shake 0.2s ease-in-out 0s 2; }
</style>
@endsection
