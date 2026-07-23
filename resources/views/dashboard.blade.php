@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    {{-- CSS Navbar Baru - Dijamin Rapih & Responsif --}}
    <style>
        .top-navbar {
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
            gap: 2rem !important;
            padding: 0.75rem 1.5rem !important;
            background-color: #ffffff !important;
            border-bottom: 1px solid #f3f4f6 !important;
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

    {{-- Top Navbar HTML --}}
    <nav class="top-navbar mb-4">
        {{-- Left: Hamburger & Welcome Greeting --}}
        <div class="navbar-left">
            <button class="btn d-lg-none p-1 me-2" onclick="toggleSidebar()" aria-label="Toggle sidebar">
                <i class="bi bi-list fs-4 text-secondary"></i>
            </button>

            <div class="navbar-greeting d-none d-md-block">
                <div class="greeting-text">
                    {{-- Diubah agar dapat menampilkan hingga 2 kata pertama dari nama user --}}
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
            <input type="text" placeholder="Cari Tugas berdasarkan No, Judul, atau PIC..." id="searchInput" />
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

    {{-- Alert Pemberitahuan Batas Waktu --}}
    @php
        $user = Auth::user();
        $notifTasks = \App\Models\Task::where('status', '!=', \App\Enums\TaskStatus::DONE)
            ->when($user->role === \App\Enums\UserRole::STAFF, function($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->where(function($q) {
                $q->whereDate('deadline', '<', now()->toDateString()) // Overdue
                  ->orWhereBetween('deadline', [now()->toDateString(), now()->addDays(1)->toDateString()]); // Besok
            })
            ->orderBy('deadline', 'asc')
            ->take(5)
            ->get();
    @endphp

    @if($notifTasks->isNotEmpty())
    <div class="alert-deadline mb-4">
        <div class="alert-title">
            <svg class="alert-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
            </svg>
            Pemberitahuan Batas Waktu Tugas
        </div>
        <ul class="alert-list">
            @foreach($notifTasks as $nt)
                @if($nt->deadline && $nt->deadline->isPast())
                    <li>[OVERDUE] <a href="{{ route('tasks.show', $nt) }}" class="fw-bold text-danger decoration-none">{{ $nt->task_number }}</a>: {{ $nt->title }} (Lewat {{ now()->diffInDays($nt->deadline) }} hari)</li>
                @else
                    <li>[BESOK] <a href="{{ route('tasks.show', $nt) }}" class="fw-bold text-warning decoration-none">{{ $nt->task_number }}</a>: {{ $nt->title }}</li>
                @endif
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Stat Cards --}}
    <div class="stat-cards mb-4">
        {{-- Total Tugas --}}
        <div class="stat-card">
            <div class="stat-card-info">
                <div class="stat-label">Total Tugas</div>
                <div class="stat-value">{{ $stats['total'] }}</div>
            </div>
            <div class="stat-card-icon icon-blue">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15a2.25 2.25 0 0 1 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                </svg>
            </div>
        </div>

        {{-- Selesai --}}
        <div class="stat-card">
            <div class="stat-card-info">
                <div class="stat-label">Selesai</div>
                <div class="stat-value">{{ $stats['done'] }}</div>
            </div>
            <div class="stat-card-icon icon-green">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </div>
        </div>

        {{-- Sedang Jalan --}}
        <div class="stat-card">
            <div class="stat-card-info">
                <div class="stat-label">Sedang Jalan</div>
                <div class="stat-value">{{ $stats['in_progress'] }}</div>
            </div>
            <div class="stat-card-icon icon-indigo">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12a7.5 7.5 0 0 0 15 0m-15 0a7.5 7.5 0 1 1 15 0m-15 0H3m16.5 0H21m-1.5 0H12m-8.457 3.077 1.41-.513m14.095-5.13 1.41-.513M5.106 17.785l1.15-.964m11.49-9.642 1.149-.964M7.501 19.795l.75-1.3m7.5-12.99.75-1.3m-6.063 16.658.26-1.477m2.605-14.772.26-1.477m-2.091 17.199-.515-1.41m5.13-14.095-.514-1.41" />
                </svg>
            </div>
        </div>

        {{-- Terlambat --}}
        <div class="stat-card">
            <div class="stat-card-info">
                <div class="stat-label">Terlambat</div>
                <div class="stat-value">{{ $stats['overdue'] }}</div>
            </div>
            <div class="stat-card-icon icon-red">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </div>
        </div>

        {{-- Mendekati DL --}}
        <div class="stat-card">
            <div class="stat-card-info">
                <div class="stat-label">Mendekati DL</div>
                <div class="stat-value">{{ $stats['near_deadline'] }}</div>
            </div>
            <div class="stat-card-icon icon-amber">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                </svg>
            </div>
        </div>
    </div>

    {{-- Charts Row --}}
    <div class="row g-4 mb-4">
        {{-- Monthly Chart --}}
        <div class="col-12 col-lg-8">
            <div class="bg-white p-4 rounded-3 border border-light shadow-sm h-100">
                <h5 class="fs-6 fw-bold text-dark mb-3">
                    <i class="bi bi-bar-chart-line text-primary me-2"></i>Tren Jumlah Tugas per Bulan (2026)
                </h5>
                <div style="position: relative; height: 260px;">
                    <canvas id="monthlyChart"></canvas>
                </div>
            </div>
        </div>

        {{-- Status Chart --}}
        <div class="col-12 col-lg-4">
            <div class="bg-white p-4 rounded-3 border border-light shadow-sm h-100">
                <h5 class="fs-6 fw-bold text-dark mb-3">
                    <i class="bi bi-pie-chart text-primary me-2"></i>Komposisi Status Tugas
                </h5>
                <div style="position: relative; height: 260px;">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter Bar --}}
    <div class="filter-bar mb-4">
        <div class="filter-group">
            <a href="{{ route('calendar') }}" class="btn btn-sm btn-white border rounded-pill px-3 py-1.5 fw-semibold text-secondary d-inline-flex align-items-center gap-1.5 shadow-sm">
                <i class="bi bi-calendar3 text-primary"></i>
                <span>Lihat Kalender</span>
            </a>
        </div>

        @if(Auth::user()->role === \App\Enums\UserRole::KABID)
        <a href="{{ route('tasks.create') }}" class="btn-add-task text-decoration-none">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Tambah Tugas Baru
        </a>
        @endif
    </div>

    {{-- Task Table --}}
    <h5 class="fs-6 fw-bold text-dark mb-3 mt-4 px-2">Tugas Terbaru</h5>
    <div class="task-table-wrapper" style="margin-bottom: 90px;">
        <table class="task-table">
            <thead>
                <tr>
                    <th class="ps-4">No. Tugas</th>
                    <th>Judul Tugas</th>
                    <th>Penanggung Jawab</th>
                    <th>Batas Waktu (Deadline)</th>
                    <th>Status</th>
                    <th class="pe-4 text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tasks as $t)
                <tr>
                    <td class="task-number ps-4">
                        <a href="{{ route('tasks.show', $t['id']) }}" class="text-decoration-none fw-semibold">{{ $t['task_number'] }}</a>
                    </td>
                    <td class="task-title">{{ $t['title'] }}</td>
                    <td>{{ $t['assignee'] }}</td>
                    <td>{{ $t['deadline'] }}</td>
                    <td>
                        <span class="badge-status badge-{{ $t['badge_class'] }}">{{ $t['status_label'] }}</span>
                    </td>
                    <td class="pe-4 text-end">
                        <div class="d-inline-flex gap-1">
                            <a href="{{ route('tasks.show', $t['id']) }}" class="btn-action btn-view" title="Lihat Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                            
                            <a href="{{ route('calendar') }}?date={{ $t['deadline'] }}" class="btn-action btn-edit text-info" title="Lihat di Kalender">
                                <i class="bi bi-calendar-event"></i>
                            </a>

                            @if(Auth::user()->role === \App\Enums\UserRole::KABID)
                            <a href="{{ route('tasks.edit', $t['id']) }}" class="btn-action btn-edit" title="Edit">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('tasks.destroy', $t['id']) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tugas ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action btn-delete" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">
                        <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
                        Belum ada tugas aktif untuk ditampilkan di dashboard.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Footer --}}
    <footer class="bg-white p-3 text-center shadow-sm border-top border-light" 
            style="position: fixed; bottom: 0; right: 0; left: var(--sidebar-width, 260px); width: calc(100% - var(--sidebar-width, 260px)); z-index: 999;">
        <small class="text-muted" style="font-size: 11px; letter-spacing: 0.5px; display: block;">
            &copy; {{ now()->year }} Pemerintah Kabupaten Cirebon &mdash; Dinas Komunikasi dan Informatika &mdash; Bidang Statistika Persandian dan E-Government.
        </small>
    </footer>

@endsection

@push('scripts')
{{-- Load Chart.js from CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // 1. Tren Jumlah Tugas per Bulan (Bar Chart)
        const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
        const monthlyData = @json($monthlyChartData);
        
        new Chart(monthlyCtx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Jumlah Tugas',
                    data: monthlyData,
                    backgroundColor: 'rgba(37, 99, 235, 0.85)', // primary blue
                    borderColor: 'rgb(37, 99, 235)',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // 2. Komposisi Status Tugas (Doughnut Chart)
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        const statusData = @json($statusChartData);

        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Belum Dimulai', 'Sedang Dikerjakan', 'Menunggu Review', 'Selesai'],
                datasets: [{
                    data: statusData,
                    backgroundColor: [
                        'rgba(59, 130, 246, 0.85)',   // Blue (todo)
                        'rgba(99, 102, 241, 0.85)',   // Indigo (in progress)
                        'rgba(245, 158, 11, 0.85)',   // Amber (review)
                        'rgba(16, 185, 129, 0.85)'    // Emerald (done)
                    ],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            font: {
                                size: 11
                            }
                        }
                    }
                },
                cutout: '65%'
            }
        });
    });
</script>
@endpush