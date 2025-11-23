<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanySize;
use Illuminate\Http\Request;

class CompanySizeController extends Controller
{
    public function index()
    {
        $companySizes = CompanySize::orderBy('sort_order')->orderBy('name')->get();
        $tab = 'company-attributes';
        $settings = [];
        return view('admin.settings.index', compact('companySizes', 'tab', 'settings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:company_sizes,name',
            'sort_order' => 'nullable|integer',
        ]);

        CompanySize::create([
            'name' => $request->name,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => true,
        ]);

        return back()->with('success', 'Company size added successfully.');
    }

    public function update(Request $request, CompanySize $companySize)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:company_sizes,name,' . $companySize->id,
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $companySize->update($request->only(['name', 'sort_order', 'is_active']));

        return back()->with('success', 'Company size updated successfully.');
    }

    public function destroy(CompanySize $companySize)
    {
        $companySize->delete();
        return back()->with('success', 'Company size deleted successfully.');
    }
}

