<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'kabid@egov.local'],
            [
                'name' => 'Kepala Bidang',
                'password' => Hash::make('password'),
                'role' => UserRole::KABID,
                'nip' => '198001010001',
                'position' => 'Kepala Bidang E-Government',
            ]
        );

        User::updateOrCreate(
            ['email' => 'staff1@egov.local'],
            [
                'name' => 'Aiman Gandra',
                'password' => Hash::make('password'),
                'role' => UserRole::STAFF,
                'nip' => '199901010001',
                'position' => 'Programmer',
            ]
        );

        User::updateOrCreate(
            ['email' => 'staff2@egov.local'],
            [
                'name' => 'Fajar Ramadhan',
                'password' => Hash::make('password'),
                'role' => UserRole::STAFF,
                'nip' => '199901010002',
                'position' => 'System Analyst',
            ]
        );
    }
}
