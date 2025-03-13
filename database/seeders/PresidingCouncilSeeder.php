<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PresidingCouncilSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('presiding_councils')->insert([
            'name' => 'Ken Gilman',
            'designation' => 'Chairman',
            'image' => 'backend/images/presending/user1.png',
            'bio' => 'American Neurologist, Psychiatrist, and Internal Medicine Specialist.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('presiding_councils')->insert([
            'name' => 'Michael Berk',
            'designation' => 'Vice Chairperson',
            'image' => 'backend/images/presending/user2.png',
            'bio' => 'American Neurologist, Psychiatrist, and Internal Medicine Specialist.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('presiding_councils')->insert([
            'name' => 'Robert Brown',
            'designation' => 'Professor',
            'image' => 'backend/images/presending/user3.png',
            'bio' => 'Robert Brown is a pioneer in his field of study.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('presiding_councils')->insert([
            'name' => 'Emily Davis',
            'designation' => 'Professor',
            'image' => 'backend/images/presending/user4.png',
            'bio' => 'Emily Davis has mentored many students in her career.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('presiding_councils')->insert([
            'name' => 'Michael Wilson',
            'designation' => 'Professor',
            'image' => 'backend/images/presending/user5.png',
            'bio' => 'Michael Wilson is known for his innovative teaching methods.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('presiding_councils')->insert([
            'name' => 'Sarah Johnson',
            'designation' => 'Professor',
            'image' => 'backend/images/presending/user6.png',
            'bio' => 'Sarah Johnson specializes in curriculum development.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('presiding_councils')->insert([
            'name' => 'David Martinez',
            'designation' => 'Professor',
            'image' => 'backend/images/presending/user7.png',
            'bio' => 'David Martinez has authored several academic papers.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('presiding_councils')->insert([
            'name' => 'Emma Garcia',
            'designation' => 'Professor',
            'image' => 'backend/images/presending/user8.png',
            'bio' => 'Emma Garcia has received numerous awards for her research.',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
