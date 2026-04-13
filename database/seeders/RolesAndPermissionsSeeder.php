<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpiamos la cache de Spatie por seguridad
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Crear Permisos (Auditoría Forense)
        $guard = 'web';
        
        Permission::create(['name' => 'view-dashboard', 'guard_name' => $guard]);
        Permission::create(['name' => 'manage-invoices', 'guard_name' => $guard]);
        Permission::create(['name' => 'view-fraud-alerts', 'guard_name' => $guard]);
        Permission::create(['name' => 'manage-vendors', 'guard_name' => $guard]);
        Permission::create(['name' => 'manage-employees', 'guard_name' => $guard]);
        Permission::create(['name' => 'view-reports', 'guard_name' => $guard]);

        // 2. Crear Roles y asignar permisos

        // Rol: Auditor Senior
        $roleAuditor = Role::create(['name' => 'auditor-senior', 'guard_name' => $guard]);
        $roleAuditor->syncPermissions(Permission::where('guard_name', $guard)->get());

        // Rol: Analista Junior
        $roleAnalista = Role::create(['name' => 'analista-junior', 'guard_name' => $guard]);
        $roleAnalista->syncPermissions(Permission::whereIn('name', [
            'view-dashboard', 
            'view-fraud-alerts', 
            'manage-invoices'
        ])->where('guard_name', $guard)->get());

        // Rol: Super Admin Global (AuditCore)
        $roleSuperAdmin = Role::create(['name' => 'super_admin', 'guard_name' => $guard]);
    }
}
