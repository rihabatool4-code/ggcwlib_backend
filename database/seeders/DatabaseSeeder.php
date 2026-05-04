<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {


         $this->call(LbadminSeeder::class);

         $this->call(LbstudentSeeder::class);
         $this->call(LbnoteSeeder::class);
         $this->call(LbebookSeeder::class);
         $this->call(LbteacherSeeder::class);
         $this->call(LbadminSeeder::class);


        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
