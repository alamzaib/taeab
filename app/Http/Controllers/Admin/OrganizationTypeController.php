<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrganizationType;
use Illuminate\Http\Request;

class OrganizationTypeController extends Controller
{
    public function index()
    {
        $organizationTypes = OrganizationType::orderBy('sort_order')->orderBy('name')->get();
        $tab = 'company-attributes';
        $settings = [];
        return view('admin.settings.index', compact('organizationTypes', 'tab', 'settings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:organization_types,name',
            'sort_order' => 'nullable|integer',
        ]);

        OrganizationType::create([
            'name' => $request->name,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => true,
        ]);

        return back()->with('success', 'Organization type added successfully.');
    }

    public function update(Request $request, OrganizationType $organizationType)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:organization_types,name,' . $organizationType->id,
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $organizationType->update($request->only(['name', 'sort_order', 'is_active']));

        return back()->with('success', 'Organization type updated successfully.');
    }

    public function destroy(OrganizationType $organizationType)
    {
        $organizationType->delete();
        return back()->with('success', 'Organization type deleted successfully.');
    }
}

