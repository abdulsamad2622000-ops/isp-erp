<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ISP ERP - @yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f2f5;
            font-family: 'Segoe UI', sans-serif;
        }
        .sidebar {
            width: 260px;
            min-height: 100vh;
            background: linear-gradient(180deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1050;
            overflow-y: auto;
            transition: transform 0.3s ease;
        }
        .sidebar .brand {
            padding: 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .sidebar .brand h4 {
            color: #e94560;
            font-weight: 700;
            margin: 0;
        }
        .sidebar .brand small {
            color: rgba(255,255,255,0.5);
            font-size: 11px;
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.7);
            padding: 10px 20px;
            border-radius: 8px;
            margin: 2px 10px;
            transition: all 0.3s;
            font-size: 14px;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: rgba(233, 69, 96, 0.2);
            color: #e94560;
        }
        .sidebar .nav-link i {
            width: 20px;
            margin-right: 8px;
        }
        .sidebar .nav-section {
            color: rgba(255,255,255,0.3);
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 1px;
            padding: 15px 20px 5px;
            text-transform: uppercase;
        }
        .sidebar-close-btn {
            display: none;
            position: absolute;
            top: 15px;
            right: 15px;
            background: none;
            border: none;
            color: rgba(255,255,255,0.7);
            font-size: 20px;
            cursor: pointer;
        }
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1040;
        }
        .sidebar-overlay.active { display: block; }
        .main-content {
            margin-left: 260px;
            min-height: 100vh;
            transition: margin 0.3s ease;
        }
        .topbar {
            background: #fff;
            padding: 15px 25px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 999;
        }
        .topbar h5 {
            margin: 0;
            font-weight: 600;
            color: #1a1a2e;
            font-size: 16px;
        }
        .content-area { padding: 20px; }
        .hamburger-btn {
            display: none;
            background: none;
            border: none;
            font-size: 22px;
            color: #1a1a2e;
            cursor: pointer;
            padding: 0;
            margin-right: 10px;
        }
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }
        .card-header {
            background: #fff;
            border-bottom: 1px solid #f0f2f5;
            border-radius: 12px 12px 0 0 !important;
            font-weight: 600;
            padding: 15px 20px;
        }
        .btn-primary { background: #e94560; border-color: #e94560; }
        .btn-primary:hover { background: #c73652; border-color: #c73652; }
        .table th {
            background: #f8f9fa;
            font-size: 12px;
            font-weight: 600;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .table td { font-size: 13px; vertical-align: middle; }
        .badge { font-size: 11px; padding: 5px 10px; border-radius: 20px; }
        .notif-dropdown .dropdown-item:hover { background: #f8f9fa; }

        @media (max-width: 991px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.active { transform: translateX(0); }
            .sidebar-close-btn { display: block; }
            .main-content { margin-left: 0; }
            .hamburger-btn { display: block; }
            .content-area { padding: 15px; }
            .topbar { padding: 12px 15px; }
        }
        @media (max-width: 576px) {
            .table-responsive { font-size: 12px; }
            .btn-sm { padding: 3px 7px; font-size: 11px; }
            .card-header { font-size: 14px; padding: 12px 15px; }
            .content-area { padding: 10px; }
        }
    </style>
</head>
<body>

<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

<div class="sidebar" id="sidebar">
    <button class="sidebar-close-btn" onclick="closeSidebar()">
        <i class="bi bi-x-lg"></i>
    </button>
    <div class="brand">
        <h4><i class="bi bi-wifi"></i> ISP ERP</h4>
        <small>Management System</small>
    </div>

    <nav class="mt-3">
        <div class="nav-section">Main</div>
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" onclick="closeSidebar()">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>

        <div class="nav-section">Customers</div>
        <a href="{{ route('customers.index') }}" class="nav-link {{ request()->routeIs('customers.*') ? 'active' : '' }}" onclick="closeSidebar()">
            <i class="bi bi-people"></i> Customers
        </a>
        <a href="{{ route('areas.index') }}" class="nav-link {{ request()->routeIs('areas.*') ? 'active' : '' }}" onclick="closeSidebar()">
            <i class="bi bi-geo-alt"></i> Areas
        </a>

        <div class="nav-section">Billing</div>
        <a href="{{ route('packages.index') }}" class="nav-link {{ request()->routeIs('packages.*') ? 'active' : '' }}" onclick="closeSidebar()">
            <i class="bi bi-box"></i> Packages
        </a>
        <a href="{{ route('invoices.index') }}" class="nav-link {{ request()->routeIs('invoices.*') ? 'active' : '' }}" onclick="closeSidebar()">
            <i class="bi bi-receipt"></i> Invoices
        </a>
        <a href="{{ route('payments.index') }}" class="nav-link {{ request()->routeIs('payments.*') ? 'active' : '' }}" onclick="closeSidebar()">
            <i class="bi bi-cash-stack"></i> Payments
        </a>

        <div class="nav-section">Support</div>
        <a href="{{ route('complaints.index') }}" class="nav-link {{ request()->routeIs('complaints.*') ? 'active' : '' }}" onclick="closeSidebar()">
            <i class="bi bi-chat-left-text"></i> Complaints
        </a>
        <a href="{{ route('suspensions.index') }}" class="nav-link {{ request()->routeIs('suspensions.*') ? 'active' : '' }}" onclick="closeSidebar()">
            <i class="bi bi-pause-circle"></i> Suspensions
        </a>

        <div class="nav-section">Operations</div>
        <a href="{{ route('inventory.index') }}" class="nav-link {{ request()->routeIs('inventory.*') ? 'active' : '' }}" onclick="closeSidebar()">
            <i class="bi bi-archive"></i> Inventory
        </a>
        <a href="{{ route('expenses.index') }}" class="nav-link {{ request()->routeIs('expenses.*') ? 'active' : '' }}" onclick="closeSidebar()">
            <i class="bi bi-wallet2"></i> Expenses
        </a>
        <a href="{{ route('notifications.index') }}" class="nav-link {{ request()->routeIs('notifications.*') ? 'active' : '' }}" onclick="closeSidebar()">
            <i class="bi bi-bell"></i> Notifications
        </a>

        <div class="nav-section">Settings</div>
        <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}" onclick="closeSidebar()">
            <i class="bi bi-person-gear"></i> Users
        </a>
    </nav>
</div>

<div class="main-content">
    <div class="topbar">
        <div class="d-flex align-items-center">
            <button class="hamburger-btn" onclick="openSidebar()">
                <i class="bi bi-list"></i>
            </button>
            <h5>@yield('title')</h5>
        </div>

        <div class="d-flex align-items-center gap-2">
            <span class="text-muted d-none d-md-block" style="font-size:13px;">
                <i class="bi bi-calendar3"></i> {{ now()->format('d M Y') }}
            </span>

            {{-- Bell Notification --}}
            @php $pendingCount = \App\Models\Notification::where('is_sent', false)->count(); @endphp
            <div class="dropdown notif-dropdown">
                <button class="btn btn-sm btn-outline-secondary position-relative" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-bell"></i>
                    @if($pendingCount > 0)
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:9px;padding:3px 5px;">
                        {{ $pendingCount > 99 ? '99+' : $pendingCount }}
                    </span>
                    @endif
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow p-0" style="width:320px;max-height:400px;overflow-y:auto;border-radius:12px;">
                    <li class="px-3 py-2 border-bottom d-flex justify-content-between align-items-center" style="background:#f8f9fa;border-radius:12px 12px 0 0;">
                        <strong style="font-size:13px;"><i class="bi bi-bell-fill text-warning me-1"></i> Notifications</strong>
                        <a href="{{ route('notifications.index') }}" class="btn btn-sm btn-outline-primary" style="font-size:11px;">View All</a>
                    </li>
                    @php $notifs = \App\Models\Notification::with('customer')->where('is_sent', false)->latest()->take(8)->get(); @endphp
                    @forelse($notifs as $notif)
                    <li>
                        <a class="dropdown-item py-2 px-3" href="{{ route('notifications.index') }}" style="border-bottom:1px solid #f0f2f5;">
                            <div class="d-flex gap-2 align-items-start">
                                <div class="mt-1" style="width:20px;flex-shrink:0;">
                                    @if($notif->type == 'bill_reminder')
                                        <i class="bi bi-receipt-cutoff text-warning fs-6"></i>
                                    @elseif($notif->type == 'suspension_warning')
                                        <i class="bi bi-exclamation-triangle-fill text-danger fs-6"></i>
                                    @elseif($notif->type == 'promotion')
                                        <i class="bi bi-megaphone-fill text-success fs-6"></i>
                                    @else
                                        <i class="bi bi-info-circle-fill text-info fs-6"></i>
                                    @endif
                                </div>
                                <div style="flex:1;min-width:0;">
                                    <div style="font-size:12px;font-weight:600;color:#1a1a2e;">{{ $notif->title }}</div>
                                    <div style="font-size:11px;color:#6c757d;">{{ $notif->customer->name ?? 'All Customers' }}</div>
                                    <div style="font-size:10px;color:#adb5bd;">{{ $notif->created_at->diffForHumans() }}</div>
                                </div>
                            </div>
                        </a>
                    </li>
                    @empty
                    <li class="text-center text-muted py-4" style="font-size:13px;">
                        <i class="bi bi-bell-slash d-block fs-3 mb-2"></i>
                        No pending notifications
                    </li>
                    @endforelse
                    @if($pendingCount > 8)
                    <li class="text-center py-2 border-top">
                        <a href="{{ route('notifications.index') }}" style="font-size:12px;">
                            View {{ $pendingCount - 8 }} more notifications
                        </a>
                    </li>
                    @endif
                </ul>
            </div>

            {{-- User Dropdown --}}
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle"></i>
                    <span class="d-none d-md-inline"> {{ Auth::user()->name }}</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
<li><a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="bi bi-lock me-2"></i>Change Password</a></li>                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="bi bi-box-arrow-right me-2"></i>Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="content-area">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @yield('content')
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function openSidebar() {
        document.getElementById('sidebar').classList.add('active');
        document.getElementById('sidebarOverlay').classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    function closeSidebar() {
        document.getElementById('sidebar').classList.remove('active');
        document.getElementById('sidebarOverlay').classList.remove('active');
        document.body.style.overflow = '';
    }
</script>
@yield('scripts')
</body>
</html>