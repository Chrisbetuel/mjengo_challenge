@extends('layouts.app')

@section('no_sidebar', true)

@section('title', $group->name . ' - Oweru')

@section('content')
<style>
    /* Oweru Brand Colors */
    :root {
        --oweru-dark: #09172A;
        --oweru-gold: #C89128;
        --oweru-light: #F8F8F9;
        --oweru-blue: #2D3A58;
        --oweru-secondary: #E5B972;
        --oweru-gray: #889898;
        --oweru-success: #27ae60;
        --oweru-danger: #e74c3c;
        --oweru-warning: #f39c12;
        --oweru-gradient: linear-gradient(135deg, #09172A 0%, #2D3A58 100%);
        --oweru-gradient-gold: linear-gradient(135deg, #C89128 0%, #E5B972 100%);
        --whatsapp-green: #25D366;
    }

    /* Force full-width layout - hide sidebar completely */
    .sidebar,
    [class*="sidebar"],
    .col-md-3,
    .col-lg-2,
    .navbar-vertical {
        display: none !important;
    }

    /* Adjust main content to take full width */
    .main-content,
    .col-md-9,
    .col-lg-10,
    .content-wrapper {
        width: 100% !important;
        margin-left: 0 !important;
        padding-left: 0 !important;
        max-width: 100% !important;
    }

    .container-fluid {
        padding-left: 20px !important;
        padding-right: 20px !important;
        margin-left: 0 !important;
        margin-right: 0 !important;
        width: 100% !important;
        max-width: 100% !important;
    }

    .group-details-page {
        background: var(--oweru-light);
        min-height: 100vh;
        padding: 2rem 0;
        width: 100% !important;
        margin: 0 !important;
    }

    .main-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 15px 40px rgba(9, 23, 42, 0.1);
        border: none;
        overflow: hidden;
        width: 100%;
        margin-bottom: 2rem;
    }

    .card-header-oweru {
        background: var(--oweru-gradient);
        border: none;
        padding: 1.5rem 2rem;
    }

    .stat-card {
        background: white;
        border-radius: 15px;
        padding: 1.5rem;
        box-shadow: 0 8px 25px rgba(9, 23, 42, 0.1);
        border: none;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        text-align: center;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--oweru-gradient-gold);
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(9, 23, 42, 0.15);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: var(--oweru-dark);
        background: var(--oweru-gradient-gold);
        margin: 0 auto 1rem;
    }

    .btn-oweru-primary {
        background: var(--oweru-gradient-gold);
        border: none;
        border-radius: 10px;
        color: var(--oweru-dark);
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        transition: all 0.3s ease;
    }

    .btn-oweru-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(200, 145, 40, 0.3);
        color: var(--oweru-dark);
    }

    .btn-oweru-danger {
        background: var(--oweru-danger);
        border: none;
        border-radius: 10px;
        color: white;
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        transition: all 0.3s ease;
    }

    .btn-oweru-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(231, 76, 60, 0.3);
        color: white;
    }

    .btn-oweru-success {
        background: var(--oweru-success);
        border: none;
        border-radius: 10px;
        color: white;
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        transition: all 0.3s ease;
    }

    .btn-oweru-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(39, 174, 96, 0.3);
        color: white;
    }

    .btn-oweru-outline {
        border: 2px solid var(--oweru-gold);
        border-radius: 10px;
        color: var(--oweru-gold);
        font-weight: 600;
        padding: 0.5rem 1rem;
        transition: all 0.3s ease;
    }

    .btn-oweru-outline:hover {
        background: var(--oweru-gold);
        color: var(--oweru-dark);
        transform: translateY(-2px);
    }

    .btn-whatsapp {
        background: var(--whatsapp-green);
        border: none;
        border-radius: 10px;
        color: white;
        font-weight: 600;
        padding: 0.75rem 1.5rem;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-whatsapp:hover {
        background: #128C7E;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(37, 211, 102, 0.3);
    }

    .badge-leader {
        background: var(--oweru-gradient-gold);
        color: var(--oweru-dark);
        border-radius: 20px;
        padding: 0.5rem 1rem;
        font-weight: 600;
        font-size: 0.8rem;
    }

    .badge-member {
        background: var(--oweru-success);
        color: white;
        border-radius: 20px;
        padding: 0.5rem 1rem;
        font-weight: 600;
        font-size: 0.8rem;
    }

    .badge-pending {
        background: var(--oweru-warning);
        color: white;
        border-radius: 20px;
        padding: 0.5rem 1rem;
        font-weight: 600;
        font-size: 0.8rem;
    }

    .badge-active {
        background: var(--oweru-success);
        color: white;
        border-radius: 20px;
        padding: 0.5rem 1rem;
        font-weight: 600;
        font-size: 0.8rem;
    }

    .member-card {
        background: white;
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 4px 15px rgba(9, 23, 42, 0.08);
        border: 1px solid #f1f3f4;
        transition: all 0.3s ease;
    }

    .member-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(9, 23, 42, 0.12);
    }

    .user-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: var(--oweru-gradient-gold);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--oweru-dark);
        font-weight: 600;
        font-size: 1.2rem;
    }

    .challenge-banner {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        padding: 1.5rem;
        color: white;
        margin-bottom: 2rem;
    }

    .member-progress {
        height: 8px;
        border-radius: 4px;
        background: #e9ecef;
        overflow: hidden;
        margin-top: 1rem;
    }

    .member-progress-bar {
        height: 100%;
        background: var(--oweru-gradient-gold);
        border-radius: 4px;
    }

    .empty-state {
        padding: 4rem 2rem;
        text-align: center;
    }

    .empty-state-icon {
        font-size: 4rem;
        color: var(--oweru-gray);
        margin-bottom: 1.5rem;
    }

    .share-modal .modal-content {
        border-radius: 20px;
        border: none;
        box-shadow: 0 25px 50px rgba(9, 23, 42, 0.2);
    }

    .share-modal .modal-header {
        background: var(--oweru-gradient);
        color: white;
        border-radius: 20px 20px 0 0;
        border: none;
        padding: 1.5rem 2rem;
    }

    .share-modal .modal-body {
        padding: 2rem;
    }

    .share-link-container {
        background: var(--oweru-light);
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 1.5rem;
    }

    .share-link {
        font-family: 'Courier New', monospace;
        background: white;
        padding: 0.75rem 1rem;
        border-radius: 8px;
        border: 1px solid #e9ecef;
        word-break: break-all;
        font-size: 0.9rem;
    }

    .copy-btn {
        background: var(--oweru-gold);
        border: none;
        border-radius: 8px;
        color: var(--oweru-dark);
        padding: 0.5rem 1rem;
        font-weight: 600;
        transition: all 0.3s ease;
        white-space: nowrap;
    }

    .copy-btn:hover {
        background: var(--oweru-secondary);
        transform: translateY(-1px);
    }

    .copy-btn.copied {
        background: var(--oweru-success);
        color: white;
    }

    .share-options {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 1rem;
        margin-top: 1.5rem;
    }

    .share-option-btn {
        background: white;
        border: 2px solid #f1f3f4;
        border-radius: 12px;
        padding: 1rem;
        text-align: center;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .share-option-btn:hover {
        border-color: var(--oweru-gold);
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(9, 23, 42, 0.1);
    }

    .share-option-icon {
        font-size: 2rem;
        margin-bottom: 0.5rem;
    }

    .futura-font {
        font-family: 'Futura PT', Arial, sans-serif;
    }

    .poppins-font {
        font-family: 'Poppins', Arial, sans-serif;
    }

    /* Ensure full width for all containers */
    .row {
        margin-left: 0 !important;
        margin-right: 0 !important;
        width: 100% !important;
    }

    .col-12 {
        padding-left: 0 !important;
        padding-right: 0 !important;
    }

    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .container-fluid {
            padding-left: 15px !important;
            padding-right: 15px !important;
        }
        
        .stat-card {
            margin-bottom: 1rem;
        }
        
        .member-card {
            padding: 1rem;
        }

        .share-options {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="group-details-page">
    <div class="container-fluid">
        <!-- Group Header -->
        <div class="main-card">
            <div class="card-header-oweru text-white">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="h2 mb-2 futura-font">
                            <i class="fas fa-users me-2"></i>
                            {{ $group->name }}
                        </h1>
                        <p class="mb-0 poppins-font opacity-90">{{ $group->description }}</p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        @if($group->isUserLeader(Auth::id()))
                            <span class="badge-leader px-3 py-2">
                                <i class="fas fa-crown me-1"></i>Group Leader
                            </span>
                        @elseif($group->isUserMember(Auth::id()))
                            <span class="badge-member px-3 py-2">
                                <i class="fas fa-user-check me-1"></i>Member
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-body p-4">
                <!-- Statistics Row -->
                <div class="row mb-4">
                    <div class="col-md-4 mb-3">
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-user-shield"></i>
                            </div>
                            <h3 class="text-oweru-dark mb-1 futura-font">{{ $group->leader->username ?? 'N/A' }}</h3>
                            <p class="text-oweru-gray mb-0 poppins-font">Group Leader</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-user-friends"></i>
                            </div>
                            <h3 class="text-oweru-dark mb-1 futura-font">{{ $group->getMemberCount() }}/{{ $group->max_members }}</h3>
                            <p class="text-oweru-gray mb-0 poppins-font">Members</p>
                            <div class="member-progress">
                                <div class="member-progress-bar" style="width: {{ ($group->getMemberCount() / $group->max_members) * 100 }}%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="stat-card">
                            <div class="stat-icon">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <h3 class="text-oweru-dark mb-1 futura-font text-capitalize">{{ $group->status }}</h3>
                            <p class="text-oweru-gray mb-0 poppins-font">Group Status</p>
                        </div>
                    </div>
                </div>

                <!-- Associated Challenge -->
                @if($group->challenge)
                    <div class="challenge-banner">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h4 class="futura-font mb-2">
                                    <i class="fas fa-trophy me-2"></i>
                                    Associated Challenge
                                </h4>
                                <h5 class="futura-font mb-2">{{ $group->challenge->name }}</h5>
                                <p class="mb-0 poppins-font opacity-90">{{ $group->challenge->description }}</p>
                            </div>
                            <div class="col-md-4 text-md-end">
                                <span class="badge-active px-3 py-2">
                                    <i class="fas fa-play-circle me-1"></i>Active Challenge
                                </span>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="d-flex gap-3 flex-wrap">
                    @if($group->isUserMember(Auth::id()) && !$group->isUserLeader(Auth::id()))
                        <form action="{{ route('groups.leave', $group) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn-oweru-danger" 
                                    onclick="return confirm('Are you sure you want to leave this group? This action cannot be undone.')">
                                <i class="fas fa-sign-out-alt me-2"></i>Leave Group
                            </button>
                        </form>
                    @endif

                    @if(!$group->isUserMember(Auth::id()) && $group->hasAvailableSlots())
                        <form action="{{ route('groups.join', $group) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn-oweru-success">
                                <i class="fas fa-user-plus me-2"></i>Join This Group
                            </button>
                        </form>
                    @endif

                    <!-- Share Group Button -->
                    <button type="button" class="btn-whatsapp" data-bs-toggle="modal" data-bs-target="#shareGroupModal">
                        <i class="fab fa-whatsapp me-2"></i>Share Group
                    </button>

                    <a href="{{ route('groups.index') }}" class="btn-oweru-outline">
                        <i class="fas fa-arrow-left me-2"></i>Back to Groups
                    </a>
                </div>
            </div>
        </div>

        <!-- Group Members Section -->
        <div class="main-card">
            <div class="card-header-oweru text-white">
                <h6 class="m-0 font-weight-bold futura-font">
                    <i class="fas fa-users me-2"></i>
                    Group Members ({{ $group->members->count() }})
                </h6>
            </div>
            <div class="card-body p-4">
                @if($group->members->count() > 0)
                    <div class="row g-4">
                        @foreach($group->members as $member)
                            <div class="col-lg-6">
                                <div class="member-card">
                                    <div class="row align-items-center">
                                        <div class="col-3">
                                            <div class="user-avatar mx-auto">
                                                {{ strtoupper(substr($member->user->username, 0, 1)) }}
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <h6 class="futura-font text-oweru-dark mb-1">{{ $member->user->username }}</h6>
                                            <p class="text-oweru-gray small poppins-font mb-0">{{ $member->user->email }}</p>
                                            @if($member->user->id === $group->leader_id)
                                                <span class="badge-leader mt-1">
                                                    <i class="fas fa-crown me-1"></i>Leader
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-3 text-end">
                                            @if($member->status === 'active')
                                                <span class="badge-active">
                                                    <i class="fas fa-check-circle me-1"></i>Active
                                                </span>
                                            @elseif($member->status === 'pending')
                                                <span class="badge-pending">
                                                    <i class="fas fa-clock me-1"></i>Pending
                                                </span>
                                            @endif

                                            @if($group->isUserLeader(Auth::id()) && $member->status === 'pending' && $member->user->id !== $group->leader_id)
                                                <div class="mt-2">
                                                    <form action="{{ route('groups.approve-member', [$group, $member->user_id]) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn-oweru-success btn-sm" 
                                                                data-bs-toggle="tooltip" title="Approve Member">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('groups.reject-member', [$group, $member->user_id]) }}" method="POST" class="d-inline ms-1">
                                                        @csrf
                                                        <button type="submit" class="btn-oweru-danger btn-sm" 
                                                                data-bs-toggle="tooltip" title="Reject Member"
                                                                onclick="return confirm('Are you sure you want to reject this member?')">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <h4 class="futura-font text-oweru-dark mb-3">No Members Yet</h4>
                        <p class="poppins-font text-oweru-gray mb-4">Be the first to join this group and start collaborating!</p>
                        @if(!$group->isUserMember(Auth::id()) && $group->hasAvailableSlots())
                            <form action="{{ route('groups.join', $group) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn-oweru-primary">
                                    <i class="fas fa-user-plus me-2"></i>Join This Group
                                </button>
                            </form>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Share Group Modal -->
