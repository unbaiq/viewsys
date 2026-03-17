<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index(Request $request)
    {
        $query = Plan::query();

        // Search
        if ($request->search) {
            $query->where('name','like','%'.$request->search.'%');
        }

        // Status
        if ($request->status !== null) {
            $query->where('is_active',$request->status);
        }

        $plans = $query->latest()
            ->paginate(10)
            ->withQueryString();

        return view('plans.index', compact('plans'));
    }


    public function create()
    {
        return view('plans.create');
    }


    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'price'=>'required'
        ]);

        $data = $request->all();

        $data['features'] = explode(',', $request->features);

        Plan::create($data);

        return redirect()->route('plans.index')
            ->with('success','Plan created');
    }


    public function show(Plan $plan)
    {
        return view('plans.show', compact('plan'));
    }


    public function edit(Plan $plan)
    {
        return view('plans.edit', compact('plan'));
    }


    public function update(Request $request, Plan $plan)
    {
        $data = $request->all();

        $data['features'] = explode(',', $request->features);

        $plan->update($data);

        return redirect()->route('plans.index')
            ->with('success','Plan updated');
    }


    public function destroy(Plan $plan)
    {
        $plan->delete();

        return back()->with('success','Deleted');
    }


    public function toggle(Plan $plan)
    {
        $plan->update([
            'is_active'=>!$plan->is_active
        ]);

        return back();
    }
}