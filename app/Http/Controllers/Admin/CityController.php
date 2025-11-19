<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function index()
    {
        $cities = City::with('country')->orderBy('sort_order')->orderBy('name')->get();
        $countries = Country::where('is_active', true)->orderBy('name')->get();
        $tab = 'cities';
        $settings = [];
        return view('admin.settings.index', compact('cities', 'countries', 'tab', 'settings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'country_id' => 'required|exists:countries,id',
            'sort_order' => 'nullable|integer',
        ]);

        City::create([
            'name' => $request->name,
            'country_id' => $request->country_id,
            'sort_order' => $request->sort_order ?? 0,
            'is_active' => true,
        ]);

        return back()->with('success', 'City added successfully.');
    }

    public function update(Request $request, City $city)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'country_id' => 'required|exists:countries,id',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $city->update($request->only(['name', 'country_id', 'sort_order', 'is_active']));

        return back()->with('success', 'City updated successfully.');
    }

    public function destroy(City $city)
    {
        $city->delete();
        return back()->with('success', 'City deleted successfully.');
    }
}
