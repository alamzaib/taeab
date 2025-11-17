<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agent;
use App\Models\Seeker;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of seekers
     */
    public function seekersIndex(Request $request)
    {
        $query = Seeker::query();

        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $seekers = $query->latest()->paginate(15);
        return view('admin.users.seekers.index', compact('seekers'));
    }

    /**
     * Show the form for creating a new seeker
     */
    public function seekersCreate()
    {
        return view('admin.users.seekers.create');
    }

    /**
     * Store a newly created seeker
     */
    public function seekersStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:seekers',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'status' => 'required|in:active,inactive',
        ]);

        Seeker::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.users.seekers.index')
            ->with('success', 'Job seeker created successfully!');
    }

    /**
     * Show the form for editing a seeker
     */
    public function seekersEdit(Seeker $seeker)
    {
        return view('admin.users.seekers.edit', compact('seeker'));
    }

    /**
     * Update the specified seeker
     */
    public function seekersUpdate(Request $request, Seeker $seeker)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('seekers')->ignore($seeker->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'status' => 'required|in:active,inactive',
        ]);

        $seeker->name = $validated['name'];
        $seeker->email = $validated['email'];
        $seeker->phone = $validated['phone'] ?? null;
        $seeker->status = $validated['status'];

        if (!empty($validated['password'])) {
            $seeker->password = Hash::make($validated['password']);
        }

        $seeker->save();

        return redirect()->route('admin.users.seekers.index')
            ->with('success', 'Job seeker updated successfully!');
    }

    /**
     * Remove the specified seeker
     */
    public function seekersDestroy(Seeker $seeker)
    {
        $seeker->delete();
        return redirect()->route('admin.users.seekers.index')
            ->with('success', 'Job seeker deleted successfully!');
    }

    /**
     * Display a listing of agents
     */
    public function agentsIndex(Request $request)
    {
        $query = Agent::query();

        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $agents = $query->latest()->paginate(15);
        return view('admin.users.agents.index', compact('agents'));
    }

    /**
     * Show the form for creating a new agent
     */
    public function agentsCreate()
    {
        return view('admin.users.agents.create');
    }

    /**
     * Store a newly created agent
     */
    public function agentsStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:agents',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'status' => 'required|in:active,inactive',
        ]);

        Agent::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.users.agents.index')
            ->with('success', 'Agent created successfully!');
    }

    /**
     * Show the form for editing an agent
     */
    public function agentsEdit(Agent $agent)
    {
        return view('admin.users.agents.edit', compact('agent'));
    }

    /**
     * Update the specified agent
     */
    public function agentsUpdate(Request $request, Agent $agent)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('agents')->ignore($agent->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'status' => 'required|in:active,inactive',
        ]);

        $agent->name = $validated['name'];
        $agent->email = $validated['email'];
        $agent->phone = $validated['phone'] ?? null;
        $agent->status = $validated['status'];

        if (!empty($validated['password'])) {
            $agent->password = Hash::make($validated['password']);
        }

        $agent->save();

        return redirect()->route('admin.users.agents.index')
            ->with('success', 'Agent updated successfully!');
    }

    /**
     * Remove the specified agent
     */
    public function agentsDestroy(Agent $agent)
    {
        $agent->delete();
        return redirect()->route('admin.users.agents.index')
            ->with('success', 'Agent deleted successfully!');
    }

    /**
     * Display a listing of companies
     */
    public function companiesIndex(Request $request)
    {
        $query = Company::query();

        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('company_name', 'like', '%' . $request->search . '%')
                  ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $companies = $query->latest()->paginate(15);
        return view('admin.users.companies.index', compact('companies'));
    }

    /**
     * Show the form for creating a new company
     */
    public function companiesCreate()
    {
        return view('admin.users.companies.create');
    }

    /**
     * Store a newly created company
     */
    public function companiesStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:companies',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'company_name' => 'required|string|max:255',
            'company_size' => 'nullable|string|max:50',
            'industry' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        Company::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'company_name' => $validated['company_name'],
            'company_size' => $validated['company_size'] ?? null,
            'industry' => $validated['industry'] ?? null,
            'website' => $validated['website'] ?? null,
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.users.companies.index')
            ->with('success', 'Company created successfully!');
    }

    /**
     * Show the form for editing a company
     */
    public function companiesEdit(Company $company)
    {
        return view('admin.users.companies.edit', compact('company'));
    }

    /**
     * Update the specified company
     */
    public function companiesUpdate(Request $request, Company $company)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('companies')->ignore($company->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'company_name' => 'required|string|max:255',
            'company_size' => 'nullable|string|max:50',
            'industry' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $company->name = $validated['name'];
        $company->email = $validated['email'];
        $company->phone = $validated['phone'] ?? null;
        $company->company_name = $validated['company_name'];
        $company->company_size = $validated['company_size'] ?? null;
        $company->industry = $validated['industry'] ?? null;
        $company->website = $validated['website'] ?? null;
        $company->status = $validated['status'];

        if (!empty($validated['password'])) {
            $company->password = Hash::make($validated['password']);
        }

        $company->save();

        return redirect()->route('admin.users.companies.index')
            ->with('success', 'Company updated successfully!');
    }

    /**
     * Remove the specified company
     */
    public function companiesDestroy(Company $company)
    {
        $company->delete();
        return redirect()->route('admin.users.companies.index')
            ->with('success', 'Company deleted successfully!');
    }
}

