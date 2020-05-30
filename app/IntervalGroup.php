<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IntervalGroup extends Model
{
    //
    protected $fillable = ['time', 'sets'];
    public $timestamps = false;

    public function exerciseWorkouts()
    {
        return $this->hasMany( 'App\ExerciseWorkout' );
    }

    public function userWorkout(){
        return $this->belongsTo('App\User_Workout', 'workout_id');
    }

    public function intervalResults(){
        return $this->hasOne('App\IntervalResults');
    }
}
