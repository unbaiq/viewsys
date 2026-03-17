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
        if ($request->search) {
            $query->where('name','like','%'.$request->search.'%')
                  ->orWhere('email','like','%'.$request->search.'%');
        }

        // Plan filter
        if ($request->plan) {
            $query->where('plan',$request->plan);
        }

        // Status filter
        if ($request->status !== null) {
            $query->where('is_active',$request->status);
        }

        // Sorting
        if ($request->sort) {
            $query->orderBy($request->sort,'desc');
        } else {
            $query->latest();
        }

        $companies = $query->paginate(10)->withQueryString();

        return view('companies.index', compact('companies'));
    }

    public function create()
    {
        $users = User::all();

        return view('companies.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'user_id'=>'required'
        ]);

        Company::create($request->all());

        return redirect()->route('companies.index')
            ->with('success','Company created');
    }

    public function edit(Company $company)
    {
        $users = User::all();

        return view('companies.edit', compact('company','users'));
    }

    public function update(Request $request, Company $company)
    {
        $company->update($request->all());

        return redirect()->route('companies.index')
            ->with('success','Updated');
    }

    public function destroy(Company $company)
    {
        $company->delete();

        return back()->with('success','Deleted');
    }
}