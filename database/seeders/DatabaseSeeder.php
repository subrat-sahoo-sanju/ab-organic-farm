<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RbacSeeder::class,
            SettingsSeeder::class,
            DeliveryAreaSeeder::class,
            CatalogSeeder::class,
            MarketingSeeder::class,
            DemoDataSeeder::class,
        ]);
    }
}
