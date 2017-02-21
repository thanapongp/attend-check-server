<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FacultiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //DB::table('faculties')->truncate();
        
        DB::table('faculties')->insert([
            ['id' => '11', 'name' => 'วิทยาศาสตร์']
        ]);
    }
}
