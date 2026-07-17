@extends('layouts.app')

@section('title', 'Tambah Tugas Baru')

@section('content')

    {{-- CSS Tambahan: Top Navbar & Layout Adjustment --}}
    <style>
        /* ================= NAVBAR STYLES ================= */
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
            flex-shrink: 0 !important;
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
            white-space: nowrap !important;
        }

        .search-box {
            position: relative !important;
            width: 100% !important;
            max-width: 550px !important;
            flex-grow: 1 !important;
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
            flex-shrink: 0 !important;
        }
        .user-info {
            display: flex !important;
            align-items: center !important;
            gap: 12px !important;
            padding: 6px 12px !important;
            border-radius: 50px !important;
            transition: background 0.2s !important;
            white-space: nowrap !important;
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
            white-space: nowrap !important;
        }
        .user-role {
            font-size: 0.75rem !important;
            color: #6b7280 !important;
            line-height: 1.2 !important;
            margin-top: 2px !important;
            white-space: nowrap !important;
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

        @media (max-width: 768px) {
            .top-navbar {
                padding: 0.5rem 1rem !important;
                gap: 1rem !important;
            }
            .search-box {
                max-width: 100% !important;
            }
        }
    </style>

    {{-- 1. Top Navbar --}}
    <nav class="top-navbar mb-4">
        {{-- Left: Hamburger & Welcome Greeting --}}
        <div class="navbar-left">
            <button class="btn d-lg-none p-1 me-2" onclick="toggleSidebar()" aria-label="Toggle sidebar">
                <i class="bi bi-list fs-4 text-secondary"></i>
            </button>

            <div class="navbar-greeting d-none d-md-block">
                <div class="greeting-text">
                    {{-- Diubah agar menampilkan hingga 2 kata pertama dari nama user --}}
                    Selamat Datang, <strong>{{ implode(' ', array_slice(explode(' ', Auth::user()->name), 0, 2)) }}</strong>
                </div>
                <div class="greeting-sub">{{ now()->locale('id')->translatedFormat('l, d F Y') }}</div>
            </div>
        </div>

        {{-- Center: Search Box (Mencari tugas di index jika ditekan Enter) --}}
        <div class="search-box">
            <form method="GET" action="{{ route('tasks.index') }}" class="m-0 p-0">
                <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
                <input type="text" name="search" placeholder="Cari Tugas berdasarkan Nomor, Judul, atau PIC..." />
            </form>
        </div>

        {{-- Right: User Info + Dropdown --}}
        <div class="dropdown navbar-user">
            <div class="user-info" data-bs-toggle="dropdown" aria-expanded="false" role="button">
                <div class="user-info-text d-none d-sm-flex">
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

    {{-- Breadcrumb / Back Button --}}
    <div class="mb-4">
        <a href="{{ route('tasks.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1.5 fw-semibold d-inline-flex align-items-center gap-2">
            <i class="bi bi-arrow-left"></i>
            <span>Kembali ke Daftar Tugas</span>
        </a>
    </div>

    {{-- Form Header --}}
    <div class="mb-4">
        <h2 class="fs-4 fw-bold text-dark m-0">Buat Tugas Baru</h2>
        <p class="text-xs text-secondary mt-1 m-0">Tentukan rincian tugas baru untuk didelegasikan ke Staff Bidang E-Gov.</p>
    </div>

    {{-- Validation Error Alerts --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show rounded-3 border-0 shadow-sm mb-4" role="alert">
            <div class="fw-bold mb-1">Periksa kembali input Anda:</div>
            <ul class="m-0 ps-3">
                @foreach ($errors->all() as $error)
                    <li class="text-sm">{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Card Form --}}
    <div class="bg-white p-4 rounded-3 border border-light shadow-sm" style="margin-bottom: 120px;">
        <form method="POST" action="{{ route('tasks.store') }}" class="row g-3">
            @csrf

            {{-- Judul Tugas --}}
            <div class="col-12">
                <label for="title" class="form-label text-sm fw-semibold text-secondary">Judul Tugas <span class="text-danger">*</span></label>
                <input type="text" name="title" id="title" class="form-control rounded-3 text-sm @error('title') is-invalid @enderror" value="{{ old('title') }}" placeholder="Contoh: Integrasi API Geoportal Aplikasi Daerah" required />
            </div>

            {{-- Deskripsi Tugas --}}
            <div class="col-12">
                <label for="description" class="form-label text-sm fw-semibold text-secondary">Deskripsi Tugas</label>
                <textarea name="description" id="description" rows="5" class="form-control rounded-3 text-sm @error('description') is-invalid @enderror" placeholder="Tuliskan instruksi tugas secara detail di sini...">{{ old('description') }}</textarea>
            </div>

            {{-- Penanggung Jawab (Staff) --}}
            <div class="col-12 col-md-6">
                <label for="user_id" class="form-label text-sm fw-semibold text-secondary">Penanggung Jawab <span class="text-danger">*</span></label>
                @if(Auth::user()->role === \App\Enums\UserRole::STAFF)
                    <input type="hidden" name="user_id" value="{{ Auth::id() }}">
                    <input type="text" class="form-control rounded-3 text-sm bg-light text-muted" value="{{ Auth::user()->name }}" readonly disabled />
                @else
                    <select name="user_id" id="user_id" class="form-select rounded-3 text-sm @error('user_id') is-invalid @enderror" required>
                        <option value="" disabled selected>Pilih Staff Penanggung Jawab...</option>
                        @foreach($staffs as $staff)
                            <option value="{{ $staff->id }}" {{ old('user_id') == $staff->id ? 'selected' : '' }}>{{ $staff->name }}</option>
                        @endforeach
                    </select>
                @endif
            </div>

            {{-- Prioritas --}}
            <div class="col-12 col-md-6">
                <label for="priority" class="form-label text-sm fw-semibold text-secondary">Prioritas Tugas <span class="text-danger">*</span></label>
                <select name="priority" id="priority" class="form-select rounded-3 text-sm @error('priority') is-invalid @enderror" required>
                    <option value="" disabled selected>Pilih Prioritas...</option>
                    @foreach($priorities as $priority)
                        <option value="{{ $priority->value }}" {{ old('priority') == $priority->value ? 'selected' : '' }}>{{ $priority->label() }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Tanggal Mulai --}}
            <div class="col-12 col-md-6">
                <label for="start_date" class="form-label text-sm fw-semibold text-secondary">Tanggal Mulai <span class="text-danger">*</span></label>
                <input type="date" name="start_date" id="start_date" class="form-control rounded-3 text-sm @error('start_date') is-invalid @enderror" value="{{ old('start_date', date('Y-m-d')) }}" required />
            </div>

            {{-- Deadline --}}
            <div class="col-12 col-md-6">
                <label for="deadline" class="form-label text-sm fw-semibold text-secondary">Batas Waktu (Deadline) <span class="text-danger">*</span></label>
                <input type="date" name="deadline" id="deadline" class="form-control rounded-3 text-sm @error('deadline') is-invalid @enderror" value="{{ old('deadline') }}" required />
            </div>

            {{-- Submit Area --}}
            <div class="col-12 mt-4 pt-3 border-t d-flex justify-content-end gap-2">
                <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary rounded-pill px-4 fw-semibold text-sm">Batal</a>
                <button type="submit" class="btn btn-primary rounded-pill px-4 fw-semibold text-sm shadow-sm">Simpan Tugas</button>
            </div>
        </form>
    </div>

    {{-- Footer Fixed Putih - Ukuran & Posisi Diam Mengikuti Lebar Header --}}
    <footer class="bg-white p-3 text-center shadow-sm border-top border-light" 
            style="position: fixed; bottom: 0; right: 0; left: var(--sidebar-width, 260px); width: calc(100% - var(--sidebar-width, 260px)); z-index: 999;">
        <small class="text-muted" style="font-size: 11px; letter-spacing: 0.5px; display: block; font-family: sans-serif;">
            &copy; {{ now()->year }} Pemerintah Kabupaten Cirebon &mdash; Dinas Komunikasi dan Informatika &mdash; Bidang Statistika Persandian dan E-Government.
        </small>
    </footer>

@endsection