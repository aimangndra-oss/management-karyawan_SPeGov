<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPeGov - Dinas Komunikasi dan Informatika Kabupaten Cirebon</title>
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white font-sans antialiased text-gray-900">

    <nav class="w-full bg-gradient-to-r from-blue-900 to-blue-500 shadow-md sticky top-0 z-50">
        <div class="w-full px-4 sm:px-8 lgpx-12 flex flex-wrap justify-between items-center py-4 gap-4">
            
            <div class="flex items-center w-auto md:flex-1 justify-start">
                <a href="#">
                    <img src="{{ asset('images/logoo.png') }}" alt="Logo" class="h-11 w-auto object-contain">
                </a>
            </div>

             <div class="flex space-x-6 md:space-x-12 overflow-x-auto w-full md:w-auto md:flex-1 justify-center order- md:order-none mt-2 md:mt-0 custom-scrollbar">
                <a href="#home" class="nav-link pb-1 border-b-2 border-white text-xs font-semibold tracking-widest uppercase text-white whitespace-nowrap transition cursor-pointer">HOME</a>
                <a href="#features" class="nav-link pb-1 border-b-2 border-transparent text-xs font-bold tracking-widest uppercase text-blue-200 hover:text-white transition whitespace-nowrap cursor-pointer">FITUR</a>
                <a href="#contact" class="nav-link pb-1 border-b-2 border-transparent text-xs font-semibold tracking-widest uppercase text-blue-200 hover:text-white transition whitespace-nowrap cursor-pointer">KONTAK</a>
            </div>

            <div class="flex items-center space-x-6 w-auto md:flex-1 justify-end order-2 md:order-none">
                @guest
                    <a href="{{ route('login') }}" class="text-xs font-bold tracking-widest uppercase text-white hover:text-blue-200 transition">LOGIN</a>
                @else
                    <form method="POST" action="{{ route('logout') }}" class="inline m-0 p-0">
                        @csrf
                        <button type="submit" class="text-xs font-bold tracking-widest uppercase text-red-400 hover:text-red-300 transition">LOGOUT</button>
                    </form>
                @endguest
            </div>
            
        </div>
    </nav>

    <section id="home" class="relative w-full h-[65vh] bg-white flex items-start pt-32">
        
        <img src="/images/dkis.png" class="absolute inset-0 w-full h-full object-cover z-0" alt="Hero Background">
        
        <div class="absolute inset-0 bg-black/50 z-10"></div>

        <div class="relative z-20 w-full max-w-7xl mx-auto px-6 lg:px-8 flex flex-col justify-start items-start">
            <div class="text-white max-w-2xl">
                <h1 class="text-4xl md:text-5xl font-light tracking-wide mb-4 text-shadow-sm">Sistem Monitoring dan Manajemen Tugas</h1>
                <p class="text-sm md:text-base font-light tracking-wide leading-relaxed opacity-90">
                    Aplikasi Monitoring, Pengelolaan, Dokumentasi, dan Evaluasi Tugas Bidang Statistik Persandian dan E-Government DISKOMINFO Kabupaten Cirebon.
                </p>
            </div>
        </div>
    </section>

    <section id="about" class="bg-gray-50 py-12">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
                <div>
                    <h2 class="text-3xl font-light text-gray-900 mb-6 tracking-wide">Tentang Aplikasi</h2>
                    <p class="text-gray-600 font-light leading-relaxed mb-4 text-sm md:text-base">
                        Dirancang khusus untuk mempermudah monitoring, pengelolaan, dokumentasi, dan evaluasi seluruh tugas yang dikerjakan oleh para pegawai Bidang Statistik Persandian dan E-Government Dinas Komunikasi dan Informatika Kabupaten Cirebon.
                    </p>
                    <p class="text-gray-600 font-light leading-relaxed text-sm md:text-base">
                        Dengan sistem terintegrasi ini, Kepala Bidang (Kabid) dapat memantau produktivitas tim secara real-time, mendistribusikan tugas secara merata, dan memastikan seluruh program kerja terlaksana tepat waktu sesuai dengan target kinerja.
                    </p>
                </div>
                <div>
                    <div class="bg-white p-8 md:p-10 shadow-sm border-l-4 border-blue-600">
                        <h5 class="text-lg font-semibold text-blue-600 mb-4 flex items-center">
                            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Visi & Misi Bidang SPeGov
                        </h5>
                        <p class="text-sm text-gray-500 font-light leading-relaxed">
                            Mewujudkan tata kelola pemerintahan yang bersih, efektif, transparan, dan akuntabel berbasis sistem informasi terintegrasi serta pengamanan informasi yang andal di lingkungan Kabupaten Cirebon.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
