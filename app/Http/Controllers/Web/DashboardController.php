<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Enums\TaskStatus;
use App\Enums\UserRole;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // 1. Hitung Statistik Real-time
        $queryStats = Task::query();
        if ($user->role === UserRole::STAFF) {
            $queryStats->where('user_id', $user->id);
        }

        $total = (clone $queryStats)->count();
        $done = (clone $queryStats)->where('status', TaskStatus::DONE)->count();
        $inProgress = (clone $queryStats)->where('status', TaskStatus::IN_PROGRESS)->count();
        $review = (clone $queryStats)->where('status', TaskStatus::REVIEW)->count();

        // Overdue: status bukan done, dan melewati deadline hari ini
        $overdue = (clone $queryStats)
            ->where('status', '!=', TaskStatus::DONE)
            ->whereDate('deadline', '<', now()->toDateString())
            ->count();

        // Near Deadline: status bukan done, deadline dalam 3 hari ke depan (termasuk hari ini)
        $nearDeadline = (clone $queryStats)
            ->where('status', '!=', TaskStatus::DONE)
            ->whereBetween('deadline', [now()->toDateString(), now()->addDays(3)->toDateString()])
            ->count();

        $stats = [
            'total' => $total,
            'done' => $done,
            'in_progress' => $inProgress,
            'overdue' => $overdue,
            'near_deadline' => $nearDeadline,
        ];

        // 2. Ambil 10 Tugas Terbaru
        $queryTasks = Task::with('assignee');
        if ($user->role === UserRole::STAFF) {
            $queryTasks->where('user_id', $user->id);
        }

        $tasksCollection = $queryTasks->orderBy('deadline', 'asc')->take(10)->get();

        $tasks = $tasksCollection->map(function ($t) {
            $badgeClass = match($t->status) {
                TaskStatus::TO_DO => 'todo',
                TaskStatus::IN_PROGRESS => 'in-progress',
                TaskStatus::REVIEW => 'review',
                TaskStatus::DONE => 'done',
                default => 'todo',
            };

            return [
                'id' => $t->id,
                'task_number' => $t->task_number,
                'title' => $t->title,
                'assignee' => $t->assignee?->name ?? 'Belum Ditugaskan',
                'deadline' => $t->deadline ? $t->deadline->format('d M Y') : '-',
                'status_label' => $t->status->label(),
                'badge_class' => $badgeClass,
            ];
        })->toArray();

        // 3. Persiapkan Data Grafik Bulanan (Tahun Berjalan)
        $currentYear = now()->year;
        $monthlyChartData = [];
        for ($m = 1; $m <= 12; $m++) {
            $monthlyChartData[] = (clone $queryStats)
                ->whereYear('deadline', $currentYear)
                ->whereMonth('deadline', $m)
                ->count();
        }

        // 4. Persiapkan Data Grafik Kategori Status
        $todoCount = (clone $queryStats)->where('status', TaskStatus::TO_DO)->count();
        $inProgressCount = (clone $queryStats)->where('status', TaskStatus::IN_PROGRESS)->count();
        $reviewCount = (clone $queryStats)->where('status', TaskStatus::REVIEW)->count();
        $doneCount = (clone $queryStats)->where('status', TaskStatus::DONE)->count();

        $statusChartData = [
            $todoCount,
            $inProgressCount,
            $reviewCount,
            $doneCount
        ];

        return view('dashboard', compact('stats', 'tasks', 'monthlyChartData', 'statusChartData'));
    }
}
