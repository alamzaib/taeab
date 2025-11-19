<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExperienceLevel;
use Illuminate\Http\Request;

class ExperienceLevelController extends Controller
{
    public function index()
    {
        $experienceLevels = ExperienceLevel::orderBy('sort_order')->orderBy('name')->get();
        $tab = 'experience-levels';
        $settings = [];
        return view('admin.settings.index', compact('experienceLevels', 'tab', 'settings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:experience_levels,name',
            'sort_order' => 'nullable|integer',
        ]);

        ExperienceLevel::create([
            'name' => $request->name,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => true,
        ]);

        return back()->with('success', 'Experience level added successfully.');
    }

    public function update(Request $request, ExperienceLevel $experienceLevel)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:experience_levels,name,' . $experienceLevel->id,
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $experienceLevel->update($request->only(['name', 'sort_order', 'is_active']));

        return back()->with('success', 'Experience level updated successfully.');
    }

    public function destroy(ExperienceLevel $experienceLevel)
    {
        $experienceLevel->delete();
        return back()->with('success', 'Experience level deleted successfully.');
    }
}
