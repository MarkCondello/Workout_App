<?php

use Illuminate\Database\Seeder;

class Exercise_Type_table_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('exercise_type')->insert([
            'name' => 'weight',
        ]);

        DB::table('exercise_type')->insert([
            'name' => 'cardio',
        ]);

        DB::table('exercise_type')->insert([
            'name' => 'calisthenics',
        ]);

        DB::table('exercise_type')->insert([
            'name' => 'stretching',
        ]);


    }
}
