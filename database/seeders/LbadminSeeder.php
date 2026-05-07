<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\admin\Lbadmin;
use Illuminate\Support\Facades\Hash;

class LbadminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $testings = [
            [
                "name" => "Student 1",
                "email" => "student1@gmail.com",
                "password" => Hash::make("123456"),
            ],
            [
                "name" => "Riha",
                "email" => "riha12@gmail.com",
                "password" => Hash::make("1234"),
                "InApp_notif" => "InApp_notif",
                "email_notif" => "email_notif"
            ],
            [
                "name" => "Maria",
                "email" => "maria12@gmail.com",
                "password" => Hash::make("1234"),
                "InApp_notif" => "InApp_notif",
                "email_notif" => "email_notif"
            ],
            [
                "name" => "Sonia",
                "email" => "sonia12@gmail.com",
                "password" => Hash::make("1234"),
                "InApp_notif" => "InApp_notif",
                "email_notif" => "email_notif"
            ],
            [
                "name" => "Malaika",
                "email" => "malaika12@gmail.com",
                "password" => Hash::make("1234"),
                "InApp_notif" => "InApp_notif",
                "email_notif" => "email_notif"
            ],
            [
                "name" => "Faiza",
                "email" => "faiza12@gmail.com",
                "password" => Hash::make("1234"),
                "InApp_notif" => "InApp_notif",
                "email_notif" => "email_notif"
            ],
            [
                "name" => "Aiman",
                "email" => "aiman12@gmail.com",
                "password" => Hash::make("1234"),
                "InApp_notif" => "InApp_notif",
                "email_notif" => "email_notif"
            ],
        ];

        foreach ($testings as $testing) {
            Lbadmin::create($testing);
        }
    }
}