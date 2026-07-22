@extends('layouts.app')

@section('title', 'Kalender Deadline')

@section('content')

{{-- Desain CSS Khusus untuk Kalender Premium --}}
<style>
    .calendar-wrapper {
        background: #ffffff;
        border-radius: 1rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        overflow: hidden;
        border: 1px solid #e2e8f0;
    }
    .calendar-header-days {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        background: #f8fafc;
        text-align: center;
        font-weight: 700;
        font-size: 0.85rem;
        color: #64748b;
        padding: 1rem 0;
        border-bottom: 1px solid #e2e8f0;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .calendar-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        background: #e2e8f0; 
        gap: 1px; 
    }
    .calendar-day-cell {
        background: #ffffff;
        min-height: 140px; 
        padding: 0.75rem;
        text-decoration: none !important;
        color: inherit;
        transition: all 0.2s ease-in-out;
        display: flex;
        flex-direction: column;
    }
    .calendar-day-cell:hover {
        background: #f8fafc;
        cursor: pointer;
    }
    .calendar-day-number {
        font-weight: 600;
        font-size: 1rem;
        color: #334155;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        transition: all 0.2s;
    }
    
    /* STYLE UNTUK HARI INI */
    .today-cell {
        background: #eff6ff !important; 
    }
    .today-number {
        background: #3b82f6;
        color: #ffffff;
        box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.5);
    }

    /* 1. AKHIR PEKAN (SABTU & MINGGU BIASA) - MERAH */
    .weekend-cell {
        background: #fff1f2 !important; 
    }
    .weekend-cell:hover {
        background: #ffe4e6 !important;
    }
    .weekend-number {
        color: #e11d48 !important; 
        background: #fecdd3;
    }
    .weekend-label {
        font-size: 0.65rem;
        font-weight: 700;
        color: #e11d48;
        margin-top: auto; 
        display: flex;
        align-items: center;
        gap: 0.25rem;
        justify-content: flex-end;
        text-align: right;
    }

    /* 2. HARI LIBUR NASIONAL / HARI BESAR - UNGU */
    .holiday-cell {
        background: #f5f3ff !important; 
    }
    .holiday-cell:hover {
        background: #ede9fe !important;
    }
    .holiday-number {
        color: #6d28d9 !important; 
        background: #ddd6fe;
    }
    .holiday-label {
        font-size: 0.65rem;
        font-weight: 700;
        color: #6d28d9;
        margin-top: auto; 
        display: flex;
        align-items: center;
        gap: 0.25rem;
        justify-content: flex-end;
        text-align: right;
    }

    /* 3. CUTI BERSAMA - AMBER / ORANGE */
    .leave-cell {
        background: #fffbeb !important;
    }
    .leave-cell:hover {
        background: #fef3c7 !important;
    }
    .leave-number {
        color: #d97706 !important;
        background: #fde68a;
    }
    .leave-label {
        font-size: 0.65rem;
        font-weight: 700;
        color: #d97706;
        margin-top: auto;
        display: flex;
        align-items: center;
        gap: 0.25rem;
        justify-content: flex-end;
        text-align: right;
    }

    /* STYLE PEKERJAAN */
    .has-task-cell {
        box-shadow: inset 0 0 0 2px #22c55e !important;
        background: #f0fdf4; 
    }
    
    @keyframes pulse-danger {
        0% { transform: scale(1); }
        50% { transform: scale(1.25); color: #b91c1c; }
        100% { transform: scale(1); }
    }
    .icon-overdue-alert {
        animation: pulse-danger 1.5s infinite;
    }
    
    .muted-day {
        background: #fcfcfc;
    }
    .muted-day .calendar-day-number {
        color: #cbd5e1;
    }
    
    .calendar-tasks-list {
        display: flex;
        flex-direction: column;
        gap: 0.35rem;
        margin-top: 0.5rem;
    }
    .calendar-task-item {
        font-size: 0.75rem;
        padding: 0.35rem 0.5rem;
        border-radius: 0.375rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        display: flex;
        align-items: center;
        gap: 0.4rem;
        font-weight: 600;
        transition: transform 0.1s;
    }
    .calendar-task-item:hover {
        transform: scale(1.02);
    }
    .task-status-done { background: #dcfce7; color: #166534; border: 1px solid #bbf7d0; }
    .task-status-overdue { background: #fee2e2; color: #991b1b; border: 1px solid #fecaca; }
    .task-status-upcoming { background: #dbeafe; color: #1e40af; border: 1px solid #bfdbfe; }
</style>

    {{-- Control Panel --}}
    <div class="bg-white p-3 p-md-4 rounded-4 shadow-sm mb-4 border-0">
        <div class="d-flex flex-column flex-xl-row align-items-start align-items-xl-center justify-content-between gap-4">
            
            {{-- Navigasi Bulan & Tahun --}}
            <div class="d-flex flex-wrap align-items-center gap-3">
                <div class="d-flex align-items-center gap-2">
                    <select id="monthSelect" class="form-select fw-bold text-dark border-0 bg-light px-4 py-2 fs-6" style="min-width: 150px; cursor: pointer; border-radius: 0.75rem; box-shadow: none;" onchange="handleSelectChange()">
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
                    
                    <select id="yearSelect" class="form-select fw-bold text-dark border-0 bg-light px-4 py-2 fs-6" style="min-width: 110px; cursor: pointer; border-radius: 0.75rem; box-shadow: none;" onchange="handleSelectChange()">
                    </select>
                </div>
                
                <div class="d-flex align-items-center border rounded-pill bg-white shadow-sm" style="padding: 4px;">
                    <button onclick="changeMonth(-1)" class="btn btn-sm border-0 text-primary fs-5 px-3 rounded-pill" aria-label="Previous Month">
                        <i class="bi bi-chevron-left"></i>
                    </button>
                    <div class="vr bg-secondary opacity-10" style="width: 2px; height: 24px;"></div>
                    <button onclick="changeMonth(1)" class="btn btn-sm border-0 text-primary fs-5 px-3 rounded-pill" aria-label="Next Month">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </div>
            </div>

            {{-- Legend --}}
            <div class="d-flex flex-wrap align-items-center gap-3 gap-md-4 text-secondary bg-light px-4 py-3 rounded-4" style="font-size: 0.85rem;">
                <div class="d-flex flex-wrap align-items-center gap-3">
                    <div class="d-flex align-items-center gap-2">
                        <div style="width: 16px; height: 16px; border: 2px solid #22c55e; border-radius: 4px; background: #f0fdf4;"></div> 
                        <span class="fw-semibold text-nowrap">Ada Pekerjaan</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <div style="width: 16px; height: 16px; background: #fff1f2; border: 1px solid #fecdd3; border-radius: 4px;"></div> 
                        <span class="fw-semibold text-nowrap">Akhir Pekan</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <div style="width: 16px; height: 16px; background: #f5f3ff; border: 1px solid #ddd6fe; border-radius: 4px;"></div> 
                        <span class="fw-semibold text-nowrap">Hari Besar</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <div style="width: 16px; height: 16px; background: #fffbeb; border: 1px solid #fde68a; border-radius: 4px;"></div> 
                        <span class="fw-semibold text-nowrap">Cuti Bersama</span>
                    </div>
                </div>

                <div class="vr bg-secondary opacity-25 d-none d-md-block" style="width: 2px; height: 20px;"></div>
                
                <div class="d-flex flex-wrap align-items-center gap-3">
                    <div class="d-flex align-items-center gap-2" title="Ada Deadline Terlewat">
                        <i class="bi bi-exclamation-triangle-fill text-danger fs-5"></i> <span class="fw-semibold text-nowrap">Overdue</span>
                    </div>
                    <div class="d-flex align-items-center gap-2" title="Mendekati Deadline">
                        <i class="bi bi-hourglass-bottom text-warning fs-5"></i> <span class="fw-semibold text-nowrap">Dekat Deadline</span>
                    </div>
                    <div class="d-flex align-items-center gap-2" title="Semua Pekerjaan Selesai">
                        <i class="bi bi-briefcase-fill text-success fs-5"></i> <span class="fw-semibold text-nowrap">Selesai</span>
                    </div>
                </div>

            </div>

        </div>
    </div>

    {{-- Calendar Grid --}}
    <div class="calendar-wrapper">
        <div class="calendar-header-days">
            <div>Senin</div>
            <div>Selasa</div>
            <div>Rabu</div>
            <div>Kamis</div>
            <div>Jumat</div>
            <div class="text-danger">Sabtu</div>
            <div class="text-danger">Minggu</div>
        </div>

        <div id="calendar-grid" class="calendar-grid">
        </div>
    </div>

    {{-- Fixed Footer --}}
    <footer class="bg-white p-3 text-center shadow-sm border-top border-light" 
            style="position: fixed; bottom: 0; right: 0; left: var(--sidebar-width, 260px); width: calc(100% - var(--sidebar-width, 260px)); z-index: 999;">
        <small class="text-muted" style="font-size: 11px; letter-spacing: 0.5px; display: block; font-family: sans-serif;">
            &copy; {{ now()->year }} Pemerintah Kabupaten Cirebon &mdash; Dinas Komunikasi dan Informatika &mdash; Bidang Statistika Persandian dan E-Government.
        </small>
    </footer>

@endsection

@push('scripts')
<script>
    let originalTaskData = []; 
    let taskData = [];
    let currentDate = new Date();
    let displayedYear = currentDate.getFullYear();
    let displayedMonth = currentDate.getMonth();

    // 1. HARI LIBUR TETAP (Pasti Sama di Setiap Tahun)
    const FIXED_HOLIDAYS = {
        '01-01': { name: 'Tahun Baru Masehi', type: 'national' },
        '05-01': { name: 'Hari Buruh Internasional', type: 'national' },
        '06-01': { name: 'Hari Lahir Pancasila', type: 'national' },
        '08-17': { name: 'Hari Kemerdekaan RI', type: 'national' },
        '12-25': { name: 'Hari Raya Natal', type: 'national' }
    };

    // 2. DATABASE DATABASE HARI LIBUR BERGERAK & CUTI BERSAMA LENGKAP (2023 - 2028)
    const HOLIDAY_DATABASE = {
        // TAHUN 2024
        '2024-01-08': { name: 'Isra Mikraj Nabi Muhammad SAW', type: 'national' },
        '2024-02-09': { name: 'Cuti Bersama Imlek', type: 'leave' },
        '2024-02-10': { name: 'Tahun Baru Imlek 2575', type: 'national' },
        '2024-03-11': { name: 'Hari Raya Nyepi 1946', type: 'national' },
        '2024-03-12': { name: 'Cuti Bersama Nyepi', type: 'leave' },
        '2024-03-29': { name: 'Wafat Yesus Kristus', type: 'national' },
        '2024-03-31': { name: 'Hari Paskah', type: 'national' },
        '2024-04-08': { name: 'Cuti Bersama Idul Fitri', type: 'leave' },
        '2024-04-09': { name: 'Cuti Bersama Idul Fitri', type: 'leave' },
        '2024-04-10': { name: 'Hari Raya Idul Fitri 1445 H', type: 'national' },
        '2024-04-11': { name: 'Hari Raya Idul Fitri 1445 H', type: 'national' },
        '2024-04-12': { name: 'Cuti Bersama Idul Fitri', type: 'leave' },
        '2024-04-15': { name: 'Cuti Bersama Idul Fitri', type: 'leave' },
        '2024-05-09': { name: 'Kenaikan Yesus Kristus', type: 'national' },
        '2024-05-10': { name: 'Cuti Bersama Kenaikan Yesus Kristus', type: 'leave' },
        '2024-05-23': { name: 'Hari Raya Waisak 2568', type: 'national' },
        '2024-05-24': { name: 'Cuti Bersama Waisak', type: 'leave' },
        '2024-06-17': { name: 'Hari Raya Idul Adha 1445 H', type: 'national' },
        '2024-06-18': { name: 'Cuti Bersama Idul Adha', type: 'leave' },
        '2024-07-07': { name: 'Tahun Baru Islam 1446 H', type: 'national' },
        '2024-09-16': { name: 'Maulid Nabi Muhammad SAW', type: 'national' },
        '2024-12-26': { name: 'Cuti Bersama Natal', type: 'leave' },

        // TAHUN 2025
        '2025-01-27': { name: 'Isra Mikraj Nabi Muhammad SAW', type: 'national' },
        '2025-01-28': { name: 'Cuti Bersama Imlek', type: 'leave' },
        '2025-01-29': { name: 'Tahun Baru Imlek 2576', type: 'national' },
        '2025-03-28': { name: 'Cuti Bersama Nyepi', type: 'leave' },
        '2025-03-29': { name: 'Hari Raya Nyepi 1947', type: 'national' },
        '2025-03-31': { name: 'Hari Raya Idul Fitri 1446 H', type: 'national' },
        '2025-04-01': { name: 'Hari Raya Idul Fitri 1446 H', type: 'national' },
        '2025-04-02': { name: 'Cuti Bersama Idul Fitri', type: 'leave' },
        '2025-04-03': { name: 'Cuti Bersama Idul Fitri', type: 'leave' },
        '2025-04-04': { name: 'Cuti Bersama Idul Fitri', type: 'leave' },
        '2025-04-07': { name: 'Cuti Bersama Idul Fitri', type: 'leave' },
        '2025-04-18': { name: 'Wafat Yesus Kristus', type: 'national' },
        '2025-04-20': { name: 'Hari Paskah', type: 'national' },
        '2025-05-12': { name: 'Hari Raya Waisak 2569', type: 'national' },
        '2025-05-13': { name: 'Cuti Bersama Waisak', type: 'leave' },
        '2025-05-29': { name: 'Kenaikan Yesus Kristus', type: 'national' },
        '2025-05-30': { name: 'Cuti Bersama Kenaikan Yesus Kristus', type: 'leave' },
        '2025-06-06': { name: 'Hari Raya Idul Adha 1446 H', type: 'national' },
        '2025-06-09': { name: 'Cuti Bersama Idul Adha', type: 'leave' },
        '2025-06-27': { name: 'Tahun Baru Islam 1447 H', type: 'national' },
        '2025-09-05': { name: 'Maulid Nabi Muhammad SAW', type: 'national' },
        '2025-12-26': { name: 'Cuti Bersama Natal', type: 'leave' },

        // TAHUN 2026
        '2026-01-16': { name: 'Isra Mikraj Nabi Muhammad SAW', type: 'national' },
        '2026-02-16': { name: 'Cuti Bersama Imlek', type: 'leave' },
        '2026-02-17': { name: 'Tahun Baru Imlek 2577', type: 'national' },
        '2026-03-18': { name: 'Cuti Bersama Nyepi', type: 'leave' },
        '2026-03-19': { name: 'Hari Raya Nyepi 1948', type: 'national' },
        '2026-03-20': { name: 'Hari Raya Idul Fitri 1447 H', type: 'national' },
        '2026-03-21': { name: 'Hari Raya Idul Fitri 1447 H', type: 'national' },
        '2026-03-23': { name: 'Cuti Bersama Idul Fitri', type: 'leave' },
        '2026-03-24': { name: 'Cuti Bersama Idul Fitri', type: 'leave' },
        '2026-03-25': { name: 'Cuti Bersama Idul Fitri', type: 'leave' },
        '2026-04-03': { name: 'Wafat Yesus Kristus', type: 'national' },
        '2026-04-05': { name: 'Hari Kebangkitan Yesus Kristus (Paskah)', type: 'national' },
        '2026-05-14': { name: 'Kenaikan Yesus Kristus', type: 'national' },
        '2026-05-15': { name: 'Cuti Bersama Kenaikan Yesus Kristus', type: 'leave' },
        '2026-05-27': { name: 'Hari Raya Idul Adha 1447 H', type: 'national' },
        '2026-05-28': { name: 'Cuti Bersama Idul Adha', type: 'leave' },
        '2026-05-31': { name: 'Hari Raya Waisak 2570', type: 'national' },
        '2026-06-01': { name: 'Cuti Bersama Waisak', type: 'leave' },
        '2026-06-16': { name: 'Tahun Baru Islam 1448 H', type: 'national' },
        '2026-08-25': { name: 'Maulid Nabi Muhammad SAW', type: 'national' },
        '2026-12-24': { name: 'Cuti Bersama Natal', type: 'leave' },

        // TAHUN 2027
        '2027-01-05': { name: 'Isra Mikraj Nabi Muhammad SAW', type: 'national' },
        '2027-02-06': { name: 'Tahun Baru Imlek 2578', type: 'national' },
        '2027-03-08': { name: 'Hari Raya Nyepi 1949', type: 'national' },
        '2027-03-09': { name: 'Hari Raya Idul Fitri 1448 H', type: 'national' },
        '2027-03-10': { name: 'Hari Raya Idul Fitri 1448 H', type: 'national' },
        '2027-03-26': { name: 'Wafat Yesus Kristus', type: 'national' },
        '2027-05-06': { name: 'Kenaikan Yesus Kristus', type: 'national' },
        '2027-05-16': { name: 'Hari Raya Idul Adha 1448 H', type: 'national' },
        '2027-05-20': { name: 'Hari Raya Waisak 2571', type: 'national' },
        '2027-06-06': { name: 'Tahun Baru Islam 1449 H', type: 'national' },
        '2027-08-15': { name: 'Maulid Nabi Muhammad SAW', type: 'national' },
        '2027-12-24': { name: 'Cuti Bersama Natal', type: 'leave' }
    };

    function getHolidayInfo(year, month, day) {
        const mm = String(month + 1).padStart(2, '0');
        const dd = String(day).padStart(2, '0');
        const fullDateStr = `${year}-${mm}-${dd}`;
        const monthDayStr = `${mm}-${dd}`;

        // 1. Prioritas 1: Database Libur Spesifik & Cuti Bersama (YYYY-MM-DD)
        if (HOLIDAY_DATABASE[fullDateStr]) {
            return HOLIDAY_DATABASE[fullDateStr];
        }

        // 2. Prioritas 2: Libur Tetap Tahunan (MM-DD)
        if (FIXED_HOLIDAYS[monthDayStr]) {
            return FIXED_HOLIDAYS[monthDayStr];
        }

        // 3. Prioritas 3: Akhir Pekan (Sabtu & Minggu Otomatis di SEMUA TAHUN)
        const dateObj = new Date(year, month, day);
        const dayOfWeek = dateObj.getDay();
        if (dayOfWeek === 0 || dayOfWeek === 6) {
            return { name: 'Akhir Pekan', type: 'weekend' };
        }

        return null;
    }

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
        const monthSelect = document.getElementById('monthSelect');
        const yearSelect = document.getElementById('yearSelect');
        
        monthSelect.value = month;
        yearSelect.value = year;

        gridContainer.innerHTML = ''; 

        // Logika Geser Hari: Minggu = 0 -> dikonversi agar Senin = Kolom 0
        const rawFirstDay = new Date(year, month, 1).getDay();
        const startOffset = (rawFirstDay === 0) ? 6 : rawFirstDay - 1;

        const totalDaysInMonth = new Date(year, month + 1, 0).getDate();
        const totalDaysInPrevMonth = new Date(year, month, 0).getDate();

        // Hari Pudar (Bulan Lalu)
        for (let i = startOffset; i > 0; i--) {
            const dayNum = totalDaysInPrevMonth - i + 1;
            gridContainer.innerHTML += `
                <div class="calendar-day-cell muted-day">
                    <span class="calendar-day-number">${dayNum}</span>
                </div>`;
        }

        // Hari Utama (Bulan Aktif)
        for (let day = 1; day <= totalDaysInMonth; day++) {
            const currentStrDate = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            const isToday = (new Date().toDateString() === new Date(year, month, day).toDateString());

            const holidayInfo = getHolidayInfo(year, month, day);

            let cellClass = "calendar-day-cell";
            let numberClass = "calendar-day-number";
            let holidayHtml = '';

            if (holidayInfo) {
                if (holidayInfo.type === 'leave') {
                    cellClass += " leave-cell";
                    numberClass += " leave-number";
                    holidayHtml = `
                        <div class="leave-label">
                            <i class="bi bi-airplane-fill"></i> ${holidayInfo.name}
                        </div>`;
                } else if (holidayInfo.type === 'national') {
                    cellClass += " holiday-cell";
                    numberClass += " holiday-number";
                    holidayHtml = `
                        <div class="holiday-label">
                            <i class="bi bi-star-fill"></i> ${holidayInfo.name}
                        </div>`;
                } else if (holidayInfo.type === 'weekend') {
                    cellClass += " weekend-cell";
                    numberClass += " weekend-number";
                    holidayHtml = `
                        <div class="weekend-label">
                            <i class="bi bi-calendar2-x-fill"></i> ${holidayInfo.name}
                        </div>`;
                }
            }

            if (isToday) {
                cellClass += " today-cell";
                numberClass += " today-number";
            }

            const dayTasks = taskData.filter(t => t.date === currentStrDate);
            let htmlTasks = '';
            let taskIconHeader = '';
            
            let hasOverdue = false;
            let hasUpcoming = false;
            let hasDone = false;

            if (dayTasks.length > 0) {
                cellClass += " has-task-cell";
                
                dayTasks.forEach(task => {
                    let badgeClass = "";
                    let iconHtml = "";
                    
                    if (task.status === 'selesai') {
                        hasDone = true;
                        badgeClass = "task-status-done";
                        iconHtml = '<i class="bi bi-check-circle-fill"></i>';
                    } else if (task.status === 'overdue') {
                        hasOverdue = true;
                        badgeClass = "task-status-overdue";
                        iconHtml = '<i class="bi bi-exclamation-circle-fill"></i>';
                    } else {
                        hasUpcoming = true;
                        badgeClass = "task-status-upcoming";
                        iconHtml = '<i class="bi bi-clock-fill"></i>';
                    }

                    htmlTasks += `
                        <div class="calendar-task-item ${badgeClass}" data-task-id="${task.id}" title="#${task.number} ${task.title}">
                            ${iconHtml} <span class="text-truncate">${task.title}</span>
                        </div>`;
                });

                if (hasOverdue) {
                    taskIconHeader = `<i class="bi bi-exclamation-triangle-fill text-danger fs-5 ms-auto icon-overdue-alert" title="Ada Deadline Terlewat!"></i>`;
                } else if (hasUpcoming) {
                    taskIconHeader = `<i class="bi bi-hourglass-bottom text-warning fs-5 ms-auto" title="Mendekati Deadline!"></i>`;
                } else if (hasDone) {
                    taskIconHeader = `<i class="bi bi-briefcase-fill text-success fs-5 ms-auto" title="Semua Pekerjaan Selesai"></i>`;
                }
            }

            gridContainer.innerHTML += `
                <div class="${cellClass}" onclick="window.open('/calendar/date/${currentStrDate}', '_blank')">
                    <div class="d-flex justify-content-between align-items-start">
                        <span class="${numberClass}">${day}</span>
                        ${taskIconHeader}
                    </div>
                    <div class="calendar-tasks-list">
                        ${htmlTasks}
                    </div>
                    ${holidayHtml}
                </div>`;
        }

        // Hari Pudar (Bulan Depan)
        const totalCellsUsed = startOffset + totalDaysInMonth;
        const totalGridCells = Math.ceil(totalCellsUsed / 7) * 7; 
        const remainingCells = totalGridCells - totalCellsUsed;

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
                    <div class="modal-content border-0 shadow-lg rounded-4">
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
            <p class="mb-2"><i class="bi bi-person-fill text-primary me-2"></i><strong>PIC:</strong> ${detail.assignee ?? '-'} </p>
            <p class="mb-2"><i class="bi bi-calendar-event-fill text-danger me-2"></i><strong>Deadline:</strong> ${detail.deadline ?? '-'} </p>
            <p class="mb-3"><i class="bi bi-info-circle-fill text-info me-2"></i><strong>Status:</strong> ${detail.status ?? '-'} </p>
            <div class="bg-light p-3 rounded-3 text-dark fs-7 border" style="border-color: #e2e8f0 !important;">${detail.description ?? 'Tidak ada deskripsi.'}</div>
        `;
        document.getElementById('openTask').href = `/tasks/${detail.id}`;
        modal.style.display = 'block';
        modal.classList.add('show');
    }
</script>
@endpush