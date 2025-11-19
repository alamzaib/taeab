<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function index()
    {
        $countries = Country::orderBy('sort_order')->orderBy('name')->get();
        $tab = 'countries';
        return view('admin.settings.index', compact('countries', 'tab'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:countries,name',
            'code' => 'nullable|string|max:3|unique:countries,code',
            'sort_order' => 'nullable|integer',
        ]);

        Country::create([
            'name' => $request->name,
            'code' => $request->code,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => true,
        ]);

        return back()->with('success', 'Country added successfully.');
    }

    public function update(Request $request, Country $country)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:countries,name,' . $country->id,
            'code' => 'nullable|string|max:3|unique:countries,code,' . $country->id,
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $country->update($request->only(['name', 'code', 'sort_order', 'is_active']));

        return back()->with('success', 'Country updated successfully.');
    }

    public function destroy(Country $country)
    {
        $country->delete();
        return back()->with('success', 'Country deleted successfully.');
    }
}
