{{-- CSS Navbar Perbaikan (Menggunakan Flexbox agar Rapi & Fleksibel) --}}
<style>
    .top-navbar {
        display: flex !important;
        justify-content: space-between !important;
        align-items: center !important;
        gap: 2rem !important;
        padding: 0.75rem 1.5rem !important;
        background-color: #ffffff !important;
        border-bottom: 1px solid #f3f4f6 !important;
        border-radius: 12px !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05) !important;
    }

    .navbar-left {
        display: flex !important;
        align-items: center !important;
        flex-shrink: 0 !important; /* Mencegah area kiri mengecil */
    }

    .navbar-greeting {
        min-width: 0 !important;
    }
    .navbar-greeting .greeting-text {
        font-family: 'Inter', sans-serif !important;
        font-weight: 500 !important;
        font-size: 0.95rem !important;
        color: #374151 !important;
        line-height: 1.3 !important;
        white-space: nowrap !important;
    }
    .navbar-greeting .greeting-text strong {
        font-weight: 700 !important;
        color: #2563eb !important;
    }
    .navbar-greeting .greeting-sub {
        font-family: 'Inter', sans-serif !important;
        font-size: 0.75rem !important;
        color: #9ca3af !important;
        margin-top: 2px !important;
    }

    .search-box {
        position: relative !important;
        width: 100% !important;
        max-width: 500px !important; /* Batasi lebar maksimal kotak pencarian */
        flex-grow: 1 !important; /* Membuat search box mengisi ruang tengah secara fleksibel */
    }
    .search-icon {
        position: absolute !important;
        left: 14px !important;
        top: 50% !important;
        transform: translateY(-50%) !important;
        width: 18px !important;
        height: 18px !important;
        color: #9ca3af !important;
    }
    .search-box input {
        width: 100% !important;
        padding: 0.55rem 1rem 0.55rem 2.5rem !important;
        border: 1px solid #e5e7eb !important;
        border-radius: 50px !important;
        background-color: #f9fafb !important;
        font-size: 0.85rem !important;
        color: #374151 !important;
        outline: none !important;
        transition: all 0.2s !important;
    }
    .search-box input:focus {
        border-color: #3b82f6 !important;
        background-color: #ffffff !important;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
    }

    .navbar-user {
        display: flex !important;
        align-items: center !important;
        flex-shrink: 0 !important; /* Mencegah area user menu terpotong/mengecil */
    }
    .user-info {
        display: flex !important;
        align-items: center !important;
        gap: 12px !important;
        padding: 6px 14px !important;
        border-radius: 50px !important;
        transition: background 0.2s !important;
        text-decoration: none !important;
    }
    .user-info:hover {
        background-color: #f3f4f6 !important;
    }
    .user-info-text {
        text-align: right !important;
        display: flex !important;
        flex-direction: column !important;
        justify-content: center !important;
    }
    .user-name {
        font-size: 0.85rem !important;
        font-weight: 700 !important;
        color: #1f2937 !important;
        line-height: 1.2 !important;
        white-space: nowrap !important; /* Mencegah teks nama patah jadi dua baris */
    }
    .user-role {
        font-size: 0.75rem !important;
        color: #6b7280 !important;
        margin-top: 2px !important;
        line-height: 1.2 !important;
        white-space: nowrap !important; /* Mencegah teks role patah jadi dua baris */
    }
    .user-avatar {
        width: 40px !important;
        height: 40px !important;
        border-radius: 50% !important;
        background-color: #3b82f6 !important;
        color: #ffffff !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        font-weight: 600 !important;
        font-size: 1rem !important;
        flex-shrink: 0 !important;
    }

    @media (max-width: 992px) {
        .search-box {
            max-width: 100% !important;
        }
    }
</style>

{{-- Top Navbar HTML --}}
<nav class="top-navbar">
    {{-- Left: Hamburger & Welcome Greeting --}}
    <div class="navbar-left">
        <button class="btn d-lg-none p-1 me-2" onclick="toggleSidebar()" aria-label="Toggle sidebar">
            <i class="bi bi-list fs-4 text-secondary"></i>
        </button>

        <div class="navbar-greeting d-none d-md-block">
            <div class="greeting-text">
                {{-- Menampilkan nama lengkap seutuhnya --}}
                Selamat Datang, <strong>{{ Auth::user()->name }}</strong>
            </div>
            <div class="greeting-sub">{{ now()->locale('id')->translatedFormat('l, d F Y') }}</div>
        </div>
    </div>

    {{-- Center: Search Box --}}
    <div class="search-box">
        <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
        </svg>
        <input type="text" placeholder="Cari Tugas berdasarkan No, Judul, atau PIC..." id="searchInput" />
    </div>

    {{-- Right: User Info + Dropdown --}}
    <div class="dropdown navbar-user">
        <div class="user-info" data-bs-toggle="dropdown" aria-expanded="false" role="button">
            <div class="user-info-text d-none d-sm-block">
                <div class="user-name">{{ Auth::user()->name }}</div>
                <div class="user-role">{{ Auth::user()->role->label() }}</div>
            </div>
            <div class="user-avatar shadow-sm">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
        </div>
        <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2">
            <li>
                <a class="dropdown-item py-2" href="{{ route('profile.edit') }}">
                    <i class="bi bi-person me-2 text-secondary"></i> Profil Saya
                </a>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item py-2 text-danger">
                        <i class="bi bi-box-arrow-right me-2"></i> Keluar
                    </button>
                </form>
            </li>
        </ul> 
    </div>
</nav>