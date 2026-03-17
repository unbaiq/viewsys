<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Events\ContentUpdatedEvent;

class MediaController extends Controller
{

    public function index(Request $request)
    {
        $query = Media::with('company');

        if (auth()->user()->role !== 'superadmin') {
            $query->where('company_id', auth()->user()->company_id);
        }

        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $media = $query->latest()->paginate(12);

        return view('media.index', compact('media'));
    }


    public function create()
    {
        $companies = Company::pluck('name','id');

        return view('media.create', compact('companies'));
    }


    public function store(Request $request)
    {

        $request->validate([
            'file' => 'required|mimes:jpg,jpeg,png,webp,mp4,mov,webm|max:512000'
        ]);

        $file = $request->file('file');
        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | Determine Company
        |--------------------------------------------------------------------------
        */

        if ($user->role === 'superadmin') {

            if (!$request->company_id) {
                return back()->withErrors(['company_id' => 'Company is required']);
            }

            $companyId = $request->company_id;

        } else {

            $companyId = $user->company_id;

        }


        /*
        |--------------------------------------------------------------------------
        | Storage Limit
        |--------------------------------------------------------------------------
        */

        $company = Company::find($companyId);

        $used = Media::where('company_id', $companyId)->sum('size');

        $limitMB = $company->storage_limit ?? 10240;

        $limitBytes = $limitMB * 1024 * 1024;

        if (($used + $file->getSize()) > $limitBytes) {

            return back()->withErrors([
                'file' => 'Storage limit reached. Please upgrade your plan.'
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Store File
        |--------------------------------------------------------------------------
        */

        $path = $file->store('media', 'public');

        $type = str_contains($file->getMimeType(), 'video') ? 'video' : 'image';


        /*
        |--------------------------------------------------------------------------
        | Save Database
        |--------------------------------------------------------------------------
        */

        $media = Media::create([
            'company_id' => $companyId,
            'name' => $file->getClientOriginalName(),
            'type' => $type,
            'file_path' => $path,
            'size' => $file->getSize(),
            'created_by' => $user->id
        ]);


        /*
        |--------------------------------------------------------------------------
        | Logs
        |--------------------------------------------------------------------------
        */

        if (function_exists('system_log')) {
            system_log('media', 'Media Uploaded', $file->getClientOriginalName());
        }


        /*
        |--------------------------------------------------------------------------
        | Trigger Player Sync
        |--------------------------------------------------------------------------
        */

        event(new ContentUpdatedEvent($media));


        return redirect()->route('media.index')
            ->with('success', 'Media uploaded successfully');
    }


    public function show(Media $media)
    {
        if(auth()->user()->role !== 'superadmin' &&
           $media->company_id !== auth()->user()->company_id){
            abort(403);
        }

        return view('media.show', compact('media'));
    }


    public function destroy(Media $media)
    {
        if(auth()->user()->role !== 'superadmin' &&
           $media->company_id !== auth()->user()->company_id){
            abort(403);
        }

        Storage::disk('public')->delete($media->file_path);

        if (function_exists('system_log')) {
            system_log('media', 'Media Deleted', $media->name);
        }

        $media->delete();

        return back()->with('success', 'Media deleted');
    }

}