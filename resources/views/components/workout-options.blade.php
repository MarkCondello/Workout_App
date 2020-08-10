 <div class="d-flex justify-content-between">
    <div>
        <a href="/planner/{{ $workout->id }}/weight-training" class="btn btn-primary">Add
            Weights</a>
        <a href="/planner/{{ $workout->id }}/cardio-training" class="btn btn-primary">Add
            Cardio</a>
        <a href="/planner/{{ $workout->id }}/interval-training" class="btn btn-primary">Add
            Intervals</a>
    </div>
    <div>

        {!! Form::open(['route' => ['workout.destroy', $workout], 'method' => 'POST']) !!}
        @method('DELETE')
        <button class="btn btn-danger ml-1">Delete</button>
        {!! Form::close() !!}

        <a href="{{ route('workout.show', ['workout' => $workout]) }}" class="btn btn-success ">Show</a>
                {{-- <a href="/planner/{{ $workout->id }}/show" class="btn btn-success ">Show</a> --}}

    </div>
</div>
