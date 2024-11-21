@extends('layouts.app')

@section('content')
<h1>Recommended Courses</h1>
@if($recommendations->isEmpty())
    <p>No recommendations available at the moment.</p>
@else
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
