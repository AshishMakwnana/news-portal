<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions
        $permissions = [
            'create_news',
            'edit_news',
            'publish_news',
            'delete_news',
            'manage_users',
        ];

        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // Create roles and assign permissions
        $super = Role::firstOrCreate(['name' => 'Super Admin']);
        $editor = Role::firstOrCreate(['name' => 'Editor']);
        $reporter = Role::firstOrCreate(['name' => 'Reporter']);
        $moderator = Role::firstOrCreate(['name' => 'Moderator']);
        $userRole = Role::firstOrCreate(['name' => 'User']);

        $super->givePermissionTo(Permission::all());
        $editor->givePermissionTo(['create_news', 'edit_news', 'publish_news']);
        $reporter->givePermissionTo(['create_news', 'edit_news']);
        $moderator->givePermissionTo(['edit_news', 'delete_news']);

        // Create demo users
        $admin = User::firstOrCreate([
            'email' => 'admin@example.com'
        ], [
            'name' => 'Super Admin',
            'password' => bcrypt('password')
        ]);
        $admin->assignRole($super);

        $editorUser = User::firstOrCreate([
            'email' => 'editor@example.com'
        ], [
            'name' => 'Editor User',
            'password' => bcrypt('password')
        ]);
        $editorUser->assignRole($editor);

        $reporterUser = User::firstOrCreate([
            'email' => 'reporter@example.com'
        ], [
            'name' => 'Reporter User',
            'password' => bcrypt('password')
        ]);
        $reporterUser->assignRole($reporter);
    }
}
