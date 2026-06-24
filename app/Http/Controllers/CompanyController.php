<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $query = Company::query();

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        // Plan filter
        if ($request->filled('plan')) {
            $query->where('plan', $request->plan);
        }

        // Status filter
        if ($request->has('status') && $request->status !== '') {
            $query->where('is_active', (bool) $request->status);
        }

        // Sorting
        $allowedSorts = ['id', 'name', 'plan', 'created_at'];

        if ($request->filled('sort') && in_array($request->sort, $allowedSorts)) {
            $query->orderBy($request->sort, 'desc');
        } else {
            $query->latest();
        }

        $companies = $query->paginate(10)->withQueryString();

        return view('companies.index', compact('companies'));
    }

    public function create()
    {
        $users = User::select('id', 'name')->get();

        return view('companies.create', compact('users'));
    }

    public function store(Request $request)
    {
        // ✅ VALIDATION (MATCHES UI + MIGRATION)
        $data = $request->validate([
            'name'              => 'required|string|max:255',
            'user_id'           => 'nullable|exists:users,id',

            'email'             => 'nullable|email',
            'phone'             => 'nullable|string|max:20',
            'website'           => 'nullable|string|max:255',

            'industry'          => 'nullable|string|max:255',
            'gst_number'        => 'nullable|string|max:50',
            'pan_number'        => 'nullable|string|max:50',

            'address'           => 'nullable|string|max:255',
            'city'              => 'nullable|string|max:100',
            'state'             => 'nullable|string|max:100',
            'country'           => 'nullable|string|max:100',
            'zip_code'          => 'nullable|string|max:20',

            'plan'              => 'nullable|string',
            'screen_limit'      => 'nullable|integer',
            'storage_limit'     => 'nullable|integer',
            'user_limit'        => 'nullable|integer',

            'plan_start_date'   => 'nullable|date',
            'plan_end_date'     => 'nullable|date|after_or_equal:plan_start_date',

            'is_active'         => 'nullable|boolean',
            'is_trial'          => 'nullable|boolean',
        ]);

        // ✅ BOOLEAN HANDLING
        $data['is_active'] = $request->boolean('is_active');
        $data['is_trial']  = $request->boolean('is_trial');

        Company::create($data);

        return redirect()->route('companies.index')
            ->with('success', 'Company created successfully');
    }

    public function edit(Company $company)
    {
        $users = User::select('id', 'name')->get();

        return view('companies.edit', compact('company', 'users'));
    }

    public function update(Request $request, Company $company)
    {
        // ✅ VALIDATION (SAME AS STORE)
        $data = $request->validate([
            'name'              => 'required|string|max:255',
            'user_id'           => 'nullable|exists:users,id',

            'email'             => 'nullable|email',
            'phone'             => 'nullable|string|max:20',
            'website'           => 'nullable|string|max:255',

            'industry'          => 'nullable|string|max:255',
            'gst_number'        => 'nullable|string|max:50',
            'pan_number'        => 'nullable|string|max:50',

            'address'           => 'nullable|string|max:255',
            'city'              => 'nullable|string|max:100',
            'state'             => 'nullable|string|max:100',
            'country'           => 'nullable|string|max:100',
            'zip_code'          => 'nullable|string|max:20',

            'plan'              => 'nullable|string',
            'screen_limit'      => 'nullable|integer',
            'storage_limit'     => 'nullable|integer',
            'user_limit'        => 'nullable|integer',

            'plan_start_date'   => 'nullable|date',
            'plan_end_date'     => 'nullable|date|after_or_equal:plan_start_date',

            'is_active'         => 'nullable|boolean',
            'is_trial'          => 'nullable|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['is_trial']  = $request->boolean('is_trial');

        $company->update($data);

        return redirect()->route('companies.index')
            ->with('success', 'Company updated successfully');
    }

    public function destroy(Company $company)
    {
        $company->delete();

        return back()->with('success', 'Company deleted successfully');
    }

    public function toggle(Company $company)
{
    $company->update([
        'is_active' => !$company->is_active
    ]);

    return back()->with('success', 'Company status updated');
}
}