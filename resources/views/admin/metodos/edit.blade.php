@extends('layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto space-y-10">
    <div class="fade-element">
        <h1 class="text-4xl font-extrabold text-primary tracking-tighter mb-2">Editar Método: {{ $metodo->name }}</h1>
        <p class="text-on-surface-variant font-medium text-lg">Actualiza la configuración o detalles de este método de pago.</p>
    </div>

    <form action="{{ route('metodos.update', $metodo) }}" method="POST" class="bg-white p-10 rounded-[2.5rem] ambient-shadow border border-surface-container space-y-8">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="md:col-span-2">
                <label class="block text-xs font-black text-on-surface mb-3 tracking-widest uppercase ml-1">Nombre del Método *</label>
                <input type="text" name="name" value="{{ $metodo->name }}" required class="block w-full px-6 py-4 border border-surface-container rounded-2xl focus:ring-4 focus:ring-primary/10 transition-all font-bold text-lg" placeholder="Ej. Zelle, Banesco Pago Móvil...">
            </div>

            <div>
                <label class="block text-xs font-black text-on-surface mb-3 tracking-widest uppercase ml-1">Tipo de Cuenta</label>
                <div class="relative">
                    <select name="type" class="block w-full px-6 py-4 border border-surface-container rounded-2xl appearance-none font-bold text-on-surface-variant focus:ring-4 focus:ring-primary/10 transition-all">
                        <option value="Digital" {{ $metodo->type == 'Digital' ? 'selected' : '' }}>Transferencia / Digital</option>
                        <option value="Cash" {{ $metodo->type == 'Cash' ? 'selected' : '' }}>Efectivo</option>
                        <option value="Crypto" {{ $metodo->type == 'Crypto' ? 'selected' : '' }}>Criptomoneda</option>
                        <option value="Card" {{ $metodo->type == 'Card' ? 'selected' : '' }}>Tarjeta de Crédito/Débito</option>
                    </select>
                    <i class="fa-solid fa-chevron-down absolute right-6 top-1/2 -translate-y-1/2 text-outline pointer-events-none text-xs"></i>
                </div>
            </div>

            <div>
                <label class="block text-xs font-black text-on-surface mb-3 tracking-widest uppercase ml-1">Color Representativo</label>
                <div class="flex items-center gap-4 border border-surface-container rounded-2xl px-4 py-2">
                    <input type="color" name="color" value="{{ $metodo->color ?? '#00478d' }}" class="w-12 h-12 rounded-xl border-none p-0 cursor-pointer overflow-hidden shadow-inner">
                    <span class="text-xs font-bold text-outline uppercase tracking-widest">Cambiar color</span>
                </div>
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs font-black text-on-surface mb-3 tracking-widest uppercase ml-1">Ícono Visual</label>
                <div class="grid grid-cols-4 sm:grid-cols-6 gap-4">
                    @foreach([
                        'fa-wallet' => 'Billetera',
                        'fa-money-bill-1' => 'Efectivo',
                        'fa-credit-card' => 'Tarjeta',
                        'fa-mobile-screen-button' => 'Pago Móvil',
                        'fa-bitcoin-sign' => 'Crypto',
                        'fa-building-columns' => 'Banco',
                        'fa-money-check-dollar' => 'Cheque',
                        'fa-qrcode' => 'QR'
                    ] as $iconClass => $label)
                    <label class="cursor-pointer group">
                        <input type="radio" name="icon" value="{{ $iconClass }}" {{ $metodo->icon == $iconClass ? 'checked' : '' }} class="hidden peer">
                        <div class="flex flex-col items-center gap-2 p-4 rounded-2xl border-2 border-surface-container transition-all group-hover:border-primary/30 peer-checked:border-primary peer-checked:bg-primary/5">
                            <i class="fa-solid {{ $iconClass }} text-xl text-outline group-hover:text-primary peer-checked:text-primary transition-colors"></i>
                            <span class="text-[9px] font-black uppercase text-outline group-hover:text-primary peer-checked:text-primary text-center leading-tight">{{ $label }}</span>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>
        </div>

        <div>
            <label class="block text-xs font-black text-on-surface mb-3 tracking-widest uppercase ml-1">Detalles de la Cuenta (Opcional)</label>
            <textarea name="details" rows="3" class="block w-full px-6 py-4 border border-surface-container rounded-2xl focus:ring-4 focus:ring-primary/10 transition-all font-medium resize-none shadow-inner" placeholder="Ej. Correo, Número de cuenta, Cédula, Nombre del titular...">{{ $metodo->details }}</textarea>
        </div>

        <div class="flex flex-col sm:flex-row gap-6 pt-6">
            <a href="{{ route('finanzas.index') }}" class="flex-1 text-center py-5 border-2 border-surface-container rounded-2xl font-black text-outline hover:bg-surface-container-low transition-all uppercase text-xs">
                Cancelar
            </a>
            <button type="submit" class="flex-[2] hero-gradient text-white py-5 rounded-2xl font-black text-lg shadow-xl hover:scale-[1.02] transition-all flex items-center justify-center gap-3">
                <i class="fa-solid fa-sync"></i> Actualizar Método
            </button>
        </div>
    </form>

    <div class="pt-10 border-t border-surface-container">
        <form action="{{ route('metodos.destroy', $metodo) }}" method="POST" class="confirm-delete">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-error font-bold flex items-center gap-3 hover:underline text-sm uppercase tracking-widest">
                <i class="fa-solid fa-trash"></i> Eliminar definitivamente
            </button>
        </form>
    </div>
</div>
@endsection
