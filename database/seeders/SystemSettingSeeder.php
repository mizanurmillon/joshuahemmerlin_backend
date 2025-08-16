<?php

namespace Database\Seeders;

use App\Models\SystemSetting;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SystemSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SystemSetting::insert([
            [
                'id'             => 1,
                'email'          => 'support@gmail.com',
                'system_name'    => 'System Name',
                'logo'           => 'backend/images/logo.png',
                'favicon'        => 'backend/images/logo.png',
                'created_at'     => Carbon::now(),
            ],
        ]);
    }
}
