<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beranda - SPeGov</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-blue-600 min-h-screen flex flex-col antialiased font-sans overflow-x-hidden">
    
    <nav class="relative z-50 w-full px-8 py-6 flex justify-between items-center text-white">
        
      <div class="flex items-center" style="border: 2px solid red; padding: 5px;">
    <h1 style="color: white; font-weight: bold; margin-right: 10px;">TES LOGO:</h1>
    <img src="{{ asset('images/logoo.png') }}" alt="Logo Diskominfo" style="height: 50px; width: 200px; object-fit: contain; background-color: rgba(255,255,255,0.2);">
</div>

       
    </nav>

    <div class="flex-1 flex flex-col md:flex-row relative">
        
        <div class="w-full md:w-1/2 flex flex-col justify-center px-10 md:px-16 lg:px-24 z-10 text-white pb-20 pt-10 md:pt-0">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold leading-tight mb-6">
                Sistem Monitoring<br>dan Evaluasi Kinerja
            </h1>
            <p class="text-blue-100 text-lg mb-10 max-w-lg leading-relaxed">
                Kinerja adalah keluaran/hasil dari kegiatan/program yang akan atau telah dicapai sehubungan dengan penggunaan anggaran dengan kuantitas dan kualitas yang terukur pada Dinas Komunikasi dan Informatika.
            </p>
            <div>
                <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-10 py-3 border-2 border-white rounded-full text-white font-semibold hover:bg-white hover:text-blue-600 transition-all duration-300 shadow-lg hover:shadow-xl active:scale-95">
                    Login
                </a>
            </div>
        </div>

        <div class="hidden md:block absolute right-0 top-0 w-1/2 h-full z-0 pl-10 pb-10 pt-4 pr-4">
            <div class="w-full h-full rounded-tl-[8rem] rounded-bl-[2rem] rounded-tr-[2rem] rounded-br-[2rem] overflow-hidden relative shadow-2xl bg-blue-900 border-4 border-white/10">
                <img src="https://images.unsplash.com/photo-1541888045610-a29270e5b7b6?q=80&w=2000&auto=format&fit=crop" alt="Gedung Pemerintahan" class="w-full h-full object-cover opacity-70 mix-blend-overlay">
                <div class="absolute inset-0 bg-gradient-to-tr from-blue-900/60 to-transparent"></div>
            </div>
        </div>

    </div>
</body>
</html>