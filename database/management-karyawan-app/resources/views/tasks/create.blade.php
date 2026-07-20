@extends('layouts.app')

@section('title', 'Tambah Tugas Baru')

@section('content')

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
    <div class="bg-white p-4 rounded-3 border border-light shadow-sm">
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

@endsection
