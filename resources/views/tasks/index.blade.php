@extends('layouts.app')

@section('title', 'Manajemen Tugas')

@section('content')

    {{-- CSS Tambahan: Top Navbar & Gaya Manajemen Tugas --}}
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

        {{-- Center: Search Box --}}
        <div class="search-box">
            <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
            </svg>
            {{-- Hubungkan pencarian navbar dengan submit query pencarian jika user menekan tombol Enter --}}
            <input type="text" placeholder="Cari Tugas berdasarkan Nomor, Judul, atau PIC..." id="topSearchInput" value="{{ request('search') }}" onkeydown="if(event.key === 'Enter') { document.getElementById('filterSearchInput').value = this.value; this.closest('nav').nextElementSibling.nextElementSibling.querySelector('form').submit(); }" />
        </div>

        {{-- Right: User Info + Dropdown --}}
        <div class="dropdown navbar-user">
            <div class="user-info" data-bs-toggle="dropdown" aria-expanded="false" role="button">
                <div class="user-info-text d-none d-sm-flex">
                    <div class="user-name">{{ Auth::user()->name }}</div>
                    <div class="user-role">{{ Auth::user()->role->label() }}</div>
                </div>
              @if (Auth::user()->avatar)
                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Profil" class="user-avatar shadow-sm" style="object-fit: cover;">
                @else
                    <div class="user-avatar shadow-sm">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                @endif
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

    {{-- Header Area --}}
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h2 class="fs-4 fw-bold text-dark m-0">Manajemen Tugas Bidang E-Gov</h2>
            <p class="text-xs text-secondary mt-1 m-0">Kelola dan pantau seluruh pekerjaan bidang secara real-time.</p>
        </div>
        
        @can('create', \App\Models\Task::class)
        <a href="{{ route('tasks.create') }}" class="btn btn-primary rounded-pill px-4 py-2 text-sm fw-semibold shadow-sm d-inline-flex align-items-center gap-2">
            <i class="bi bi-plus-lg"></i>
            <span>Tambah Tugas Baru</span>
        </a>
        @endcan
    </div>

    {{-- Filter & Search Bar --}}
    <div class="bg-white p-4 rounded-3 border border-light shadow-sm mb-4">
        <form method="GET" action="{{ route('tasks.index') }}" class="row g-3">
            {{-- Search Input --}}
            <div class="col-12 col-md-4">
                <div class="position-relative">
                    <input type="text" name="search" id="filterSearchInput" value="{{ request('search') }}" class="form-control rounded-pill ps-5 text-sm" placeholder="Cari berdasarkan No. Tugas atau Judul..." />
                    <i class="bi bi-search position-absolute text-muted" style="left: 18px; top: 50%; transform: translateY(-50%);"></i>
                </div>
            </div>

            {{-- Filter PIC (Only Kabid can filter) --}}
            @if(Auth::user()->role === \App\Enums\UserRole::KABID)
            <div class="col-12 col-sm-6 col-md-2">
                <select name="user_id" class="form-select rounded-pill text-sm">
                    <option value="">Semua PIC</option>
                    @foreach($staffs as $staff)
                        <option value="{{ $staff->id }}" {{ request('user_id') == $staff->id ? 'selected' : '' }}>{{ $staff->name }}</option>
                    @endforeach
                </select>
            </div>
            @endif

            {{-- Filter Status --}}
            <div class="col-6 col-sm-3 col-md-2">
                <select name="status" class="form-select rounded-pill text-sm">
                    <option value="">Semua Status</option>
                    @foreach($statuses as $status)
                        <option value="{{ $status->value }}" {{ request('status') == $status->value ? 'selected' : '' }}>{{ $status->label() }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Bulan --}}
            <div class="col-6 col-sm-3 col-md-2">
                <select name="month" class="form-select rounded-pill text-sm">
                    <option value="">Semua Bulan</option>
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>{{ DateTime::createFromFormat('!m', $m)->format('F') }}</option>
                    @endfor
                </select>
            </div>

            {{-- Submit & Reset Button --}}
            <div class="col-12 col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary rounded-pill w-100 text-sm fw-semibold">Filter</button>
                <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary rounded-pill w-100 text-sm fw-semibold">Reset</a>
            </div>
        </form>
    </div>

    {{-- Alert Success --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3 border-0 shadow-sm mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    {{-- Task Table --}}
    <div class="task-table-wrapper" style="margin-bottom: 90px;">
        <table class="task-table m-0">
            <thead>
                <tr>
                    <th class="ps-4">No. Tugas</th>
                    <th>Judul Tugas</th>
                    <th>Penanggung Jawab</th>
                    <th>Batas Waktu (Deadline)</th>
                    <th>Progres</th>
                    <th>Status</th>
                    <th class="pe-4 text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tasks as $task)
                <tr>
                    <td class="task-number ps-4">{{ $task->task_number }}</td>
                    <td class="task-title">{{ $task->title }}</td>
                    <td>{{ $task->assignee->name }}</td>
                    <td>{{ $task->deadline ? $task->deadline->format('d M Y') : '-' }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-2" style="max-width: 140px;">
                            <div class="progress w-100" style="height: 6px;">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $task->progress_percentage }}%;" aria-valuenow="{{ $task->progress_percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <span class="text-xs fw-semibold">{{ $task->progress_percentage }}%</span>
                        </div>
                    </td>
                    <td>
                        @php
                            $badgeClass = match($task->status) {
                                \App\Enums\TaskStatus::TO_DO => 'todo',
                                \App\Enums\TaskStatus::IN_PROGRESS => 'in-progress',
                                \App\Enums\TaskStatus::REVIEW => 'review',
                                \App\Enums\TaskStatus::DONE => 'done',
                                default => 'todo',
                            };
                        @endphp
                        <span class="badge-status badge-{{ $badgeClass }}">{{ $task->status->label() }}</span>
                    </td>
                    <td class="pe-4 text-end">
                        <div class="d-inline-flex gap-1">
                            <a href="{{ route('tasks.show', $task) }}" class="btn-action btn-view" title="Lihat Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                            
                            @can('update', $task)
                            <a href="{{ route('tasks.edit', $task) }}" class="btn-action btn-edit" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            @endcan
                            @can('delete', $task)
                            <form action="{{ route('tasks.destroy', $task) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tugas ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action btn-delete" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">
                        <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
                        Belum ada tugas yang sesuai filter atau pencarian Anda.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4 d-flex justify-content-center" style="margin-bottom: 120px;">
        {{ $tasks->links() }}
    </div>

    {{-- Footer Fixed Putih - Ukuran & Posisi Diam Mengikuti Lebar Header --}}
    <footer class="bg-white p-3 text-center shadow-sm border-top border-light" 
            style="position: fixed; bottom: 0; right: 0; left: var(--sidebar-width, 260px); width: calc(100% - var(--sidebar-width, 260px)); z-index: 999;">
        <small class="text-muted" style="font-size: 11px; letter-spacing: 0.5px; display: block; font-family: sans-serif;">
            &copy; {{ now()->year }} Pemerintah Kabupaten Cirebon &mdash; Dinas Komunikasi dan Informatika &mdash; Bidang Statistika Persandian dan E-Government.
        </small>
    </footer>

@endsection