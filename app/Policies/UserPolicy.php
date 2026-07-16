<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\User;

class UserPolicy
{
    /**
     * Hanya Kabid yang bisa melihat daftar seluruh pegawai.
     */
    public function viewAny(User $authUser): bool
    {
        return $authUser->role === UserRole::KABID;
    }

    /**
     * Kabid bisa melihat profil semua user.
     * Staff hanya bisa melihat profil diri sendiri.
     */
    public function view(User $authUser, User $targetUser): bool
    {
        if ($authUser->role === UserRole::KABID) {
            return true;
        }

        return $authUser->id === $targetUser->id;
    }

    /**
     * Hanya Kabid yang bisa membuat akun pegawai baru.
     */
    public function create(User $authUser): bool
    {
        return $authUser->role === UserRole::KABID;
    }

    /**
     * Kabid bisa mengubah semua data user.
     * Staff hanya bisa mengubah data profil diri sendiri.
     */
    public function update(User $authUser, User $targetUser): bool
    {
        if ($authUser->role === UserRole::KABID) {
            return true;
        }

        return $authUser->id === $targetUser->id;
    }

    /**
     * Hanya Kabid yang bisa menghapus akun pegawai.
     * Kabid tidak boleh menghapus diri sendiri.
     */
    public function delete(User $authUser, User $targetUser): bool
    {
        if ($authUser->role !== UserRole::KABID) {
            return false;
        }

        // Kabid tidak bisa menghapus akunnya sendiri
        return $authUser->id !== $targetUser->id;
    }
}
