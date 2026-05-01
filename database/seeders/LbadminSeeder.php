<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\admin\Lbadmin;

class LbadminSeeder extends Seeder{


namespace Database\Seeders;

use App\Models\admin\Lbadmin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LbadminSeeder extends Seeder
{

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

                "name"=>"Riha",
                "email"=>"riha12@gmail.com",
                "password"=>"1234",
                "InApp_notif"=>"InApp_notif",
                "email_notif"=>"email_notif"
            ),
              array(
                "name"=>"Maria",
                "email"=>"maria12@gmail.com",
                "password"=>"1234",
                "InApp_notif"=>"InApp_notif",
                "email_notif"=>"email_notif"
            ),
            array(
                "name"=>"Sonia",
                "email"=>"sonia12@gmail.com",
                "password"=>"1234",
                "InApp_notif"=>"InApp_notif",
                "email_notif"=>"email_notif"
            ),
            array(
                "name"=>"Malaika",
                "email"=>"malaika12@gmail.com",
                "password"=>"1234",
                "InApp_notif"=>"InApp_notif",
                "email_notif"=>"email_notif"
            ),
            array(
                "name"=>"Faiza",
                "email"=>"faiza12@gmail.com",
                "password"=>"1234",
                "InApp_notif"=>"InApp_notif",
                "email_notif"=>"email_notif"
            ),
            array(
                "name"=>"Aiman",
                "email"=>"aiman12@gmail.com",
                "password"=>"1234",
                "InApp_notif"=>"InApp_notif",
                "email_notif"=>"email_notif"
            ),
           

        );

        foreach($testings as $testing)
        {
            Lbadmin::create($testing);   
        }
    }
}
