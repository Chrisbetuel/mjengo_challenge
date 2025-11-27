@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('messages.notifications') }}</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-success btn-sm" id="markAllRead">
                            <i class="fas fa-check"></i> {{ __('messages.mark_all_read') }}
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($notifications->count() > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($notifications as $notification)
                                <li class="list-group-item {{ $notification->is_read ? '' : 'bg-light' }}">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1 {{ $notification->is_read ? 'text-muted' : 'font-weight-bold' }}">
                                                {{ $notification->title }}
                                            </h6>
                                            <p class="mb-1 {{ $notification->is_read ? 'text-muted' : '' }}">
                                                {{ $notification->message }}
                                            </p>
                                            <small class="text-muted">
                                                {{ $notification->created_at->diffForHumans() }}
                                            </small>
                                        </div>
                                        <div class="ml-3">
                                            @if(!$notification->is_read)
                                                <button class="btn btn-sm btn-outline-primary mark-read" data-id="{{ $notification->id }}">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">{{ __('messages.no_notifications') }}</h5>
                        </div>
                    @endif
                </div>
                @if($notifications->hasPages())
                    <div class="card-footer">
                        {{ $notifications->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('.mark-read').on('click', function() {
        var notificationId = $(this).data('id');
        var $item = $(this).closest('.list-group-item');

        $.post('/notifications/' + notificationId + '/read', {
            _token: '{{ csrf_token() }}'
        }).done(function(response) {
            if (response.success) {
                $item.removeClass('bg-light');
                $item.find('h6').removeClass('font-weight-bold').addClass('text-muted');
                $item.find('p').addClass('text-muted');
                $(this).remove();
                updateUnreadCount();
            }
        }.bind(this));
    });

    $('#markAllRead').on('click', function() {
        $.post('/notifications/mark-all-read', {
            _token: '{{ csrf_token() }}'
        }).done(function(response) {
            if (response.success) {
                $('.list-group-item').removeClass('bg-light');
                $('.list-group-item h6').removeClass('font-weight-bold').addClass('text-muted');
                $('.list-group-item p').addClass('text-muted');
                $('.mark-read').remove();
                updateUnreadCount();
            }
        });
    });

    function updateUnreadCount() {
        $.get('/notifications/unread-count').done(function(response) {
            $('#notificationBadge').text(response.count > 0 ? response.count : '');
        });
    }
});
</script>
@endsection
