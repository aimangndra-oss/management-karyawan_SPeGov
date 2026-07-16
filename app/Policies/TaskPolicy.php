<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    /**
     * Kabid dapat melihat daftar seluruh tugas.
     * Staff hanya melihat tugas yang ditugaskan kepadanya.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Kabid bisa melihat semua tugas.
     * Staff hanya bisa melihat tugas miliknya.
     */
    public function view(User $user, Task $task): bool
    {
        if ($user->role === UserRole::KABID) {
            return true;
        }

        return $user->id === $task->user_id;
    }

    /**
     *Kabid dapat membuat tugas baru untuk siapa saja.
     *Staff hanya dapat membuat tugas untuk dirinya sendiri.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, [UserRole::KABID, UserRole::STAFF], true);
    }

    /**
     * Kabid bisa mengubah semua tugas.
     * Staff bisa mengubah tugas yang ditugaskan kepadanya.
     */
    public function update(User $user, Task $task): bool
    {
        if ($user->role === UserRole::KABID) {
            return true;
        }

        return $user->id === $task->user_id;
    }

    /**
     * Kabid bisa menghapus semua tugas.
     * Staff hanya bisa menghapus tugas miliknya sendiri.
     */
    public function delete(User $user, Task $task): bool
    {
        if ($user->role === UserRole::KABID) {
            return true;
        }

        return $user->id === $task->user_id;
    }

    /**
     * Kabid bisa memulihkan semua tugas.
     * Staff hanya bisa memulihkan tugas miliknya sendiri.
     */
    public function restore(User $user, Task $task): bool
    {
        if ($user->role === UserRole::KABID) {
            return true;
        }

        return $user->id === $task->user_id;
    }

    /**
     * Kabid bisa menghapus permanen semua tugas.
     * Staff hanya bisa menghapus permanen tugas miliknya sendiri.
     */
    public function forceDelete(User $user, Task $task): bool
    {
        if ($user->role === UserRole::KABID) {
            return true;
        }

        return $user->id === $task->user_id;
    }
}
