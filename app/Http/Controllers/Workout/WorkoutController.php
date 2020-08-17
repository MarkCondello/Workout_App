<?php

namespace App\Http\Controllers\Workout;

use App\IntervalResults;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exercise;
use App\ExerciseTypes;
use App\User_Workout;
use App\ExerciseWorkout;
use App\Result;
use App\IntervalGroup;
use App\Services\PlannerService;
use DB;
use App\Http\Requests\CreateWorkoutRequest;
use App\Http\Requests\Site\CopyWorkoutRequest;

//ToDo: Controller should just navigate trafic to views, db logic and processing should be added to model and service layers respectivley
class WorkoutController extends Controller
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
        $results = PlannerService::sortWorkouts( 
            auth()->user()->workouts->where('has_results', true) 
        );
        $workouts = PlannerService::sortWorkouts( 
            auth()->user()->workouts->where('has_results', false)
        );

        return view('planner.index')
        ->with(compact(
            'results' ,
            'workouts'  
        ));
    }

    public function create(CreateWorkoutRequest $request )
    {
        User_Workout::create([
            'name' => request()->get('name'),
            'user_id' => auth()->user()->id
        ]);

        session()->flash('status', 'A new workout was created.');
        return redirect('planner');
    }

    public function destroy(User_Workout $workout) {      
        $workout->delete();
        session()->flash('flashWarning', 'Workout was deleted.');
        return redirect('planner');
    }

    public function show(User_Workout $workout) 
    {
        return view('planner.show')
        ->with( PlannerService::showStartWorkoutData($workout) );
    }
    
    public function start(User_Workout $workout) {
        return view('planner.start')
        ->with( PlannerService::showStartWorkoutData($workout) );
    }

    public function edit(User_Workout $workout) 
    {
        $exercises = [];
        //add the name and type for each exercise
        foreach ($workout->exercise_workouts as $exercise):
            $exercise['name'] = Exercise::find($exercise->exercise_id)->name;
            $exercise['type'] = Exercise::find($exercise->exercise_id)->exerciseType->name;
            $exercises[] = $exercise;
        endforeach;

        return view('planner.edit')
            ->with(compact('exercises', 'workout'));
    }

    //ToDo: add the update back in, needs intervals added
    public function update(User_Workout $workout) 
    {
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

                    //ToDO: use update model method instead Exercise_Workouts::update( [ key => vals] )
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
                    //ToDO: use update model method instead Exercise_Workouts::update( [ key => vals] )

                    DB::table('exercise_workouts')
                        ->where('id', '=', $item)
                        ->update([
                            'time' => '00:' . $minutes . ':' . $seconds,
                            'sets' => $exercise_sets[$key]
                        ]);
                    break;
            }
        endforeach;
        session()->flash('flashNotice', 'Workout details are updated.');
        return $this->show($workout);
    }

    public function save(User_Workout $workout) 
    {
        $intervalSets = request('interval_sets');
        if (!is_null($intervalSets)):
            foreach ($intervalSets as $key => $val):
                //ToDO: use the model to save
                $intervalResult = new IntervalResults();
                $intervalResult->interval_group_id = $key;
                $intervalResult->sets_completed = $val;
                $intervalResult->save();
            endforeach;
        endif;

        $exerciseTypes = request('exercise_type');
        $formIds = request('exercise_workout_id');
        $formReps = request('reps');
        $formWeights = request('weight');
        $formTimes = request('time');

        if (!is_null($exerciseTypes)):
            foreach ($exerciseTypes as $index => $typeGroup):
                foreach ($typeGroup as $key => $type):
                    $result = new Result();
                    $result->exercise_workout_id = $formIds[$index][$key];

                    switch ($type):
                        case 'cardio' :
                            $result->recorded_time = $formTimes[$index][$key];
                            $result->recorded_reps = 0;
                            $result->recorded_weight = 0;
                            break;
                        case 'weight' :
                            $result->recorded_time = '00:00:00';
                            $result->recorded_reps = $formReps[$index][$key];
                            $result->recorded_weight = $formWeights[$index][$key];
                            break;
                    endswitch;

                    $result->save();
                endforeach;
            endforeach;
        endif;

        $workout->has_results = true;
        $workout->save();
 
        session()->flash('flashNotice', "Workout {$workout->name} results saved successfully.");
        return redirect('planner');
    }

    public function copy(User_Workout $workout) 
    {
        return view('planner.copy')
            ->with(compact('workout'));
    }

    public function saveCopy(
        CopyWorkoutRequest $request, 
        User_Workout $workoutCopy) 
        {
 
        $workout = User_Workout::create([
            'name' => request('workoutName'),
            'user_id' => auth()->user()->id
        ]);

        $copiedWorkoutExercises =  $workoutCopy->exercise_workouts;
        $copiedIntervalGroups = $workoutCopy->interval_groups;

        //get intervalGroups and save each items data to a new entity
        $copiedIntervalExercises = [];
        foreach ($copiedIntervalGroups as $key => $copiedIntervalGroup):
            $groupId = $copiedIntervalGroup->id;
        //user create on the model
            $intervalGroup = new IntervalGroup();
            $intervalGroup->workout_id = $workout->id;
            $intervalGroup->time = $copiedIntervalGroup->time;
            $intervalGroup->sets = $copiedIntervalGroup->sets;
            $intervalGroup->save();

            $copiedIntervalExercises = IntervalGroup::find($groupId)->exerciseWorkouts;
            //get the exercises in the Interval Groups and save those against the IntervalGroup Id ->exerciseWorkouts;
            foreach ($copiedIntervalExercises as $copiedIntervalExercise):
                //user create on the model
                $exerciseWorkout = new ExerciseWorkout();
                $exerciseWorkout->workout_id = $workout->id;
                $exerciseWorkout->exercise_id = $copiedIntervalExercise['exercise_id'];
                $exerciseWorkout->interval_group_id = $intervalGroup->id;
                $exerciseWorkout->reps = $copiedIntervalExercise['reps'];
                $exerciseWorkout->weight = $copiedIntervalExercise['weight'];
                $exerciseWorkout->sets = $copiedIntervalExercise['sets'];
                $exerciseWorkout->time = $copiedIntervalExercise['time'];
                $exerciseWorkout->distance = $copiedIntervalExercise['distance'];
                $exerciseWorkout->save();
            endforeach;
        endforeach;

        // build up new exercises which reference the new workout id
        foreach ($copiedWorkoutExercises as $exercise):
            if (is_null($exercise->interval_group_id)):
                $exerciseWorkout = new ExerciseWorkout();
                $exerciseWorkout->workout_id = $workout->id;
                $exerciseWorkout->exercise_id = $exercise['exercise_id'];
                $exerciseWorkout->reps = $exercise['reps'];
                $exerciseWorkout->weight = $exercise['weight'];
                $exerciseWorkout->sets = $exercise['sets'];
                $exerciseWorkout->save();
            endif;
        endforeach;

        session()->flash('flashNotice', 'Workout' . $workout->name . 'has been created.');
        return $this->show($workout);
    }
}