{{-- Top Navbar --}}
<nav class="top-navbar">
    {{-- Hamburger (Mobile) --}}
    <button class="btn d-lg-none p-1 me-2" onclick="toggleSidebar()" aria-label="Toggle sidebar">
        <i class="bi bi-list fs-4 text-secondary"></i>
    </button>

    {{-- Left: Welcome Greeting --}}
    <div class="navbar-greeting d-none d-md-block">
        <div class="greeting-text">
            Selamat Datang, <strong>{{ explode(' ', Auth::user()->name)[0] }}</strong>
        </div>
        <div class="greeting-sub">{{ now()->locale('id')->translatedFormat('l, d F Y') }}</div>
    </div>

    {{-- Center: Search Box --}}
    <div class="search-box">
        <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
        </svg>
        <input type="text" placeholder="Cari Tugas berdasarkan Nomor, Judul, atau PIC..." id="searchInput" />
    </div>

    {{-- Right: User Info + Dropdown --}}
    <div class="dropdown navbar-user">
        <div class="user-info" data-bs-toggle="dropdown" aria-expanded="false">
            <div class="user-info-text d-none d-sm-block">
                <div class="user-name">{{ Auth::user()->name }}</div>
                <div class="user-role">{{ Auth::user()->role->label() }}</div>
            </div>
            <div class="user-avatar">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
        </div>
        <ul class="dropdown-menu dropdown-menu-end">
            <li>
                <a class="dropdown-item" href="{{ route('profile.edit') }}">
                    <i class="bi bi-person me-2"></i>Profile
                </a>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item text-danger">
                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                    </button>
                </form>
            </li>
        </ul>
    </div>
</nav>

@push('styles')
<style>
    .top-navbar {
        display: grid;
        grid-template-columns: 1fr minmax(400px, 640px) 1fr;
        align-items: center;
        gap: 1.5rem;
        padding: 0 0.5rem;
    }

    .navbar-greeting {
        min-width: 0;
        white-space: nowrap;
        overflow: hidden;
    }
    .navbar-greeting .greeting-text {
        font-family: 'Inter', sans-serif;
        font-weight: 500;
        font-size: 1rem;
        color: #1f2937;
        line-height: 1.3;
    }
    .navbar-greeting .greeting-text strong {
        font-weight: 700;
        color: #2563eb;
    }
    .navbar-greeting .greeting-sub {
        font-family: 'Inter', sans-serif;
        font-size: 0.8rem;
        color: #9ca3af;
        margin-top: 2px;
    }

    .search-box {
        width: 100%;
        justify-self: center;
    }

    .navbar-user {
        display: flex;
        justify-content: flex-end;
        padding-right: 0.5rem;
    }
</style>
@endpush