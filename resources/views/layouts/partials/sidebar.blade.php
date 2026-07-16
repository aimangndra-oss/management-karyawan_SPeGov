{{-- Sidebar --}}
<aside class="sidebar" id="sidebar">
  {{-- Brand --}}
    <div class="sidebar-brand" style="display: flex; flex-direction: column; justify-content: center; align-items: flex-start; text-align: left; height: auto; padding: 2rem 1rem 1.5rem 1.25rem; gap: 0.85rem;">
        
        <div style="display: flex; align-items: center; justify-content: flex-start; width: 100%;">
            <img src="/images/logoo.png" alt="Logo" class="img-fluid" style="width: 85%; max-width: 180px; height: auto; object-fit: contain;">
        </div>
        
        <div class="sidebar-brand-text" style="margin: 0; padding: 0;">
            <div class="brand-subtitle" style="font-weight: 600; font-size: 0.85rem; line-height: 1.4; letter-spacing: 0.02em; color: white;">
                <br>Bidang Statistika<br>Persandian dan E-Goverment
            </div>
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