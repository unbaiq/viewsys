<?php

namespace App\Http\Controllers;

use App\Models\Cluster;
use App\Models\ClusterLayout;
use App\Models\Company;
use App\Models\Media;
use App\Models\Screen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ClusterController extends Controller
{
    protected function companyId()
    {
        $user = Auth::user();

        if (!$user || !$user->company_id) {
            abort(403, 'User not assigned to company.');
        }

        return $user->company_id;
    }

    protected function authorizeCluster(Cluster $cluster)
    {
        $user = Auth::user();

        if ($user->role === 'superadmin') {
            return;
        }

        if ($cluster->company_id != $this->companyId()) {
            abort(403);
        }
    }

    public function index()
    {
        $user = Auth::user();

        $query = Cluster::withCount('screens')
            ->with('company')
            ->latest();

        if ($user->role !== 'superadmin') {
            $query->where('company_id', $this->companyId());
        }

        $clusters = $query->paginate(15);

        return view('clusters.index', compact('clusters'));
    }

    public function create()
    {
        $user = Auth::user();

        if ($user->role === 'superadmin') {

            $companies = Company::orderBy('name')->get();
            $screens = [];
            $media = [];

        } else {

            $companies = [];

            $screens = Screen::where(
                'company_id',
                $this->companyId()
            )->orderBy('name')->get();

            $media = Media::where(
                'company_id',
                $this->companyId()
            )->orderBy('name')->get();
        }

        return view('clusters.create', compact(
            'companies',
            'screens',
            'media'
        ));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $companyId = $user->role === 'superadmin'
            ? $request->company_id
            : $this->companyId();

        $request->validate([
            'name' => 'required|max:255',
            'layout' => 'required',
            'company_id' => $user->role === 'superadmin'
                ? 'required|exists:companies,id'
                : 'nullable',
            'screens' => 'nullable|array',
            'screens.*' => 'integer|exists:screens,id',
            'media_sections' => 'nullable|array',
        ]);

        DB::transaction(function () use ($request, $companyId, $user) {

            $cluster = Cluster::create([
                'company_id' => $companyId,
                'name' => $request->name,
                'slug' => Str::slug($request->name) . '-' . Str::random(6),
                'description' => $request->description,
                'location' => $request->location,
                'type' => $request->layout,
                'header_text' => $request->header_text,
                'ticker_text' => $request->ticker_text,
                'is_active' => true,
                'created_by' => Auth::id(),
            ]);

            if ($request->filled('screens')) {

                $screenQuery = Screen::query();

                if ($user->role !== 'superadmin') {
                    $screenQuery->where(
                        'company_id',
                        $this->companyId()
                    );
                }

                $screenIds = $screenQuery
                    ->whereIn('id', $request->screens)
                    ->pluck('id')
                    ->toArray();

                $cluster->screens()->sync($screenIds);
            }

            if ($request->has('media_sections')) {

                foreach (
                    $request->media_sections as $zone => $mediaIds
                ) {

                    $layout = ClusterLayout::create([
                        'cluster_id' => $cluster->id,
                        'zone_name' => $zone,
                    ]);

                    if (!empty($mediaIds)) {

                        foreach ($mediaIds as $index => $mediaId) {

                            $layout->media()->attach(
                                $mediaId,
                                [
                                    'sort_order' => $index + 1
                                ]
                            );
                        }
                    }
                }
            }
        });

        return redirect()
            ->route('clusters.index')
            ->with(
                'success',
                'Cluster created successfully.'
            );
    }

    public function show(Cluster $cluster)
    {
        $this->authorizeCluster($cluster);

        $cluster->load([
            'company',
            'screens',
            'layouts.media'
        ]);

        return view(
            'clusters.show',
            compact('cluster')
        );
    }

    public function edit(Cluster $cluster)
    {
        $this->authorizeCluster($cluster);

        $user = Auth::user();

        if ($user->role === 'superadmin') {

            $companies = Company::orderBy('name')->get();

            $screens = Screen::where(
                'company_id',
                $cluster->company_id
            )->orderBy('name')->get();

            $media = Media::where(
                'company_id',
                $cluster->company_id
            )->orderBy('name')->get();

        } else {

            $companies = [];

            $screens = Screen::where(
                'company_id',
                $this->companyId()
            )->orderBy('name')->get();

            $media = Media::where(
                'company_id',
                $this->companyId()
            )->orderBy('name')->get();
        }

        $cluster->load('layouts.media');

        $assignedScreens = $cluster->screens()
            ->pluck('screens.id')
            ->toArray();

        return view('clusters.edit', compact(
            'cluster',
            'companies',
            'screens',
            'media',
            'assignedScreens'
        ));
    }

    public function update(Request $request, Cluster $cluster)
    {
        $this->authorizeCluster($cluster);

        $request->validate([
            'name' => 'required|max:255',
            'layout' => 'required',
            'screens' => 'nullable|array',
            'screens.*' => 'integer|exists:screens,id',
            'media_sections' => 'nullable|array',
        ]);

        DB::transaction(function () use ($request, $cluster) {

            $cluster->update([
                'name' => $request->name,
                'description' => $request->description,
                'location' => $request->location,
                'type' => $request->layout,
                'header_text' => $request->header_text,
                'ticker_text' => $request->ticker_text,
                'is_active' => $request->boolean('is_active'),
            ]);

            // Update screen assignment
            $cluster->screens()->sync($request->screens ?? []);

            // Get assigned screens
            $screenIds = $cluster->screens()
                ->pluck('screens.id')
                ->toArray();

            // Remove old media links
            foreach ($cluster->layouts as $layout) {
                $layout->media()->detach();
            }

            // Remove old layouts
            $cluster->layouts()->delete();

            // Create new layouts
            if ($request->has('media_sections')) {

                foreach ($request->media_sections as $zone => $mediaIds) {

                    $layout = ClusterLayout::create([
                        'cluster_id' => $cluster->id,
                        'zone_name' => $zone,
                    ]);

                    if (!empty($mediaIds)) {

                        foreach ($mediaIds as $index => $mediaId) {

                            $layout->media()->attach(
                                $mediaId,
                                [
                                    'sort_order' => $index + 1,
                                ]
                            );
                        }
                    }
                }
            }

            // Notify screens that cluster layout/content changed
            if (!empty($screenIds)) {

                Screen::whereIn('id', $screenIds)
                    ->increment('content_version');
            }
        });

        return redirect()
            ->route('clusters.index')
            ->with(
                'success',
                'Cluster updated successfully.'
            );
    }
    public function destroy(Cluster $cluster)
    {
        $this->authorizeCluster($cluster);

        DB::transaction(function () use ($cluster) {

            foreach ($cluster->layouts as $layout) {
                $layout->media()->detach();
            }

            $cluster->layouts()->delete();

            $cluster->screens()->detach();

            $cluster->delete();
        });

        return redirect()
            ->route('clusters.index')
            ->with(
                'success',
                'Cluster deleted successfully.'
            );
    }

    public function getScreens($companyId)
    {
        $user = Auth::user();

        if (
            $user->role !== 'superadmin' &&
            $companyId != $this->companyId()
        ) {
            abort(403);
        }

        return Screen::where(
            'company_id',
            $companyId
        )
            ->select('id', 'name')
            ->orderBy('name')
            ->get();
    }

    public function getMedia($companyId)
    {
        $user = Auth::user();

        if (
            $user->role !== 'superadmin' &&
            $companyId != $this->companyId()
        ) {
            abort(403);
        }

        return Media::where(
            'company_id',
            $companyId
        )
            ->select('id', 'name', 'type')
            ->orderBy('name')
            ->get();
    }


    private function saveLayouts(Cluster $cluster, Request $request): void
    {
        $zones = $request->media_sections ?? [];


        // Layouts that don't submit media sections
        if (empty($zones)) {

            switch ($request->layout) {

                case 'fullscreen':
                    $zones['main'] = [];
                    break;

                case 'header':
                    $zones['header'] = [];
                    break;

                case 'ticker':
                    $zones['ticker'] = [];
                    break;
            }
        }

        foreach ($zones as $zone => $mediaIds) {

            $layout = ClusterLayout::create([
                'cluster_id' => $cluster->id,
                'zone_name' => $zone,
            ]);

            if (!empty($mediaIds)) {

                foreach ($mediaIds as $index => $mediaId) {

                    if (empty($mediaId)) {
                        continue;
                    }

                    $layout->media()->attach(
                        $mediaId,
                        [
                            'sort_order' => $index + 1,
                        ]
                    );
                }
            }
        }


    }

}
