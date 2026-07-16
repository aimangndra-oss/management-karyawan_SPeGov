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

    /* STYLE UNTUK HARI LIBUR / ACARA */
    .holiday-cell {
        background: #fff0f0 !important; 
    }
    .holiday-cell:hover {
        background: #ffe4e4 !important;
    }
    .holiday-number {
        color: #dc2626 !important; 
        background: #fee2e2;
    }
    .holiday-label {
        font-size: 0.65rem;
        font-weight: 700;
        color: #dc2626;
        margin-top: auto; 
        display: flex;
        align-items: center;
        gap: 0.25rem;
        justify-content: flex-end;
    }

    /* STYLE UNTUK HARI YANG ADA PEKERJAAN */
    .has-task-cell {
        box-shadow: inset 0 0 0 2px #22c55e !important;
        background: #f0fdf4; 
    }
    
    /* ANIMASI UNTUK DEADLINE TERLEWAT */
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

    {{-- Control Panel (Tampilan Baru: Lebih Besar & Super Rapi) --}}
    <div class="bg-white p-3 p-md-4 rounded-4 shadow-sm mb-4 border-0">
        <div class="d-flex flex-column flex-xl-row align-items-start align-items-xl-center justify-content-between gap-4">
            
            {{-- Navigasi Bulan, Tahun & Tombol --}}
            <div class="d-flex flex-wrap align-items-center gap-3">
                <div class="d-flex align-items-center gap-2">
                    {{-- Kotak Bulan (Diperbesar dengan min-width dan padding extra) --}}
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
                    
                    {{-- Kotak Tahun (Diperbesar) --}}
                    <select id="yearSelect" class="form-select fw-bold text-dark border-0 bg-light px-4 py-2 fs-6" style="min-width: 110px; cursor: pointer; border-radius: 0.75rem; box-shadow: none;" onchange="handleSelectChange()">
                    </select>
                </div>
                
                {{-- Tombol Kiri Kanan (Ikon sedikit dibesarkan ke fs-5) --}}
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

            {{-- Legend Lengkap (Dibungkus dalam kotak bg-light agar rapi setara) --}}
            <div class="d-flex flex-wrap align-items-center gap-3 gap-md-4 text-secondary bg-light px-4 py-3 rounded-4" style="font-size: 0.85rem;">
                
                {{-- Keterangan Kotak --}}
                <div class="d-flex flex-wrap align-items-center gap-3">
                    <div class="d-flex align-items-center gap-2">
                        <div style="width: 16px; height: 16px; border: 2px solid #22c55e; border-radius: 4px; background: #f0fdf4;"></div> 
                        <span class="fw-semibold text-nowrap">Ada Pekerjaan</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <div style="width: 16px; height: 16px; background: #fff0f0; border: 1px solid #fecaca; border-radius: 4px;"></div> 
                        <span class="fw-semibold text-nowrap">Libur / Acara</span>
                    </div>
                </div>

                {{-- Garis Pembatas (Sembunyi di HP, Tampil di Desktop) --}}
                <div class="vr bg-secondary opacity-25 d-none d-md-block" style="width: 2px; height: 20px;"></div>
                
                {{-- Keterangan Icon --}}
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
            {{-- Sabtu & Minggu diubah menjadi warna merah --}}
            <div class="text-danger">Sabtu</div>
            <div class="text-danger">Minggu</div>
        </div>

        <div id="calendar-grid" class="calendar-grid">
        </div>
    </div>

@endsection

@push('scripts')
<script>
    let taskData = [];
    let currentDate = new Date();
    let displayedYear = currentDate.getFullYear();
    let displayedMonth = currentDate.getMonth();

    const eventHolidays = {
        '2026-07-19': 'Tahun Baru Islam',
        '2026-08-17': 'HUT Kemerdekaan RI',
        '2026-12-25': 'Hari Raya Natal'
    };

    function populateYearDropdown() {
        const yearSelect = document.getElementById('yearSelect');
        const currentY = new Date().getFullYear();
        yearSelect.innerHTML = '';
        for (let y = currentY - 5; y <= currentY + 5; y++) {
            yearSelect.innerHTML += `<option value="${y}">${y}</option>`;
        }
    }

    function renderCalendar(year, month) {
        const gridContainer = document.getElementById('calendar-grid');
        const monthSelect = document.getElementById('monthSelect');
        const yearSelect = document.getElementById('yearSelect');
        gridContainer.innerHTML = ''; 

        monthSelect.value = month;
        yearSelect.value = year;

        let firstDayIndex = new Date(year, month, 1).getDay();
        let startOffset = firstDayIndex === 0 ? 6 : firstDayIndex - 1;

        const totalDaysInMonth = new Date(year, month + 1, 0).getDate();
        const totalDaysInPrevMonth = new Date(year, month, 0).getDate();

        for (let i = startOffset; i > 0; i--) {
            const dayNum = totalDaysInPrevMonth - i + 1;
            gridContainer.innerHTML += `
                <div class="calendar-day-cell muted-day">
                    <span class="calendar-day-number">${dayNum}</span>
                </div>`;
        }

        for (let day = 1; day <= totalDaysInMonth; day++) {
            const currentStrDate = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            const isToday = (new Date().toDateString() === new Date(year, month, day).toDateString());
            
            // Cek apakah hari ini adalah Hari Sabtu (6) atau Minggu (0)
            const dayOfWeek = new Date(year, month, day).getDay();
            const isWeekend = (dayOfWeek === 0 || dayOfWeek === 6);
            
            // Terapkan libur weekend atau libur acara khusus
            const holidayName = eventHolidays[currentStrDate] || (isWeekend ? 'Libur Akhir Pekan' : null);
            
            let cellClass = "calendar-day-cell";
            let numberClass = "calendar-day-number";

            let holidayHtml = '';
            if (holidayName) {
                cellClass += " holiday-cell";
                numberClass += " holiday-number";
                holidayHtml = `
                    <div class="holiday-label">
                        <i class="bi bi-calendar2-x-fill"></i> ${holidayName}
                    </div>`;
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

    async function loadTasksAndRender() {
        try {
            const res = await fetch('/api/calendar-tasks');
            const data = await res.json();
            taskData = data.map(t => ({ id: t.id, number: t.task_number ?? ('TGS-' + t.id), title: t.title, date: t.deadline, status: t.status }));
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
                console.error('Gagal ambil detail tugas', err);
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
            console.warn('Invalid date param', e);
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

        document.getElementById('modalTitle').innerText = `#${detail.task_number} ${detail.title}`;
        document.getElementById('modalBody').innerHTML = `
            <p class="mb-2"><i class="bi bi-person-fill text-primary me-2"></i><strong>PIC:</strong> ${detail.assignee ?? '-'} </p>
            <p class="mb-2"><i class="bi bi-calendar-event-fill text-danger me-2"></i><strong>Deadline:</strong> ${detail.deadline ?? '-'} </p>
            <p class="mb-3"><i class="bi bi-info-circle-fill text-info me-2"></i><strong>Status:</strong> ${detail.status ?? '-'} </p>
            <div class="bg-light p-3 rounded-3 text-dark fs-7 border" style="border-color: #e2e8f0 !important;">${detail.description ?? 'Tidak ada deskripsi.'}</div>
        `;
        const openTask = document.getElementById('openTask');
        openTask.href = `/tasks/${detail.id}`;
        modal.style.display = 'block';
        modal.classList.add('show');
    }
</script>
@endpush