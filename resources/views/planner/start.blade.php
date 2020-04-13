@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center mt-5">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="col-md-8">
                <div class="card mb-5">
                    <div class="card-header d-flex justify-content-between">
                        <h2> {{$workoutName[0]->name}}</h2>
                    </div>
                    {!! Form::open(['route' => ['planner.save-workout-results', $workoutId], 'action' => 'PlannerController@saveResults', 'method' => 'POST']) !!}
                    <div class="card-body">
                        @forelse($exercisesGrouped as $exerciseGroup)
                            <div>
                                <hr/>
                                <div class="d-flex justify-content-between">
                                    <h3> {{$exerciseGroup[0]['name']}} | {{$exerciseGroup[0]['type']}}</h3>
                                </div>
                                @foreach($exerciseGroup as $key=>$exercise)
                                    @switch($exerciseGroup[0]['type'])
                                        @case('weight')
                                        <div class="d-flex justify-content-between">
                                            {{Form::hidden('exercise_workout_id[' .  $key . ']', $exercise['id'])}}
                                            {{Form::hidden('exercise_type[' .  $key . ']', $exercise['type'])}}
                                            <div>
                                                {{Form::label('reps[' . $key . ']', 'Reps target:' . $exercise['reps']) }}
                                                {{Form::number('reps[' . $key . ']', 0, ['min' => 1])}}
                                            </div>
                                            <div>
                                                {{Form::label('weight[' . $key . ']' , 'Weight target:' . $exercise['weight'] . ' kgs') }}
                                                {{Form::number('weight[' . $key . ']', 0, ['min' => 1])}}
                                            </div>
                                        </div>
                                        <hr>
                                        @break
                                        @case('cardio')
                                        @php $timeArr = explode(":", $exercise['time']);
                                            $timeInSeconds = $timeArr[1] * 60 + $timeArr[2];
                                        @endphp
                                        <div class="countdown"  data-time="5" xmlns="http://www.w3.org/1999/html">
                                            <svg class="base-timer__svg" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg">
                                                <g class="base-timer__circle">
                                                    <circle class="base-timer__path-elapsed" cx="50" cy="50" r="45"/>
                                                    </circle>
                                                    <path
                                                        id="base-timer-path-remaining"
                                                        stroke-dasharray="283"
                                                        class="base-timer__path-remaining"
                                                        d="
                      M 50, 50
                      m -45, 0
                      a 45,45 0 1,0 90,0
                      a 45,45 0 1,0 -90,0
                    "></path>
                                                </g>
                                            </svg>
                                            <span id="base-timer-label" class="base-timer__label">
          </span>
                                        </div>
                                        <div class="d-flex justify-content-between">

                                            {{Form::hidden('exercise_workout_id[' .  $key . ']', $exercise['id'])}}
                                            {{Form::hidden('exercise_type[' .  $key . ']', $exercise['type'])}}
                                            <div>
                                                <div>
                                                    {{Form::label('', ' target:' . $timeArr[1] . $timeArr[2])   }}
                                                </div>

                                            </div>
                                        </div>
                                        <hr>
                                        @break
                                    @endswitch
                                @endforeach
                            </div>
                        @empty
                            <p>You have no exercises. Go back to add exercises.
                                <a href="/planner{{$workoutId}}/show" class="btn btn-primary">Back to workout</a>
                            </p>
                        @endforelse
                    </div>
                    <div class="card-footer">
                        <div class="form-group">
                            {{Form::submit('Save workout results', ['class' =>'btn btn-success'])}}
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
                <div class="d-flex justify-content-between">
                    <a href="/planner/{{$workoutId}}/show" class="btn btn-primary">Back to workout</a>
                </div>
            </div>
        </div>
    </div>
@endsection
