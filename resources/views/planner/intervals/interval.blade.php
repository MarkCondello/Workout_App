@extends('layouts.app')
@section('content')
    <div class="container">
{{--        <div class="row">--}}
{{--            <div class="col-md-12">--}}
{{--                {{ Breadcrumbs::render('intervals', $workoutId) }}--}}
{{--            </div>--}}
{{--        </div>--}}
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{  $workoutName[0]->name  }} | Add Interval </div>
                    <div class="card-body">
                        {!! Form::open(['route' => ['interval.create', $workoutId], 'method' => 'POST']) !!}

                        <div class="form-group">
                            <div>
                                {{Form::label('mins', 'Minutes')}}
                                {{Form::number('mins', 0, ['min' => 0 , 'max' => 59] )}}
                                @error('mins')
                                <p class="help is-danger">{{$errors->first('mins')}}</p>
                                @enderror
                            </div>
                            <div>
                                {{Form::label('seconds', 'Seconds')}}
                                {{Form::number('seconds', 0, ['step' => '10', 'min' => 0 ,'max' => '50'])}}
                                @error('seconds')
                                <p class="help is-danger">{{$errors->first('exercise')}}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            {{Form::label('sets', 'Sets')}}
                            {{Form::number('sets', 0, ['min' => '1'])}}
                        </div>
                        <div class="form-group">
                            {{Form::submit('Add Exercises', ['class' =>'btn btn-success'])}}
                        </div>

                        {!! Form::close() !!}
                    </div>
                    <div class="card-footer">
                        <a href="/planner" class="btn btn-primary">Back to planner</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<!-- All exercises plotted against a interval workout (cardio or weights) will be set to a reps range (A distance should be included in v2)
The user sets an interval and this creates a container for which the user can add weights or cardio to.
Throughout this update, they can set or update their interval time and sets for these exercises.

interval_group
    interval_id
    time
    sets
-->
