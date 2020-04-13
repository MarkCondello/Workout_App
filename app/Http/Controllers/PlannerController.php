<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Exercise;
use App\ExerciseTypes;
use App\User_Workout;
use App\ExerciseWorkout;
use App\Result;
use App\IntervalGroup;
use DB;

class PlannerController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user_workouts = DB::table('user_workouts')
            ->where('user_id', '=', auth()->user()->id)
            ->where('has_results', '=', false)
            ->get();

        $user_results = DB::table('user_workouts')
            ->where('user_id', '=', auth()->user()->id)
            ->where('has_results', '=', true)
            ->get();

        function sortWorkouts($user_data)
        {
            return $user_data->sortByDesc(function ($workout) {
                $workout->exercisesGrouped = PlannerController::getSavedWorkoutData($workout->id);
                return $workout->created_at;
            });
        }

        $results = sortWorkouts($user_results);
        $workouts = sortWorkouts($user_workouts);

        return view('planner.index')->with([
            'results' => $results,
            'workouts' => $workouts
        ]);
    }

    public
    function createWorkout()
    {
        request()->validate([
            'name' => 'required|unique:user_workouts,name'
        ]);

        $workout = new User_Workout();
        $workout->name = request('name');
        $workout->user_id = auth()->user()->id;
        $workout->save();

        session()->flash('status', 'A new workout was created.');
        return redirect('planner');
    }

    public
    function deleteWorkout(
        $workoutId
    ) {
        User_Workout::first()->where('id', '=', $workoutId)->delete();
        session()->flash('flashWarning', 'Workout was deleted.');
        return redirect('/planner');
    }

    public
    function showWorkout(
        $workoutId
    ) {
        $workoutName = User_Workout::select('name')
            ->where([
                ['user_id', '=', auth()->user()->id],
                ['id', '=', $workoutId]
            ])
            ->get();

        $exercisesGrouped = $this->getSavedWorkoutData($workoutId);
        return view('planner.show')->with([
            'exercisesGrouped' => $exercisesGrouped,
            'workoutName' => $workoutName,
            'workoutId' => $workoutId
        ]);
    }

    public
    function startWorkout(
        $workoutId
    ) {
        $workoutName = User_Workout::select('name')
            ->where([
                ['user_id', '=', auth()->user()->id],
                ['id', '=', $workoutId]
            ])
            ->get();

        $exercisesGrouped = $this->getSavedWorkoutData($workoutId);
        return view('planner.start')->with([
            'exercisesGrouped' => $exercisesGrouped,
            'workoutName' => $workoutName,
            'workoutId' => $workoutId
        ]);
    }

    public
    function editWorkout(
        $workoutId
    ) {
        $workoutName = User_Workout::select('name')
            ->where([
                ['user_id', '=', auth()->user()->id],
                ['id', '=', $workoutId]
            ])
            ->get();

        $exercises = User_Workout::find($workoutId)->exercise_workouts;
        $data = [];
        //add the name and type for each exercise
        foreach ($exercises as $exercise):
            $exercise['name'] = Exercise::find($exercise->exercise_id)->name;
            $exercise['type'] = Exercise::find($exercise->exercise_id)->exerciseType->name;

            $data[] = $exercise;
        endforeach;

        return view('planner.edit')->with([
            'exercises' => $data,
            'workoutName' => $workoutName,
            'workoutId' => $workoutId
        ]);
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

    public
    function createInterval(
        $workoutId
    ) {

        $mins = request('mins');
        $minutes = $mins >= 10 ? $mins : '0' . $mins;
        $secs = request('seconds');
        $seconds = $secs >= 10 ? $secs : '0' . $secs;

        $intervalGroup = new IntervalGroup();
        $intervalGroup->workout_id = $workoutId;
        $intervalGroup->time = '00 :' . $minutes . ':' . $seconds;
        $intervalGroup->sets = request('sets');
        $intervalGroup->save();

        $workoutName = User_Workout::find($workoutId)->get('name');

        session()->flash('flashNotice', 'A new interval group is included in your workout.');
        return view('planner.intervals.index')
            ->with([
                "intervalGroup" => $intervalGroup,
                'workoutName' => $workoutName
            ]);
    }

    //landing page for intervals created for the user to see what exercises have been added and to include more exercises
    public
    function intervalDetails(
        $intervalId
    ) {
        $interval = IntervalGroup::find($intervalId);
        $workoutName = $this->getWorkoutName($interval->workout_id);

        //  check the exercise type in the request and get the stored exercise data if it exists
        if (request('exercise_type') === 'weights') {
            $exerciseWorkout = new ExerciseWorkout();
            $exerciseWorkout->workout_id = $interval->workout_id;
            $exerciseWorkout->interval_group_id = $interval->id;

            $exerciseWorkout->exercise_id = request('exercise');
            $exerciseWorkout->reps = request('reps');
            $exerciseWorkout->weight = request('weight');
            $exerciseWorkout->time = '00:00:00';

            $exerciseWorkout->save();

            session()->flash('flashNotice', 'A weight exercise has been is included in your interval.');

        }

        $intervalExercises = $interval->exerciseWorkouts;

        foreach ($intervalExercises as $key => $exercise) {
            $intervalExercises[$key]['name'] = Exercise::find($exercise->exercise_id)->name;
            $intervalExercises[$key]['exercise_type'] =  ExerciseTypes::find( Exercise::find($exercise->exercise_id)->exercise_type_id )->name;
         }

         return view('planner.intervals.index')->with([
            'workoutName' => $workoutName,
            'intervalGroup' => $interval,
            'intervalExercises' => $intervalExercises,

        ]);

    }

    public
    function addIntervalWeights(
        $intervalId
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
                'weights' => $exercises,
                'intervalId' => $intervalId,
            ]);


    }

