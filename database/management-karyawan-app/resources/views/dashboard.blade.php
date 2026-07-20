@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    {{-- Alert Pemberitahuan Batas Waktu --}}
    @php
        // Mengambil notifikasi overdue atau mendekati deadline nyata dari database
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
                    <i class="bi bi-bar-chart-line text-primary me-2"></i>Tren Jumlah Tugas per Bulan ({{ now()->year }})
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
    <div class="task-table-wrapper">
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
