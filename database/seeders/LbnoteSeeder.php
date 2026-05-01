<?php

namespace Database\Seeders;

use App\Models\note\Lbnote;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LbnoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $testings = array(
            array(
                "title"=>"Processing System",
                "subject"=>"Computer Science",
                "pdf_file"=>"pdf_file",
            ),
            array(
                "title"=>"Scientific System",
                "subject"=>"Mathematics",
                "pdf_file"=>"pdf_file",
            ),
           
        );

        foreach($testings as $testing)
        {
            Lbnote::create($testing);   
        }
    }
    }
