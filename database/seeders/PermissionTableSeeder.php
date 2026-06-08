<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
          'role' => ['role-list', 'role-create', 'role-edit','role-delete'],
          'user' => ['user-list', 'user-create', 'user-edit','user-delete', 'user-password-edit', 'user-profile-edit', 'user-profile-password-edit'],
          'product' => ['product-list', 'product-create', 'product-edit','product-delete'],
          'customer' => ['customer-list', 'customer-create', 'customer-edit','customer-delete'],
          'order' => ['order-list', 'order-create', 'order-edit','order-delete'],
          'invoice' => ['invoice-list', 'invoice-create', 'invoice-edit','invoice-delete'],
          'expense' => ['expense-list', 'expense-create', 'expense-edit','expense-delete'],
          'report' => ['report-list'],
          'loan' => ['loan-list', 'loan-create', 'loan-edit','loan-delete'],
          'loan-payment' => ['loan-payment-list', 'loan-payment-create', 'loan-payment-edit','loan-payment-delete'],
          'company-setting' => ['company-setting-edit']
        ];

         foreach ($permissions as $permission) {
              Permission::create(['name' => $permission]);
         }
    }
}
