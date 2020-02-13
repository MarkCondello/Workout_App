<?php

use Illuminate\Database\Seeder;

class Exercise_table_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('exercises')->insert([
            'name' => 'Bench Press',
            'exercise_type_id' => 1,
            'instructions' => 'Lie on the flat bench, pull the wieght off the rack, bring the bar bell down to chest while inhaling. Exhale and push the bar away from the chest.',
            'equipment' => 'Flat Bench, Bar bell, Weight plates.',
            'muscle_group' => 'Pec, Deltoids',
        ]);

        DB::table('exercises')->insert([
            'name' => 'Bent Over Row',
            'exercise_type_id' => 1,
            'instructions' => 'Bend over with back straight and pull the bar to the torso and hold while inhaling. SLowly release the bar downward while exhaling.',
            'equipment' => 'Bar bell, Weight plates.',
            'muscle_group' => 'Lats, Biceps',
        ]);

        DB::table('exercises')->insert([
            'name' => 'Squats',
            'exercise_type_id' => 1,
            'instructions' => 'Do this exercise carefully.',
            'equipment' => 'Bar Rack, Bar bell, Weight Plates.',
            'muscle_group' => 'Quads, Glutes, Lower back, Hamstrings.',
        ]);
    }
}
