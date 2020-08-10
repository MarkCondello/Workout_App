<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class Admin_role_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
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
