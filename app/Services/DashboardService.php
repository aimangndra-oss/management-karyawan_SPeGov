<?php

namespace App\Services;

use App\Enums\TaskStatus;
use App\Enums\UserRole;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Collection;

class DashboardService
{
    public function getDashboardData(User $user): array
    {
        $query = Task::query()->with(['assignee', 'creator']);

        if ($user->role !== UserRole::KABID) {
            $query->where('user_id', $user->id);
        }

        $totalTasks = (clone $query)->count();
        $completedTasks = (clone $query)->where('status', TaskStatus::DONE->value)->count();
        $inProgressTasks = (clone $query)->where('status', TaskStatus::IN_PROGRESS->value)->count();
        $overdueTasks = (clone $query)
            ->where('deadline', '<', now()->toDateString())
            ->where('status', '!=', TaskStatus::DONE->value)
            ->count();
        $averageProgress = (clone $query)->avg('progress_percentage') ?? 0;

        $recentTasks = (clone $query)
            ->latest('created_at')
            ->limit(5)
            ->get();

        $upcomingDeadlines = (clone $query)
            ->where('deadline', '>=', now()->toDateString())
            ->orderBy('deadline')
            ->limit(5)
            ->get();

        return [
            'user' => $user,
            'isKabid' => $user->role === UserRole::KABID,
            'stats' => [
                [
                    'label' => 'Total Tugas',
                    'value' => $totalTasks,
                    'description' => 'Semua tugas yang terlihat oleh Anda',
                    'icon' => '📋',
                ],
                [
                    'label' => 'Selesai',
                    'value' => $completedTasks,
                    'description' => 'Tugas dengan status selesai',
                    'icon' => '✅',
                ],
                [
                    'label' => 'Dalam Proses',
                    'value' => $inProgressTasks,
                    'description' => 'Tugas yang sedang dikerjakan',
                    'icon' => '🔄',
                ],
                [
                    'label' => 'Terlambat',
                    'value' => $overdueTasks,
                    'description' => 'Tugas melewati deadline',
                    'icon' => '⚠️',
                ],
            ],
            'averageProgress' => round($averageProgress, 0),
            'recentTasks' => $recentTasks,
            'upcomingDeadlines' => $upcomingDeadlines,
        ];
    }
}
