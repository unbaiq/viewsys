<?php

namespace App\Http\Controllers;

use App\Models\Screen;
use App\Models\Company;
use Illuminate\Http\Request;

class ScreenController extends Controller
{

    public function index(Request $request)
    {
        $user = auth()->user();

        $query = Screen::with('company');

        // Admin only sees own company
        if ($user->role === 'admin') {
            $query->where('company_id', $user->company_id);
        }

        // Manager only sees assigned screen
        if ($user->role === 'manager') {
            $query->where('id', $user->screen_id);
        }

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('device_id', 'like', '%' . $request->search . '%')
                  ->orWhere('location', 'like', '%' . $request->search . '%');
            });
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', (bool) $request->status);
        }

        $screens = $query->latest()->paginate(10)->withQueryString();

        return view('screens.index', compact('screens'));
    }


    public function create()
    {
        $user = auth()->user();

        // Admin should only see own company
        if ($user->role === 'admin') {
            $companies = Company::where('id', $user->company_id)->pluck('name', 'id');
        } else {
            $companies = Company::pluck('name', 'id');
        }

        return view('screens.create', compact('companies'));
    }


    public function store(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'company_id' => 'required|exists:companies,id',
            'device_id' => 'nullable|unique:screens,device_id'
        ]);

        // Prevent admin from assigning other companies
        if ($user->role === 'admin') {
            $request->merge([
                'company_id' => $user->company_id
            ]);
        }

        $deviceId = $request->device_id;

        // Auto generate device id
        if (!$deviceId) {

            do {
                $deviceId = 'SCR-' . strtoupper(substr(md5(uniqid()), 0, 8));
            } while (Screen::where('device_id', $deviceId)->exists());

        }

        $data = $request->only([
            'name',
            'company_id',
            'location',
            'orientation'
        ]);

        $data['device_id'] = $deviceId;

        Screen::create($data);

        if (function_exists('system_log')) {
            system_log('screen', 'Screen Created', $request->name);
        }

        return redirect()
            ->route('screens.index')
            ->with('success', 'Screen created successfully');
    }


    public function show(Screen $screen)
    {
        $this->authorizeAccess($screen);

        return view('screens.show', compact('screen'));
    }


    public function edit(Screen $screen)
    {
        $this->authorizeAccess($screen);

        $companies = Company::pluck('name', 'id');

        return view('screens.edit', compact('screen', 'companies'));
    }


    public function update(Request $request, Screen $screen)
    {
        $this->authorizeAccess($screen);

        $request->validate([
            'name' => 'required|string|max:255',
            'device_id' => 'nullable|unique:screens,device_id,' . $screen->id
        ]);

        $data = $request->only([
            'name',
            'device_id',
            'company_id',
            'location',
            'orientation',
            'status'
        ]);

        $screen->update($data);

        if (function_exists('system_log')) {
            system_log('screen', 'Screen Updated', $screen->name);
        }

        return redirect()
            ->route('screens.index')
            ->with('success', 'Screen updated');
    }


    public function destroy(Screen $screen)
    {
        $this->authorizeAccess($screen);

        $screen->delete();

        if (function_exists('system_log')) {
            system_log('screen', 'Screen Deleted', $screen->name);
        }

        return back()->with('success', 'Screen deleted');
    }


    /*
    |--------------------------------------------------------------------------
    | Authorization Helper
    |--------------------------------------------------------------------------
    */

    private function authorizeAccess(Screen $screen)
    {
        $user = auth()->user();

        if ($user->role === 'admin' && $screen->company_id !== $user->company_id) {
            abort(403);
        }

        if ($user->role === 'manager' && $screen->id !== $user->screen_id) {
            abort(403);
        }
    }
}