<?php

namespace App\Http\Controllers;

use App\Models\Screen;
use App\Models\Company;
use Illuminate\Http\Request;

class ScreenController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $user = auth()->user();

       $query = Screen::with('company');

        // Admin → only own company
        if ($user->hasRole('admin')) {
            $query->where('company_id', $user->company_id);
        }

        // Manager → only assigned screen
        if ($user->hasRole('manager')) {
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

        // ✅ FIX: ENUM status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

      $screens = $query->latest()->paginate(10)->withQueryString();

        // Optional: Live status
        $screens->getCollection()->transform(function ($screen) {
            $screen->live_status = $screen->isOnline() ? 'online' : 'offline';
            return $screen;
        });

        return view('screens.index', compact('screens'));
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            $companies = Company::where('id', $user->company_id)->pluck('name', 'id');
        } else {
            $companies = Company::pluck('name', 'id');
        }

        return view('screens.create', compact('companies'));
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name'       => 'required|string|max:255',
            'company_id' => 'required|exists:companies,id',
            'device_id'  => 'nullable|unique:screens,device_id',
        ]);

        // Admin → force company
        if ($user->hasRole('admin')) {
            $request->merge([
                'company_id' => $user->company_id
            ]);
        }

        // Auto device ID
        $deviceId = $request->device_id;

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

        return redirect()
            ->route('screens.index')
            ->with('success', 'Screen created successfully');
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */
    public function show(Screen $screen)
    {
        $this->authorizeAccess($screen);

        return view('screens.show', compact('screen'));
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */
    public function edit(Screen $screen)
    {
        $this->authorizeAccess($screen);

        $user = auth()->user();

        if ($user->hasRole('admin')) {
            $companies = Company::where('id', $user->company_id)->pluck('name', 'id');
        } else {
            $companies = Company::pluck('name', 'id');
        }

        return view('screens.edit', compact('screen', 'companies'));
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, Screen $screen)
    {
        $this->authorizeAccess($screen);

        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $user = auth()->user();

        $data = $request->only([
            'name',
            'company_id',
            'location',
            'orientation',
            'status'
        ]);

        // Admin → force company
        if ($user->hasRole('admin')) {
            $data['company_id'] = $user->company_id;
        }

        // 🚫 Prevent device_id tampering
        // (do NOT allow update)

        $screen->update($data);

        return redirect()
            ->route('screens.index')
            ->with('success', 'Screen updated successfully');
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */
    public function destroy(Screen $screen)
    {
        $this->authorizeAccess($screen);

        $screen->delete();

        return back()->with('success', 'Screen deleted successfully');
    }

    /*
    |--------------------------------------------------------------------------
    | REQUEST SCREENSHOT
    |--------------------------------------------------------------------------
    */
    public function requestScreenshot(Screen $screen)
    {
        $this->authorizeAccess($screen);

        $screen->requestScreenshot();

        return back()->with('success', 'Screenshot request sent');
    }

    /*
    |--------------------------------------------------------------------------
    | RESTART SCREEN
    |--------------------------------------------------------------------------
    */
    public function restart(Screen $screen)
    {
        $this->authorizeAccess($screen);

        $screen->requestRestart();

        return back()->with('success', 'Restart command sent');
    }

    /*
    |--------------------------------------------------------------------------
    | SEND COMMAND (ADVANCED)
    |--------------------------------------------------------------------------
    */
    public function sendCommand(Request $request, Screen $screen)
    {
        $this->authorizeAccess($screen);

        $request->validate([
            'command' => 'required|string'
        ]);

        $screen->addCommand($request->command);

        return back()->with('success', 'Command sent: ' . $request->command);
    }

    /*
    |--------------------------------------------------------------------------
    | MAP VIEW
    |--------------------------------------------------------------------------
    */
    public function map()
    {
        $screens = Screen::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        return view('screens.map', compact('screens'));
    }

    /*
    |--------------------------------------------------------------------------
    | AUTHORIZATION
    |--------------------------------------------------------------------------
    */
    private function authorizeAccess(Screen $screen)
    {
        $user = auth()->user();

        if ($user->hasRole('admin') && $screen->company_id !== $user->company_id) {
            abort(403);
        }

        if ($user->hasRole('manager') && $screen->id !== $user->screen_id) {
            abort(403);
        }
    }
}