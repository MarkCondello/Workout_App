@extends('layouts.app')
{{-- @php--}}
{{--     print_r($exercisesGrouped );--}}
{{--@endphp--}}
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
                        @if($exercisesGrouped || $intervalsGrouped)
                            @if($intervalsGrouped)
                                @foreach($intervalsGrouped as $index => $group)
                                    <div class="d-flex justify-content-between">
                                        <h3>{{$group['time']}} | Sets: {{$group['sets']}} | id: {{$group['id']}}</h3>
                                        {{Form::label('interval[' .  $index . '][' . $group['id'] . ']', 'Competed interval') }}
                                        {{Form::checkbox('interval[' .  $index . '][' . $group['id'] . ']' )}}
                                    </div>
                                    <div>
                                        @php $timeArr = explode(":", $group['time']);
                                                $timeInSeconds = $timeArr[1] * 60 + $timeArr[2];
                                        @endphp
                                        <div class="d-flex justify-content-between">
                                            <div class="countdown" data-time="{{$timeInSeconds}}">
                                                <svg class="base-timer__svg" viewBox="0 0 100 100">
                                                    <g class="base-timer__circle">
                                                        <circle class="base-timer__path-elapsed" cx="50" cy="50"
                                                                r="45"/>
                                                        </circle>
                                                        <path
                                                                id="base-timer-path-remaining"
                                                                stroke-dasharray="283"
                                                                class="base-timer__path-remaining"
                                                                d="M 50, 50 m -45, 0 a 45, 45 0 1,0 90,0 a 45,45 0 1,0 -90,0"></path>
                                                    </g>
                                                </svg>
                                                <span id="base-timer-label" class="base-timer__label"></span>
                                            </div>
                                        </div>
                                        <ul>
                                            @foreach($group['exercises_grouped'] as $exerciseTypes)
                                                @foreach($exerciseTypes as $exercise)
                                                    @if($exercise['type'] === 'weight')
                                                        <li>  {{$exercise['name']}} | reps: {{$exercise['reps'] }} |
                                                            weight: {{ $exercise['weight'] }}</li>
                                                    @endif

                                                    @if($exercise['type'] === 'cardio')
                                                        <li>  {{$exercise['name']}} | reps: {{$exercise['reps'] }}
                                                            @if($exercise['distance'])
                                                                | distance: {{ $exercise['distance'] }}
                                                            @endif
                                                        </li>
                                                    @endif
                                                @endforeach
                                            @endforeach
                                        </ul>
                                        @endforeach
                                        @endif
                                        @foreach($exercisesGrouped as $index => $exerciseGroup)
                                            <div>
                                                <hr/>
                                                <div class="d-flex justify-content-between">
                                                    <h3> {{$exerciseGroup[0]['name']}}
                                                        | {{$exerciseGroup[0]['type']}}</h3>
                                                </div>
                                                @foreach($exerciseGroup as $key=>$exercise)
                                                    {{Form::hidden('exercise_workout_id[' .  $index . '][' . $key . ']', $exercise['id'])}}
                                                    {{Form::hidden('exercise_type[' .  $index . '][' . $key . ']', $exercise['type'])}}

                                                    @switch($exerciseGroup[0]['type'])
                                                        @case('weight')
                                                        <div class="d-flex justify-content-between">
                                                            <div>
                                                                {{Form::label('reps[' .  $index . '][' . $key . ']', 'Reps target: ' . $exercise['reps']) }}
                                                                {{Form::number('reps[' .  $index . '][' . $key . ']', 0, ['min' => 1])}}
                                                            </div>
                                                            <div>
                                                                {{Form::label('weight[' .  $index . '][' . $key . ']' , 'Weight target: ' . $exercise['weight'] . ' kgs') }}
                                                                {{Form::number('weight[' .  $index . '][' . $key . ']', 0, ['min' => 5, 'step' => 5])}}
                                                            </div>
                                                        </div>
                                                        @break
                                                        @case('cardio')
                                                        {{--                                    ToDo: Need a way to allow user to have an option if they completed it or not--}}
                                                        @php $timeArr = explode(":", $exercise['time']);
                                                $timeInSeconds = $timeArr[1] * 60 + $timeArr[2];
                                                        @endphp
                                                        <div class="d-flex justify-content-between">
                                                            <div class="countdown" data-time="{{$timeInSeconds}}">
                                                                <svg class="base-timer__svg" viewBox="0 0 100 100">
                                                                    <g class="base-timer__circle">
                                                                        <circle class="base-timer__path-elapsed" cx="50"
                                                                                cy="50"
                                                                                r="45"/>
                                                                        </circle>
                                                                        <path
                                                                                id="base-timer-path-remaining"
                                                                                stroke-dasharray="283"
                                                                                class="base-timer__path-remaining"
                                                                                d="M 50, 50 m -45, 0 a 45, 45 0 1,0 90,0 a 45,45 0 1,0 -90,0"></path>
                                                                    </g>
                                                                </svg>
                                                                <span id="base-timer-label"
                                                                      class="base-timer__label"></span>
                                                            </div>
                                                            <div>
                                                                <div>
                                                                    {{Form::label('time[' .  $index . '][' . $key . ']', 'Time target: ' . $timeArr[1] . ':' . $timeArr[2])   }}
                                                                    {{Form::hidden('time[' .  $index . '][' . $key . ']', $exercise['time'] )}}
                                                                </div>
                                                            </div>
                                                        </div>
                                                        @break
                                                    @endswitch
                                                @endforeach
                                            </div>
                                        @endforeach
                                        @else
                                            <p>You have no exercises. Go back to add exercises.
                                                <a href="/planner{{$workoutId}}/show" class="btn btn-primary">Back to
                                                    workout</a>
                                            </p>
                                        @endif
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
