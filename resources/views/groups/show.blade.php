@extends('layouts.app')

@section('title', $group->name)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white shadow-md rounded-lg p-8 mb-8">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h1 class="text-3xl font-bold mb-2">{{ $group->name }}</h1>
                    <p class="text-gray-600">{{ $group->description }}</p>
                </div>
                @if($group->isUserLeader(Auth::id()))
                    <span class="bg-blue-100 text-blue-800 text-sm font-medium px-3 py-1 rounded-full">
                        Group Leader
                    </span>
                @elseif($group->isUserMember(Auth::id()))
                    <span class="bg-green-100 text-green-800 text-sm font-medium px-3 py-1 rounded-full">
                        Member
                    </span>
                @endif
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-sm font-medium text-gray-500">Leader</h3>
                    <p class="text-lg font-semibold">{{ $group->leader->name }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-sm font-medium text-gray-500">Members</h3>
                    <p class="text-lg font-semibold">{{ $group->getMemberCount() }}/{{ $group->max_members }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-sm font-medium text-gray-500">Status</h3>
                    <p class="text-lg font-semibold capitalize">{{ $group->status }}</p>
                </div>
            </div>

            @if($group->challenge)
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <h3 class="text-sm font-medium text-blue-800 mb-2">Associated Challenge</h3>
                    <p class="text-blue-700">{{ $group->challenge->name }}</p>
                    <p class="text-sm text-blue-600 mt-1">{{ $group->challenge->description }}</p>
                </div>
            @endif

            @if($group->isUserMember(Auth::id()) && !$group->isUserLeader(Auth::id()))
                <form action="{{ route('groups.leave', $group) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" onclick="return confirm('Are you sure you want to leave this group?')">
                        Leave Group
                    </button>
                </form>
            @endif
        </div>

        <div class="bg-white shadow-md rounded-lg p-8">
            <h2 class="text-2xl font-bold mb-6">Group Members</h2>

            @if($group->members->count() > 0)
                <div class="space-y-4">
                    @foreach($group->members as $member)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-4">
                                <div>
                                    <p class="font-medium">{{ $member->user->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $member->user->email }}</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                @if($member->status === 'active')
                                    <span class="bg-green-100 text-green-800 text-sm font-medium px-3 py-1 rounded-full">
                                        Active
                                    </span>
                                @elseif($member->status === 'pending')
                                    <span class="bg-yellow-100 text-yellow-800 text-sm font-medium px-3 py-1 rounded-full">
                                        Pending Approval
                                    </span>
                                @endif

                                @if($group->isUserLeader(Auth::id()) && $member->status === 'pending')
                                    <form action="{{ route('groups.approve-member', [$group, $member->user_id]) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white text-sm font-bold py-1 px-2 rounded">
                                            Approve
                                        </button>
                                    </form>
                                    <form action="{{ route('groups.reject-member', [$group, $member->user_id]) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white text-sm font-bold py-1 px-2 rounded" onclick="return confirm('Are you sure you want to reject this member?')">
                                            Reject
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-8">No members yet.</p>
            @endif

            @if(!$group->isUserMember(Auth::id()) && $group->hasAvailableSlots())
                <div class="mt-6">
                    <form action="{{ route('groups.join', $group) }}" method="POST">
                        @csrf
                        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Join This Group
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
