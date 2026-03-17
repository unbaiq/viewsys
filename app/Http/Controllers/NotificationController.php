<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{

    /**
     * Display notifications
     */
    public function index()
    {
        $user = auth()->user();

        $query = Notification::query();

        // Multi-tenant filter
        if ($user->role !== 'superadmin') {
            $query->where(function ($q) use ($user) {
                $q->where('company_id', $user->company_id)
                  ->orWhere('user_id', $user->id);
            });
        }

        $notifications = $query
            ->latest()
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }


    /**
     * Mark single notification as read
     */
    public function read($id)
    {
        $notification = Notification::findOrFail($id);

        // Security check
        if(auth()->user()->role !== 'superadmin' &&
           $notification->company_id !== auth()->user()->company_id){
            abort(403);
        }

        $notification->update([
            'read' => true
        ]);

        return back()->with('success','Notification marked as read');
    }


    /**
     * Mark all notifications as read
     */
    public function readAll()
    {
        $user = auth()->user();

        $query = Notification::query();

        if ($user->role !== 'superadmin') {
            $query->where('company_id', $user->company_id);
        }

        $query->update([
            'read' => true
        ]);

        return back()->with('success','All notifications marked as read');
    }


    /**
     * Delete notification
     */
    public function destroy($id)
    {
        $notification = Notification::findOrFail($id);

        if(auth()->user()->role !== 'superadmin' &&
           $notification->company_id !== auth()->user()->company_id){
            abort(403);
        }

        $notification->delete();

        return back()->with('success','Notification deleted');
    }


    /**
     * Unread counter for navbar
     */
    public function unreadCount()
    {
        $user = auth()->user();

        $query = Notification::where('read', false);

        if ($user->role !== 'superadmin') {
            $query->where('company_id', $user->company_id);
        }

        return response()->json([
            'count' => $query->count()
        ]);
    }

}