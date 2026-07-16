@extends('layouts.app')

@section('title', 'Kalender Deadline')

@section('content')

    {{-- Control Panel --}}
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 bg-white p-4 rounded-3 border border-light shadow-sm mb-4">
        
        {{-- Bagian Header Navigasi Kalender --}}
        <div class="d-flex align-items-center gap-3">
            
            <div class="d-flex align-items-center gap-2">
                <select id="monthSelect" class="form-select fw-bold text-dark border-0 bg-light px-5     py-2" style="width: auto; cursor: pointer; border-radius: 0.75rem;" onchange="handleSelectChange()">
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
                
                <select id="yearSelect" class="form-select fw-bold text-dark border-0 bg-light px-5 py-2" style="width: auto; cursor: pointer; border-radius: 0.75rem;" onchange="handleSelectChange()">
                    {{-- Akan diisi otomatis melalui JavaScript --}}
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

        {{-- Bagian Keterangan Warna (Legend) --}}
        <div class="d-flex align-items-center gap-4 text-xs font-medium text-secondary">
            <div class="d-flex align-items-center gap-2"><span class="badge rounded-circle bg-success p-1">&nbsp;</span> Selesai</div>
            <div class="d-flex align-items-center gap-2"><span class="badge rounded-circle bg-danger p-1">&nbsp;</span> Lewat Deadline</div>
            <div class="d-flex align-items-center gap-2"><span class="badge rounded-circle bg-primary p-1">&nbsp;</span> Akan Datang</div>
        </div>
    </div>

    {{-- Calendar Grid --}}
    <div class="calendar-container">
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
            {{-- Generated dynamically via JS --}}
        </div>
    </div>

@endsection

