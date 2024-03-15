<?php

namespace Modules\User\Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Define resources and their respective CRUD operations
        $resources = [
            'size',
            'cart',
            'newsletter',
            'review',
            'user',
            'role',
            'shipping',
            'comment',
            'casys',
            'coupon',
            'brand',
            'message',
            'banner',
            'settings',
            'categories',
            'tags',
            'post',
            'product',
            'order',
            'attributes'
        ];
        $operations = ['list', 'create', 'update', 'delete'];

        // Create permissions
        foreach ($resources as $resource) {
            foreach ($operations as $operation) {
                Permission::create(['name' => "{$resource}-{$operation}"]);
            }
        }

        // Assign permissions to roles
        $managerPermissions = [
            'post',
            'product',
            'order',
            'coupon',
            'brand',
            'banner',
            'user'
        ];
        $clientPermissions = [
            'order',
            'comment',
            'review',
            'user'
        ];

        $this->createRoleWithPermissions('manager', $managerPermissions, $operations);
        $this->createRoleWithPermissions('client', $clientPermissions, $operations);

        // Super-admin gets all permissions
        $role3 = Role::create(['name' => 'super-admin']);
        $role3->givePermissionTo(Permission::all());
    }

    /**
     * @param  string[]  $operations  Array of operation names
     * @param  string[]  $resources  Array of resource names
     */
    private function createRoleWithPermissions(string $roleName, array $resources, array $operations): void
    {
        $role = Role::create(['name' => $roleName]);
        foreach ($resources as $resource) {
            foreach ($operations as $operation) {
                $role->givePermissionTo("{$resource}-{$operation}");
            }
        }
    }

}
