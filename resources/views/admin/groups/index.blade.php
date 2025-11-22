@extends('layouts.app')

@section('title', 'Manage Groups')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold">Manage Groups</h1>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h2 class="text-xl font-semibold">Pending Group Approvals</h2>
        </div>

        @if($pendingGroups->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Group Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Leader</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Members</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Challenge</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($pendingGroups as $group)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $group->name }}</div>
                                    <div class="text-sm text-gray-500">{{ Str::limit($group->description, 50) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $group->leader->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $group->leader->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $group->getMemberCount() }}/{{ $group->max_members }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $group->challenge ? $group->challenge->name : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $group->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <form action="{{ route('admin.groups.approve', $group) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-3 rounded text-sm">
                                            Approve
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.groups.reject', $group) }}" method="POST" class="inline ml-2">
                                        @csrf
                                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-3 rounded text-sm" onclick="return confirm('Are you sure you want to reject this group?')">
                                            Reject
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="px-6 py-8 text-center">
                <div class="text-gray-400 mb-4">
                    <i class="fas fa-check-circle text-4xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-600 mb-2">No Pending Groups</h3>
                <p class="text-gray-500">All groups have been processed.</p>
            </div>
        @endif
    </div>

    <div class="bg-white shadow-md rounded-lg overflow-hidden mt-8">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <h2 class="text-xl font-semibold">Active Groups</h2>
        </div>

        @if($activeGroups->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Group Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Leader</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Members</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Challenge</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($activeGroups as $group)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $group->name }}</div>
                                    <div class="text-sm text-gray-500">{{ Str::limit($group->description, 50) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $group->leader->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $group->leader->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $group->getMemberCount() }}/{{ $group->max_members }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $group->challenge ? $group->challenge->name : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        Active
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="{{ route('groups.show', $group) }}" class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                                    <form action="{{ route('admin.groups.deactivate', $group) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to deactivate this group?')">
                                            Deactivate
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="px-6 py-8 text-center">
                <div class="text-gray-400 mb-4">
                    <i class="fas fa-users text-4xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-600 mb-2">No Active Groups</h3>
                <p class="text-gray-500">No groups are currently active.</p>
            </div>
        @endif
    </div>
</div>
@endsection
