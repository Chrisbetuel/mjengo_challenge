<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    // 🔹 User creates group (status = pending)
    // 🔹 User creates group (status = pending and be done)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'max_members' => 'required|integer|min:2|max:50',
        ]);

        $group = Group::create([
            'name' => $request->name,
            'description' => $request->description,
            'leader_id' => Auth::id(), // Sanctum user
            'max_members' => $request->max_members,
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Group created and awaiting admin approval',
            'data' => $group
        ], 201);
    }

    // 🔹 List ACTIVE groups only
    public function index()
    {
        $groups = Group::where('status', 'active')
            ->with('leader')
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $groups
        ]);
    }

    public function myGroups(Request $request)
{
    $user = $request->user();

    $groups = Group::where('leader_id', $user->id)
        ->orWhereHas('members', fn ($q) => $q->where('user_id', $user->id))
        ->withCount('members')
        ->with('leader')
        ->get()
        ->map(function ($group) {
            return [
                'id' => $group->id,
                'name' => $group->name,
                'status' => $group->status, // active | pending
                'members' => $group->members_count,
                'leader' => $group->leader->name,
                'challenge' => $group->challenge_name,
                'progress' => $group->target_amount > 0
                    ? round($group->total_saved / $group->target_amount, 2)
                    : 0,
                'totalSaved' => $group->total_saved,
                'targetAmount' => $group->target_amount,
                'nextPaymentDate' => optional($group->next_payment_date)->toDateString(),
                'createdDate' => $group->created_at->toDateString(),
                'image' => null,
            ];
        });

    return response()->json([
        'success' => true,
        'data' => $groups,
    ]);
 }

}
