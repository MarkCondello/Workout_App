<?php

namespace App\Http\Controllers\Cardio;

use App\ExerciseTypes;
use App\ExerciseWorkout;
use app\Http\Controllers\Controller;
use App\User_Workout;
use Illuminate\Http\Request;

class CardioController extends Controller
{


    public
    function addCardio($workoutId) {
        //Add to a service layer???
        $cardio = ExerciseTypes::find(2)->exercises->map(function ($exercise) {
            return [$exercise->id => $exercise->name];
        });

        $exercises = [];
        foreach ($cardio as $item) {
            $exercises += $item;
        }

        //Add to a service layer???
        $workoutName = User_Workout::find($workoutId)->get('name');
        return view('planner.cardio')
            ->with([
                'cardio' => $exercises,
                'workoutId' => $workoutId,
                'workoutName' => $workoutName
            ]);
    }

    public
    function store(
        $workoutId
    ) {
        request()->validate([
            'exercise' => 'required',
            'sets' => 'required|min:1'
//ToDO: need to find out if there is conditional validation, if mins is 0, seconds must exist, vice versa
//            'mins' => 'numeric|min:1',
//            'seconds' => 'numeric|min:1',
        ]);

        $mins = request('mins');
        $minutes = $mins >= 10 ? $mins : '0' . $mins;
        $secs = request('seconds');
        $seconds = $secs >= 10 ? $secs : '0' . $secs;

        $exerciseWorkout = new ExerciseWorkout();
        $exerciseWorkout->workout_id = $workoutId;
        $exerciseWorkout->exercise_id = request('exercise');
        $exerciseWorkout->time = '00 :' . $minutes . ':' . $seconds;
        $exerciseWorkout->sets = request('sets');

        $exerciseWorkout->save();
        session()->flash('flashNotice', 'Cardio was successfully included to your workout.');
        return redirect('/planner');
    }
}
