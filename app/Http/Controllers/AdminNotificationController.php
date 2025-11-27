<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;

class AdminNotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::with('user')
            ->latest()
            ->paginate(20);

        return view('admin.notifications.index', compact('notifications'));
    }

    public function create()
    {
        $users = User::select('id', 'username', 'email')->get();
        return view('admin.notifications.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:payment,challenge,penalty,system,group',
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $createdCount = 0;
        foreach ($request->user_ids as $userId) {
            Notification::create([
                'user_id' => $userId,
                'title' => $request->title,
                'message' => $request->message,
                'type' => $request->type,
                'data' => $request->data ?? null,
            ]);
            $createdCount++;
        }

        return redirect()->route('admin.notifications.index')
            ->with('success', "Notification sent to {$createdCount} user(s) successfully!");
    }

    public function sendToAll(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:payment,challenge,penalty,system,group',
        ]);

        $users = User::all();
        $createdCount = 0;

        foreach ($users as $user) {
            Notification::create([
                'user_id' => $user->id,
                'title' => $request->title,
                'message' => $request->message,
                'type' => $request->type,
                'data' => $request->data ?? null,
            ]);
            $createdCount++;
        }

        return redirect()->route('admin.notifications.index')
            ->with('success', "Notification sent to all {$createdCount} users successfully!");
    }

    public function destroy($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->delete();

        return redirect()->back()
            ->with('success', 'Notification deleted successfully!');
    }

    public function bulkDelete(Request $request)
    {
        $request->validate([
            'notification_ids' => 'required|array',
            'notification_ids.*' => 'exists:notifications,id',
        ]);

        Notification::whereIn('id', $request->notification_ids)->delete();

        return redirect()->back()
            ->with('success', count($request->notification_ids) . ' notification(s) deleted successfully!');
    }

    public function show($id)
    {
        $notification = Notification::with('user')->findOrFail($id);
        return view('admin.notifications.show', compact('notification'));
    }
}