//    public function storeIntervalWeights($intervalId){
//        die("Reached store interval weights");
//    }

    public
    function addIntervalCardio(
        $intervalId
    ) {

    }

    public
    function addCardio(
        $workoutId
    ) {
        $cardio = ExerciseTypes::find(5)->exercises->map(function ($exercise) {
            return [$exercise->id => $exercise->name];
        });

        $exercises = [];
        foreach ($cardio as $cardio) {
            $exercises += $cardio;
        }
        $workoutName = User_Workout::find($workoutId)->get('name');
        return view('planner.cardio')
            ->with([
                'cardio' => $exercises,
                'workoutId' => $workoutId,
                'workoutName' => $workoutName
            ]);
    }

    public
    function storeWeight(
        $workoutId
    ) {
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
    function storeCardio(
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

    public
    function updateWorkout(
        $workoutId
    ) {
        $exercise_workout_id_items = request()->get('exercise_workout_id');
        $exercise_type = request()->get('exercise_type');
        $exercise_reps = request()->get('reps');
        $exercise_weight = request()->get('weight');
        $exercise_sets = request()->get('sets');
        $mins = request()->get('mins');
        $secs = request()->get('seconds');

        foreach ($exercise_workout_id_items as $key => $item):
            switch ($exercise_type[$key]) {
                case 'weight':
                    DB::table('exercise_workouts')
                        ->where('id', '=', $item)
                        ->update([
                            'reps' => $exercise_reps[$key],
                            'weight' => $exercise_weight[$key],
                            'sets' => $exercise_sets[$key]
                        ]);
                    break;
                case 'cardio':
                    $min = $mins[$key];
                    $sec = $secs[$key];
                    $minutes = $min >= 10 ? $min : '0' . $min;
                    $seconds = $sec >= 10 ? $sec : '0' . $sec;

                    DB::table('exercise_workouts')
                        ->where('id', '=', $item)
                        ->update([
                            'time' => '00 :' . $minutes . ':' . $seconds,
                            'sets' => $exercise_sets[$key]
                        ]);
                    break;
            }
        endforeach;
        session()->flash('flashNotice', 'Workout details are updated.');
        return $this->showWorkout($workoutId);
    }

    public
    function saveResults(
        $workoutId
    ) {
        $formIds = request('exercise_workout_id');
        $formReps = request('reps');
        $formWeights = request('weight');

        foreach ($formIds as $key => $value):
            $result = new Result();
            $result->exercise_workout_id = $value;
            $result->recorded_reps = $formReps[$key];
            $result->recorded_weight = $formWeights[$key];
            $result->recorded_time = now();
            $result->save();
        endforeach;

        DB::table('user_workouts')
            ->where('id', '=', $workoutId)
            ->update(['has_results' => true]);

        session()->flash('flashNotice', 'Workout results saved successfully.');
        return redirect('/planner');
    }

    public
    function copyWorkout(
        $workoutId
    ) {
        $workoutName = $this->getWorkoutName($workoutId);

        return view('planner.copy')
            ->with([
                'workoutId' => $workoutId,
                'workoutName' => $workoutName
            ]);
    }

    public
    function saveCopiedWorkout(
        $workoutId
    ) {
        request()->validate([
            'workoutName' => 'required|unique:user_workouts,name'
        ]);

        $workout = new User_Workout();
        $workout->name = request('workoutName');
        $workout->user_id = auth()->user()->id;
        $workout->save();

        $copiedWorkoutExercises = User_Workout::find($workoutId)->exercise_workouts;
        // dd($copiedWorkoutExercises);

        // build up new exercises which reference the new workoutid
        foreach ($copiedWorkoutExercises as $exercise):
            $exerciseWorkout = new ExerciseWorkout();
            $exerciseWorkout->workout_id = $workout->id;
            $exerciseWorkout->exercise_id = $exercise['exercise_id'];
            $exerciseWorkout->reps = $exercise['reps'];
            $exerciseWorkout->weight = $exercise['weight'];
            $exerciseWorkout->sets = $exercise['sets'];
            $exerciseWorkout->save();
        endforeach;

        session()->flash('flashNotice', 'Workout' . $workout->name . 'has been created.');
        return $this->showWorkout($workout->id);
    }

    public
    static
    function getSavedWorkoutData(
        $workoutId,
        $grouped = true
    ) {
        $exercises = User_Workout::find($workoutId)->exercise_workouts;
        //add the name and result for each exercise
        foreach ($exercises as $exercise) {
            $exercise['name'] = Exercise::find($exercise->exercise_id)->name;
            $exercise['type'] = Exercise::find($exercise->exercise_id)->exerciseType->name;
            $exercise['result'] = ExerciseWorkout::find($exercise->id)->result;
        }
        if ($grouped):
            $exercises = $exercises->groupBy('exercise_id')->toArray();
        endif;
        return $exercises;
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
