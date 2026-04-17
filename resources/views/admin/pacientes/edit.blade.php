@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto space-y-10">
    <div class="fade-element">
        <h1 class="text-4xl font-extrabold text-primary tracking-tighter mb-2">Editar Expediente</h1>
        <p class="text-on-surface-variant font-medium text-lg">Actualiza la información del paciente.</p>
    </div>

    <form action="{{ route('pacientes.update', $paciente) }}" method="POST" class="space-y-8">
        @csrf
        @method('PUT')
        
        <!-- Sección: Información Personal -->
        <div class="bg-white p-10 rounded-[2.5rem] ambient-shadow border border-surface-container space-y-8">
            <div class="flex items-center gap-4 mb-2">
                <i class="fa-solid fa-user-pen text-primary text-xl"></i>
                <h3 class="text-lg font-black text-primary tracking-tight uppercase italic">Información Personal</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label class="block text-xs font-black text-on-surface mb-3 tracking-widest uppercase ml-1">Nombre *</label>
                    <input type="text" name="name" required value="{{ $paciente->name }}" class="block w-full px-6 py-4 border border-surface-container rounded-2xl focus:ring-4 focus:ring-primary/10 transition-all font-bold text-lg">
                </div>
                <div>
                    <label class="block text-xs font-black text-on-surface mb-3 tracking-widest uppercase ml-1">Apellido *</label>
                    <input type="text" name="last_name" required value="{{ $paciente->last_name }}" class="block w-full px-6 py-4 border border-surface-container rounded-2xl focus:ring-4 focus:ring-primary/10 transition-all font-bold text-lg">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <label class="block text-xs font-black text-on-surface mb-3 tracking-widest uppercase ml-1">Cédula / ID</label>
                    <input type="text" name="id_number" value="{{ $paciente->id_number }}" class="block w-full px-6 py-4 border border-surface-container rounded-2xl focus:ring-4 focus:ring-primary/10 transition-all font-bold">
                </div>
                <div>
                    <label class="block text-xs font-black text-on-surface mb-3 tracking-widest uppercase ml-1">Fecha de Nacimiento</label>
                    <input type="date" name="birth_date" value="{{ $paciente->birth_date ? $paciente->birth_date->format('Y-m-d') : '' }}" class="block w-full px-6 py-4 border border-surface-container rounded-2xl focus:ring-4 focus:ring-primary/10 transition-all font-bold text-on-surface-variant">
                </div>
                <div>
                    <label class="block text-xs font-black text-on-surface mb-3 tracking-widest uppercase ml-1">Género</label>
                    <div class="relative">
                        <select name="gender" class="block w-full px-6 py-4 border border-surface-container rounded-2xl appearance-none font-bold text-on-surface-variant focus:ring-4 focus:ring-primary/10 transition-all">
                            <option value="Masculino" {{ $paciente->gender == 'Masculino' ? 'selected' : '' }}>Masculino</option>
                            <option value="Femenino" {{ $paciente->gender == 'Femenino' ? 'selected' : '' }}>Femenino</option>
                            <option value="Otro" {{ $paciente->gender == 'Otro' ? 'selected' : '' }}>Otro / No binario</option>
                        </select>
                        <i class="fa-solid fa-chevron-down absolute right-6 top-1/2 -translate-y-1/2 text-outline pointer-events-none text-xs"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white p-10 rounded-[2.5rem] ambient-shadow border border-surface-container space-y-8">
            <div class="flex items-center gap-4 mb-2">
                <i class="fa-solid fa-address-book text-primary text-xl"></i>
                <h3 class="text-lg font-black text-primary tracking-tight uppercase italic">Contacto</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label class="block text-xs font-black text-on-surface mb-3 tracking-widest uppercase ml-1">Teléfono</label>
                    <input type="text" name="phone" value="{{ $paciente->phone }}" class="block w-full px-6 py-4 border border-surface-container rounded-2xl focus:ring-4 focus:ring-primary/10 transition-all font-bold">
                </div>
                <div>
                    <label class="block text-xs font-black text-on-surface mb-3 tracking-widest uppercase ml-1">Correo Electrónico</label>
                    <input type="email" name="email" value="{{ $paciente->email }}" class="block w-full px-6 py-4 border border-surface-container rounded-2xl focus:ring-4 focus:ring-primary/10 transition-all font-bold">
                </div>
            </div>
            <div>
                <label class="block text-xs font-black text-on-surface mb-3 tracking-widest uppercase ml-1">Dirección</label>
                <textarea name="address" rows="2" class="block w-full px-6 py-4 border border-surface-container rounded-2xl focus:ring-4 focus:ring-primary/10 transition-all font-medium resize-none shadow-inner">{{ $paciente->address }}</textarea>
            </div>
        </div>

        <div class="bg-white p-10 rounded-[2.5rem] ambient-shadow border border-surface-container space-y-8">
            <div class="flex items-center gap-4 mb-2">
                <i class="fa-solid fa-notes-medical text-primary text-xl"></i>
                <h3 class="text-lg font-black text-primary tracking-tight uppercase italic">Observaciones Médicas Rápidas</h3>
            </div>
            <textarea name="medical_notes" rows="4" class="block w-full px-6 py-4 border border-surface-container rounded-2xl focus:ring-4 focus:ring-primary/10 transition-all font-medium resize-none shadow-inner">{{ $paciente->medical_notes }}</textarea>
        </div>

        <div class="flex flex-col sm:flex-row gap-6 pt-6 tracking-wide">
            <a href="{{ route('pacientes.index') }}" class="flex-1 text-center py-5 border-2 border-surface-container rounded-2xl font-black text-outline hover:bg-surface-container-low transition-all uppercase text-xs">
                Cancelar
            </a>
            <button type="submit" class="flex-[2] hero-gradient text-white py-5 rounded-2xl font-black text-lg shadow-xl hover:scale-[1.02] transition-all flex items-center justify-center gap-3">
                <i class="fa-solid fa-sync"></i> Actualizar Expediente
            </button>
        </div>
    </form>
    
    <div class="pt-10 border-t border-surface-container mb-20">
        <form action="{{ route('pacientes.destroy', $paciente) }}" method="POST" class="confirm-delete">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-error font-bold flex items-center gap-3 hover:underline text-sm uppercase tracking-widest">
                <i class="fa-solid fa-trash-can"></i> Eliminar expediente definitivamente
            </button>
        </form>
    </div>
</div>
@endsection
