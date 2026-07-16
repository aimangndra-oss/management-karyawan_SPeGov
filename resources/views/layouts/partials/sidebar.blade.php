{{-- Sidebar --}}
<aside class="sidebar" id="sidebar">
    {{-- Brand --}}
    <div class="sidebar-brand">
        <div class="sidebar-brand-icon" style="background: white; border-radius: 50%; padding: 3px;">
            <img src="/images/logo.png" alt="Logo" class="img-fluid" style="max-height: 32px; object-fit: contain;">
        </div>
        <div class="sidebar-brand-text">
            <div class="brand-title">DISKOMINFO</div>
            <div class="brand-subtitle">Bidang Statistika Persandian dan E-Goverment</div>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="sidebar-nav">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2-fill nav-icon"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('tasks.index') }}" class="nav-link {{ request()->routeIs('tasks.*') ? 'active' : '' }}">
                    <i class="bi bi-list-task nav-icon"></i>
                    <span>Manajemen Tugas</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('calendar') }}" class="nav-link {{ request()->routeIs('calendar') ? 'active' : '' }}">
                    <i class="bi bi-calendar-event nav-icon"></i>
                    <span>Kalender Deadline</span>
                </a>
            </li>
        </ul>
    </nav>
</aside>
