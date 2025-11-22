@extends('layouts.app')

@section('title', 'Create Challenge')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">Create New Challenge</h1>
                <a href="{{ route('admin.challenges') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Challenges
                </a>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Challenge Details</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.challenges.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Challenge Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="name" name="name" value="{{ old('name') }}"
                                       placeholder="Enter challenge name" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="daily_amount" class="form-label">Daily Amount (KES) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('daily_amount') is-invalid @enderror"
                                       id="daily_amount" name="daily_amount" value="{{ old('daily_amount') }}"
                                       placeholder="1000" min="1000" step="100" required>
                                @error('daily_amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Minimum amount is 1000 KES</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="max_participants" class="form-label">Maximum Participants <span class="text-danger">*</span></label>
                                <input type="number" class="form-control @error('max_participants') is-invalid @enderror"
                                       id="max_participants" name="max_participants" value="{{ old('max_participants', 30) }}"
                                       placeholder="30" min="1" max="90" required>
                                @error('max_participants')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Between 1 and 90 participants</div>
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="start_date" class="form-label">Start Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('start_date') is-invalid @enderror"
                                       id="start_date" name="start_date" value="{{ old('start_date') }}"
                                       min="{{ date('Y-m-d') }}" required>
                                @error('start_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-3 mb-3">
                                <label for="end_date" class="form-label">End Date <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('end_date') is-invalid @enderror"
                                       id="end_date" name="end_date" value="{{ old('end_date') }}"
                                       min="{{ date('Y-m-d') }}" required>
                                @error('end_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description" name="description" rows="4"
                                      placeholder="Describe the challenge objectives, rules, and benefits..." required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Create Challenge
                                </button>
                                <a href="{{ route('admin.challenges') }}" class="btn btn-secondary ms-2">
                                    <i class="fas fa-times me-2"></i>Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Challenge Preview Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">Challenge Preview</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 id="preview-name">Challenge Name</h5>
                            <p class="text-muted mb-2" id="preview-description">Challenge description will appear here...</p>
                            <div class="row">
                                <div class="col-6">
                                    <strong>Daily Amount:</strong> <span id="preview-amount">0</span> KES
                                </div>
                                <div class="col-6">
                                    <strong>Max Participants:</strong> <span id="preview-participants">0</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-6">
                                    <strong>Start Date:</strong> <span id="preview-start">Not set</span>
                                </div>
                                <div class="col-6">
                                    <strong>End Date:</strong> <span id="preview-end">Not set</span>
                                </div>
                            </div>
                            <div class="mt-3">
                                <strong>Duration:</strong> <span id="preview-duration">0 days</span>
                            </div>
                            <div class="mt-2">
                                <strong>Total Expected:</strong> <span id="preview-total">0</span> KES per participant
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const inputs = form.querySelectorAll('input, textarea');

    function updatePreview() {
        const name = document.getElementById('name').value || 'Challenge Name';
        const description = document.getElementById('description').value || 'Challenge description will appear here...';
        const amount = parseFloat(document.getElementById('daily_amount').value) || 0;
        const participants = parseInt(document.getElementById('max_participants').value) || 0;
        const startDate = document.getElementById('start_date').value;
        const endDate = document.getElementById('end_date').value;

        document.getElementById('preview-name').textContent = name;
        document.getElementById('preview-description').textContent = description;
        document.getElementById('preview-amount').textContent = amount.toLocaleString();
        document.getElementById('preview-participants').textContent = participants;

        if (startDate) {
            document.getElementById('preview-start').textContent = new Date(startDate).toLocaleDateString();
        }
        if (endDate) {
            document.getElementById('preview-end').textContent = new Date(endDate).toLocaleDateString();
        }

        if (startDate && endDate) {
            const start = new Date(startDate);
            const end = new Date(endDate);
            const diffTime = Math.abs(end - start);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
            document.getElementById('preview-duration').textContent = diffDays + ' days';
            document.getElementById('preview-total').textContent = (amount * diffDays).toLocaleString();
        }
    }

    inputs.forEach(input => {
        input.addEventListener('input', updatePreview);
    });

    // Initial preview update
    updatePreview();
});
</script>
@endpush
@endsection
