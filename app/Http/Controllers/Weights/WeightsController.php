<?php

namespace App\Http\Controllers\Weights;

use App\ExerciseTypes;
use App\ExerciseWorkout;
use App\User_Workout;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WeightsController extends Controller
{
    public
    function store($workoutId) {
         request()->validate([
            'exercise' => 'required',
            'reps' => 'numeric|min:1',
            'weight' => 'numeric|min:1',
            'sets' => 'numeric|min:1',
        ]);

        //generate exercises based on sets value sent
        $setsCount = request('sets');
        while ($setsCount > 0):
            $exerciseWorkout = new ExerciseWorkout();
            $exerciseWorkout->workout_id = $workoutId;
            $exerciseWorkout->exercise_id = request('exercise');
            $exerciseWorkout->reps = request('reps');
            $exerciseWorkout->weight = request('weight');
            $exerciseWorkout->sets = request('sets');

            $exerciseWorkout->time = '00:00:00';
            $exerciseWorkout->save();
            --$setsCount;
        endwhile;

        session()->flash('flashNotice', 'Weights are successfully included to your workout.');
        return redirect('/planner');
    }

    public
    function addWeights(
        $workoutId
    ) {

        $workoutName = User_Workout::find($workoutId)->get('name');

        return view('planner.weights')
            ->with([
                'weights' => $this->getWeights(),
                'workoutId' => $workoutId,
                'workoutName' => $workoutName
            ]);
    }

    public function getWeights()
    {
        $weights = ExerciseTypes::find(1)->exercises->map(function ($exercise) {
            return [$exercise->id => $exercise->name];
        });

        $exercises = [];
        foreach ($weights as $weight) {
            $exercises += $weight;
        }

        return $exercises;
    }

}
