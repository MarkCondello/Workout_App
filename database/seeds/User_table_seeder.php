<?php

use Illuminate\Database\Seeder;

class User_table_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('users')->insert([
            'name' => 'JD',
            'email' => 'jd@gmail.com',
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
            'password' => Hash::make('password'),
        ]);

        DB::table('users')->insert([
            'name' => 'Mark Condello',
            'email' => 'condellomark@gmail.com',
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
            'password' => Hash::make('password'),
        ]);

    }
}
