@extends('layouts.app')

@section('title', 'Manajemen Tugas')

@section('content')

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
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control rounded-pill ps-5 text-sm" placeholder="Cari berdasarkan No. Tugas atau Judul..." />
                    <i class="bi bi-search position-absolute text-muted" style="left: 18px; top: 50%; transform: translateY(-50%);"></i>
                </div>
            </div>

            {{-- Filter PIC (Only Kabid can filter, Staff is locked to their own tasks anyway) --}}
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
    <div class="task-table-wrapper">
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
    <div class="mt-4 d-flex justify-content-center">
        {{ $tasks->links() }}
    </div>

@endsection
