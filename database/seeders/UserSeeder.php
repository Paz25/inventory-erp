<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $db = DB::connection('users_igr');

        $roles = [
            ['code' => 'admin', 'name' => 'Administrator'],
            ['code' => 'stf_gudang', 'name' => 'Staff Gudang'],
        ];

        $users = [
            [
                'username' => 'Admin',
                'password' => 'Admin123',
                'role_id' => 1
            ],
            [
                'username' => 'StaffGudang',
                'password' => 'Staff123',
                'role_id' => 2
            ]
        ];

        $roleIDs = [];
        foreach ($roles as $role) {
            $roleIDs[] = $db->table('roles')->insertGetId([
                'code' => $role['code'],
                'name' => $role['name'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        foreach ($users as $user) {
            $db->table('users')->insert([
                'username' => $user['username'],
                'password' => bcrypt($user['password']),
                'role_id' => $roleIDs[$user['role_id'] - 1],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
