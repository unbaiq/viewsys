<?php

namespace App\Http\Controllers;

use App\Models\SystemLog;
use Illuminate\Http\Request;

class SystemLogController extends Controller
{

    public function index(Request $request)
    {
        $query = SystemLog::with('user');

        if ($request->search) {
            $query->where('action','like',"%{$request->search}%")
                  ->orWhere('description','like',"%{$request->search}%");
        }

        if ($request->type) {
            $query->where('type',$request->type);
        }

        $logs = $query->latest()->paginate(20)->withQueryString();

        return view('logs.index', compact('logs'));
    }


    public function show(SystemLog $log)
    {
        return view('logs.show', compact('log'));
    }


    public function destroy(SystemLog $log)
    {
        $log->delete();

        return back()->with('success','Log deleted');
    }


    public function clear()
    {
        SystemLog::truncate();

        return back()->with('success','All logs cleared');
    }

}