</html>

    <section id="features" class="py-24 bg-white border-t border-gray-100 overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-light text-gray-900 mb-4 tracking-wide">Fitur Unggulan Aplikasi
                </h2>
                <p class="text-gray-500 font-light tracking-wide text-sm md:text-base">Aplikasi dirancang dengan fitur-fitur penting untuk mempermudah pekerjaan</p>
            </div>

            <div class="relative w-full flex items-center justify-center h-[320px] mb-8">
                <button onclick="slideFeature('prev')" class="absolute left-0 md:left-12 z-30 bg-blue-600 text-white p-3 rounded-full hover:bg-blue-800 transition shadow-lg focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </button>

                <div id="feature-track" class="relative w-full h-full flex items-center justify-center">
                </div>

                <button onclick="slideFeature('next')" class="absolute right-0 md:right-12 z-30 bg-blue-600 text-white p-3 rounded-full hover:bg-blue-800 transition shadow-lg focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </button>
            </div>

            <div class="text-center max-w-2xl mx-auto px-4 h-[100px]">
                <h3 id="feature-title" class="text-2xl font-semibold text-gray-900 mb-2 transition-all duration-300"></h3>
                <p id="feature-desc" class="text-sm text-gray-500 tracking-wide transition-all duration-300"></p>
            </div>
        </div>
    </section>

   <section id="contact" class="py-12 bg-gray-50">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
            <div>
                <h2 class="text-2xl font-light text-gray-900 mb-4 tracking-wide">Hubungi Kami</h2>
                <p class="text-gray-500 font-light mb-6 text-sm leading-relaxed">Untuk pertanyaan lebih lanjut seputar aplikasi, Anda dapat mengunjungi kantor kami atau melalui kontak resmi berikut.</p>
                
                <div class="flex items-start mb-4">
                    <div class="flex-shrink-0 mt-0.5">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </div>
                    <div class="ml-3">
                        <h6 class="text-sm font-semibold text-gray-900">Alamat Kantor</h6>
                        <p class="mt-0.5 text-xs text-gray-500 font-light">Jl. Sunan Drajat No.15, Sumber, Kec. Sumber, Kabupaten Cirebon</p>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <div class="flex-shrink-0 mt-0.5">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <div class="ml-3">
                        <h6 class="text-sm font-semibold text-gray-900">E-mail Resmi</h6>
                        <p class="mt-0.5 text-xs text-gray-500 font-light">diskominfo@cirebonkab.go.id</p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white p-2 shadow-sm rounded-lg">
                <div class="w-full h-[200px] md:h-[250px] bg-gray-200 overflow-hidden rounded">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3962.339243764834!2d108.47702877589332!3d-6.728448765785721!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2b6f13d8e5c8dd01%3A0xe54be0cf4ff128aa!2sDinas%20Komunikasi%20dan%20Informatika%20(DISKOMINFO)%20Kabupaten%20Cirebon!5e0!3m2!1sid!2sid!4v1700000000000!5m2!1sid!2sid" class="w-full h-full border-0" allowfullscreen="" loading="lazy"></iframe>
                </div>
            </div>
        </div>
    </div>
