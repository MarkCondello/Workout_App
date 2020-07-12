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
        <a href="/planner/{{ $workout->id }}/delete"
           class="btn btn-danger ">Delete</a>
        <a href="/planner/{{ $workout->id }}/show" class="btn btn-success ">Show</a>
    </div>
</div>
