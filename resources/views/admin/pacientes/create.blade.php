@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto space-y-10">
    <div class="fade-element">
        <h1 class="text-4xl font-extrabold text-primary tracking-tighter mb-2">Nuevo Paciente</h1>
        <p class="text-on-surface-variant font-medium text-lg">Crea un nuevo expediente para tu registro médico.</p>
    </div>

    <form action="{{ route('pacientes.store') }}" method="POST" class="space-y-8">
        @csrf
        
        <!-- Sección: Información Personal -->
        <div class="bg-white p-10 rounded-[2.5rem] ambient-shadow border border-surface-container space-y-8">
            <div class="flex items-center gap-4 mb-2">
                <i class="fa-solid fa-user-plus text-primary text-xl"></i>
                <h3 class="text-lg font-black text-primary tracking-tight uppercase italic">Información Personal</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label class="block text-xs font-black text-on-surface mb-3 tracking-widest uppercase ml-1">Nombre *</label>
                    <input type="text" name="name" required class="block w-full px-6 py-4 border border-surface-container rounded-2xl focus:ring-4 focus:ring-primary/10 transition-all font-bold text-lg" placeholder="Nombre">
                </div>
                <div>
                    <label class="block text-xs font-black text-on-surface mb-3 tracking-widest uppercase ml-1">Apellido *</label>
                    <input type="text" name="last_name" required class="block w-full px-6 py-4 border border-surface-container rounded-2xl focus:ring-4 focus:ring-primary/10 transition-all font-bold text-lg" placeholder="Apellido">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <label class="block text-xs font-black text-on-surface mb-3 tracking-widest uppercase ml-1">Cédula / ID</label>
                    <input type="text" name="id_number" class="block w-full px-6 py-4 border border-surface-container rounded-2xl focus:ring-4 focus:ring-primary/10 transition-all font-bold" placeholder="Ejem: V-12.345.678">
                </div>
                <div>
                    <label class="block text-xs font-black text-on-surface mb-3 tracking-widest uppercase ml-1">Fecha de Nacimiento</label>
                    <input type="date" name="birth_date" class="block w-full px-6 py-4 border border-surface-container rounded-2xl focus:ring-4 focus:ring-primary/10 transition-all font-bold text-on-surface-variant">
                </div>
                <div>
                    <label class="block text-xs font-black text-on-surface mb-3 tracking-widest uppercase ml-1">Género</label>
                    <div class="relative">
                        <select name="gender" class="block w-full px-6 py-4 border border-surface-container rounded-2xl appearance-none font-bold text-on-surface-variant focus:ring-4 focus:ring-primary/10 transition-all">
                            <option value="">Seleccionar...</option>
                            <option value="Masculino">Masculino</option>
                            <option value="Femenino">Femenino</option>
                            <option value="Otro">Otro / No binario</option>
                        </select>
                        <i class="fa-solid fa-chevron-down absolute right-6 top-1/2 -translate-y-1/2 text-outline pointer-events-none text-xs"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sección: Contacto -->
        <div class="bg-white p-10 rounded-[2.5rem] ambient-shadow border border-surface-container space-y-8">
            <div class="flex items-center gap-4 mb-2">
                <i class="fa-solid fa-address-book text-primary text-xl"></i>
                <h3 class="text-lg font-black text-primary tracking-tight uppercase italic">Contacto</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label class="block text-xs font-black text-on-surface mb-3 tracking-widest uppercase ml-1">Teléfono</label>
                    <input type="text" name="phone" class="block w-full px-6 py-4 border border-surface-container rounded-2xl focus:ring-4 focus:ring-primary/10 transition-all font-bold" placeholder="+58 414 ...">
                </div>
                <div>
                    <label class="block text-xs font-black text-on-surface mb-3 tracking-widest uppercase ml-1">Correo Electrónico</label>
                    <input type="email" name="email" class="block w-full px-6 py-4 border border-surface-container rounded-2xl focus:ring-4 focus:ring-primary/10 transition-all font-bold" placeholder="paciente@correo.com">
                </div>
            </div>

            <div>
                <label class="block text-xs font-black text-on-surface mb-3 tracking-widest uppercase ml-1">Dirección de Habitación</label>
                <textarea name="address" rows="2" class="block w-full px-6 py-4 border border-surface-container rounded-2xl focus:ring-4 focus:ring-primary/10 transition-all font-medium resize-none shadow-inner" placeholder="Dirección completa..."></textarea>
            </div>
        </div>

        <!-- Sección: Notas Médicas -->
        <div class="bg-white p-10 rounded-[2.5rem] ambient-shadow border border-surface-container space-y-8">
            <div class="flex items-center gap-4 mb-2">
                <i class="fa-solid fa-notes-medical text-primary text-xl"></i>
                <h3 class="text-lg font-black text-primary tracking-tight uppercase italic">Observaciones Médicas Rápidas</h3>
            </div>
            <div>
                <textarea name="medical_notes" rows="4" class="block w-full px-6 py-4 border border-surface-container rounded-2xl focus:ring-4 focus:ring-primary/10 transition-all font-medium resize-none shadow-inner" placeholder="Ejem: Alérgico a la penicilina, hipertenso..."></textarea>
            </div>
        </div>

        <!-- Footer -->
        <div class="flex flex-col sm:flex-row gap-6 pt-6 mb-20">
            <a href="{{ route('pacientes.index') }}" class="flex-1 text-center py-5 border-2 border-surface-container rounded-2xl font-black text-outline hover:bg-surface-container-low transition-all uppercase text-xs">
                Cancelar
            </a>
            <button type="submit" class="flex-[2] hero-gradient text-white py-5 rounded-2xl font-black text-lg shadow-xl hover:scale-[1.02] transition-all flex items-center justify-center gap-3">
                <i class="fa-solid fa-user-check"></i> Registrar Paciente
            </button>
        </div>
    </form>
</div>
@endsection
