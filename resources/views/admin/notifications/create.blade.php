@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Send Notification</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.notifications.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.notifications.store') }}">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title">Title *</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                                           id="title" name="title" value="{{ old('title') }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="type">Type *</label>
                                    <select class="form-control @error('type') is-invalid @enderror"
                                            id="type" name="type" required>
                                        <option value="">Select Type</option>
                                        <option value="system" {{ old('type') == 'system' ? 'selected' : '' }}>System</option>
                                        <option value="payment" {{ old('type') == 'payment' ? 'selected' : '' }}>Payment</option>
                                        <option value="challenge" {{ old('type') == 'challenge' ? 'selected' : '' }}>Challenge</option>
                                        <option value="penalty" {{ old('type') == 'penalty' ? 'selected' : '' }}>Penalty</option>
                                        <option value="group" {{ old('type') == 'group' ? 'selected' : '' }}>Group</option>
                                    </select>
                                    @error('type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="message">Message *</label>
                            <textarea class="form-control @error('message') is-invalid @enderror"
                                      id="message" name="message" rows="4" required>{{ old('message') }}</textarea>
                            @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label>Select Recipients *</label>
                            <div class="border p-3" style="max-height: 300px; overflow-y: auto;">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="selectAllUsers">
                                    <label class="form-check-label font-weight-bold" for="selectAllUsers">
                                        Select All Users
                                    </label>
                                </div>
                                <hr>
                                @foreach($users as $user)
                                    <div class="form-check">
                                        <input class="form-check-input user-checkbox" type="checkbox"
                                               id="user_{{ $user->id }}" name="user_ids[]" value="{{ $user->id }}">
                                        <label class="form-check-label" for="user_{{ $user->id }}">
                                            {{ $user->username }} ({{ $user->email }})
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @error('user_ids')
                                <div class="text-danger mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-paper-plane"></i> Send Notification
                            </button>
                            <a href="{{ route('admin.notifications.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Select all users checkbox
    $('#selectAllUsers').on('change', function() {
        $('.user-checkbox').prop('checked', $(this).prop('checked'));
    });

    // Individual user checkbox change
    $('.user-checkbox').on('change', function() {
        var allChecked = $('.user-checkbox:checked').length === $('.user-checkbox').length;
        $('#selectAllUsers').prop('checked', allChecked);
    });
});
</script>
@endsection
