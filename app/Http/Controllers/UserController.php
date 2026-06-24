<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Company;
use App\Models\Screen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class UserController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $authUser = auth()->user();

        $query = User::with(['company','roles','screen']);

        if ($authUser->isSuperAdmin()) {

            $query->whereHas('roles', function ($q) {
                $q->whereIn('name', ['admin','manager']);
            });

        } elseif ($authUser->isAdmin()) {

            $query->where('company_id', $authUser->company_id)
                  ->role('manager');

        } else {

            $query->where('id', $authUser->id);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name','like','%'.$request->search.'%')
                  ->orWhere('email','like','%'.$request->search.'%');
            });
        }

        if ($request->filled('role')) {
            $query->role($request->role);
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status);
        }

     $users = $query->latest()->paginate(10)->withQueryString();

        return view('users.index', compact('users'));
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        $authUser = auth()->user();

        $companies = $authUser->isSuperAdmin()
            ? Company::pluck('name','id')
            : Company::where('id', $authUser->company_id)->pluck('name','id');

        $roles = $authUser->isSuperAdmin()
            ? Role::whereIn('name',['admin','manager'])->pluck('name','name')
            : Role::where('name','manager')->pluck('name','name');

        $screens = $authUser->isSuperAdmin()
            ? Screen::pluck('name','id')
            : Screen::where('company_id', $authUser->company_id)->pluck('name','id');

        return view('users.create', compact('companies','roles','screens'));
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $authUser = auth()->user();

        $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users',
            'password'   => 'required|min:6',
            'role'       => 'required|in:admin,manager',
            'company_id' => 'nullable|exists:companies,id',
            'screen_id'  => 'required_if:role,manager|nullable|exists:screens,id',
        ]);

        if ($authUser->isAdmin() && $request->role !== 'manager') {
            abort(403);
        }

        $companyId = $authUser->isAdmin()
            ? $authUser->company_id
            : $request->company_id;

        $user = User::create([
            'name'       => $request->name,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'company_id' => $companyId,
            'is_active'  => 1,
        ]);

        $user->syncRoles([$request->role]);

        /*
        |-----------------------------------------
        | SCREEN ASSIGN
        |-----------------------------------------
        */
        if ($request->role === 'manager' && $request->filled('screen_id')) {

            $screen = Screen::find($request->screen_id);

            if ($screen && $screen->company_id != $companyId) {
                return back()->withErrors([
                    'screen_id' => 'Screen does not belong to selected company'
                ]);
            }

            $user->update([
                'screen_id' => $request->screen_id
            ]);

        } else {
            $user->update([
                'screen_id' => null
            ]);
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return redirect()->route('users.index')->with('success','User created');
    }

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */
    public function edit(User $user)
    {
        $authUser = auth()->user();

        $this->authorizeAccess($user);

        if ($authUser->isManager()) {
            abort(403);
        }

        $companies = $authUser->isSuperAdmin()
            ? Company::pluck('name','id')
            : Company::where('id', $authUser->company_id)->pluck('name','id');

        $roles = $authUser->isSuperAdmin()
            ? Role::whereIn('name',['admin','manager'])->pluck('name','name')
            : Role::where('name','manager')->pluck('name','name');

        $screens = $authUser->isSuperAdmin()
            ? Screen::pluck('name','id')
            : Screen::where('company_id', $authUser->company_id)->pluck('name','id');

        $user->load('roles');

        return view('users.edit', compact('user','companies','roles','screens'));
    }

    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, User $user)
    {
        $authUser = auth()->user();

        $this->authorizeAccess($user);

        $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email,'.$user->id,
            'role'       => 'required|in:admin,manager',
            'company_id' => 'nullable|exists:companies,id',
            'screen_id'  => 'required_if:role,manager|nullable|exists:screens,id',
        ]);

        if ($authUser->isAdmin() && $request->role !== 'manager') {
            abort(403);
        }

        $companyId = $authUser->isAdmin()
            ? $authUser->company_id
            : $request->company_id;

        $data = [
            'name'       => $request->name,
            'email'      => $request->email,
            'company_id' => $companyId,
            'is_active'  => $request->boolean('is_active'),
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        /*
        |-----------------------------------------
        | SCREEN ASSIGN
        |-----------------------------------------
        */
        if ($request->role === 'manager' && $request->filled('screen_id')) {

            $screen = Screen::find($request->screen_id);

            if ($screen && $screen->company_id != $companyId) {
                return back()->withErrors([
                    'screen_id' => 'Screen does not belong to selected company'
                ]);
            }

            $data['screen_id'] = $request->screen_id;

        } else {
            $data['screen_id'] = null;
        }

        $user->update($data);

        $user->syncRoles([$request->role]);

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return redirect()->route('users.index')->with('success','User updated');
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */
    public function destroy(User $user)
    {
        $authUser = auth()->user();

        if ($authUser->id === $user->id) {
            return back()->with('error','Cannot delete yourself');
        }

        $this->authorizeAccess($user);

        $user->delete();

        return back()->with('success','User deleted');
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESS CONTROL
    |--------------------------------------------------------------------------
    */
    private function authorizeAccess($user)
    {
        $authUser = auth()->user();

        if ($authUser->isSuperAdmin()) {
            return true;
        }

        if ($authUser->isAdmin()) {
            if (!$user->hasRole('manager') || $user->company_id !== $authUser->company_id) {
                abort(403);
            }
            return true;
        }

        if ($authUser->isManager()) {
            if ($user->id !== $authUser->id) {
                abort(403);
            }
            return true;
        }

        abort(403);
    }
}