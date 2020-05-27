@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">Create a new workout</div>
                    <div class="card-body">
                        @if(session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif
                        {!! Form::open(['action' => 'PlannerController@createWorkout', 'method' => 'POST']) !!}
                        <div class="form-group">
                            {{Form::label('name', 'Workout name.')}}
                            {{Form::text('name', '', ['class'=> 'form-control'])}}
                            @error('name')
                            <p class="help is-danger">{{$errors->first('name')}}</p>
                            @enderror
                        </div>
                        <div class="form-group">
                            {{Form::submit('Save', ['class' =>'btn btn-primary'])}}
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Your current workouts</div>
                    <div class="card-body">
                        @forelse($workouts as $workout)
                            <div class="card-body border border-secondary mt-2  ">
                                {{--                                ToDo: Add toggles for workout details--}}
                                <div class="card-header d-flex justify-content-between mb-4">
                                    <h3> {!! $workout->name !!}</h3>
                                    <div class="btn btn-primary disabled">Details</div>
                                </div>
                                @if(!empty($workout->exercisesGrouped) || !empty($workout->intervalsGrouped))
                                    @include('components/workout-options', ['workout' => $workout])

                                    @if($workout->exercisesGrouped)
                                        <div class="card-body border border-secondary mt-4 mb-4">
                                            <h4>Weights and Cardio</h4>
                                            <hr>
                                            @include('components/exercise-groups', ['exercisesGrouped' => $workout->exercisesGrouped])
                                        </div>
                                    @endif

                                    @if($workout->intervalsGrouped)
                                        <div class="card-body border border-secondary mb-4">
                                            <h4>Intervals</h4>
                                            @include('components/intervals-grouped', [ 'intervalsGrouped'=> $workout->intervalsGrouped])
                                        </div>
                                    @endif
                            </div>

                            @else
                                @include('components/workout-options', ['workout' => $workout])
                                <li class="d-flex">Add exercises to {{$workout->name}}</li>
                                <hr>
                            @endif
                        @empty
                            <p>You have no workouts. Create one to get stated.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        {{--@dd($results)--}}
        @if(isset($results))
            <div class="row justify-content-end">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">Previous Workout Results</div>
                        <div class="card-body">
                            @foreach($results as $workout)
                                <div class="d-flex justify-content-between">
                                    <h3> {!! $workout->name !!}</h3>
                                    <div class="d-flex justify-content-end  ">
                                        <button class="btn btn-primary" data-details="workout-{{$workout->id}}-details">
                                            Details
                                        </button>
                                        <a href="/planner/{{$workout->id}}}/copy-workout" class="btn btn-success "
                                           style="margin-left: .5rem;">Copy {!! $workout->name !!}</a>
                                    </div>
                                </div>
                                <div id="workout-{{$workout->id}}-details">
                                    @foreach($workout->exercisesGrouped as $exerciseGroup)
                                        <ul style="padding:0; margin-top: 1rem">
                                            <li>
                                                <h4>{{ $exerciseGroup[0]['name'] }}</h4>
                                            </li>
                                            @foreach($exerciseGroup as $key=>$exercise)
                                                <li><h5>Target Set {{$key+1}}</h5></li>
                                                <li class="d-flex justify-content-between">
                                                    <span>weight: {!! $exercise['weight'] !!}</span>
                                                    <span>sets: {!! $exercise['sets'] !!}</span>
                                                </li>
                                                <li><h5>Result</h5></li>
                                                <li class="d-flex justify-content-between">
                                                    <span>weight: {!! $exercise['result']['recorded_weight'] !!}</span>
                                                    <span>sets: {!! $exercise['sets'] !!}</span>
                                                </li>
                                                <hr>
                                            @endforeach
                                        </ul>
                                    @endforeach
                                    @endforeach
                                </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    @endif
@endsection
