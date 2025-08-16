<?php

namespace Database\Seeders;

use App\Models\DataSource;
use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DataSourcesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DataSource::insert([
            [
                'name' => 'Tekion',
                'image' => 'backend/images/tekion.jpg',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Protractor',
                'image' => 'backend/images/protractor.png',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Shop Ware',
                'image' => 'backend/images/shopware.png',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Tekmetric',
                'image' => 'backend/images/tekmetric.png',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
             [
                'name' => 'Mitchell1',
                'image' => 'backend/images/mitchell1.jpg',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Quickbooks',
                'image' => 'backend/images/quickbooks.png',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Shopmonkey',
                'image' => 'backend/images/shopmonkey.jpg',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Rowriter',
                'image' => 'backend/images/rowriter.png',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
           
        ]);
    }
}
