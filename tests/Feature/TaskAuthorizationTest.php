<?php

namespace Tests\Feature;

use App\Enums\Priority;
use App\Enums\TaskStatus;
use App\Enums\UserRole;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    private User $kabid;
    private User $staff1;
    private User $staff2;

    protected function setUp(): void
    {
        parent::setUp();

        // Buat user dengan role Kabid
        $this->kabid = User::factory()->create([
            'role' => UserRole::KABID,
        ]);

        // Buat user dengan role Staff
        $this->staff1 = User::factory()->create([
            'role' => UserRole::STAFF,
        ]);

        $this->staff2 = User::factory()->create([
            'role' => UserRole::STAFF,
        ]);
    }

    /**
     * Helper untuk membuat task tanpa factory
     */
    private function createTask(array $overrides = []): Task
    {
        return Task::create(array_merge([
            'title' => 'Tugas Default',
            'description' => 'Instruksi default',
            'user_id' => $this->staff1->id,
            'created_by' => $this->kabid->id,
            'status' => TaskStatus::TO_DO,
            'priority' => Priority::MEDIUM,
            'start_date' => now()->toDateString(),
            'deadline' => now()->addDays(5)->toDateString(),
            'progress_percentage' => 0,
        ], $overrides));
    }

    /**
     * KABID: Bisa melihat semua tugas.
     */
    public function test_kabid_can_view_all_tasks(): void
    {
        // Buat tugas untuk staff1 dan staff2
        $this->createTask(['user_id' => $this->staff1->id]);
        $this->createTask(['user_id' => $this->staff2->id]);

        $response = $this->actingAs($this->kabid)->get(route('tasks.index'));

        $response->assertStatus(200);
        $response->assertViewHas('tasks', function ($tasks) {
            return $tasks->count() === 2;
        });
    }

    /**
     * STAFF: Hanya bisa melihat tugas miliknya sendiri.
     */
    public function test_staff_can_only_view_own_tasks(): void
    {
        // Buat tugas untuk staff1 dan staff2
        $this->createTask([
            'user_id' => $this->staff1->id,
            'title' => 'Tugas Staff 1',
        ]);
        $this->createTask([
            'user_id' => $this->staff2->id,
            'title' => 'Tugas Staff 2',
        ]);

        $response = $this->actingAs($this->staff1)->get(route('tasks.index'));

        $response->assertStatus(200);
        $response->assertViewHas('tasks', function ($tasks) {
            return $tasks->count() === 1 && $tasks->first()->title === 'Tugas Staff 1';
        });
    }

    /**
     * KABID: Bisa membuat tugas untuk seluruh staff.
     */
    public function test_kabid_can_create_task_for_any_staff(): void
    {
        $response = $this->actingAs($this->kabid)->post(route('tasks.store'), [
            'title' => 'Tugas Baru dari Kabid',
            'description' => 'Instruksi detail',
            'user_id' => $this->staff1->id,
            'priority' => Priority::HIGH->value,
            'start_date' => now()->toDateString(),
            'deadline' => now()->addDays(5)->toDateString(),
        ]);

        $response->assertRedirect(route('tasks.index'));
        $this->assertDatabaseHas('tasks', [
            'title' => 'Tugas Baru dari Kabid',
            'user_id' => $this->staff1->id,
            'created_by' => $this->kabid->id,
        ]);
    }

    /**
     * STAFF: Selalu membuat tugas untuk dirinya sendiri (mengabaikan input user_id lain).
     */
    public function test_staff_always_creates_task_for_themselves(): void
    {
        $response = $this->actingAs($this->staff1)->post(route('tasks.store'), [
            'title' => 'Tugas Buatan Staff 1',
            'description' => 'Detail tugas',
            'user_id' => $this->staff2->id, // Mencoba menunjuk staff2
            'priority' => Priority::MEDIUM->value,
            'start_date' => now()->toDateString(),
            'deadline' => now()->addDays(3)->toDateString(),
        ]);

        $response->assertRedirect(route('tasks.index'));
        // Pastikan tugas disimpan dengan user_id = staff1 (diabaikan)
        $this->assertDatabaseHas('tasks', [
            'title' => 'Tugas Buatan Staff 1',
            'user_id' => $this->staff1->id,
            'created_by' => $this->staff1->id,
        ]);
    }

    /**
     * KABID: Bisa mengedit dan mengubah seluruh tugas.
     */
    public function test_kabid_can_edit_and_update_all_tasks(): void
    {
        $task = $this->createTask([
            'user_id' => $this->staff1->id,
            'title' => 'Judul Awal',
        ]);

        $response = $this->actingAs($this->kabid)->put(route('tasks.update', $task), [
            'title' => 'Judul Diubah Kabid',
            'user_id' => $this->staff2->id, // Kabid memindahkan tugas ke staff2
            'priority' => Priority::LOW->value,
            'start_date' => now()->toDateString(),
            'deadline' => now()->addDays(7)->toDateString(),
        ]);

        $response->assertRedirect(route('tasks.show', $task));
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Judul Diubah Kabid',
            'user_id' => $this->staff2->id,
        ]);
    }

    /**
     * STAFF: Hanya bisa mengedit dan mengubah tugas miliknya sendiri (tidak boleh memindahkan kepemilikan).
     */
    public function test_staff_can_only_update_own_tasks_without_changing_ownership(): void
    {
        $task = $this->createTask([
            'user_id' => $this->staff1->id,
            'created_by' => $this->staff1->id,
            'title' => 'Tugas Saya',
        ]);

        $response = $this->actingAs($this->staff1)->put(route('tasks.update', $task), [
            'title' => 'Tugas Saya Diubah',
            'user_id' => $this->staff2->id, // Mencoba memindahkan kepemilikan ke staff2
            'priority' => Priority::HIGH->value,
            'start_date' => now()->toDateString(),
            'deadline' => now()->addDays(5)->toDateString(),
        ]);

        $response->assertRedirect(route('tasks.show', $task));
        // Judul terupdate, namun user_id tetap staff1 (diabaikan)
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Tugas Saya Diubah',
            'user_id' => $this->staff1->id,
        ]);
    }

    /**
     * STAFF: Tidak boleh mengedit tugas staff lain.
     */
    public function test_staff_cannot_edit_other_staff_tasks(): void
    {
        $task = $this->createTask([
            'user_id' => $this->staff2->id,
            'title' => 'Tugas Staff 2',
        ]);

        $response = $this->actingAs($this->staff1)->get(route('tasks.edit', $task));
        $response->assertStatus(403);

        $responseUpdate = $this->actingAs($this->staff1)->put(route('tasks.update', $task), [
            'title' => 'Mencoba Bajak',
            'user_id' => $this->staff1->id,
            'priority' => Priority::HIGH->value,
            'start_date' => now()->toDateString(),
            'deadline' => now()->addDays(5)->toDateString(),
        ]);
        $responseUpdate->assertStatus(403);
    }

    /**
     * STAFF: Tidak boleh menghapus tugas staff lain.
     */
    public function test_staff_cannot_delete_other_staff_tasks(): void
    {
        $task = $this->createTask([
            'user_id' => $this->staff2->id,
        ]);

        $response = $this->actingAs($this->staff1)->delete(route('tasks.destroy', $task));
        $response->assertStatus(403);
        $this->assertDatabaseHas('tasks', ['id' => $task->id]);
    }

    /**
     * STAFF: Bisa menghapus tugas miliknya sendiri.
     */
    public function test_staff_can_delete_own_tasks(): void
    {
        $task = $this->createTask([
            'user_id' => $this->staff1->id,
            'created_by' => $this->staff1->id,
        ]);

        $response = $this->actingAs($this->staff1)->delete(route('tasks.destroy', $task));
        $response->assertRedirect(route('tasks.index'));
        $this->assertSoftDeleted('tasks', ['id' => $task->id]);
    }
}
