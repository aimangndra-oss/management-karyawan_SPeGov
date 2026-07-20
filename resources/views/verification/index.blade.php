<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Safe Verify - SPeGov</title>
    
   
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
  
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
      
        #sliderThumb { cursor: grab; touch-action: none; }
        #sliderThumb:active { cursor: grabbing; }
    </style>
</head>
<body class=" bg-gradient-to-r from-blue-900 to-blue-500  min-h-screen flex flex-col items-center justify-center p-4 antialiased font-sans">

    
    <div class="w-full max-w-md flex flex-col items-center">
        
        
        <div class="mb-8 flex justify-center">
            <img src="{{ asset('images/logoo.png') }}" alt="Logo Diskominfo" class="h-20 md:h-24 w-auto drop-shadow-xl">
        </div>

       
        <div class="bg-slate-900/80 backdrop-blur-md border border-slate-700/50 rounded-3xl shadow-2xl p-8 w-full text-center relative overflow-hidden">
            
         
            <div class="absolute -top-10 -left-10 w-32 h-32 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>

          
            <p class="text-blue-400 text-sm font-medium mb-1">Bidang Statistik Persandian dan E-Government</p>

            
            <hr class="border-slate-700/60 mb-6">

          
            <p class="text-slate-300 text-sm mb-6 leading-relaxed">
                Silakan geser slider di bawah ini untuk masuk ke sistem Manajemen tugas.
            </p>

          
            <div class="relative w-full h-14 bg-slate-800 rounded-full overflow-hidden border border-slate-600 select-none shadow-[inset_0_2px_4px_rgba(0,0,0,0.4)]" id="sliderWrapper">
                
                
                <div class="absolute inset-0 flex items-center justify-center text-slate-400 text-sm font-medium z-0">
                    Geser untuk memverifikasi
                </div>
                
              
                <div class="absolute top-0 left-0 h-full bg-blue-600/70 z-10 w-0 transition-all duration-75" id="sliderProgress"></div>
               
                <div class="absolute top-1 left-1 h-12 w-12 bg-blue-500 hover:bg-blue-400 rounded-full flex items-center justify-center text-white shadow-lg z-20 transition-colors" id="sliderThumb">
                    <i class="bi bi-chevron-double-right text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-8 text-center text-slate-400/60 text-xs tracking-wider">
            &copy; {{ date('Y') }} Bidang Statistik Persandian dan E-Government.<br>DISKOMINFO Kabupaten Cirebon.
        </div>
    </div>

</body>
</html>