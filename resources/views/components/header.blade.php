<div class="flex items-center justify-between">
    <div class="flex items-center gap-4 w-full">
        <div class="md:hidden">
            <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-md hover:bg-gray-100">
                <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
        </div>

        {{-- Search bar --}}
        <div class="flex-1">
            <div class="relative">
                <input type="text" placeholder="Cari Tugas berdasarkan Nomor, Judul, atau PIC..." class="w-full bg-white border border-gray-200 rounded-full px-6 py-3 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-300" />
                <div class="absolute left-5 top-1/2 -translate-y-1/2 text-gray-400">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M10.5 18a7.5 7.5 0 100-15 7.5 7.5 0 000 15z"/></svg>
                </div>
            </div>
        </div>

        {{-- User info --}}
        <div class="flex items-center gap-3 ms-4">
            <div class="text-right hidden sm:block">
                    <div class="text-sm font-semibold text-gray-800">Admin E-Gov</div>
                    <div class="text-xs text-gray-500">Bidang Statistika Persandian dan E-Goverment</div>
            </div>
            <div class="h-10 w-10 bg-gradient-to-br from-blue-500 to-cyan-400 rounded-full text-white flex items-center justify-center font-semibold">A</div>
        </div>
    </div>
</div>  