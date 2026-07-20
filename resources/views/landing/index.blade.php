<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPeGov - Dinas Komunikasi dan Informatika Kabupaten Cirebon</title>
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
</head>
<body class="landing-body">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-landing py-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="#">
                <img src="{{ asset('images/logo.png') }}" alt="Logo DISKOMINFO" class="logo-nav me-2">
                <div class="brand-text">
                    <span class="brand-title">SPeGov</span>
                    <span class="brand-subtitle">DISKOMINFO Cirebon</span>
                </div>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarContent">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center">
                    <li class="nav-item">
                        <a class="nav-link active" href="#home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">Tentang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Fitur</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Kontak</a>
                    </li>
                    @guest
                        <li class="nav-item ms-lg-3">
                            <a class="btn btn-login-nav" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right me-1"></i> Login
                            </a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('profile.edit') }}">Profil</a>
                        </li>
                        <li class="nav-item ms-lg-3">
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-login-nav bg-danger border-0">
                                    <i class="bi bi-box-arrow-right me-1"></i> Logout
                                </button>
                            </form>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero-section py-5">
        <div class="container py-4">
            <div class="row align-items-center gy-4">
                <div class="col-lg-6 hero-text-col">
                    <h1 class="hero-title mb-3">Sistem Monitoring dan Manajemen Tugas</h1>
                    <p class="hero-subtitle mb-4">
                        Aplikasi Monitoring, Pengelolaan, Dokumentasi, dan Evaluasi Tugas Bidang Statistik Persandian dan E-Government DISKOMINFO Kabupaten Cirebon.
                    </p>
                    <a href="{{ route('login') }}" class="btn btn-hero-action px-4 py-3">
                        Masuk Sistem <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                </div>
                <div class="col-lg-6 hero-image-col">
                    <div class="hero-image-wrapper">
                        <!-- Default illustration / placeholder or styled container representing the building/office -->
                        <div class="office-overlay-card">
                            <i class="bi bi-building-fill text-white mb-3" style="font-size: 3rem;"></i>
                            <h4 class="text-white mb-2">DISKOMINFO Kabupaten Cirebon</h4>
                            <p class="text-white-50 mb-0">Jl. Sunan Drajat No.15, Sumber, Kec. Sumber, Kabupaten Cirebon, Jawa Barat 45611</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="about-section py-5 bg-light">
        <div class="container py-4">
            <div class="row align-items-center gy-4">
                <div class="col-lg-6">
                    <h2 class="section-title mb-3">Tentang SPeGov</h2>
                    <p class="section-text">
                        SPeGov dirancang khusus untuk mempermudah monitoring, pengelolaan, dokumentasi, dan evaluasi seluruh tugas yang diemban oleh para pegawai Bidang Statistik Persandian dan E-Government Dinas Komunikasi dan Informatika Kabupaten Cirebon.
                    </p>
                    <p class="section-text">
                        Dengan sistem terintegrasi ini, Kepala Bidang (Kabid) dapat memantau produktivitas tim secara real-time, mendistribusikan tugas secara merata, dan memastikan seluruh program kerja terlaksana tepat waktu sesuai dengan target kinerja.
                    </p>
                </div>
                <div class="col-lg-6">
                    <div class="p-4 bg-white rounded shadow-sm border-left-custom">
                        <h5 class="mb-3 text-primary"><i class="bi bi-shield-check me-2"></i> Visi & Misi Bidang SPeGov</h5>
                        <p class="small text-muted mb-0">
                            Mewujudkan tata kelola pemerintahan yang bersih, efektif, transparan, dan akuntabel berbasis sistem informasi terintegrasi serta pengamanan informasi yang andal di lingkungan Kabupaten Cirebon.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="features-section py-5">
        <div class="container py-4">
            <div class="text-center mb-5">
                <h2 class="section-title">Fitur Unggulan Sistem</h2>
                <p class="text-muted">Aplikasi dirancang dengan modul-modul penting untuk kelancaran organisasi</p>
            </div>
            <div class="row g-4">
                <!-- Card 1 -->
                <div class="col-md-6 col-lg-3">
                    <div class="card feature-card h-100 p-4 border-0 shadow-sm text-center">
                        <div class="feature-icon-wrapper mb-3 mx-auto">
                            <i class="bi bi-clipboard-data text-primary"></i>
                        </div>
                        <h5 class="feature-title mb-2">Monitoring Tugas</h5>
                        <p class="feature-desc text-muted mb-0">Pantau progres pekerjaan staf secara transparan dan detail.</p>
                    </div>
                </div>
                <!-- Card 2 -->
                <div class="col-md-6 col-lg-3">
                    <div class="card feature-card h-100 p-4 border-0 shadow-sm text-center">
                        <div class="feature-icon-wrapper mb-3 mx-auto">
                            <i class="bi bi-calendar3-event text-primary"></i>
                        </div>
                        <h5 class="feature-title mb-2">Kalender Deadline</h5>
                        <p class="feature-desc text-muted mb-0">Visualisasi jadwal tenggat waktu terintegrasi secara dinamis.</p>
                    </div>
                </div>
                <!-- Card 3 -->
                <div class="col-md-6 col-lg-3">
                    <div class="card feature-card h-100 p-4 border-0 shadow-sm text-center">
                        <div class="feature-icon-wrapper mb-3 mx-auto">
                            <i class="bi bi-folder-check text-primary"></i>
                        </div>
                        <h5 class="feature-title mb-2">Dokumentasi Kegiatan</h5>
                        <p class="feature-desc text-muted mb-0">Unggah berkas pembuktian tugas dengan aman dalam sistem.</p>
                    </div>
                </div>
                <!-- Card 4 -->
                <div class="col-md-6 col-lg-3">
                    <div class="card feature-card h-100 p-4 border-0 shadow-sm text-center">
                        <div class="feature-icon-wrapper mb-3 mx-auto">
                            <i class="bi bi-speedometer2 text-primary"></i>
                        </div>
                        <h5 class="feature-title mb-2">Dashboard Real-time</h5>
                        <p class="feature-desc text-muted mb-0">Ringkasan grafik performa dan statistika pencapaian tugas.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="contact-section py-5 bg-light">
        <div class="container py-4">
            <div class="row gy-4">
                <div class="col-lg-6">
                    <h2 class="section-title mb-3">Hubungi Kami</h2>
                    <p class="text-muted mb-4">Untuk pertanyaan lebih lanjut seputar sistem SPeGov, Anda dapat mengunjungi kantor kami atau melalui kontak resmi berikut.</p>
                    <div class="d-flex align-items-center mb-3">
                        <div class="contact-icon me-3"><i class="bi bi-geo-alt-fill text-primary"></i></div>
                        <div>
                            <h6 class="mb-0 text-dark">Alamat Kantor</h6>
                            <span class="text-muted small">Jl. Sunan Drajat No.15, Sumber, Kec. Sumber, Kabupaten Cirebon</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        <div class="contact-icon me-3"><i class="bi bi-envelope-fill text-primary"></i></div>
                        <div>
                            <h6 class="mb-0 text-dark">E-mail Resmi</h6>
                            <span class="text-muted small">diskominfo@cirebonkab.go.id</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="p-4 bg-white rounded shadow-sm">
                        <h5 class="mb-3 text-dark">Lokasi Kantor</h5>
                        <div class="ratio ratio-16x9 rounded overflow-hidden">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3962.339243764834!2d108.47702877589332!3d-6.728448765785721!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2b6f13d8e5c8dd01%3A0xe54be0cf4ff128aa!2sDinas%20Komunikasi%20dan%20Informatika%20(DISKOMINFO)%20Kabupaten%20Cirebon!5e0!3m2!1sid!2sid!4v1700000000000!5m2!1sid!2sid" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer-landing py-4">
        <div class="container text-center">
            <p class="mb-1 text-white-50">© {{ date('Y') }} Bidang Statistik Persandian dan E-Government</p>
            <p class="mb-0 text-white-50">Dinas Komunikasi dan Informatika Kabupaten Cirebon</p>
        </div>
    </footer>
</body>
</html>
