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
      ['id' => '1', 'name' => 'KYC Management', 'guard_name' => 'admin', 'group_name' => 'KYC Management'],
      ['id' => '2', 'name' => 'Role Management', 'guard_name' => 'admin', 'group_name' => 'Access Management'],
      ['id' => '3', 'name' => 'Role User Management', 'guard_name' => 'admin', 'group_name' => 'Access Management'],
    ];

    // Insérer les données
    DB::table('permissions')->insert($permissions);
  }
}