<div class="modal fade share-modal" id="shareGroupModal" tabindex="-1" aria-labelledby="shareGroupModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title futura-font" id="shareGroupModalLabel">
                    <i class="fas fa-share-alt me-2"></i>Share Group Invitation
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="share-link-container">
                    <div class="d-flex align-items-center gap-2">
                        <div class="share-link flex-grow-1" id="groupShareLink">
                            {{ route('groups.show', $group) }}
                        </div>
                        <button type="button" class="copy-btn" onclick="copyGroupLink()">
                            <i class="fas fa-copy me-1"></i>Copy
                        </button>
                    </div>
                </div>

                <p class="text-muted poppins-font mb-4">
                    Share this group link with friends to invite them to join. They'll be able to view the group details and request to join.
                </p>

                <div class="share-options">
                    <div class="share-option-btn" onclick="shareOnWhatsApp()">
                        <div class="share-option-icon text-success">
                            <i class="fab fa-whatsapp"></i>
                        </div>
                        <div class="share-option-text poppins-font">
                            <strong>WhatsApp</strong>
                        </div>
                    </div>
                    
                    <div class="share-option-btn" onclick="shareOnFacebook()">
                        <div class="share-option-icon text-primary">
                            <i class="fab fa-facebook"></i>
                        </div>
                        <div class="share-option-text poppins-font">
                            <strong>Facebook</strong>
                        </div>
                    </div>
                    
                    <div class="share-option-btn" onclick="shareOnTwitter()">
                        <div class="share-option-icon text-info">
                            <i class="fab fa-twitter"></i>
                        </div>
                        <div class="share-option-text poppins-font">
                            <strong>Twitter</strong>
                        </div>
                    </div>
                    
                    <div class="share-option-btn" onclick="shareViaEmail()">
                        <div class="share-option-icon text-danger">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="share-option-text poppins-font">
                            <strong>Email</strong>
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
    // Force full width layout
    document.body.style.overflowX = 'hidden';
    const mainContainer = document.querySelector('.container-fluid');
    if (mainContainer) {
        mainContainer.style.maxWidth = '100%';
        mainContainer.style.marginLeft = '0';
        mainContainer.style.marginRight = '0';
        mainContainer.style.paddingLeft = '20px';
        mainContainer.style.paddingRight = '20px';
    }

    // Remove any sidebar elements that might still be in DOM
    const sidebarElements = document.querySelectorAll('.sidebar, .col-md-3, .col-lg-2, [class*="sidebar"]');
    sidebarElements.forEach(element => {
        element.style.display = 'none';
    });

    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // Add hover effects to member cards
    const memberCards = document.querySelectorAll('.member-card');
    memberCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-3px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
});

