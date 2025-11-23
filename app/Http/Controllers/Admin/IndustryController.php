<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Industry;
use Illuminate\Http\Request;

class IndustryController extends Controller
{
    public function index()
    {
        $industries = Industry::orderBy('sort_order')->orderBy('name')->get();
        $tab = 'company-attributes';
        $settings = [];
        return view('admin.settings.index', compact('industries', 'tab', 'settings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:industries,name',
            'sort_order' => 'nullable|integer',
        ]);

        Industry::create([
            'name' => $request->name,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => true,
        ]);

        return back()->with('success', 'Industry added successfully.');
    }

    public function update(Request $request, Industry $industry)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:industries,name,' . $industry->id,
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $industry->update($request->only(['name', 'sort_order', 'is_active']));

        return back()->with('success', 'Industry updated successfully.');
    }

    public function destroy(Industry $industry)
    {
        $industry->delete();
        return back()->with('success', 'Industry deleted successfully.');
    }
}

