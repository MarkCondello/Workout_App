<?php

namespace App\Http\Controllers\Interval;

use App\Exercise;
use App\ExerciseTypes;
use App\ExerciseWorkout;
use App\IntervalGroup;
use App\User_Workout;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class IntervalController extends Controller
{
    //
    public function intervals($workoutId)
    {
        return view('planner.intervals.interval')->with([
            'workoutName' => $this->getWorkoutName($workoutId),
            'workoutId' => $workoutId,
        ]);
    }

    public
    function create(
        $workoutId
    ) {
        $mins = request('mins');
        $minutes = $mins >= 10 ? $mins : '0' . $mins;
        $secs = request('seconds');
        $seconds = $secs >= 10 ? $secs : '0' . $secs;

        $intervalGroup = new IntervalGroup();
        $intervalGroup->workout_id = $workoutId;
        $intervalGroup->time = '00:' . $minutes . ':' . $seconds;
        $intervalGroup->sets = request('sets');
        $intervalGroup->save();

        return redirect()
            ->route('interval-show', [
                'workoutId' => $workoutId,
                "intervalId" => $intervalGroup->id,
            ])->with('status', 'An interval group is included in your workout.');
    }

    public function show($workoutId, $intervalId)
    {
        $workoutName = User_Workout::find($workoutId)->get('name');
        $intervalExercises = IntervalGroup::find($intervalId)->exerciseWorkouts;

        foreach ($intervalExercises as $key => $exercise) {
            $intervalExercises[$key]['name'] = Exercise::find($exercise->exercise_id)->name;
            $intervalExercises[$key]['exercise_type'] = ExerciseTypes::find(Exercise::find($exercise->exercise_id)->exercise_type_id)->name;
        }

        $intervalGroup = IntervalGroup::find($intervalId);
        return view('planner.intervals.index')
            ->with([
                'workoutId' => $workoutId,
                "intervalGroup" => $intervalGroup,
                "intervalId" => $intervalGroup->id,
                "workoutName" => $workoutName,
                'intervalExercises' => $intervalExercises,
            ]);
    }

    public
    function intervalDetails(
        $workoutId,
        $intervalId
    ) {
//dd("Reached int details");
        $intervalGroup = IntervalGroup::find($intervalId);

        //  check the exercise type in the request and get the stored exercise data if it exists
        if (request('exercise_type') === 'weights') {
            $exerciseWorkout = new ExerciseWorkout();
            $exerciseWorkout->workout_id = $intervalGroup->workout_id;
            $exerciseWorkout->interval_group_id = $intervalGroup->id;

            $exerciseWorkout->exercise_id = request('exercise');
            $exerciseWorkout->reps = request('reps');
            $exerciseWorkout->weight = request('weight');
            $exerciseWorkout->time = '00:00:00';
            $exerciseWorkout->save();

            return redirect()
                ->route('interval-show', [
                    'workoutId' => $workoutId,
                    "intervalId" => $intervalGroup->id,
                ])->with('status', 'A weight exercise has been is included in your interval.');
        }

        if (request('exercise_type') === 'cardio') {
            $exerciseWorkout = new ExerciseWorkout();
            $exerciseWorkout->workout_id = $intervalGroup->workout_id;
            $exerciseWorkout->interval_group_id = $intervalGroup->id;
            $exerciseWorkout->exercise_id = request('exercise');
            $exerciseWorkout->reps = request('reps');
            $exerciseWorkout->distance = request('distance');
            $exerciseWorkout->time = '00:00:00';
            $exerciseWorkout->save();

            return redirect()
                ->route('interval-show', [
                    'workoutId' => $workoutId,
                     "intervalId" => $intervalGroup->id,
                ])->with('status', 'A cardio exercise has been is included in your interval.');
        }
    }

    public
    function addWeights(
        $intervalId,
        $workoutId
    ) {

        $weights = ExerciseTypes::find(1)->exercises->map(function ($exercise) {
            return [$exercise->id => $exercise->name];
        });

        $exercises = [];
        foreach ($weights as $weight) {
            $exercises += $weight;
        }


        return view('planner.intervals.add-weights')
            ->with([
                'workoutId' => $workoutId,
                'weights' => $exercises,
                'intervalId' => $intervalId,
            ]);
    }


    public
    function addCardio(
        $intervalId,
        $workoutId
    ) {
        $cardio = ExerciseTypes::find(2)->exercises->map(function ($exercise) {
            return [$exercise->id => $exercise->name];
        });

        $exercises = [];
        foreach ($cardio as $item) {
            $exercises += $item;
        }

        return view('planner.intervals.add-cardio')
            ->with([
                'workoutId' => $workoutId,
                'cardio' => $exercises,
                'intervalId' => $intervalId,
            ]);
    }


    public
    function delete(
        $intervalId
    ) {
        $interval = IntervalGroup::find($intervalId);
        $interval->delete();

        session()->flash('status', 'Interval id:' . $intervalId . ' was deleted.');
        return redirect('planner');
    }

    public
    function getWorkoutName(
        $workoutId
    ) {

        $workoutName = User_Workout::select('name')
            ->where([
                ['id', '=', $workoutId],
                ['user_id', '=', auth()->user()->id]
            ])
            ->get();
        return $workoutName;
    }
}
