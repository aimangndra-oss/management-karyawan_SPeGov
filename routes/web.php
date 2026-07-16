<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/ui-dashboard');

// Temporary view-only route for UI testing (no auth) - remove when integrating backend
Route::view('/ui-dashboard', 'dashboard')->name('ui.dashboard');

// API and Calendar Date routes have been moved to auth middleware group for security

// UI view for calendar (no auth) - quick preview
Route::view('/ui-calendar', 'calendar')->name('ui.calendar');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
|
| Route yang membutuhkan login. Semua role (Kabid & Staff) dapat mengakses.
|
*/
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard - Accessible oleh semua role yang sudah login
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Kalender Deadline - Accessible oleh semua role yang sudah login
    Route::get('/calendar', function () {
        return view('calendar');
    })->name('calendar');

    // Manajemen Tugas CRUD (Akses dibatasi sesuai Policy di dalam Controller)
    Route::resource('tasks', TaskController::class);

    // Tambah Progres Timeline Tugas
    Route::post('/tasks/{task}/timeline', [TaskController::class, 'storeTimeline'])->name('tasks.timeline.store');

    // Update Progres Timeline Tugas
    Route::put('/tasks/{task}/timeline/{timeline}', [TaskController::class, 'updateTimeline'])->name('tasks.timeline.update');

    // Hapus Progres Timeline Tugas
    Route::delete('/tasks/{task}/timeline/{timeline}', [TaskController::class, 'destroyTimeline'])->name('tasks.timeline.destroy');

    // Upload Dokumentasi Tugas
    Route::post('/tasks/{task}/document', [TaskController::class, 'storeDocument'])->name('tasks.document.store');

    // Download Dokumentasi Tugas
    Route::get('/documents/{document}/download', [TaskController::class, 'downloadDocument'])->name('documents.download');

    // API: expose tasks for calendar (scoped to role)
    Route::get('/api/calendar-tasks', function () {
        $today = now()->toDateString();
        $query = \App\Models\Task::query();
        
        if (auth()->user()->role === \App\Enums\UserRole::STAFF) {
            $query->where('user_id', auth()->id());
        }

        $tasks = $query->select('id', 'task_number', 'title', 'deadline', 'status')->get()->map(function ($t) use ($today) {
            $deadline = $t->deadline?->toDateString();
            $status = $t->status;
            
            // Check status correctly using both enum object or value check
            $statusValue = $status instanceof \App\Enums\TaskStatus ? $status->value : $status;

            if ($statusValue === 'done') {
                $calStatus = 'selesai';
            } elseif ($deadline && $deadline < $today) {
                $calStatus = 'overdue';
            } else {
                $calStatus = 'upcoming';
            }

            return [
                'id' => $t->id,
                'task_number' => $t->task_number,
                'title' => $t->title,
                'deadline' => $deadline,
                'status' => $calStatus,
            ];
        });

        return response()->json($tasks);
    });

    // API: task detail by id (checked against TaskPolicy)
    Route::get('/api/tasks/{id}', function ($id) {
        $t = \App\Models\Task::find($id);
        if (! $t) {
            return response()->json(['error' => 'Not found'], 404);
        }

        if (auth()->user()->cannot('view', $t)) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return response()->json([
            'id' => $t->id,
            'task_number' => $t->task_number,
            'title' => $t->title,
            'description' => $t->description,
            'assignee' => $t->assignee?->name,
            'deadline' => $t->deadline?->toDateString(),
            'status' => $t->status,
            'priority' => $t->priority,
            'progress_percentage' => $t->progress_percentage,
        ]);
    });

    // Web view: daftar tugas pada tanggal tertentu (scoped to role)
    Route::get('/calendar/date/{date}', function ($date) {
        $query = \App\Models\Task::whereDate('deadline', $date);
        
        if (auth()->user()->role === \App\Enums\UserRole::STAFF) {
            $query->where('user_id', auth()->id());
        }

        $tasks = $query->get()->map(function ($t) {
            return [
                'id' => $t->id,
                'task_number' => $t->task_number,
                'title' => $t->title,
                'assignee' => $t->assignee?->name,
                'deadline' => $t->deadline?->toDateString(),
                'status' => $t->status,
            ];
        });

        return view('tasks_by_date', ['date' => $date, 'tasks' => $tasks]);
    });

});

/*
|--------------------------------------------------------------------------
| Kabid Only Routes
|--------------------------------------------------------------------------
|
| Route yang hanya bisa diakses oleh Kepala Bidang.
| Fitur-fitur ini akan diimplementasikan pada tahap selanjutnya.
|
*/
Route::middleware(['auth', 'verified', 'role:kabid'])->group(function () {

    // Manajemen Pegawai (Tahap 13)
    // Manajemen Tugas - Create/Delete (Tahap 14)
    // Export PDF (Tahap 22)

});

/*
|--------------------------------------------------------------------------
| Staff Only Routes (jika diperlukan di masa depan)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified', 'role:staff'])->group(function () {

    // Route khusus Staff (jika ada)

});

/*
|--------------------------------------------------------------------------
| Profile Routes (Breeze Default)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