@push('scripts')
<script>
    // data awal kosong, akan diisi lewat API
    let taskData = [];

    // Gunakan tanggal sistem sebagai default
    let currentDate = new Date();
    let displayedYear = currentDate.getFullYear();
    let displayedMonth = currentDate.getMonth();

    // Fungsi untuk mengisi pilihan dropdown tahun (5 tahun ke belakang dan 5 tahun ke depan)
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
        gridContainer.innerHTML = ''; // Reset isi grid sebelumnya

        // Sinkronisasi nilai Dropdown dengan bulan & tahun yang sedang dirender
        monthSelect.value = month;
        yearSelect.value = year;

        // Cari tahu hari pertama di bulan ini (0 = Minggu, 1 = Senin, dst)
        let firstDayIndex = new Date(year, month, 1).getDay();
        // Konversi agar index dimulai dari Senin (0 = Senin, 6 = Minggu)
        let startOffset = firstDayIndex === 0 ? 6 : firstDayIndex - 1;

        // Hitung jumlah hari di bulan aktif dan bulan sebelumnya
        const totalDaysInMonth = new Date(year, month + 1, 0).getDate();
        const totalDaysInPrevMonth = new Date(year, month, 0).getDate();

        // 1. Sisa hari dari bulan sebelumnya (Muted Day)
        for (let i = startOffset; i > 0; i--) {
            const dayNum = totalDaysInPrevMonth - i + 1;
            gridContainer.innerHTML += `
                <div class="calendar-day-cell muted-day">
                    <span class="calendar-day-number">${dayNum}</span>
                </div>`;
        }

        // 2. Mengisi tanggal di bulan aktif
        for (let day = 1; day <= totalDaysInMonth; day++) {
            const currentStrDate = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            const isToday = (new Date().toDateString() === new Date(year, month, day).toDateString());
            
            let cellClass = "calendar-day-cell";
            let numberClass = "calendar-day-number";

            if (isToday) {
                cellClass += " today-cell";
                numberClass += " today-number";
            }

            // Cari data tugas yang jatuh pada tanggal ini
            const dayTasks = taskData.filter(t => t.date === currentStrDate);
            let htmlTasks = '';

            dayTasks.forEach(task => {
                let badgeClass = "";
                let dotColor = "";
                
                if (task.status === 'selesai') {
                    badgeClass = "task-status-done";
                    dotColor = "🟢";
                } else if (task.status === 'overdue') {
                    badgeClass = "task-status-overdue";
                    dotColor = "🔴";
                } else {
                    badgeClass = "task-status-upcoming";
                    dotColor = "🔵";
                }

                htmlTasks += `
                    <div class="calendar-task-item ${badgeClass}" data-task-id="${task.id}" title="#${task.number} ${task.title}">
                        ${dotColor} #${task.number} ${task.title}
                    </div>`;
            });

            gridContainer.innerHTML += `
                <a href="/calendar/date/${currentStrDate}" target="_blank" class="${cellClass}">
                    <span class="${numberClass}">${day}</span>
                    <div class="calendar-tasks-list">
                        ${htmlTasks}
                    </div>
                </a>`;
        }

        // 3. Mengisi sisa slot grid dengan awal bulan berikutnya
        const totalCellsUsed = startOffset + totalDaysInMonth;
        const remainingCells = 42 - totalCellsUsed; // Standard 6 baris x 7 kolom = 42 kotak
        for (let i = 1; i <= remainingCells; i++) {
            gridContainer.innerHTML += `
                <div class="calendar-day-cell muted-day">
                    <span class="calendar-day-number">${i}</span>
                </div>`;
        }
    }

    // Fungsi yang dipanggil saat user mengubah Dropdown Bulan atau Tahun
    function handleSelectChange() {
        displayedMonth = parseInt(document.getElementById('monthSelect').value);
        displayedYear = parseInt(document.getElementById('yearSelect').value);
        renderCalendar(displayedYear, displayedMonth);
    }

    // Fungsi kontrol dari panah Kiri / Kanan
    function changeMonth(direction) {
        displayedMonth += direction;
        if (displayedMonth > 11) {
            displayedMonth = 0;
            displayedYear++;
        } else if (displayedMonth < 0) {
            displayedMonth = 11;
            displayedYear--;
        }
        
        // Render ulang (nilai dropdown akan diupdate di dalam renderCalendar)
        renderCalendar(displayedYear, displayedMonth);
    }

    // Ambil data tugas dari API dan render kalender
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

    // Jalankan fungsi saat halaman pertama kali dibuka
    document.addEventListener("DOMContentLoaded", () => {
        populateYearDropdown(); // Panggil fungsi isi dropdown tahun
        loadTasksAndRender();
        
        // Delegated click handler: buka modal detail ketika klik kartu tugas
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

    // Jika query param 'date' diberikan, fokus ke bulan tersebut
    (function handleInitialDateParam(){
        const params = new URLSearchParams(window.location.search);
        const date = params.get('date');
        if (!date) return;
        try {
            const d = new Date(date);
            if (!isNaN(d)) {
                displayedYear = d.getFullYear();
                displayedMonth = d.getMonth();
                // Tunggu DOM load untuk pastikan dropdown ada, bisa diletakkan di dalam render
                setTimeout(() => {
                    renderCalendar(displayedYear, displayedMonth);
                    window.open(`/calendar/date/${date}`, '_blank');
                }, 100);
            }
        } catch (e) {
            console.warn('Invalid date param', e);
        }
    })();

    // Modal helper
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

        document.getElementById('modalTitle').innerText = `#${detail.task_number} ${detail.title}`;
        document.getElementById('modalBody').innerHTML = `
            <p class="mb-2"><strong>Penanggung Jawab:</strong> ${detail.assignee ?? '-'} </p>
            <p class="mb-2"><strong>Batas Waktu (Deadline):</strong> ${detail.deadline ?? '-'} </p>
            <p class="mb-3"><strong>Status:</strong> ${detail.status ?? '-'} </p>
            <div class="bg-light p-3 rounded-2 text-dark fs-7">${detail.description ?? 'Tidak ada deskripsi.'}</div>
        `;
        const openTask = document.getElementById('openTask');
        openTask.href = `/tasks/${detail.id}`;
        modal.style.display = 'block';
        modal.classList.add('show');
    }
</script>
@endpush