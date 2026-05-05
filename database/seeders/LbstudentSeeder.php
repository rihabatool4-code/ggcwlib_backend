<?php

namespace Database\Seeders;

use App\Models\student\Lbstudent;
use Database\Factories\LbstudentFactory;
use Illuminate\Database\Seeder;

class LbstudentSeeder extends Seeder
{
    public function run(): void
    {
        LbstudentFactory::factory()->count(100)->create();
       /* $testings = array(
            array(
                "fullName"=>"Student 1",
                "email"=>"student1@gmail.com",
                "phone"=>"0300000001",
                "roll_no"=>"101",
                "program"=>"BSCS",
                "session"=>"2023",
                "password"=>"123456",
            ),
            array(
                "fullName"=>"Student 2",
                "email"=>"student2@gmail.com",
                "phone"=>"0300000002",
                "roll_no"=>"102",
                "program"=>"BSCS",
                "session"=>"2023",
                "password"=>"123456",
            ),
            array(
                "fullName"=>"Student 3",
                "email"=>"student3@gmail.com",
                "phone"=>"0300000003",
                "roll_no"=>"103",
                "program"=>"BSCS",
                "session"=>"2023",
                "password"=>"123456",
            ),
            array(
                "fullName"=>"Student 4",
                "email"=>"student4@gmail.com",
                "phone"=>"0300000004",
                "roll_no"=>"104",
                "program"=>"BSCS",
                "session"=>"2023",
                "password"=>"123456",
            ),
            array(
                "fullName"=>"Student 5",
                "email"=>"student5@gmail.com",
                "phone"=>"0300000005",
                "roll_no"=>"105",
                "program"=>"BSCS",
                "session"=>"2023",
                "password"=>"123456",
            ),
            array(
                "fullName"=>"Student 6",
                "email"=>"student6@gmail.com",
                "phone"=>"0300000006",
                "roll_no"=>"106",
                "program"=>"BSCS",
                "session"=>"2023",
                "password"=>"123456",
            ),
            array(
                "fullName"=>"Student 7",
                "email"=>"student7@gmail.com",
                "phone"=>"0300000007",
                "roll_no"=>"107",
                "program"=>"BSCS",
                "session"=>"2023",
                "password"=>"123456",
            ),
            array(
                "fullName"=>"Student 8",
                "email"=>"student8@gmail.com",
                "phone"=>"0300000008",
                "roll_no"=>"108",
                "program"=>"BSCS",
                "session"=>"2023",
                "password"=>"123456",
            ),
            array(
                "fullName"=>"Student 9",
                "email"=>"student9@gmail.com",
                "phone"=>"0300000009",
                "roll_no"=>"109",
                "program"=>"BSCS",
                "session"=>"2023",
                "password"=>"123456",
            ),
            array(
                "fullName"=>"Student 10",
                "email"=>"student10@gmail.com",
                "phone"=>"0300000010",
                "roll_no"=>"110",
                "program"=>"BSCS",
                "session"=>"2023",
                "password"=>"123456",
            ),
        );

        foreach($testings as $testing)
        {
            Lbstudent::create($testing);   
        }*/
    }
}