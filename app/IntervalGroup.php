<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IntervalGroup extends Model
{
    //
    protected $fillable = ['time', 'sets'];
    public $timestamps = false;


    public function exerciseWorkouts( )
    {
        $this->hasMany( 'App\ExerciseWorkout' );
    }
}
