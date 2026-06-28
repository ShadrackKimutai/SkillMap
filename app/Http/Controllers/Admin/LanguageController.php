<?php

namespace App\Http\Controllers\Admin;

use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;

class LanguageController extends Controller
{
    public function index(): View
    {
        $languages = Language::paginate(15);
        return view('admin.languages.index', compact('languages'));
    }

    public function create(): View
    {
        return view('admin.languages.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'unique:languages'],
            'code' => ['required', 'string', 'unique:languages', 'max:5'],
        ]);

        Language::create($validated);
        return redirect()->route('admin.languages.index')->with('success', 'Language created');
    }

    public function destroy(Language $language): RedirectResponse
    {
        $language->delete();
        return redirect()->route('admin.languages.index')->with('success', 'Language deleted');
    }
}
