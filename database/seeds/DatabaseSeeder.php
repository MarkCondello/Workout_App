<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(Exercise_Type_table_seeder::class);
        $this->call(Exercise_table_seeder::class);
        $this->call(User_table_seeder::class);

    }
}
