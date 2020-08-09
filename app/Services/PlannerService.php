<?php
namespace App\Services;

use App\User_Workout;
use App\Exercise;
use App\ExerciseWorkout;
use App\IntervalGroup;

class PlannerService
{

    public static function getUserWorkoutExerciseData(
        $workoutId,
        $grouped = true
     ) {
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

    public static function getUserWorkoutIntervals($workoutId)
    {
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

    public static function sortWorkouts($collection)
    {
        return $collection->sortByDesc(function ($workout)  {
            $workout->exercisesGrouped = PlannerService::getUserWorkoutExerciseData($workout->id);
            $workout->intervalsGrouped = PlannerService::getUserWorkoutIntervals($workout->id);
            return $workout->created_at;
        });
    }

}