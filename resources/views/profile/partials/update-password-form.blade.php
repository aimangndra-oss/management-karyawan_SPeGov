<div class="profile-card-icon">
    <i class="bi bi-shield-lock"></i>
</div>
<h5 class="fw-semibold mb-1">Ganti Sandi</h5>
<p class="text-muted small mb-4">Gunakan sandi yang panjang dan acak agar akun tetap aman.</p>

<form method="post" action="{{ route('password.update') }}">
    @csrf
    @method('put')

    <div class="mb-3">
        <label for="update_password_current_password" class="form-label">Sandi Saat Ini</label>
        <input id="update_password_current_password" name="current_password" type="password" class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" autocomplete="current-password">
        @error('current_password', 'updatePassword')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="update_password_password" class="form-label">Sandi Baru</label>
        <input id="update_password_password" name="password" type="password" class="form-control @error('password', 'updatePassword') is-invalid @enderror" autocomplete="new-password">
        @error('password', 'updatePassword')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-4">
        <label for="update_password_password_confirmation" class="form-label">Konfirmasi Sandi Baru</label>
        <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror" autocomplete="new-password">
        @error('password_confirmation', 'updatePassword')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="d-flex align-items-center gap-3">
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>

        @if (session('status') === 'password-updated')
            <span class="text-success small"><i class="bi bi-check-circle-fill me-1"></i>Tersimpan.</span>
        @endif
    </div>
</form>