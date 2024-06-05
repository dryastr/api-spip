<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User\Role;
use App\Models\User\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $role = Role::updateOrCreate([
            'name' => 'Admin Nasional',
        ], [
            'description' => 'Admin Nasional role access',
        ]);

        $allPermissions = Permission::where('action', 'not like', 'opd.%')
        ->orWhere('action', 'not like', 'pemda.%')
        ->get();
        foreach ($allPermissions as $permission) {
            $role->permissions()->create([
                'permission_id' => $permission->id
            ]);
        }

        $role = Role::updateOrCreate([
            'name' => 'Admin Unit',
        ], [
            'description' => 'Admin Unit role access',
        ]);

        $allPermissions = Permission::where('action', 'not like', 'settings.roles%')
        ->orWhere('action', 'not like', 'perwakilan%')
        ->orWhere('action', 'not like', 'pemda%')
        ->get();
        foreach ($allPermissions as $permission) {
            $role->permissions()->create([
                'permission_id' => $permission->id
            ]);
        }

        Role::updateOrCreate([
            'name' => 'Asesor',
        ], [
            'description' => 'Asesor role access',
        ]);

        $role = Role::updateOrCreate([
            'name' => 'Admin Perwakilan',
        ], [
            'description' => 'Admin Perwakilan role access',
        ]);

        $allPermissions = Permission::where('action', 'like', 'perwakilan%')
        ->orWhere('action', 'like', '%ashboard%')
        ->orWhere('action', 'like', 'pemda%')
        ->get();
        foreach ($allPermissions as $permission) {
            $role->permissions()->create([
                'permission_id' => $permission->id
            ]);
        }

        Role::updateOrCreate([
            'name' => 'Review',
        ], [
            'description' => 'Review role access',
        ]);

        $role = Role::updateOrCreate([
            'name' => 'Admin Pemda',
        ], [
            'description' => 'Admin Pemda role access',
        ]);


        $allPermissions = Permission::where('action', 'like', 'pemda%')
        ->orWhere('action', 'like', '%ashboard%')
        ->get();
        foreach ($allPermissions as $permission) {
            $role->permissions()->create([
                'permission_id' => $permission->id
            ]);
        }


        Role::updateOrCreate([
            'name' => 'Asesor Pemda',
        ], [
            'description' => 'Asesor Pemda role access',
        ]);

        Role::updateOrCreate([
            'name' => 'Admin OPD',
        ], [
            'description' => 'Admin OPD role access',
        ]);

        Role::updateOrCreate([
            'name' => 'Asesor OPD',
        ], [
            'description' => 'Asesor OPD role access',
        ]);

        Role::updateOrCreate([
            'name' => 'Penjamin Mutu OPD',
        ], [
            'description' => 'Penjamin Mutu OPD role access',
        ]);


    }
}
