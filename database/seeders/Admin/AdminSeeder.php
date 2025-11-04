<?php

namespace Database\Seeders\Admin;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    /** Create Super Admin */
    $admin = new Admin();
    $admin->name = 'SUPER ADMIN';
    $admin->email = 'admin@example.com';
    $admin->password = bcrypt('1234');
    $admin->save();

    /** Create Super Admin Role */
    Role::create(['name' => 'Super Admin', 'guard_name' => 'admin']);

    /** Assign Super Admin Role to Super Admin */
    $admin->assignRole('Super Admin');
  }
}
