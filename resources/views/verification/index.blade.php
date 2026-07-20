<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Safe Verify - SPeGov</title>
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
</head>
<body class="verify-body">
    <div class="verify-container">
        <!-- Logo Header -->
        <div class="verify-logos">
            <img src="{{ asset('images/logo.png') }}" alt="Logo DISKOMINFO Cirebon" class="logo-diskominfo">
            <div class="logo-divider"></div>
            <div class="logo-spegov-text">SPeGov</div>
        </div>

        <!-- Description Box -->
        <div class="verify-card">
            <h1 class="verify-title">Sistem Monitoring dan Manajemen Tugas</h1>
            <p class="verify-subtitle">Bidang Statistik Persandian dan E-Government</p>
            <p class="verify-office">Dinas Komunikasi dan Informatika Kabupaten Cirebon</p>

            <div class="verify-divider"></div>

            <p class="verify-instruction">Silakan geser slider di bawah ini untuk memverifikasi keamanan dan masuk ke sistem.</p>

            <!-- Slide to Verify Element -->
            <div class="slider-wrapper" id="sliderWrapper">
                <div class="slider-bg">Geser untuk memverifikasi</div>
                <div class="slider-thumb" id="sliderThumb">
                    <i class="bi bi-chevron-double-right"></i>
                </div>
                <div class="slider-progress" id="sliderProgress"></div>
            </div>
        </div>

        <div class="verify-footer">
            &copy; {{ date('Y') }} Bidang Statistik Persandian dan E-Government. DISKOMINFO Kabupaten Cirebon.
        </div>
    </div>
</body>
</html>
