<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Models\User;
use App\Models\TaskTimeline;
use App\Models\Document;
use App\Enums\TaskStatus;
use App\Enums\Priority;
use App\Enums\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class TaskController extends Controller
{
    /**
     * Menampilkan daftar tugas dengan filter dan pencarian.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Task::with('assignee');

        if ($user->role === UserRole::STAFF) {
            $query->where('user_id', $user->id);
        }

        // Filter Pencarian (Nomor Tugas, Judul, Penanggung Jawab)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('task_number', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%")
                  ->orWhereHas('assignee', function ($u) use ($search) {
                      $u->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filter Penanggung Jawab
        if ($request->filled('user_id') && Auth::user()->role === UserRole::KABID) {
            $query->where('user_id', $request->input('user_id'));
        }

        // Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Filter Bulan (berdasarkan deadline)
        if ($request->filled('month')) {
            $query->whereMonth('deadline', $request->input('month'));
        }

        $tasks = $query->orderBy('deadline', 'asc')->paginate(10)->withQueryString();

        // Data pendukung untuk filter di view
        $staffs = User::where('role', UserRole::STAFF)->get();
        $statuses = TaskStatus::cases();

        return view('tasks.index', compact('tasks', 'staffs', 'statuses'));
    }

    /**
     * Tampilan form tambah tugas baru.
     */
    public function create()
    {
        Gate::authorize('create', Task::class);

        $staffs = User::where('role', UserRole::STAFF)
            ->when(Auth::user()->role === UserRole::STAFF, fn($query) => $query->where('id', Auth::id()))
            ->get();
        $priorities = Priority::cases();

        return view('tasks.create', compact('staffs', 'priorities'));
    }

    /**
     * Menyimpan data tugas baru ke database.
     */
    public function store(StoreTaskRequest $request)
    {
        Gate::authorize('create', Task::class);

        $data = $request->validated();

        if (Auth::user()->role === UserRole::STAFF) {
            $data['user_id'] = Auth::id();
        }

        $data['created_by'] = Auth::id();
        $data['status'] = TaskStatus::TO_DO; // Status awal tugas baru
        $data['progress_percentage'] = 0;

        Task::create($data);

        return redirect()->route('tasks.index')->with('success', 'Tugas baru berhasil dibuat.');
    }

    /**
     * Detail suatu tugas, riwayat timeline, dan lampiran berkas.
     */
    public function show(Task $task)
    {
        Gate::authorize('view', $task);

        $task->load(['assignee', 'creator', 'timelines.user', 'documents.uploader']);

        $statuses = TaskStatus::cases();

        return view('tasks.show', compact('task', 'statuses'));
    }

    /**
     * Tampilan form edit tugas.
     */
    public function edit(Task $task)
    {
        Gate::authorize('update', $task);

        $staffs = User::where('role', UserRole::STAFF)
            ->when(Auth::user()->role === UserRole::STAFF, fn($query) => $query->where('id', Auth::id()))
            ->get();
        $priorities = Priority::cases();

        return view('tasks.edit', compact('task', 'staffs', 'priorities'));
    }

    /**
     * Mengubah data tugas di database.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        Gate::authorize('update', $task);

        $data = $request->validated();

        if (Auth::user()->role === UserRole::STAFF) {
            $data['user_id'] = Auth::id();
        }

        $task->update($data);

        // BAGIAN INI YANG DIUBAH: redirect ke tasks.index
        return redirect()->route('tasks.index')->with('success', 'Data tugas berhasil diperbarui.');
    }

    /**
     * Menghapus tugas (soft delete).
     */
    public function destroy(Task $task)
    {
        Gate::authorize('delete', $task);

        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Tugas berhasil dihapus.');
    }

    /**
     * Menyimpan progres terbaru ke timeline tugas.
     */
    public function storeTimeline(Request $request, Task $task)
    {
        Gate::authorize('view', $task); // Hak akses untuk menambahkan timeline sama dengan hak akses melihat tugas

        $request->validate([
            'status' => ['required', 'string'],
            'progress_percentage' => ['required', 'integer', 'min:0', 'max:100'],
            'note' => ['nullable', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($request, $task) {
            // Tambahkan data ke task_timelines
            TaskTimeline::create([
                'task_id' => $task->id,
                'user_id' => Auth::id(),
                'status' => $request->input('status'),
                'progress_percentage' => $request->input('progress_percentage'),
                'note' => $request->input('note'),
            ]);

            // Update status dan progress_percentage tugas utama
            $task->update([
                'status' => $request->input('status'),
                'progress_percentage' => $request->input('progress_percentage'),
            ]);
        });

        return redirect()->route('tasks.show', $task)->with('success', 'Progres tugas berhasil ditambahkan.');
    }

    /**
     * Menambahkan berkas dokumentasi tugas baru.
     */
    public function storeDocument(Request $request, Task $task)
    {
        Gate::authorize('view', $task);

        $request->validate([
            'document' => ['required', 'file', 'mimes:pdf,docx,xlsx,jpg,png,zip', 'max:10240'], // Max 10MB
        ]);

        if ($request->hasFile('document')) {
            $file = $request->file('document');
            $originalName = $file->getClientOriginalName();
            
            // Simpan file ke direktori storage/app/public/documents
            $path = $file->store('documents', 'public');

            Document::create([
                'task_id' => $task->id,
                'user_id' => Auth::id(),
                'file_name' => $originalName,
                'file_path' => $path,
                'file_type' => $file->getClientOriginalExtension(),
            ]);

            return redirect()->route('tasks.show', $task)->with('success', 'Dokumentasi berhasil diunggah.');
        }

        return redirect()->route('tasks.show', $task)->with('error', 'Gagal mengunggah file.');
    }

    /**
     * Mengunduh berkas dokumentasi tugas.
     */
    public function downloadDocument(Document $document)
    {
        // Pastikan user memiliki hak akses melihat tugas terkait
        Gate::authorize('view', $document->task);

        $filePath = $document->file_path;

        if (Storage::disk('public')->exists($filePath)) {
            return Storage::disk('public')->download($filePath, $document->file_name);
        }

        abort(404, 'File tidak ditemukan di server.');
    }

    /**
     * Memperbarui catatan progres pada timeline tugas.
     */
    public function updateTimeline(Request $request, Task $task, TaskTimeline $timeline)
    {
        // Pastikan user memiliki hak akses melihat tugas terkait
        Gate::authorize('view', $task);

        // Hanya pembuat progres atau Kabid yang boleh memperbarui progres tersebut
        if ($timeline->user_id !== Auth::id() && Auth::user()->role !== UserRole::KABID) {
            abort(403, 'Anda tidak memiliki wewenang untuk mengubah progres ini.');
        }

        $request->validate([
            'note' => ['required', 'string', 'max:1000'],
        ]);

        $timeline->update([
            'note' => $request->input('note'),
        ]);

        return redirect()->route('tasks.show', $task)->with('success', 'Catatan progres berhasil diperbarui.');
    }

    /**
     * Menghapus progres dari timeline tugas.
     */
    public function destroyTimeline(Task $task, TaskTimeline $timeline)
    {
        Gate::authorize('view', $task);

        // Hanya pembuat progres atau Kabid yang boleh menghapus
        if ($timeline->user_id !== Auth::id() && Auth::user()->role !== UserRole::KABID) {
            abort(403, 'Anda tidak memiliki wewenang untuk menghapus progres ini.');
        }

        DB::transaction(function () use ($task, $timeline) {
            $timeline->delete();

            // Cari timeline terbaru yang tersisa untuk menyinkronkan tugas utama
            $latestTimeline = $task->timelines()->orderBy('created_at', 'desc')->first();

            if ($latestTimeline) {
                $task->update([
                    'status' => $latestTimeline->status,
                    'progress_percentage' => $latestTimeline->progress_percentage,
                ]);
            } else {
                // Jika tidak ada timeline tersisa, kembalikan ke default awal
                $task->update([
                    'status' => TaskStatus::TO_DO,
                    'progress_percentage' => 0,
                ]);
            }
        });

        return redirect()->route('tasks.show', $task)->with('success', 'Catatan progres berhasil dihapus.');
    }
}