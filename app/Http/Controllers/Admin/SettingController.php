<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::first() ?? Setting::create([
            'app_name' => 'Consultia',
            'primary_color' => '#00478d',
            'enabled_modules' => ['consultorios', 'servicios', 'finanzas', 'pacientes'],
            'favorite_modules' => [],
        ]);
        $user = Auth::user();
        return view('admin.settings.index', compact('settings', 'user'));
    }

    public function update(Request $request)
    {
        $settings = Setting::first();
        
        $validated = $request->validate([
            'primary_color' => 'required|string|max:7',
            'modules' => 'nullable|array',
            'favorites' => 'nullable|array',
        ]);

        $settings->update([
            'primary_color' => $validated['primary_color'],
            'enabled_modules' => $validated['modules'] ?? [],
            'favorite_modules' => $validated['favorites'] ?? [],
        ]);

        return back()->with('success', 'Configuraciones del sistema actualizadas.');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'specialty' => 'nullable|string|max:255',
            'password' => 'nullable|min:6|confirmed',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->specialty = $validated['specialty'];
        
        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return back()->with('success', 'Perfil actualizado correctamente.');
    }
}
