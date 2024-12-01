@extends('layouts.app')
@extends('layouts.main')

@section('content')
<div class="container mt-4">
<h1>Recommended Courses</h1>
@if($recommendations->isEmpty())
<div class="alert alert-warning">No recommendations available at the moment.</div>
    <p>No recommendations available at the moment.</p>
@else
<div class="row">
    @foreach($courses as $course)
    <div class="col-md-4 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ $course->name }}</h5>
                <p class="card-text">Category: {{ $course->category }}</p>
                <p class="card-text">Predicted Rating: <strong>{{ number_format($course->predicted_rating, 2) }}</strong></p>
                <a href="{{ route('courses.show', $course->id) }}" class="btn btn-primary">View Details</a>
                <a href="#" class="btn btn-primary">Enroll Now</a>
            </div>
        </div>
    </div>
@endforeach
@endif
</div>
@endsection
<ul>
    @foreach($recommendedCourses as $course)
        <li>
            <h3>{{ $course->name }}</h3>
            <p>Category: {{ $course->category }}</p>
            <p>{{ $course->description }}</p>
            <p>Predicted Rating: {{ number_format($course->predicted_rating, 2) }}</p>
        </li>
    @endforeach
</ul>
@endif
@endsection
