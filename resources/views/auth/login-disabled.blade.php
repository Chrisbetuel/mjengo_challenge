@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-6 text-center">
        <h2 class="text-2xl font-bold mb-4">Login Disabled</h2>
        <p class="text-gray-600 mb-6">Login functionality is currently disabled for maintenance.</p>
        <a href="{{ route('home') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Go to Home
        </a>
    </div>
</div>
@endsection
