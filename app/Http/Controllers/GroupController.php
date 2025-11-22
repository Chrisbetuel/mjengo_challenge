<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Challenge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    public function index()
    {
        $groups = Group::where('status', 'active')
            ->with(['leader', 'challenge'])
            ->latest()
            ->get();

        return view('groups.index', compact('groups'));
    }

    public function create()
    {
        $challenges = Challenge::where('status', 'active')->get();
        $userGroups = Auth::user()->groupMemberships()->with('group.leader')->get();
        $groups = Group::where('status', 'active')
            ->with(['leader', 'challenge'])
            ->latest()
            ->get();
        return view('groups.create', compact('challenges', 'userGroups', 'groups'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'challenge_id' => 'nullable|exists:challenges,id',
            'max_members' => 'required|integer|min:2|max:50',
        ]);

        $group = Group::create([
            'name' => $request->name,
            'description' => $request->description,
            'leader_id' => Auth::id(),
            'challenge_id' => $request->challenge_id,
            'max_members' => $request->max_members,
            'status' => 'pending', // Groups start as pending for admin approval
        ]);

        return redirect()->route('groups.index')->with('success', 'Group created successfully and is pending admin approval.');
    }

    public function show(Group $group)
    {
        $group->load(['leader', 'members.user', 'challenge']);
        return view('groups.show', compact('group'));
    }

    public function join(Group $group)
    {
        if (!$group->hasAvailableSlots()) {
            return back()->with('error', 'Group is full.');
        }

        if ($group->isUserMember(Auth::id())) {
            return back()->with('error', 'You are already a member of this group.');
        }

        $group->addMember(Auth::id(), 'pending'); // Members also need approval

        return back()->with('success', 'Join request sent. Waiting for group leader approval.');
    }

    public function leave(Group $group)
    {
        $group->removeMember(Auth::id());
        return back()->with('success', 'You have left the group.');
    }

    public function approveMember(Group $group, $memberId)
    {
        if (!$group->isUserLeader(Auth::id())) {
            abort(403);
        }

        $group->members()->where('user_id', $memberId)->update(['status' => 'active']);
        return back()->with('success', 'Member approved.');
    }

    public function rejectMember(Group $group, $memberId)
    {
        if (!$group->isUserLeader(Auth::id())) {
            abort(403);
        }

        $group->members()->where('user_id', $memberId)->delete();
        return back()->with('success', 'Member request rejected.');
    }
}
