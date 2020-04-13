<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExerciseTypes extends Model
{
    //
    protected $table = 'exercise_type';

    public function exercises(){
        return $this->hasMany('App\Exercise', 'exercise_type_id');
    }

}
