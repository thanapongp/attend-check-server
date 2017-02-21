<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //DB::table('users')->truncate();
        
        DB::table('users')->insert([
            'username' => 'admin',
            'password' => bcrypt(env('ADMIN_DEFAULT_PASS')),
            'email' => 'admin@admin.com',
            'title' => 'นาย',
            'name'     => 'admin',
            'lastname' => 'admin',
            'faculty_id' => '11',
            'type_id' => '1',
            'active' => 1,
        ]);
    }
}
