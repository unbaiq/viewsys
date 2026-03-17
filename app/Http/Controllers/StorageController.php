<?php

namespace App\Http\Controllers;

use App\Models\StorageUsage;
use App\Models\Company;
use Illuminate\Http\Request;

class StorageController extends Controller
{

    public function index(Request $request)
    {
        $query = StorageUsage::with('company');

        // search
        if ($request->search) {
            $query->whereHas('company', function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%");
            });
        }

        // filter high usage
        if ($request->filter == 'warning') {
            $query->whereRaw('(used / `limit`) > 0.8');
        }

        $storages = $query->latest()->paginate(10)->withQueryString();

        return view('storage.index', compact('storages'));
    }


    public function create()
    {
        $companies = Company::pluck('name','id');

        return view('storage.create', compact('companies'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'company_id'=>'required',
            'limit'=>'required|numeric'
        ]);

        StorageUsage::create($request->all());

        return redirect()->route('storage.index')
            ->with('success','Storage created');
    }


    public function show(StorageUsage $storage)
    {
        return view('storage.show', compact('storage'));
    }


    public function edit(StorageUsage $storage)
    {
        $companies = Company::pluck('name','id');

        return view('storage.edit', compact('storage','companies'));
    }


    public function update(Request $request, StorageUsage $storage)
    {
        $request->validate([
            'limit'=>'required|numeric'
        ]);

        $storage->update($request->all());

        return redirect()->route('storage.index')
            ->with('success','Updated');
    }


    public function destroy(StorageUsage $storage)
    {
        $storage->delete();

        return back()->with('success','Deleted');
    }
}