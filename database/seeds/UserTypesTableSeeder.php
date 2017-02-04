<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('user_types')->truncate();
        
        DB::table('user_types')->insert([
            ['name' => 'admin', 'label' => 'admin'],
            ['name' => 'fac_admin', 'label' => 'ผู้ดูแลข้อมูลประจำคณะ'],
            ['name' => 'teacher', 'label' => 'อาจารย์'],
            ['name' => 'student', 'label' => 'นัหศึกษา'],
        ]);
    }
}
