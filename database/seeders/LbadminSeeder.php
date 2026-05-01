<?php
namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\admin\Lbadmin;

class LbadminSeeder extends Seeder{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $testings = array(
            array(
                "name"=>"Student 1",
                "email"=>"student1@gmail.com",
                "password"=>"123456",
            ),
            array(
                "name"=>"Student 1",
                "email"=>"student1@gmail.com",
                "password"=>"123456",
            ),
            array(
                "name"=>"Student 1",
                "email"=>"student1@gmail.com",
                "password"=>"123456",
            ),
            array(
                "name"=>"Student 1",
                "email"=>"student1@gmail.com",
                "password"=>"123456",
            ),
            array(
                "name"=>"Student 1",
                "email"=>"student1@gmail.com",
                "password"=>"123456",
            ),
            array(
                "name"=>"Student 1",
                "email"=>"student1@gmail.com",
                "password"=>"123456",
            ),
            array(
                "name"=>"Student 1",
                "email"=>"student1@gmail.com",
                "password"=>"123456",
            ),
        );

        foreach($testings as $testing)
        {
            Lbadmin::create($testing);   
        }
    }
}
