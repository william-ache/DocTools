<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TemplateController extends Controller
{
    public function index()
    {
        $templates = Template::latest()
            ->get();
        return view('admin.templates.index', compact('templates'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string',
            'color' => 'nullable|string',
            'content' => 'required|string',
        ]);


        Template::create($validated);

        return redirect()->route('templates.index')
            ->with('success', 'Plantilla creada exitosamente.');
    }

    public function update(Request $request, Template $template)
    {

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string',
            'color' => 'nullable|string',
            'content' => 'required|string',
        ]);

        $template->update($validated);

        return redirect()->route('templates.index')
            ->with('success', 'Plantilla actualizada exitosamente.');
    }

    public function destroy(Template $template)
    {

        $template->delete();

        return redirect()->route('templates.index')
            ->with('success', 'Plantilla eliminada exitosamente.');
    }

    public function toggleStatus(Template $template)
    {

        $template->update([
            'is_active' => !$template->is_active
        ]);

        return back()->with('success', 'Estado de la plantilla actualizado.');
    }
}
