@extends('layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto space-y-10">
    <div class="fade-element">
        <h1 class="text-4xl font-extrabold text-primary tracking-tighter mb-2">Añadir servicio</h1>
        <p class="text-on-surface-variant font-medium">Define un nuevo procedimiento médico para tu catálogo.</p>
    </div>

    <form action="{{ route('servicios.store') }}" method="POST" class="bg-white p-10 rounded-[2.5rem] ambient-shadow border border-surface-container space-y-8">
        @csrf
        <div>
            <label class="block text-xs font-black text-on-surface mb-3 tracking-widest uppercase ml-1">Nombre del Servicio *</label>
            <input type="text" name="name" required class="block w-full px-6 py-4 border border-surface-container rounded-2xl focus:ring-4 focus:ring-primary/10 transition-all font-bold text-lg" placeholder="Ej. Evaluación preoperatoria">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-end">
            <div>
                <label class="block text-xs font-black text-on-surface mb-3 tracking-widest uppercase ml-1">Precio</label>
                <div class="relative">
                    <span class="absolute left-6 top-1/2 -translate-y-1/2 text-primary font-black">$</span>
                    <input type="number" name="price" required class="block w-full pl-12 pr-6 py-4 border-2 border-primary rounded-2xl focus:ring-4 focus:ring-primary/10 transition-all font-black text-xl text-primary" placeholder="45">
                </div>
            </div>
            <div class="pb-2">
                <label class="flex items-center gap-4 cursor-pointer group p-4 rounded-xl border-2 border-surface-container has-[:checked]:border-primary transition-all">
                    <span class="text-xs font-black text-primary uppercase tracking-widest">Desde</span>
                    <input type="checkbox" name="price_from" value="1" class="w-6 h-6 rounded-lg text-primary focus:ring-primary border-surface-container-highest">
                </label>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div>
                <label class="block text-xs font-black text-on-surface mb-3 tracking-widest uppercase ml-1">Color del Servicio</label>
                <div class="flex items-center gap-4 border border-surface-container rounded-2xl px-4 py-2">
                    <input type="color" name="color" value="#00478d" class="w-12 h-12 rounded-xl border-none p-0 cursor-pointer overflow-hidden shadow-inner">
                    <span class="text-xs font-bold text-outline uppercase tracking-widest">Elegir color</span>
                </div>
            </div>
            <div>
                <label class="block text-xs font-black text-on-surface mb-3 tracking-widest uppercase ml-1">Duración estimada *</label>
                <div class="relative">
                    <select name="duration" class="block w-full px-6 py-4 border border-surface-container rounded-2xl appearance-none font-bold text-on-surface-variant focus:ring-4 focus:ring-primary/10 transition-all">
                        <option value="15">15 Minutos</option>
                        <option value="30">30 Minutos</option>
                        <option value="45" selected>45 Minutos</option>
                        <option value="60">1 Hora</option>
                        <option value="90">1.5 Horas</option>
                        <option value="120">2 Horas</option>
                    </select>
                    <i class="fa-solid fa-chevron-down absolute right-6 top-1/2 -translate-y-1/2 text-outline pointer-events-none text-xs"></i>
                </div>
            </div>
        </div>

        <div>
            <label class="block text-xs font-black text-on-surface mb-3 tracking-widest uppercase ml-1">Ícono del Procedimiento</label>
            <div class="grid grid-cols-4 sm:grid-cols-6 gap-4">
                @foreach([
                    'fa-stethoscope' => 'Consulta',
                    'fa-file-waveform' => 'Examen',
                    'fa-vial' => 'Lab',
                    'fa-briefcase-medical' => 'Plan',
                    'fa-bed' => 'Cirugía',
                    'fa-headset' => 'Tele',
                    'fa-microscope' => 'Patología',
                    'fa-user-nurse' => 'Curación'
                ] as $iconClass => $label)
                <label class="cursor-pointer group">
                    <input type="radio" name="icon" value="{{ $iconClass }}" {{ $loop->first ? 'checked' : '' }} class="hidden peer">
                    <div class="flex flex-col items-center gap-2 p-4 rounded-2xl border-2 border-surface-container transition-all group-hover:border-primary/30 peer-checked:border-primary peer-checked:bg-primary/5">
                        <i class="fa-solid {{ $iconClass }} text-xl text-outline group-hover:text-primary peer-checked:text-primary transition-colors"></i>
                        <span class="text-[9px] font-black uppercase text-outline group-hover:text-primary peer-checked:text-primary text-center leading-tight">{{ $label }}</span>
                    </div>
                </label>
                @endforeach
            </div>
        </div>

        <div>
            <label class="block text-xs font-black text-on-surface mb-3 tracking-widest uppercase ml-1">Descripción</label>
            <textarea name="description" rows="4" class="block w-full px-6 py-4 border border-surface-container rounded-2xl focus:ring-4 focus:ring-primary/10 transition-all font-medium resize-none shadow-inner" placeholder="Historia clínica, evaluación de riesgo y plan de indicaciones pre-Qx."></textarea>
        </div>

        <div class="flex flex-col sm:flex-row gap-6 pt-6">
            <a href="{{ route('servicios.index') }}" class="flex-1 text-center py-5 border-2 border-surface-container rounded-2xl font-black text-outline hover:bg-surface-container-low transition-all uppercase text-xs">
                Cancelar
            </a>
            <button type="submit" class="flex-[2] hero-gradient text-white py-5 rounded-2xl font-black text-lg shadow-xl hover:scale-[1.02] transition-all flex items-center justify-center gap-3">
                <i class="fa-solid fa-plus text-lg"></i> Guardar servicio
            </button>
        </div>
    </form>
</div>
@endsection
