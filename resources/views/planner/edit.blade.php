@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-8">
                <div class="card mb-5">
                    <div class="card-header d-flex justify-content-between">
                        <h2>Edit {{$workoutName[0]->name}}</h2>
                    </div>
                    {!! Form::open(['route' => ['planner.update-workout', $workoutId], 'action' => 'PlannerController@updateWorkkout', 'method' => 'POST']) !!}
                    <div class="card-body">
                        @foreach($exercises as $key=>$exercise)
                            @php $exerciseType = $exercise->type; @endphp
                            @switch($exerciseType)
                                @case('weight')
                                <h3>{{$exercise->name}}</h3>
                                <div class="d-flex justify-content-between">
                                    <!-- if the workout type is weights, we will expect an id, set number, reps and weights -->
                                    {{Form::hidden('exercise_workout_id[' .  $key . ']', $exercise->id)}}
                                    {{Form::hidden('exercise_type[' .  $key . ']', $exercise->type)}}
                                    <div>
                                        {{Form::label('reps[' . $key . ']', 'Reps target:' . $exercise->reps) }}
                                        {{Form::number('reps[' . $key . ']', $exercise->reps, ['min' => 1])}}
                                    </div>
                                    <div>
                                        {{Form::label('weight[' . $key . ']' , 'Weight target:' . $exercise->weight . ' kgs') }}
                                        {{Form::number('weight[' . $key . ']', $exercise->weight, ['min' => 1])}}
                                    </div>
                                    <div>
                                        {{Form::label('sets[' . $key . ']' , 'Sets:' . $exercise->sets) }}
                                        {{Form::number('sets[' . $key . ']', $exercise->sets, ['min' => 1])}}
                                    </div>
                                </div>
                                <hr>
                                @break
                                @case('cardio')
                                <h3>{{$exercise->name}} | {{$exercise->time}}</h3>
                                <div class="d-flex justify-content-between">
                                    @php $timeArr = explode(":", $exercise->time); @endphp
                                    {{Form::hidden('exercise_workout_id[' .  $key . ']', $exercise->id)}}
                                    {{Form::hidden('exercise_type[' .  $key . ']', $exercise->type)}}
                                    <div>
                                        <div>
                                            {{Form::label('mins[' . $key . ']', 'Mins target:' . $timeArr[1]) }}
                                            {{Form::number('mins[' . $key . ']', $timeArr[1], $exercise->reps, ['min' => 1])}}
                                        </div>
                                        <div>
                                            {{Form::label('seconds[' . $key . ']', 'Seconds target' . $timeArr[2])}}
                                            {{Form::number('seconds[' . $key . ']', $timeArr[2], ['step' => '10', 'min' => 0 ,'max' => '50'])}}
                                        </div>
                                    </div>
                                    <div>
                                        {{Form::label('sets[' . $key . ']' , 'Sets:' . $exercise->sets) }}
                                        {{Form::number('sets[' . $key . ']', $exercise->sets, ['min' => 1])}}
                                    </div>
                                </div>
                                <hr>
                                @break
                            @endswitch
                        @endforeach
                    </div>
                    <div class="card-footer">
                        <div class="form-group">
                            {{Form::submit('Update workout', ['class' =>'btn btn-success'])}}
                        </div>
                    </div
                    {!! Form::close() !!}
                </div>
                <div class="d-flex justify-content-between">
                    <a href="/planner/{{$workoutId}}/show" class="btn btn-primary">Back to {{$workoutName[0]->name}}</a>
                </div>
            </div>
        </div>
    </div>
@endsection
