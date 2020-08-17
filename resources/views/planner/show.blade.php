@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row justify-content-center mt-5">
            <div class="col-md-8">
                <div class="card mb-5">
                    <div class="card-header d-flex justify-content-between">
                        <h2> {{$workout->name}}</h2>
                        {{--  ToDo: Add edit options for workout--}}
                        <a href="{{ route('workout.edit', $workout) }}" class="btn btn-warning ">Edit workout</a> 
                        <a href="{{ route('workout.start', $workout) }}" class="btn btn-success">Start workout</a>
                    </div>
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if(!empty($exercisesGrouped) || !empty($intervalsGrouped))
                            @if($exercisesGrouped)
                                <div class="card-body border border-secondary mt-4 mb-4">
                                    <h4>Weights and Cardio</h4>
                                    <hr>
                                    @include('components/exercise-groups', ['exercisesGrouped' => $exercisesGrouped])
                                </div>
                            @endif
                            @if($intervalsGrouped)
                                <div class="card-body border border-secondary mt-4 mb-4">
                                    <h4>Intervals</h4>
                                    <hr>
                                    @include('components/intervals-grouped', ['intervalsGrouped' => $intervalsGrouped])
                                </div>
                            @endif
                        @else
                            <p>You have no exercises. Go back to add exercises...</p>
                        @endif
                    </div>
                </div>
                <div class="d-flex justify-content-between">
                    <a href="{{ route('workout.planner') }}" class="btn btn-primary">Back to dashboard</a>

                    {!! Form::open(['route' => ['workout.destroy', $workout] ]) !!}
                        @method('DELETE')
                        <button class="btn btn-danger ml-1">Delete workout </button>
                    {!! Form::close() !!}

                    {{-- <a href="{{ route('workout.destroy', $workout) }}" class="btn btn-danger">Delete workout</a> --}}
                </div>
            </div>
        </div>
    </div>
@endsection
