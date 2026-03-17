<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;

class SettingsController extends Controller
{

    public function index()
    {
        $settings = Setting::pluck('value','key');

        return view('settings.index', compact('settings'));
    }


    public function update(Request $request)
    {

        foreach($request->except('_token') as $key => $value){

            Setting::updateOrCreate(
                ['key'=>$key],
                [
                    'group'=>'platform',
                    'value'=>$value
                ]
            );
        }

        system_log('settings','Settings Updated');

        return back()->with('success','Settings saved');
    }

}