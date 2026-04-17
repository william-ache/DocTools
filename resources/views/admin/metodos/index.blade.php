@extends('layouts.admin')

@section('content')
<div class="space-y-10">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
        <div>
            <h1 class="text-4xl font-extrabold text-primary tracking-tighter mb-2">Gestión de Finanzas</h1>
            <p class="text-on-surface-variant font-medium">Configura las cuentas y métodos de pago que manejas en tu práctica.</p>
        </div>
        <a href="{{ route('metodos.create') }}" class="hero-gradient text-white px-8 py-4 rounded-2xl font-bold text-sm shadow-lg hover:shadow-xl transition-all scale-95 hover:scale-100 flex items-center gap-2">
            Nuevo Método <span class="text-lg">+</span>
        </a>
    </div>

    <!-- Stats Summary -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-7 rounded-[2rem] ambient-shadow border border-surface-container">
            <p class="text-[10px] font-black text-outline uppercase tracking-widest mb-4">MÉTODOS ACTIVOS</p>
            <p class="text-3xl font-black text-primary">{{ $metodos->count() }}</p>
        </div>
        <div class="bg-primary/5 p-7 rounded-[2rem] border border-primary/10">
            <p class="text-[10px] font-black text-primary uppercase tracking-widest mb-4">TIPO MÁS USADO</p>
            <p class="text-3xl font-black text-primary">Digital</p>
        </div>
        <div class="bg-secondary-container/20 p-7 rounded-[2rem] border border-secondary-container/30">
            <p class="text-[10px] font-black text-outline uppercase tracking-widest mb-4">CUENTAS VINCULADAS</p>
            <p class="text-3xl font-black text-primary">{{ $metodos->where('type', 'Digital')->count() }}</p>
        </div>
    </div>

    <!-- Lista -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($metodos as $metodo)
        <div class="bg-white p-8 rounded-[2.5rem] ambient-shadow border border-surface-container relative group transition-all hover:translate-y-[-4px]">
            <div class="flex flex-col h-full">
                <div class="flex justify-between items-start mb-6">
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center transition-all shadow-inner" style="background-color: {{ $metodo->color }}20; color: {{ $metodo->color }};">
                        <i class="fa-solid {{ $metodo->icon ?? 'fa-credit-card' }} text-2xl"></i>
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('metodos.edit', $metodo) }}" class="p-2 rounded-lg bg-surface-container-low text-outline hover:text-primary transition-all">
                            <i class="fa-solid fa-pen text-sm"></i>
                        </a>
                    </div>
                </div>

                <div class="flex-1">
                    <h3 class="text-xl font-black text-primary tracking-tight mb-1">{{ $metodo->name }}</h3>
                    <span class="text-[10px] font-black uppercase tracking-widest text-outline bg-surface-container-low px-2 py-1 rounded-md">{{ $metodo->type }}</span>
                    
                    <p class="mt-4 text-xs font-medium text-on-surface-variant leading-relaxed">
                        {{ $metodo->details ?? 'Sin detalles de cuenta especificados.' }}
                    </p>
                </div>

                <div class="mt-8 pt-6 border-t border-surface-container-low flex items-center justify-between">
                    <span class="flex items-center gap-2" style="color: {{ $metodo->is_active ? $metodo->color : 'var(--outline)' }}">
                        <span class="w-1.5 h-1.5 rounded-full" style="background-color: {{ $metodo->is_active ? $metodo->color : 'var(--outline)' }}"></span>
                        <span class="text-[10px] font-black uppercase tracking-widest">{{ $metodo->is_active ? 'Disponible' : 'Inactivo' }}</span>
                    </span>
                    <form action="{{ route('metodos.toggle-status', $metodo) }}" method="POST">
                        @csrf
                        <button type="submit" class="relative inline-flex h-5 w-10 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $metodo->is_active ? 'bg-primary' : 'bg-surface-container-highest' }}">
                            <span aria-hidden="true" class="pointer-events-none inline-block h-4 w-4 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $metodo->is_active ? 'translate-x-5' : 'translate-x-0' }}"></span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
