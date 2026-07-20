<?php

namespace Tests\Feature;

use App\Enums\TaskStatus;
use App\Enums\UserRole;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_kabid_dashboard_shows_global_statistics(): void
    {
        $kabid = User::factory()->create([
            'role' => UserRole::KABID,
        ]);

        $staff = User::factory()->create([
            'role' => UserRole::STAFF,
        ]);

        Task::create([
            'task_number' => 'TASK-001',
            'title' => 'Penyusunan Laporan',
            'description' => 'Membuat laporan mingguan',
            'user_id' => $staff->id,
            'created_by' => $kabid->id,
            'status' => TaskStatus::IN_PROGRESS,
            'priority' => 'high',
            'start_date' => now()->subDay()->toDateString(),
            'deadline' => now()->addDays(3)->toDateString(),
            'progress_percentage' => 40,
        ]);

        Task::create([
            'task_number' => 'TASK-002',
            'title' => 'Review Dokumen',
            'description' => 'Review dokumen kebijakan',
            'user_id' => $staff->id,
            'created_by' => $kabid->id,
            'status' => TaskStatus::DONE,
            'priority' => 'medium',
            'start_date' => now()->subDays(2)->toDateString(),
            'deadline' => now()->subDay()->toDateString(),
            'progress_percentage' => 100,
        ]);

        $response = $this->actingAs($kabid)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Total Tugas');
        $response->assertSee('Tugas Terbaru');
    }

    public function test_staff_dashboard_shows_only_assigned_tasks(): void
    {
        $kabid = User::factory()->create([
            'role' => UserRole::KABID,
        ]);

        $staff = User::factory()->create([
            'role' => UserRole::STAFF,
        ]);

        $otherStaff = User::factory()->create([
            'role' => UserRole::STAFF,
        ]);

        Task::create([
            'task_number' => 'TASK-101',
            'title' => 'Tugas Saya',
            'description' => 'Harus dikerjakan oleh staff',
            'user_id' => $staff->id,
            'created_by' => $kabid->id,
            'status' => TaskStatus::TO_DO,
            'priority' => 'high',
            'start_date' => now()->toDateString(),
            'deadline' => now()->addDays(2)->toDateString(),
            'progress_percentage' => 0,
        ]);

        Task::create([
            'task_number' => 'TASK-102',
            'title' => 'Tugas Rekan',
            'description' => 'Ini tugas rekan lain',
            'user_id' => $otherStaff->id,
            'created_by' => $kabid->id,
            'status' => TaskStatus::TO_DO,
            'priority' => 'medium',
            'start_date' => now()->toDateString(),
            'deadline' => now()->addDays(4)->toDateString(),
            'progress_percentage' => 0,
        ]);

        $response = $this->actingAs($staff)->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Tugas Saya');
        $response->assertDontSee('Tugas Rekan');
    }
}
