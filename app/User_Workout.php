<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User_Workout extends Model
{
    //
    protected $fillable = ['title', ];
    protected $table = 'user_workouts';

    public function user(){
       return $this->belongsTo('App\User');
    }

     public function exercise_workouts(){
       return $this->hasMany('App\ExerciseWorkout', 'workout_id');
    }

}
