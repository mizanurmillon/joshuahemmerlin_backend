<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::insert([
            [
                'permission_name' => 'Enable All',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'permission_name' => 'Gross Sales',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'permission_name' => 'Flagged Hours',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'permission_name' => 'Effective Labor Rate',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'permission_name' => 'Average Written RO',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
           
        ]);
    }
}
