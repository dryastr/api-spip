<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User\User;
use App\Models\User\Role;
use App\Models\Referensi\Klp;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::updateOrCreate([
            'nip' => '000000000000000',
            'email' => 'admin@mail.com',
        ], [
            'fullname' => 'Administrator',
            'password' => Hash::make('Password123'),
        ]);

        $allRoles = Role::select(['id', 'name'])->get();
        foreach ($allRoles as $role) {
            $user->roles()->create([
                'role_id' => $role->id,
                'ref_klp_id' => Klp::first()->id
            ]);
        }

        $user = User::updateOrCreate([
            'nip' => '000000000000001',
            'email' => 'adminunit@mail.com',
        ], [
            'fullname' => 'Admin Unit Klp',
            'password' => Hash::make('Password123'),
        ]);

        $role = Role::find(2);

        if ($role) {
            $user->roles()->create([
                        'role_id' => $role->id,
                        'ref_klp_id' => Klp::first()->id
                    ]);
        }

        $user = User::updateOrCreate([
            'nip' => '000000000000002',
            'email' => 'asesor@mail.com',
        ], [
            'fullname' => 'Asesor',
            'password' => Hash::make('Password123'),
        ]);

        $role = Role::find(3);

        if ($role) {
            $user->roles()->create([
                        'role_id' => $role->id,
                        'ref_klp_id' => Klp::first()->id
                    ]);
        }

        $user = User::updateOrCreate([
            'nip' => '000000000000003',
            'email' => 'adminperwakilan@mail.com',
        ], [
            'fullname' => 'Admin Perwakilan',
            'password' => Hash::make('Password123'),
        ]);

        $role = Role::find(4);

        if ($role) {
            $user->roles()->create([
                        'role_id' => $role->id,
                        'ref_klp_id' => Klp::find(2)->id
                    ]);
        }

        $user = User::updateOrCreate([
            'nip' => '000000000000006',
            'email' => 'adminpemda@mail.com',
        ], [
            'fullname' => 'Admin Pemda',
            'password' => Hash::make('Password123'),
        ]);

        $role = Role::find(6);

        if ($role) {
            $user->roles()->create([
                        'role_id' => $role->id,
                        'ref_klp_id' => Klp::find(3)->id
                    ]);
        }
    }
}
