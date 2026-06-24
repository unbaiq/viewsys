<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Events\ContentUpdatedEvent;

class MediaController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LIST
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $query = Media::query();
    
        // Company restriction
        if (auth()->user()->role !== 'superadmin') {
            $query->where('company_id', auth()->user()->company_id);
        }
    
        // 🔍 Search
        if ($request->search) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
    
        // 🎯 Type filter
        if ($request->type) {
            $query->where('type', $request->type);
        }
    
        // 🔃 Sorting
        switch ($request->sort) {
            case 'oldest':
                $query->oldest();
                break;
    
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
    
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
    
            case 'size_big':
                $query->orderBy('size', 'desc');
                break;
    
            case 'size_small':
                $query->orderBy('size', 'asc');
                break;
    
            default:
                $query->latest();
        }
    
        $media = $query->paginate(12)->withQueryString();
    
        return view('media.index', compact('media'));
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        $companies = Company::pluck('name', 'id');
        return view('media.create', compact('companies'));
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $file = $request->file('file');

        if (!$file) {
            return back()->withErrors(['file' => 'File is required'])->withInput();
        }

        // Detect type
        $type = str_contains($file->getMimeType(), 'video') ? 'video' : 'image';

        // Validation
        $request->validate([
            'file' => 'required|mimes:jpg,jpeg,png,webp,mp4,mov,webm|max:512000',
            'name' => 'nullable|string|max:255',
            'company_id' => 'nullable|exists:companies,id',
            'display_duration' => 'nullable|integer|min:1'
        ]);

        // Extra rule
        if ($type === 'image' && !$request->display_duration) {
            return back()->withErrors([
                'display_duration' => 'Display duration is required for images'
            ])->withInput();
        }

        $user = auth()->user();

        // Company handling
        if ($user->role === 'superadmin') {
            if (!$request->company_id) {
                return back()->withErrors(['company_id' => 'Company is required'])->withInput();
            }
            $companyId = $request->company_id;
        } else {
            $companyId = $user->company_id;
        }

        $company = Company::find($companyId);

        if (!$company) {
            return back()->withErrors(['company_id' => 'Invalid company'])->withInput();
        }

        // Storage limit check
        $used = Media::where('company_id', $companyId)->sum('size');
        $limitBytes = ($company->storage_limit ?? 10240) * 1024 * 1024;

        if (($used + $file->getSize()) > $limitBytes) {
            return back()->withErrors([
                'file' => 'Storage limit reached'
            ])->withInput();
        }

        // File name
        $extension = $file->getClientOriginalExtension();

        $baseName = $request->name
            ? Str::slug($request->name)
            : Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));

        if (!$baseName) {
            $baseName = 'media';
        }

        $fileName = $baseName . '_' . time() . '_' . uniqid() . '.' . $extension;

        $path = $file->storeAs('media', $fileName, 'public');

        // Duration logic
        $duration = $type === 'video' ? 0 : null;
        $displayDuration = $type === 'image'
            ? ($request->display_duration ?? 10)
            : null;

        // Save
        $media = Media::create([
            'company_id' => $companyId,
            'name' => $request->name ?: $file->getClientOriginalName(),
            'file_name' => $fileName,
            'type' => $type,
            'file_path' => $path,
            'size' => $file->getSize(),
            'duration' => $duration,
            'display_duration' => $displayDuration,
            'created_by' => $user->id
        ]);

        if (function_exists('system_log')) {
            system_log('media', 'Media Uploaded', $media->name);
        }

        event(new ContentUpdatedEvent($media));

        return redirect()->route('media.index')
            ->with('success', 'Media uploaded successfully');
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW
    |--------------------------------------------------------------------------
    */
    public function show(Media $media)
    {
        $this->authorizeMedia($media);

        return view('media.show', compact('media'));
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */
    public function edit(Media $media)
    {
      
        $this->authorizeMedia($media);

        $companies = Company::pluck('name', 'id');

        return view('media.edit', compact('media', 'companies'));
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, Media $media)
    {
        $this->authorizeMedia($media);

        $request->validate([
            'name' => 'nullable|string|max:255',
            'display_duration' => 'nullable|integer|min:1'
        ]);

        if ($media->isImage() && !$request->display_duration) {
            return back()->withErrors([
                'display_duration' => 'Display duration is required for images'
            ])->withInput();
        }

        $media->update([
            'name' => $request->name ?? $media->name,
            'display_duration' => $media->isImage()
                ? $request->display_duration
                : null
        ]);

        event(new ContentUpdatedEvent($media));

        return redirect()->route('media.index')
            ->with('success', 'Media updated successfully');
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */
    public function destroy(Media $media)
    {
        $this->authorizeMedia($media);

        // File delete handled in Model booted()

        if (function_exists('system_log')) {
            system_log('media', 'Media Deleted', $media->name);
        }

        $media->delete();

        return back()->with('success', 'Media deleted successfully');
    }


    /*
    |--------------------------------------------------------------------------
    | HELPER
    |--------------------------------------------------------------------------
    */
    private function authorizeMedia(Media $media)
    {
        $user = auth()->user();
    
        if (!$user) {
            abort(403);
        }
    
        // ✅ FIXED: Spatie role check
        if ($user->hasRole('superadmin')) {
            return true;
        }
    
        // ❌ User must have company
        if (!$user->company_id) {
            abort(403, 'User has no company');
        }
    
        // ✅ Auto-fix media if missing company (important)
        if (!$media->company_id) {
            $media->update([
                'company_id' => $user->company_id
            ]);
            return true;
        }
    
        // ✅ Same company
        if ((int)$media->company_id === (int)$user->company_id) {
            return true;
        }
    
        abort(403, 'Unauthorized access');
    }
}