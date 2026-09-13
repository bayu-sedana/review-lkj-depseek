<?php

namespace Database\Seeders;

use App\Models\Satker;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RoleAndUserSeeder extends Seeder
{
    /**
     * Seed the application's roles and default admin account.
     */
    public function run(): void
    {
        foreach (['admin', 'monev', 'satker'] as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

        $satker = Satker::firstOrCreate(
            ['kode_satker' => '0001'],
            ['nama_satker' => 'Satker Contoh']
        );

        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'role' => 'admin',
            ]
        );
        $admin->syncRoles(['admin']);

        $monev = User::firstOrCreate(
            ['email' => 'monev@example.com'],
            [
                'name' => 'Tim Monev',
                'password' => Hash::make('password'),
                'role' => 'monev',
            ]
        );
        $monev->syncRoles(['monev']);

        $satkerUser = User::firstOrCreate(
            ['email' => 'satker@example.com'],
            [
                'name' => 'Operator Satker',
                'password' => Hash::make('password'),
                'role' => 'satker',
                'satker_id' => $satker->id,
            ]
        );
        $satkerUser->syncRoles(['satker']);
    }
}
