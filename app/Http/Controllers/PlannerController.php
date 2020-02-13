<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exercise;
use App\User_Workout;
use App\ExerciseWorkout;
use App\Result;
use DB;

class PlannerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
//    public function __construct()
//    {
//        $this->middleware('auth');
//    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user_workouts = User_Workout::all()
            ->where('user_id', '=', auth()->user()->id);

        $sorted_user_workouts = $user_workouts->sortByDesc(function ($item) {
            return $item->created_at;
        });

        return view('planner.index')->with(['workouts' => $sorted_user_workouts]);;
    }

    public function createWorkout()
    {
        request()->validate([
            'name' => 'required|unique:user_workouts,name'
        ]);

        $workout = new User_Workout();
        $workout->name = request('name');
        $workout->user_id = auth()->user()->id;
        $workout->save();

        $user_workouts = User_Workout::all()
            ->where('user_id', '=', auth()->user()->id);

        // $article = App\Article::take(3)->latest()->get(); DOESNT WORK!!!
        $sorted_user_workouts = $user_workouts->sortByDesc(function ($item) {
            return $item->created_at;
        });

        session()->flash('status', 'A new workout was created.');
        return view('planner.index')->with(['workouts' => $sorted_user_workouts]);
    }

    public function deleteWorkout($workoutId)
    {
        User_Workout::first()->where('id', '=', $workoutId)->delete();
        session()->flash('flashWarning', 'Workout was deleted.');
        return redirect('/planner');
    }

    public function showWorkout($workoutId)
    {
        $workoutName = User_Workout::select('name')
            ->where([
                ['user_id', '=', auth()->user()->id],
                ['id', '=', $workoutId]
            ])
            ->get();

        $exercises = DB::table('exercise_workouts')
            ->join('exercises', 'exercise_workouts.exercise_id', '=', 'exercises.id')
            ->where('workout_id', '=', $workoutId)
            ->get();

        //combined the results with more than 1 set
        $collection = collect($exercises);
        $exercises = $collection->unique();
        $exercises = $exercises->values()->all();

        return view('planner.show')->with([
            'exercises' => $exercises,
            'workoutName' => $workoutName,
            'workoutId' => $workoutId
        ]);
    }

    public function startWorkout($workoutId)
    {
        $workoutName = User_Workout::select('name')
            ->where([
                ['user_id', '=', auth()->user()->id],
                ['id', '=', $workoutId]
            ])
            ->get();

        $exercises = $this->getSavedWorkoutData($workoutId);

        return view('planner.start')->with([
            'exercises' => $exercises,
            'workoutName' => $workoutName,
            'workoutId' => $workoutId
        ]);
    }

    public function addWeights($workoutId)
    {
        $weights = Exercise::select('id', 'name')
            ->where('exercise_type_id', '=', 1)
            ->get();

        $exercises = [];
        foreach ($weights as $weight) {
            $exercises += array($weight['id'] => $weight['name']);
        }

        $workoutName = User_Workout::select('name')
            ->where([
                ['id', '=', $workoutId],
                ['user_id', '=', auth()->user()->id]
            ])
            ->get();

        return view('planner.weights')
            ->with([
                'weights' => $exercises,
                'workoutId' => $workoutId,
                'workoutName' => $workoutName
            ]);
    }

    public function storeWeight($workoutId)
    {
        request()->validate([
            'exercise' => 'required',
            'reps' => 'numeric|min:1',
            'weight' => 'numeric|min:1',
            'sets' => 'numeric|min:1',
        ]);

        $sets = request('sets');
        while ($sets > 0):
            $exerciseWorkout = new ExerciseWorkout();
            $exerciseWorkout->workout_id = $workoutId;
            $exerciseWorkout->exercise_id = request('exercise');
            $exerciseWorkout->reps = request('reps');
            $exerciseWorkout->weight = request('weight');
            $exerciseWorkout->sets = request('sets');
            $exerciseWorkout->save();
            --$sets;
        endwhile;

        session()->flash('flashNotice', 'Weights are successfully added to your workout.');
        return redirect('/planner');
    }

    public function saveResults($workoutId)
    {
        $exerciseData = $this->getSavedWorkoutData($workoutId);
        $registeredFields = [];

        function id($val)
        {
            return preg_replace('/\s+/', '_', strtolower($val->name)) . '_id-' . $val->id;
        }

        foreach ($exerciseData as $exercise):
            $identifyer = id($exercise);
            switch ($exercise->exercise_type_id):
                case(1):
                    $registeredFields['recorded-reps_' . $identifyer] = 'required|min:1';
                    $registeredFields['recorded-weight_' . $identifyer] = 'required|min:1';
            endswitch;
        endforeach;
          //dd($registeredFields);
        request()->validate([
            'recorded-reps_bench_press_id-1' => 'required',
        ]);
        //request()->validate($registeredFields);
        // dd("Reached After validation."); DID NOT REACH PAST VALIDATION

        $formValues = request()->all();
        foreach ($exerciseData as $exercise):
            $identifyer = id($exercise);

            switch ($exercise->exercise_type_id):
                //weights exercise type
                case(1):
                    $result = new Result();
                    $result->exercise_workout_id = $formValues['exercise_workout_id_' . $identifyer];
                    $result->recorded_reps = $formValues['recorded-reps_' . $identifyer];
                    $result->recorded_weight = $formValues['recorded-weight_' . $identifyer];
                    $result->recorded_time = now();
                    $result->save();
                    break;
            endswitch;
        endforeach;

        session()->flash('flashNotice', 'Workout results saved successfully.');
        return redirect('/planner');
    }

    public function getSavedWorkoutData($id)
    {
        return DB::table('exercises')
            ->leftJoin('exercise_workouts', 'exercises.id', '=', 'exercise_workouts.exercise_id')
            ->where('workout_id', '=', $id)
            ->get();
    }
}
