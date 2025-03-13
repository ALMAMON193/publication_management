<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call(UserSeeder::class);
        $this->call(PresidingCouncilSeeder::class);
        $this->call(CorePublicationSeeder::class);
        $this->call(KeyDocumentSeeder::class);
    }
}
