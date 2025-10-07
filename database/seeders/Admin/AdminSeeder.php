<?php

namespace Database\Seeders\Admin;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      $admin = new Admin();
      $admin->name = 'SUPER ADMIN';
      $admin->email = 'admin@example.com';
      $admin->password = bcrypt('1234');
      $admin->save();
    }
}
