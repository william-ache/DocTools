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
        $templates = Template::where('tenant_id', Auth::user()->tenant_id)
            ->latest()
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

        $validated['tenant_id'] = Auth::user()->tenant_id;

        Template::create($validated);

        return redirect()->route('templates.index')
            ->with('success', 'Plantilla creada exitosamente.');
    }

    public function update(Request $request, Template $template)
    {
        if ($template->tenant_id !== Auth::user()->tenant_id) {
            abort(403);
        }

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
        if ($template->tenant_id !== Auth::user()->tenant_id) {
            abort(403);
        }

        $template->delete();

        return redirect()->route('templates.index')
            ->with('success', 'Plantilla eliminada exitosamente.');
    }

    public function toggleStatus(Template $template)
    {
        if ($template->tenant_id !== Auth::user()->tenant_id) {
            abort(403);
        }

        $template->update([
            'is_active' => !$template->is_active
        ]);

        return back()->with('success', 'Estado de la plantilla actualizado.');
    }
}
