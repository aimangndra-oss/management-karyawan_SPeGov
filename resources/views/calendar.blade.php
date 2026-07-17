@extends('layouts.app')

@section('title', 'Kalender Deadline')

@section('content')

    {{-- Kustomisasi Style Kalender & Navbar Premium --}}
    <style>
        /* ================= NAVBAR STYLES ================= */
        .top-navbar {
            display: flex !important;
            justify-content: space-between !important;
            align-items: center !important;
            gap: 2rem !important;
            padding: 0.75rem 1.5rem !important;
            background-color: #ffffff !important;
            border-bottom: 1px solid #f3f4f6 !important;
            border-radius: 12px !important;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05) !important;
        }

        .navbar-left {
            display: flex !important;
            align-items: center !important;
            flex-shrink: 0 !important;
        }

        .navbar-greeting {
            min-width: 0 !important;
        }
        .navbar-greeting .greeting-text {
            font-family: 'Inter', sans-serif !important;
            font-weight: 500 !important;
            font-size: 0.95rem !important;
            color: #374151 !important;
            line-height: 1.3 !important;
            white-space: nowrap !important;
        }
        .navbar-greeting .greeting-text strong {
            font-weight: 700 !important;
            color: #2563eb !important;
        }
        .navbar-greeting .greeting-sub {
            font-family: 'Inter', sans-serif !important;
            font-size: 0.75rem !important;
            color: #9ca3af !important;
            margin-top: 2px !important;
            white-space: nowrap !important;
        }

        .search-box {
            position: relative !important;
            width: 100% !important;
            max-width: 550px !important;
            flex-grow: 1 !important;
        }
        .search-icon {
            position: absolute !important;
            left: 14px !important;
            top: 50% !important;
            transform: translateY(-50%) !important;
            width: 18px !important;
            height: 18px !important;
            color: #9ca3af !important;
        }
        .search-box input {
            width: 100% !important;
            padding: 0.55rem 1rem 0.55rem 2.5rem !important;
            border: 1px solid #e5e7eb !important;
            border-radius: 50px !important;
            background-color: #f9fafb !important;
            font-size: 0.85rem !important;
            color: #374151 !important;
            outline: none !important;
            transition: all 0.2s !important;
        }
        .search-box input:focus {
            border-color: #3b82f6 !important;
            background-color: #ffffff !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1) !important;
        }

        .navbar-user {
            display: flex !important;
            align-items: center !important;
            flex-shrink: 0 !important;
        }
        .user-info {
            display: flex !important;
            align-items: center !important;
            gap: 12px !important;
            padding: 6px 12px !important;
            border-radius: 50px !important;
            transition: background 0.2s !important;
            white-space: nowrap !important;
            text-decoration: none !important;
        }
        .user-info:hover {
            background-color: #f3f4f6 !important;
        }
        .user-info-text {
            text-align: right !important;
            display: flex !important;
            flex-direction: column !important;
            justify-content: center !important;
        }
        .user-name {
            font-size: 0.85rem !important;
            font-weight: 700 !important;
            color: #1f2937 !important;
            line-height: 1.2 !important;
            white-space: nowrap !important;
        }
        .user-role {
            font-size: 0.75rem !important;
            color: #6b7280 !important;
            line-height: 1.2 !important;
            margin-top: 2px !important;
            white-space: nowrap !important;
        }
        .user-avatar {
            width: 40px !important;
            height: 40px !important;
            border-radius: 50% !important;
            background-color: #3b82f6 !important;
            color: #ffffff !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            font-weight: 600 !important;
            font-size: 1rem !important;
            flex-shrink: 0 !important;
        }

        /* ================= CALENDAR STYLES ================= */
        .calendar-container {
            background-color: #ffffff;
            border-radius: 12px;
            border: 1px solid #f3f4f6;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.02);
            overflow: hidden;
        }

        .calendar-header-days {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            background-color: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
            text-align: center;
            font-weight: 600;
            font-size: 0.85rem;
            color: #64748b;
            padding: 12px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            grid-auto-rows: 120px;
        }

        .calendar-day-cell {
            border-right: 1px solid #f1f5f9;
            border-bottom: 1px solid #f1f5f9;
            padding: 8px;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            text-decoration: none !important;
            transition: background-color 0.2s;
            cursor: pointer;
        }
        .calendar-day-cell:nth-child(7n) {
            border-right: none;
        }
        .calendar-day-cell:hover {
            background-color: #f8fafc;
        }

        .calendar-day-number {
            font-family: 'Inter', sans-serif;
            font-size: 0.85rem;
            font-weight: 600;
            color: #475569;
            align-self: flex-start;
            margin-bottom: 6px;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
        }

        .muted-day {
            background-color: #f8fafc;
            pointer-events: none;
        }
        .muted-day .calendar-day-number {
            color: #cbd5e1;
        }

        .today-cell {
            background-color: #eff6ff;
        }
        .today-cell:hover {
            background-color: #dbeafe;
        }
        .today-number {
            background-color: #3b82f6;
            color: #ffffff !important;
            box-shadow: 0 2px 4px rgba(59, 130, 246, 0.3);
        }

        .calendar-tasks-list {
            display: flex;
            flex-direction: column;
            gap: 4px;
            overflow-y: auto;
            flex-grow: 1;
            max-height: calc(100% - 30px);
        }

        /* Task Items Styling */
        .calendar-task-item {
            font-size: 11px;
            font-weight: 600;
            padding: 4px 8px;
            border-radius: 6px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: transform 0.15s, box-shadow 0.15s;
        }
        .calendar-task-item:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        /* Status Colors */
        .task-status-done {
            background-color: #ecfdf5;
            color: #047857;
            border-left: 3px solid #10b981;
        }
        .task-status-overdue {
            background-color: #fef2f2;
            color: #b91c1c;
            border-left: 3px solid #ef4444;
        }
        .task-status-upcoming {
            background-color: #eff6ff;
            color: #1d4ed8;
            border-left: 3px solid #3b82f6;
        }

        .task-status-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            display: inline-block;
            flex-shrink: 0;
        }
        .task-status-done .task-status-dot { background-color: #10b981; }
        .task-status-overdue .task-status-dot { background-color: #ef4444; }
        .task-status-upcoming .task-status-dot { background-color: #3b82f6; }

        @media (max-width: 768px) {
            .top-navbar {
                padding: 0.5rem 1rem !important;
                gap: 1rem !important;
            }
            .search-box {
                max-width: 100% !important;
            }
        }
    </style>

    {{-- 1. Top Navbar --}}
    <nav class="top-navbar mb-4">
        <div class="navbar-left">
            <button class="btn d-lg-none p-1 me-2" onclick="toggleSidebar()" aria-label="Toggle sidebar">
                <i class="bi bi-list fs-4 text-secondary"></i>
            </button>

            <div class="navbar-greeting d-none d-md-block">
                <div class="greeting-text">
                    {{-- DI SINI PERBAIKANNYA: Menghapus explode agar menampilkan nama utuh --}}
                    Selamat Datang, <strong>{{ Auth::user()->name }}</strong>
                </div>
                <div class="greeting-sub">{{ now()->locale('id')->translatedFormat('l, d F Y') }}</div>
            </div>
        </div>

        <div class="search-box">
            <svg class="search-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
            </svg>
            <input type="text" placeholder="Cari Tugas berdasarkan Nomor, Judul, atau PIC..." id="searchInput" />
        </div>

        <div class="dropdown navbar-user">
            <div class="user-info" data-bs-toggle="dropdown" aria-expanded="false" role="button">
                <div class="user-info-text d-none d-sm-flex">
                    <div class="user-name">{{ Auth::user()->name }}</div>
                    <div class="user-role">{{ Auth::user()->role->label() }}</div>
                </div>
                <div class="user-avatar shadow-sm">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
            </div>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 mt-2">
                <li>
                    <a class="dropdown-item py-2" href="{{ route('profile.edit') }}">
                        <i class="bi bi-person me-2 text-secondary"></i> Profil Saya
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item py-2 text-danger">
                            <i class="bi bi-box-arrow-right me-2"></i> Keluar
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </nav>

    {{-- 2. Control Panel --}}
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 bg-white p-4 rounded-3 border border-light shadow-sm mb-4">
        <div class="d-flex align-items-center gap-3">
            <div class="d-flex align-items-center gap-2">
                <select id="monthSelect" class="form-select fw-bold text-dark border-0 bg-light px-4 py-2" style="width: auto; cursor: pointer; border-radius: 0.75rem;" onchange="handleSelectChange()">
                    <option value="0">Januari</option>
                    <option value="1">Februari</option>
                    <option value="2">Maret</option>
                    <option value="3">April</option>
                    <option value="4">Mei</option>
                    <option value="5">Juni</option>
                    <option value="6">Juli</option>
                    <option value="7">Agustus</option>
                    <option value="8">September</option>
                    <option value="9">Oktober</option>
                    <option value="10">November</option>
                    <option value="11">Desember</option>
                </select>
                
                <select id="yearSelect" class="form-select fw-bold text-dark border-0 bg-light px-4 py-2" style="width: auto; cursor: pointer; border-radius: 0.75rem;" onchange="handleSelectChange()">
                    {{-- Diisi via JS --}}
                </select>
            </div>
            
            <div class="d-flex align-items-center border rounded-pill bg-light ms-2" style="padding: 2px 4px;">
                <button onclick="changeMonth(-1)" class="btn btn-sm border-0 text-secondary fs-6 px-2" aria-label="Previous Month">
                    <i class="bi bi-chevron-left"></i>
                </button>
                <div class="vr bg-secondary opacity-25" style="width: 1.5px; height: 20px;"></div>
                <button onclick="changeMonth(1)" class="btn btn-sm border-0 text-secondary fs-6 px-2" aria-label="Next Month">
                    <i class="bi bi-chevron-right"></i>
                </button>
            </div>
        </div>

        <div class="d-flex align-items-center gap-3 text-xs font-semibold text-secondary">
            <div class="d-flex align-items-center gap-1.5"><span class="badge rounded-circle bg-success p-1" style="width: 8px; height: 8px;">&nbsp;</span> Selesai</div>
            <div class="d-flex align-items-center gap-1.5"><span class="badge rounded-circle bg-danger p-1" style="width: 8px; height: 8px;">&nbsp;</span> Lewat Deadline</div>
            <div class="d-flex align-items-center gap-1.5"><span class="badge rounded-circle bg-primary p-1" style="width: 8px; height: 8px;">&nbsp;</span> Akan Datang</div>
        </div>
    </div>

    {{-- 3. Calendar Grid --}}
    <div class="calendar-container" style="margin-bottom: 90px;">
        <div class="calendar-header-days">
            <div>Senin</div>
            <div>Selasa</div>
            <div>Rabu</div>
            <div>Kamis</div>
            <div>Jumat</div>
            <div>Sabtu</div>
            <div>Minggu</div>
        </div>

        <div id="calendar-grid" class="calendar-grid">
            {{-- Diisi secara dinamis via JS --}}
        </div>
    </div>

    {{-- 4. Fixed Footer --}}
    <footer class="bg-white p-3 text-center shadow-sm border-top border-light" 
            style="position: fixed; bottom: 0; right: 0; left: var(--sidebar-width, 260px); width: calc(100% - var(--sidebar-width, 260px)); z-index: 999;">
        <small class="text-muted" style="font-size: 11px; letter-spacing: 0.5px; display: block; font-family: sans-serif;">
            &copy; {{ now()->year }} Pemerintah Kabupaten Cirebon &mdash; Dinas Komunikasi dan Informatika &mdash; Bidang Statistika Persandian dan E-Government.
        </small>
    </footer>

@endsection

@push('scripts')
<script>
    let taskData = [];
    let originalTaskData = []; // Untuk menyimpan state mentah data tugas dari API

    let currentDate = new Date();
    let displayedYear = currentDate.getFullYear();
    let displayedMonth = currentDate.getMonth();

    function populateYearDropdown() {
        const yearSelect = document.getElementById('yearSelect');
        const currentY = new Date().getFullYear();
        yearSelect.innerHTML = '';
        for (let y = currentY - 5; y <= currentY + 5; y++) {
            const option = document.createElement('option');
            option.value = y;
            option.textContent = y;
            yearSelect.appendChild(option);
        }
    }

    function renderCalendar(year, month) {
        const gridContainer = document.getElementById('calendar-grid');
        gridContainer.innerHTML = ''; 

        document.getElementById('monthSelect').value = month;
        document.getElementById('yearSelect').value = year;

        let firstDayIndex = new Date(year, month, 1).getDay();
        let startOffset = firstDayIndex === 0 ? 6 : firstDayIndex - 1;

        const totalDaysInMonth = new Date(year, month + 1, 0).getDate();
        const totalDaysInPrevMonth = new Date(year, month, 0).getDate();

        // 1. Hari dari bulan sebelumnya (Muted)
        for (let i = startOffset; i > 0; i--) {
            const dayNum = totalDaysInPrevMonth - i + 1;
            gridContainer.innerHTML += `
                <div class="calendar-day-cell muted-day">
                    <span class="calendar-day-number">${dayNum}</span>
                </div>`;
        }

        // 2. Hari aktif pada bulan berjalan
        for (let day = 1; day <= totalDaysInMonth; day++) {
            const currentStrDate = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            const isToday = (new Date().toDateString() === new Date(year, month, day).toDateString());
            
            let cellClass = "calendar-day-cell" + (isToday ? " today-cell" : "");
            let numberClass = "calendar-day-number" + (isToday ? " today-number" : "");

            const dayTasks = taskData.filter(t => t.date === currentStrDate);
            let htmlTasks = dayTasks.map(task => {
                let badgeClass = "task-status-upcoming";
                if (task.status === 'selesai') badgeClass = "task-status-done";
                if (task.status === 'overdue') badgeClass = "task-status-overdue";

                return `
                    <div class="calendar-task-item ${badgeClass}" data-task-id="${task.id}" title="#${task.number} ${task.title}">
                        <span class="task-status-dot"></span>
                        <span>#${task.number} ${task.title}</span>
                    </div>`;
            }).join('');

            gridContainer.innerHTML += `
                <a href="/calendar/date/${currentStrDate}" target="_blank" class="${cellClass}">
                    <span class="${numberClass}">${day}</span>
                    <div class="calendar-tasks-list">
                        ${htmlTasks}
                    </div>
                </a>`;
        }

        // 3. Hari kosong di akhir grid kalender
        const totalCellsUsed = startOffset + totalDaysInMonth;
        const remainingCells = 42 - totalCellsUsed; 
        for (let i = 1; i <= remainingCells; i++) {
            gridContainer.innerHTML += `
                <div class="calendar-day-cell muted-day">
                    <span class="calendar-day-number">${i}</span>
                </div>`;
        }
    }

    function handleSelectChange() {
        displayedMonth = parseInt(document.getElementById('monthSelect').value);
        displayedYear = parseInt(document.getElementById('yearSelect').value);
        renderCalendar(displayedYear, displayedMonth);
    }

    function changeMonth(direction) {
        displayedMonth += direction;
        if (displayedMonth > 11) {
            displayedMonth = 0;
            displayedYear++;
        } else if (displayedMonth < 0) {
            displayedMonth = 11;
            displayedYear--;
        }
        renderCalendar(displayedYear, displayedMonth);
    }

    // Fungsi Pencarian Client-Side
    function initSearchFilter() {
        const searchInput = document.getElementById('searchInput');
        searchInput.addEventListener('input', (e) => {
            const query = e.target.value.toLowerCase().trim();
            if (query === '') {
                taskData = [...originalTaskData];
            } else {
                taskData = originalTaskData.filter(t => 
                    t.number.toLowerCase().includes(query) || 
                    t.title.toLowerCase().includes(query)
                );
            }
            renderCalendar(displayedYear, displayedMonth);
        });
    }

    async function loadTasksAndRender() {
        try {
            const res = await fetch('/api/calendar-tasks');
            const data = await res.json();
            originalTaskData = data.map(t => ({ 
                id: t.id, 
                number: t.task_number ?? ('TGS-' + t.id), 
                title: t.title, 
                date: t.deadline, 
                status: t.status 
            }));
            taskData = [...originalTaskData];
        } catch (e) {
            console.error('Gagal memuat data tugas:', e);
        }
        renderCalendar(displayedYear, displayedMonth);
    }

    document.addEventListener("DOMContentLoaded", () => {
        populateYearDropdown();
        loadTasksAndRender();
        initSearchFilter();
        
        // Delegasi event click item tugas untuk memicu Detail Modal
        document.getElementById('calendar-grid').addEventListener('click', async (e) => {
            const el = e.target.closest('[data-task-id]');
            if (!el) return;
            e.preventDefault();
            e.stopPropagation();
            
            const id = el.getAttribute('data-task-id');
            try {
                const res = await fetch(`/api/tasks/${id}`);
                if (!res.ok) throw new Error('Not found');
                const detail = await res.json();
                showTaskModal(detail);
            } catch (err) {
                console.error('Gagal mengambil detail tugas:', err);
                alert('Gagal memuat detail tugas');
            }
        });
    });

    // Inisialisasi pembacaan query parameter tanggal jika dilempar dari komponen dashboard lain
    (function handleInitialDateParam(){
        const params = new URLSearchParams(window.location.search);
        const date = params.get('date');
        if (!date) return;
        try {
            const d = new Date(date);
            if (!isNaN(d)) {
                displayedYear = d.getFullYear();
                displayedMonth = d.getMonth();
                setTimeout(() => {
                    renderCalendar(displayedYear, displayedMonth);
                    window.open(`/calendar/date/${date}`, '_blank');
                }, 100);
            }
        } catch (e) {
            console.warn('Parameter tanggal tidak valid:', e);
        }
    })();

    // Modal Builder Helper
    function showTaskModal(detail) {
        let modal = document.getElementById('taskModal');
        if (!modal) {
            modal = document.createElement('div');
            modal.id = 'taskModal';
            modal.className = 'modal fade show';
            modal.style.display = 'none';
            modal.style.backgroundColor = 'rgba(0,0,0,0.5)';
            modal.innerHTML = `
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg rounded-3">
                        <div class="modal-header border-0 pb-0">
                            <h5 id="modalTitle" class="modal-title fw-bold text-dark"></h5>
                            <button id="closeModalBtn" type="button" class="btn-close" aria-label="Close"></button>
                        </div>
                        <div id="modalBody" class="modal-body text-secondary fs-6 py-4"></div>
                        <div class="modal-footer border-0 pt-0">
                            <a id="openTask" class="btn btn-primary rounded-pill px-4" href="#">Buka di Manajemen</a>
                        </div>
                    </div>
                </div>`;
            document.body.appendChild(modal);
            
            const close = () => {
                modal.style.display = 'none';
                modal.classList.remove('show');
            };
            document.getElementById('closeModalBtn').addEventListener('click', close);
            modal.addEventListener('click', (ev) => {
                if (ev.target === modal) close();
            });
        }

        document.getElementById('modalTitle').innerText = `#${detail.task_number ?? 'TGS'} ${detail.title}`;
        document.getElementById('modalBody').innerHTML = `
            <p class="mb-2"><strong>Penanggung Jawab:</strong> ${detail.assignee ?? '-'} </p>
            <p class="mb-2"><strong>Batas Waktu (Deadline):</strong> ${detail.deadline ?? '-'} </p>
            <p class="mb-3"><strong>Status:</strong> ${detail.status ?? '-'} </p>
            <div class="bg-light p-3 rounded-2 text-dark fs-7">${detail.description ?? 'Tidak ada deskripsi.'}</div>
        `;
        document.getElementById('openTask').href = `/tasks/${detail.id}`;
        modal.style.display = 'block';
        modal.classList.add('show');
    }
</script>
@endpush