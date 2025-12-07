<?php

namespace Database\Seeders\Admin;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    $permissions = [
      array('id' => '1','name' => 'KYC Management','guard_name' => 'admin','group_name' => 'KYC Management','created_at' => '2025-10-21 06:45:11','updated_at' => '2025-10-21 06:45:11'),
      array('id' => '2','name' => 'Role Management','guard_name' => 'admin','group_name' => 'Access Management','created_at' => '2025-10-21 08:13:07','updated_at' => '2025-10-21 08:13:07'),
      array('id' => '3','name' => 'Role User Management','guard_name' => 'admin','group_name' => 'Access Management','created_at' => '2025-10-21 08:14:01','updated_at' => '2025-10-21 08:14:01'),
      array('id' => '4','name' => 'Category Management','guard_name' => 'admin','group_name' => 'Product Categories','created_at' => '2025-11-10 15:36:46','updated_at' => '2025-11-10 15:36:46'),
      array('id' => '5','name' => 'Tags Management','guard_name' => 'admin','group_name' => 'Product Tags','created_at' => '2025-11-10 15:37:32','updated_at' => '2025-11-10 15:37:32'),
      array('id' => '6','name' => 'Brand Management','guard_name' => 'admin','group_name' => 'Product Brands','created_at' => '2025-11-11 12:13:07','updated_at' => '2025-11-11 12:13:07'),
      array('id' => '7','name' => 'Product Management','guard_name' => 'admin','group_name' => 'Products','created_at' => '2025-12-06 15:16:47','updated_at' => '2025-12-06 15:16:47')

    ];

    // Insérer les données
    DB::table('permissions')->insert($permissions);
  }
}



