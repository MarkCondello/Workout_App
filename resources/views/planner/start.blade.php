@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center mt-5">

            Tried to add validation
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
                        @php $exerciseName = ''; @endphp
                        @forelse($exercises as $key=>$exercise)
                            @php $identifyer = preg_replace('/\s+/', '_', strtolower($exercise->name)) . '_id-' . $exercise->id;
                            @endphp
                            <div>
                                @if( $exercise->name != $exerciseName)
                                    @php $exerciseName = $exercise->name @endphp
                                    <hr/>
                                    <div class="d-flex justify-content-between">
                                        <h3> {{$exerciseName}} </h3>
                                    </div>
                                @endif
                                @switch($exercise->exercise_type_id)
                                    @case(1)<!-- record weights display start-->
                                    <div class="d-flex justify-content-between">
                                        <!-- if the workout type is weights, we will expect an id, set number, reps and weights -->
                                        {{Form::hidden('exercise_workout_id_' . $identifyer , $exercise->id)}}
                                        <div>
                                            {{Form::label('recorded-reps_' . $identifyer , 'Reps target:' . $exercise->reps) }}
                                            {{Form::number('recorded-reps_' . $identifyer , '', [  'min' => 1])}}

                                            @error('recorded-reps_' . $identifyer )
                                            <p class="help is-danger">{{$errors->first('recorded-reps_' . $identifyer ) }}</p>
                                            @enderror
                                        </div>
                                        <div>
                                            {{Form::label('recorded-weight_' . $identifyer , 'Weight target:' . $exercise->weight . ' kgs') }}
                                            {{Form::number('recorded-weight_' . $identifyer , '', [   'min' => 1])}}
                                        </div>
                                    </div>
                                    @break <!-- weights record display end-->

                                    @default
                                    <p>Something went wrong</p>
                                @endswitch
                            </div>
                            @php $exerciseName = $exercise->name; @endphp
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
