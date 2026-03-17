<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Company;
use App\Models\Screen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function index(Request $request)
    {
        $query = User::with(['company','screen']);

        if ($request->search) {
            $query->where(function($q) use ($request){
                $q->where('name','like','%'.$request->search.'%')
                  ->orWhere('email','like','%'.$request->search.'%');
            });
        }

        if ($request->role) {
            $query->where('role',$request->role);
        }

        $users = $query->latest()->paginate(10)->withQueryString();

        return view('users.index', compact('users'));
    }


    public function create()
    {
        $companies = Company::pluck('name','id');
        $screens = Screen::pluck('name','id');

        return view('users.create', compact('companies','screens'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:6'
        ]);

        $data = $request->all();
        $data['password'] = Hash::make($request->password);

        User::create($data);

        system_log('user','User Created',$request->email);

        return redirect()->route('users.index')
            ->with('success','User created');
    }


    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }


    public function edit(User $user)
    {
        $companies = Company::pluck('name','id');
        $screens = Screen::pluck('name','id');

        return view('users.edit', compact('user','companies','screens'));
    }


    public function update(Request $request, User $user)
    {
        $data = $request->all();

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        system_log('user','User Updated',$user->email);

        return redirect()->route('users.index');
    }


    public function destroy(User $user)
    {
        $user->delete();

        system_log('user','User Deleted',$user->email);

        return back();
    }


    public function toggle(User $user)
    {
        $user->update([
            'is_active' => !$user->is_active
        ]);

        return back();
    }

}