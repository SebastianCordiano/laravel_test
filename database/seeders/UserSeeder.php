<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::create(['name' => 'edit']);
        Permission::create(['name' => 'publish']);
        Permission::create(['name' => 'administrator']);

        Role::create(['name' => 'Super Admin'])->givePermissionTo(Permission::all());
        Role::create(['name' => 'admin'])->givePermissionTo(['edit', 'publish','administrator']);
        Role::create(['name' => 'editor'])->givePermissionTo(['edit', 'publish']);

        $user_list = [
            ['name' => 'SuperAdmin', 'email' => 'superadmin@webpills.it', 'password' => 'superadmin', 'role' => Role::findByName('Super Admin')],
            ['name' => 'Admin', 'email' => 'Admin@webpills.it', 'password' => 'admin', 'role' => Role::findByName('admin')],
            ['name' => 'Editor', 'email' => 'Editor@webpills.it', 'password' => 'editor', 'role' => Role::findByName('editor')],
        ];

        foreach ($user_list as $user) {
            $u=User::create([
                'name' => $user['name'],
                'email' => $user['email'],
                'password' => Hash::make($user['password']),
            ]);
            $u->syncRoles($user['role']['name']);
            
        }
    }
}
