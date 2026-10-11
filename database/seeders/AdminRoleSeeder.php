<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class AdminRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        $user = User::first();
        if ($user && !$user->hasRole('admin')) {
            $user->assignRole('admin');
            $this->command->info('Admin role assigned to user: ' . $user->name);
        }
    }
}
