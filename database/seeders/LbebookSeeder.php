<?php

namespace Database\Seeders;

use App\Models\ebook\Lbebook;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LbebookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $testings = array(
            array(
                "title"=>"Processing System",
                "author"=>"Computer Science",
                "dept"=>"IT",
                "pdf_file"=>"pdf_file",
            ),
             array(
                "title"=>"Processing System",
                "author"=>"Computer Science",
                "dept"=>"IT",
                "pdf_file"=>"pdf_file",
            ),
             array(
                "title"=>"Processing System",
                "author"=>"Computer Science",
                "dept"=>"IT",
                "pdf_file"=>"pdf_file",
            ),
             array(
                "title"=>"Processing System",
                "author"=>"Computer Science",
                "dept"=>"IT",
                "pdf_file"=>"pdf_file",
            ),
             array(
                "title"=>"Processing System",
                "author"=>"Computer Science",
                "dept"=>"IT",
                "pdf_file"=>"pdf_file",
            ),
           
        );

        foreach($testings as $testing)
        {
            Lbebook::create($testing);   
        }
    }
}