</section>

    <footer class="bg-black py-8">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 text-center">
            <p class="text-xs text-gray-400 font-light tracking-wide uppercase mb-2">© {{ date('Y') }} Bidang Statistik Persandian dan E-Government</p>
            <p class="text-xs text-gray-500 font-light tracking-wide uppercase">Dinas Komunikasi dan Informatika Kabupaten Cirebon</p>
        </div>
    </footer>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const navLinks = document.querySelectorAll('.nav-link');
        
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                navLinks.forEach(l => {
                    l.classList.remove('border-white', 'text-white');
                    l.classList.add('border-transparent', 'text-blue-200');
                });
                
                this.classList.remove('border-transparent', 'text-blue-200');
                this.classList.add('border-white', 'text-white');
            });
        });

        initFeatureSlider();
    });

    const featuresData = [
        { 
            title: "Monitoring Tugas", 
            text: "Pantau progres pekerjaan staf secara transparan dan detail.", 
            img: "{{ asset('images/monitoring tugas.png') }}"  
        },
        { 
            title: "Kalender Deadline", 
            text: "Visualisasi jadwal tenggat waktu terintegrasi secara dinamis.", 
            img: "{{ asset('images/kalender.png') }}" 
        },
        { 
            title: "Dokumentasi Kegiatan", 
            text: "Menampilkan pembuktian tugas dalam sistem.", 
            img: "{{ asset('images/dokum kegiatan.png') }}" 
        },
        { 
            title: "Dashboard Real-time", 
            text: "Ringkasan grafik performa dan statistika pencapaian tugas.", 
            img: "{{ asset('images/dashboard.png') }}" 
        }
    ];

    let currentFeatureIndex = 0;
    let isFeatureAnimating = false;

    function initFeatureSlider() {
        const track = document.getElementById('feature-track');
        track.innerHTML = ''; 

        featuresData.forEach((feature) => {
            const slide = document.createElement('div');
            slide.className = 'feature-slide absolute w-[350px] h-[250px] md:w-[650px] md:h-[350px] flex items-center justify-center transition-all duration-700 ease-in-out';
            
            const img = document.createElement('img');
            img.src = feature.img;
            img.className = 'w-full h-full object-contain rounded-xl shadow-2xl transition-all duration-700 ease-in-out';
            
            slide.appendChild(img);
            track.appendChild(slide);
        });

        updateFeaturePositions();
    }

    function updateFeaturePositions() {
        const slides = document.querySelectorAll('.feature-slide');
        const total = slides.length;
        if (total === 0) return;

        const leftIndex = (currentFeatureIndex - 1 + total) % total;
        const rightIndex = (currentFeatureIndex + 1) % total;

        slides.forEach((slide, idx) => {
            const img = slide.querySelector('img');

            if (idx === currentFeatureIndex) {
                slide.style.transform = 'translateX(0) scale(1)';
                slide.style.opacity = '1';
                slide.style.zIndex = '10';
                slide.style.filter = 'blur(0px)';
                img.classList.remove('opacity-50');
            } else if (idx === leftIndex) {
                slide.style.transform = 'translateX(-115%) scale(0.85)';
                slide.style.opacity = '0.6';
                slide.style.zIndex = '5';
                slide.style.filter = 'blur(2px)';
                img.classList.add('opacity-50');
            } else if (idx === rightIndex) {
                slide.style.transform = 'translateX(115%) scale(0.85)';
                slide.style.opacity = '0.6';
                slide.style.zIndex = '5';
                slide.style.filter = 'blur(2px)';
                img.classList.add('opacity-50');
            } else {
                slide.style.transform = 'translateX(200%) scale(0.5)';
                slide.style.opacity = '0';
                slide.style.zIndex = '0';
                slide.style.filter = 'blur(5px)';
            }
        });

        const activeFeature = featuresData[currentFeatureIndex];
        document.getElementById('feature-title').innerText = activeFeature.title;
        document.getElementById('feature-desc').innerText = activeFeature.text;
        
        setTimeout(() => {
            isFeatureAnimating = false;
        }, 700);
    }

    function slideFeature(direction) {
        if (isFeatureAnimating) return;
        isFeatureAnimating = true;
        
        const total = featuresData.length;

        if (direction === 'next') {
            currentFeatureIndex = (currentFeatureIndex + 1) % total;
        } else {
            currentFeatureIndex = (currentFeatureIndex - 1 + total) % total;
        }
        updateFeaturePositions();
    }
</script>
</body>
</html>