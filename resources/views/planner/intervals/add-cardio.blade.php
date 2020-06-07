@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                {{ Breadcrumbs::render('interval-cardio', $workoutId, $intervalId) }}
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">WO ID: {{$workoutId}} | Add Cardio to Interval</div>
                    <div class="card-body">
                        {!! Form::open(['route' => ['interval.savecardio', $intervalId, $workoutId], 'method' => 'POST']) !!}
                        <div class="form-group">
                            {{Form::hidden('exercise_type', 'cardio')}}

                            {{Form::label('exercise', 'Select Cardio Exercise')}}

                            {{Form::select('exercise', $cardio, null, ['placeholder' => '...'])}}
                            @error('exercise')
                            <p class="help is-danger">{{$errors->first('exercise')}}</p>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div>
                                {{Form::label('reps', 'Reps')}}
                                {{Form::number('reps', 0, ['min' => 1 ] )}}
                                @error('reps')
                                <p class="help is-danger">{{$errors->first('reps')}}</p>
                                @enderror
                            </div>

                        </div>
                        <div class="form-group">
                            <div>
                                {{Form::label('distance', 'Distance in meters')}}
                                {{Form::number('distance', 0, ['min' => 0, 'step' => 10 ] )}}
                                @error('distance')
                                <p class="help is-danger">{{$errors->first('distance')}}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            {{Form::submit('Add Exercises', ['class' =>'btn btn-success'])}}
                        </div>

                        {!! Form::close() !!}
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
