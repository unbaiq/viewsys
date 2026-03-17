<?php

namespace App\Http\Controllers;

use App\Models\Cluster;
use App\Models\Screen;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ClusterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Get Company ID
    |--------------------------------------------------------------------------
    */
    private function companyId()
    {
        $user = Auth::user();

        if (!$user) {
            abort(403);
        }

        if (!$user->company_id) {

            $company = Company::first();

            if (!$company) {
                abort(500, 'No company exists.');
            }

            $user->company_id = $company->id;
            $user->save();
        }

        return $user->company_id;
    }

    /*
    |--------------------------------------------------------------------------
    | List Clusters
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $companyId = $this->companyId();

        $clusters = Cluster::where('company_id', $companyId)
            ->withCount('screens')
            ->latest()
            ->paginate(15);

        return view('clusters.index', compact('clusters'));
    }

    /*
    |--------------------------------------------------------------------------
    | Create Form
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        $companyId = $this->companyId();

        $screens = Screen::where('company_id', $companyId)
            ->orderBy('name')
            ->get();

        return view('clusters.create', compact('screens'));
    }

    /*
    |--------------------------------------------------------------------------
    | Store Cluster
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $companyId = $this->companyId();

        $request->validate([
            'name' => 'required|max:255',
            'layout' => 'required',
            'screens' => 'nullable|array',
            'screens.*' => 'integer'
        ]);

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
            'created_by' => Auth::id()
        ]);

        if ($request->filled('screens')) {

            $validScreens = Screen::where('company_id', $companyId)
                ->whereIn('id', $request->screens)
                ->pluck('id')
                ->toArray();

            $cluster->screens()->sync($validScreens);
        }

        return redirect()
            ->route('clusters.index')
            ->with('success', 'Cluster created successfully');
    }

    /*
    |--------------------------------------------------------------------------
    | Show Cluster
    |--------------------------------------------------------------------------
    */
    public function show(Cluster $cluster)
    {
        $this->authorizeCluster($cluster);

        $cluster->load('screens');

        return view('clusters.show', compact('cluster'));
    }

    /*
    |--------------------------------------------------------------------------
    | Edit Form
    |--------------------------------------------------------------------------
    */
    public function edit(Cluster $cluster)
    {
        $this->authorizeCluster($cluster);

        $companyId = $this->companyId();

        $screens = Screen::where('company_id', $companyId)
            ->orderBy('name')
            ->get();

        $assignedScreens = $cluster->screens->pluck('id')->toArray();

        return view('clusters.edit', compact('cluster', 'screens', 'assignedScreens'));
    }

    /*
    |--------------------------------------------------------------------------
    | Update Cluster
    |--------------------------------------------------------------------------
    */
    public function update(Request $request, Cluster $cluster)
    {
        $this->authorizeCluster($cluster);

        $companyId = $this->companyId();

        $request->validate([
            'name' => 'required|max:255',
            'layout' => 'required',
            'screens' => 'nullable|array',
            'screens.*' => 'integer'
        ]);

        $cluster->update([
            'name' => $request->name,
            'description' => $request->description,
            'location' => $request->location,
            'type' => $request->layout,
            'header_text' => $request->header_text,
            'ticker_text' => $request->ticker_text,
            'is_active' => $request->boolean('is_active'),
        ]);

        if ($request->filled('screens')) {

            $validScreens = Screen::where('company_id', $companyId)
                ->whereIn('id', $request->screens)
                ->pluck('id')
                ->toArray();

            $cluster->screens()->sync($validScreens);

        } else {
            $cluster->screens()->detach();
        }

        return redirect()
            ->route('clusters.index')
            ->with('success', 'Cluster updated successfully');
    }

    /*
    |--------------------------------------------------------------------------
    | Delete Cluster
    |--------------------------------------------------------------------------
    */
    public function destroy(Cluster $cluster)
    {
        $this->authorizeCluster($cluster);

        $cluster->screens()->detach();
        $cluster->delete();

        return redirect()
            ->route('clusters.index')
            ->with('success', 'Cluster deleted');
    }

    /*
    |--------------------------------------------------------------------------
    | Security
    |--------------------------------------------------------------------------
    */
    private function authorizeCluster($cluster)
    {
        if ($cluster->company_id !== $this->companyId()) {
            abort(403, 'Unauthorized action.');
        }
    }
}