// Share functionality
function copyGroupLink() {
    const shareLink = document.getElementById('groupShareLink');
    const copyBtn = document.querySelector('.copy-btn');
    
    navigator.clipboard.writeText(shareLink.textContent).then(function() {
        const originalText = copyBtn.innerHTML;
        copyBtn.innerHTML = '<i class="fas fa-check me-1"></i>Copied!';
        copyBtn.classList.add('copied');
        
        setTimeout(function() {
            copyBtn.innerHTML = originalText;
            copyBtn.classList.remove('copied');
        }, 2000);
    }).catch(function(err) {
        console.error('Failed to copy: ', err);
        alert('Failed to copy link to clipboard');
    });
}

function shareOnWhatsApp() {
    const groupLink = document.getElementById('groupShareLink').textContent;
    const groupName = "{{ $group->name }}";
    const message = `Join our group "${groupName}" on Oweru! ${groupLink}`;
    const whatsappUrl = `https://wa.me/?text=${encodeURIComponent(message)}`;
    window.open(whatsappUrl, '_blank');
}

function shareOnFacebook() {
    const groupLink = encodeURIComponent(document.getElementById('groupShareLink').textContent);
    const facebookUrl = `https://www.facebook.com/sharer/sharer.php?u=${groupLink}`;
    window.open(facebookUrl, '_blank', 'width=600,height=400');
}

function shareOnTwitter() {
    const groupLink = encodeURIComponent(document.getElementById('groupShareLink').textContent);
    const groupName = "{{ $group->name }}";
    const text = `Check out "${groupName}" on Oweru!`;
    const twitterUrl = `https://twitter.com/intent/tweet?text=${encodeURIComponent(text)}&url=${groupLink}`;
    window.open(twitterUrl, '_blank', 'width=600,height=400');
}

function shareViaEmail() {
    const groupLink = document.getElementById('groupShareLink').textContent;
    const groupName = "{{ $group->name }}";
    const subject = `Join my group "${groupName}" on Oweru`;
    const body = `Hi! I'd like to invite you to join my group "${groupName}" on Oweru.\n\nGroup Link: ${groupLink}\n\nLooking forward to having you in the group!`;
    const mailtoUrl = `mailto:?subject=${encodeURIComponent(subject)}&body=${encodeURIComponent(body)}`;
    window.location.href = mailtoUrl;
}
</script>
@endpush
@endsection