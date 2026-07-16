@extends('layouts.app')

@section('title', 'Detail Tugas - ' . $task->task_number)

@section('content')

    {{-- Breadcrumb / Back Button --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <a href="{{ route('tasks.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3 py-1.5 fw-semibold d-inline-flex align-items-center gap-2">
            <i class="bi bi-arrow-left"></i>
            <span>Kembali ke Daftar Tugas</span>
        </a>
        
        <div class="d-flex gap-2">
            @can('update', $task)
            <a href="{{ route('tasks.edit', $task) }}" class="btn btn-sm btn-light border rounded-pill px-3 py-1.5 fw-semibold text-secondary d-inline-flex align-items-center gap-1.5">
                <i class="bi bi-pencil"></i>
                <span>Edit Tugas</span>
            </a>
            @endcan
            @can('delete', $task)
            <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus tugas ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger rounded-pill px-3 py-1.5 fw-semibold d-inline-flex align-items-center gap-1.5 shadow-sm">
                    <i class="bi bi-trash"></i>
                    <span>Hapus</span>
                </button>
            </form>
            @endcan
        </div>
    </div>

    {{-- Alerts --}}
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3 border-0 shadow-sm mb-4" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show rounded-3 border-0 shadow-sm mb-4" role="alert">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>Gagal melakukan pembaruan. Silakan periksa input Anda.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    {{-- Task Overview Card --}}
    <div class="bg-white p-4 rounded-3 border border-light shadow-sm mb-4">
        <div class="row g-4">
            <div class="col-12 col-lg-8">
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="text-xs fw-bold text-primary text-uppercase tracking-wider">Tugas {{ $task->task_number }}</span>
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
                </div>
                <h3 class="fs-4 fw-bold text-dark mb-3">{{ $task->title }}</h3>
                <div class="text-secondary text-sm bg-light p-3 rounded-3" style="white-space: pre-line;">
                    {!! $task->description ? e($task->description) : '<em>Tidak ada deskripsi instruksi kerja.</em>' !!}
                </div>
            </div>

            <div class="col-12 col-lg-4 border-start-lg">
                <div class="ps-lg-3">
                    <h5 class="fs-6 fw-bold text-dark mb-3 border-bottom pb-2">Informasi Pekerjaan</h5>
                    
                    <div class="d-flex flex-column gap-2.5">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-xs text-secondary">Penanggung Jawab</span>
                            <span class="text-sm fw-semibold text-dark">{{ $task->assignee->name }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-xs text-secondary">Dibuat Oleh</span>
                            <span class="text-sm fw-semibold text-dark">{{ $task->creator->name }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-xs text-secondary">Tanggal Mulai</span>
                            <span class="text-sm fw-semibold text-dark">{{ $task->start_date ? $task->start_date->format('d M Y') : '-' }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-xs text-secondary">Tenggat Waktu</span>
                            <span class="text-sm fw-semibold text-danger">{{ $task->deadline ? $task->deadline->format('d M Y') : '-' }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-xs text-secondary">Prioritas</span>
                            <span class="text-sm fw-semibold text-dark">{{ $task->priority->label() }}</span>
                        </div>
                        <div class="mt-2">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="text-xs text-secondary">Total Progres</span>
                                <span class="text-sm fw-bold text-primary">{{ $task->progress_percentage }}%</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $task->progress_percentage }}%;" aria-valuenow="{{ $task->progress_percentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Bottom Two Columns: Timelines & Documents --}}
    <div class="row g-4">
        {{-- Column Left: Timelines --}}
        <div class="col-12 col-md-7 col-lg-8">
            <div class="bg-white p-4 rounded-3 border border-light shadow-sm h-100 d-flex flex-column">
                <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-4">
                    <h5 class="fs-6 fw-bold text-dark m-0 d-flex align-items-center gap-2">
                        <i class="bi bi-clock-history text-primary"></i>
                        <span>Timeline & Progres Tugas</span>
                    </h5>
                </div>

                {{-- Timeline Feed --}}
                <div class="flex-grow-1 overflow-auto pe-1" style="max-height: 480px;">
                    @forelse($task->timelines->sortByDesc('created_at') as $timeline)
                    <div class="d-flex gap-3 mb-4 position-relative">
                        {{-- Timeline Bullet --}}
                        <div class="d-flex flex-column align-items-center">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 28px; height: 28px; font-size: 10px;">
                                {{ $timeline->progress_percentage }}%
                            </div>
                            <div class="h-100 border-start border-2 border-light mt-1"></div>
                        </div>
                        
                        {{-- Timeline Box --}}
                        <div class="bg-light p-3 rounded-3 flex-grow-1">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-2">
                                <span class="text-xs fw-bold text-dark">{{ $timeline->user->name }}</span>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="text-xxs text-secondary">{{ $timeline->created_at->format('d M Y - H:i') }}</span>
                                    
                                    @if($timeline->user_id === Auth::id() || Auth::user()->role === \App\Enums\UserRole::KABID)
                                    <div class="d-inline-flex gap-1">
                                        {{-- Edit Button --}}
                                        <button class="btn btn-xs p-0 px-1 text-secondary" 
                                                onclick="openEditTimelineModal({{ $timeline->id }}, '{{ addslashes($timeline->note) }}')" 
                                                title="Edit Catatan">
                                            <i class="bi bi-pencil-square" style="font-size: 11px;"></i>
                                        </button>
                                        {{-- Delete Button --}}
                                        <form action="{{ route('tasks.timeline.destroy', [$task, $timeline]) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus catatan progres ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-xs p-0 px-1 text-danger border-0 bg-transparent" title="Hapus Catatan">
                                                <i class="bi bi-trash" style="font-size: 11px;"></i>
                                            </button>
                                        </form>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="d-flex align-items-center gap-2 mb-2">
                                @php
                                    $tblClass = match($timeline->status) {
                                        \App\Enums\TaskStatus::TO_DO => 'todo',
                                        \App\Enums\TaskStatus::IN_PROGRESS => 'in-progress',
                                        \App\Enums\TaskStatus::REVIEW => 'review',
                                        \App\Enums\TaskStatus::DONE => 'done',
                                        default => 'todo',
                                    };
                                @endphp
                                <span class="badge-status badge-{{ $tblClass }} py-0.5 px-2" style="font-size: 10px;">{{ $timeline->status->label() }}</span>
                            </div>
                            <p class="text-sm text-secondary m-0">{!! e($timeline->note ?? 'Perubahan status / progres tugas.') !!}</p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-5 text-muted my-auto">
                        <i class="bi bi-calendar-x fs-2 text-secondary mb-2 d-block"></i>
                        Belum ada progres yang dicatatkan di timeline ini.
                    </div>
                    @endforelse
                </div>

                {{-- Form Add Timeline (Only associated Staff or Kabid) --}}
                @if(Auth::id() === $task->user_id || Auth::user()->role === \App\Enums\UserRole::KABID)
                <div class="border-t pt-4 mt-3">
                    <h6 class="fs-7 fw-bold text-dark mb-3">Tambahkan Progres Baru</h6>
                    <form method="POST" action="{{ route('tasks.timeline.store', $task) }}" class="row g-3">
                        @csrf
                        <div class="col-12 col-sm-6">
                            <label for="status" class="form-label text-xs fw-semibold text-secondary">Status Baru</label>
                            <select name="status" id="status" class="form-select text-sm rounded-3" required>
                                @foreach($statuses as $status)
                                    <option value="{{ $status->value }}" {{ $task->status === $status ? 'selected' : '' }}>{{ $status->label() }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-sm-6">
                            <label for="progress_percentage" class="form-label text-xs fw-semibold text-secondary">Progres Kerja (%)</label>
                            <input type="number" name="progress_percentage" id="progress_percentage" class="form-control text-sm rounded-3" min="0" max="100" value="{{ $task->progress_percentage }}" required />
                        </div>
                        <div class="col-12">
                            <textarea name="note" rows="2" class="form-control text-sm rounded-3" placeholder="Tuliskan catatan progres kerja Anda di sini (contoh: Selesai setup database)..." required></textarea>
                        </div>
                        <div class="col-12 text-end">
                            <button type="submit" class="btn btn-primary btn-sm rounded-pill px-4 fw-semibold shadow-sm">Kirim Progres</button>
                        </div>
                    </form>
                </div>
                @endif
            </div>
        </div>

        {{-- Column Right: Documents --}}
        <div class="col-12 col-md-5 col-lg-4">
            <div class="bg-white p-4 rounded-3 border border-light shadow-sm h-100 d-flex flex-column">
                <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-4">
                    <h5 class="fs-6 fw-bold text-dark m-0 d-flex align-items-center gap-2">
                        <i class="bi bi-file-earmark-arrow-up text-primary"></i>
                        <span>Dokumentasi Berkas</span>
                    </h5>
                </div>

                {{-- Documents List --}}
                <div class="flex-grow-1 overflow-auto pe-1 mb-3" style="max-height: 320px;">
                    @forelse($task->documents as $doc)
                    <div class="d-flex align-items-center justify-content-between bg-light p-2.5 rounded-3 mb-2.5 border border-light">
                        <div class="d-flex align-items-center gap-2 overflow-hidden">
                            {{-- Icon by Type --}}
                            @php
                                $icon = match(strtolower($doc->file_type)) {
                                    'pdf' => 'bi-file-pdf text-danger',
                                    'docx' => 'bi-file-word text-primary',
                                    'xlsx' => 'bi-file-excel text-success',
                                    'zip' => 'bi-file-zip text-warning',
                                    'png', 'jpg', 'jpeg' => 'bi-file-image text-info',
                                    default => 'bi-file-earmark text-secondary',
                                };
                            @endphp
                            <i class="bi {{ $icon }} fs-4 flex-shrink-0"></i>
                            <div class="overflow-hidden">
                                <div class="text-xs fw-semibold text-dark truncate" title="{{ $doc->file_name }}">{{ $doc->file_name }}</div>
                                <div class="text-xxs text-secondary">{{ $doc->created_at->format('d M Y') }} &bull; {{ strtoupper($doc->file_type) }}</div>
                            </div>
                        </div>
                        <a href="{{ route('documents.download', $doc) }}" class="btn btn-sm btn-white border rounded-circle p-1.5 px-2 text-secondary hover-bg-primary hover-text-white transition" title="Download Berkas">
                            <i class="bi bi-download"></i>
                        </a>
                    </div>
                    @empty
                    <div class="text-center py-5 text-muted my-auto">
                        <i class="bi bi-folder-x fs-2 text-secondary mb-2 d-block"></i>
                        Belum ada berkas dokumentasi yang diupload.
                    </div>
                    @endforelse
                </div>

                {{-- Form Upload Dokumentasi (Only associated Staff or Kabid) --}}
                @if(Auth::id() === $task->user_id || Auth::user()->role === \App\Enums\UserRole::KABID)
                <div class="border-t pt-4 mt-auto">
                    <h6 class="fs-7 fw-bold text-dark mb-3">Upload Dokumentasi Baru</h6>
                    <form method="POST" action="{{ route('tasks.document.store', $task) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <input type="file" name="document" class="form-control text-sm rounded-3 @error('document') is-invalid @enderror" required />
                            <div class="form-text text-xxs mt-1.5 text-secondary">Format diperbolehkan: PDF, DOCX, XLSX, JPG, PNG, ZIP (Max. 10MB).</div>
                            @error('document')
                                <div class="invalid-feedback text-xs mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm w-100 rounded-pill fw-semibold shadow-sm">
                            <i class="bi bi-upload me-1.5"></i>Upload File
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>

@endsection
