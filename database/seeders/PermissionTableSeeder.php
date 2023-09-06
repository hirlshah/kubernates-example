<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [
            'role-list', 'role-create', 'role-edit', 'role-delete',
	        'event-list', 'event-create', 'event-edit', 'event-delete',
	        'user-list', 'user-create', 'user-edit', 'user-delete',
	        'admin-dashboard','seller-dashboard',
	        'seller-members', 'seller-add-member', 'seller-member-stats', 'seller-profile',
	        'seller-contacts-board', 'seller-contacts-store', 'seller-contacts-update-status', 'seller-contacts-show', 'seller-contacts-update','seller-events'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
