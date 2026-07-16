<div class="profile-card-icon">
    <i class="bi bi-person"></i>
</div>
<h5 class="fw-semibold mb-1">Informasi Profil</h5>
<p class="text-muted small mb-4">Perbarui nama dan alamat email akun Anda.</p>

<form method="post" action="{{ route('profile.update') }}">
    @csrf
    @method('patch')

    <div class="mb-3">
        <label for="name" class="form-label">Nama</label>
        <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
        @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-4">
        <label for="email" class="form-label">Email</label>
        <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required autocomplete="username">
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="d-flex align-items-center gap-3">
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>

        @if (session('status') === 'profile-updated')
            <span class="text-success small"><i class="bi bi-check-circle-fill me-1"></i>Tersimpan.</span>
        @endif
    </div>
</form>