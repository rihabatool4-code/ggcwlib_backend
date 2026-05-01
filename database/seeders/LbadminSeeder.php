<?php

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
