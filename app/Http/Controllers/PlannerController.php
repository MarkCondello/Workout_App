<?php

namespace App\Http\Controllers;

use App\IntervalResults;
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
                $workout->intervalsGrouped = PlannerController::getSavedWorkoutData($workout->id, true);
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

         User_Workout::create([
            'name' => request()->get('name'),
            'user_id' => auth()->user()->id
        ]);

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

        return view('planner.show')->with([
            'exercisesGrouped' => $this->getSavedWorkoutData($workoutId),
            'intervalsGrouped' => $this->getSavedWorkoutData($workoutId, true),
            'workoutName' => $workoutName,
            'workoutId' => $workoutId
        ]);
    }

    //This is almost the same as show except for the view and method name
    //there has to be a way to make it DRYer
    //ToDO: Add this as a method with params for the view
    public
    function startWorkout(
        $workoutId
    ) {
        //ToDo: use getWorkout name method
        $workoutName = User_Workout::select('name')
            ->where([
                ['user_id', '=', auth()->user()->id],
                ['id', '=', $workoutId]
            ])
            ->get();

        return view('planner.start')->with([
            'exercisesGrouped' => $this->getSavedWorkoutData($workoutId),
            'intervalsGrouped' => $this->getSavedWorkoutData($workoutId, true),
            'workoutName' => $workoutName,
            'workoutId' => $workoutId
        ]);
    }

    public
    function editWorkout(
        $workoutId
    ) {

        //ToDo: use getWOrkout name method
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
        return $this->showWorkout($workoutId);
    }

    public
    function saveResults(
        $workoutId
    ) {

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

        $workout = User_Workout::create([
             'name' => request('workoutName'),
            'user_id' => auth()->user()->id
        ]);

        $copiedWorkoutExercises = User_Workout::find($workoutId)->exercise_workouts;
        $copiedIntervalGroups = User_Workout::find($workoutId)->interval_groups;

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
        return $this->showWorkout($workout->id);
    }


    //ToDO: this should be added to a service layer and split into intervals and exercises.
    public
    static
    function getSavedWorkoutData(
        $workoutId,
        $getIntervals = false,
        $grouped = true
    ) {

        if (!$getIntervals) {
            $exercises = User_Workout::find($workoutId)->exercise_workouts
                ->where('interval_group_id', '==', null);

            foreach ($exercises as $exercise) :
                $exercise['name'] = Exercise::find($exercise->exercise_id)->name;
                $exercise['type'] = Exercise::find($exercise->exercise_id)->exerciseType->name;
                $exercise['result'] = ExerciseWorkout::find($exercise->id)->result;
            endforeach;
            if ($grouped):
                $exercises = $exercises->groupBy('exercise_id')->toArray();
            endif;

            return $exercises;
        }

        if ($getIntervals) {
            $intervalGroups = User_Workout::find($workoutId)->interval_groups->toArray();
            foreach ($intervalGroups as $key => $group):
                $intervalGroups[$key]['exercises_grouped'] = IntervalGroup::find($group['id'])->exerciseWorkouts
                    ->groupBy('exercise_id')
                    ->toArray();

                $intervalGroups[$key]['interval_results'] = IntervalGroup::find($group['id'])->intervalResults;

                foreach ($intervalGroups[$key]['exercises_grouped'] as $i => $exercisesGroup):
                    foreach ($exercisesGroup as $j => $exercises):
                        $intervalGroups[$key]['exercises_grouped'][$i][$j]['name'] = Exercise::find($exercises['exercise_id'])->name;
                        $intervalGroups[$key]['exercises_grouped'][$i][$j]['type'] = Exercise::find($exercises['exercise_id'])->exerciseType->name;
                    endforeach;
                endforeach;
            endforeach;

            return $intervalGroups;
        }
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