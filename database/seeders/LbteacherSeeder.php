<?php

namespace Database\Seeders;

use App\Models\teacher\Lbteacher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LbteacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $testings = array(
            array(
                "name"=>"Urwa",
                "email"=>"urwa12@gmail.com",
                "phone"=>"09176726",
                "password"=>"1234",
            ),
              array(
                "name"=>"Saira",
                "email"=>"saira12@gmail.com",
                "phone"=>"09176726",
                "password"=>"1234",
            ),
             array(
                "name"=>"Amna",
                "email"=>"amna656@gmail.com",
                "phone"=>"09176726",
                "password"=>"1234",
            ),
             array(
                "name"=>"Meerab",
                "email"=>"meeran762@gmail.com",
                "phone"=>"09176726",
                "password"=>"1234",
            ),
             array(
                "name"=>"Irfa",
                "email"=>"irfa712@gmail.com",
                "phone"=>"09176726",
                "password"=>"1234",
            ),
             array(
                "name"=>"Ayesha",
                "email"=>"ayesha12@gmail.com",
                "phone"=>"09176726",
                "password"=>"1234",
            ),
           
        );

        foreach($testings as $testing)
        {
            Lbteacher::create($testing);   
        }
    }
}
