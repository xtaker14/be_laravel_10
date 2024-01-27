<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

use App\Models\Role;
use App\Models\User;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = Role::all();

        foreach ($roles as $key2 => $role) {
            if ($role->name != 'OPEN_API') {
                $username = strtolower($role->name);
                $check = User::where('username', $username)->first();

                if (!$check) {
                    $user = new User;
                    $user->role_id = $role->role_id;
                    $user->gender = 'L';
                    $user->full_name = str_replace('_', ' ', $role->name);
                    $user->username = $username;
                    $user->email = strtolower($role->name) . '@admin.com';
                    $user->password = Hash::make($username . '123');
                    $user->is_active = 1;
                    $user->created_date = Carbon::now();
                    $user->modified_date = Carbon::now();
                    $user->created_by = 'system';
                    $user->modified_by = 'system';

                    if($user->save()){

                    }
                }
            }
        }
    }
}
