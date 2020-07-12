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

        DB::table('users')->insert([
            'name' => 'JD',
            'email' => 'jd@gmail.com',
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
            'password' => Hash::make('password'),
        ]);

        $admin = new App\Role();
        $admin->name = 'admin';
        $admin->label = 'Administrator';
        $admin->save();

        $access_admin = new App\Ability();
        $access_admin->name = 'access_admin';
        $access_admin->save();

        $admin->allowTo($access_admin);

        $me = new App\User();
        $me->name = 'Mark Condello';
        $me->email = 'condellomark@gmail.com';
        $me->email_verified_at = now();
        $me->remember_token = Str::random(10);
        $me->password = Hash::make('password');
        $me->save();

        $me->assignRole('admin');
    }
}
