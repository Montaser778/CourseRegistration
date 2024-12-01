@extends('layouts.app')
@extends('layouts.main')

@section('content')
<h1>{{ $course->name }}</h1>
<div class="row">
    <div class="col-md-8">
        <p><strong>Category:</strong> {{ $course->category }}</p>
        <p><strong>Description:</strong> {{ $course->description }}</p>
        <p><strong>Difficulty Level:</strong> {{ $course->difficulty }}</p>
        <p><strong>Enrollments:</strong> {{ $course->users()->count() }}</p>
        <form action="{{ route('courses.enroll', $course->id) }}" method="POST">
            @csrf
        <p><strong>Rating:</strong> {{ number_format($course->rating, 1) }} / 5</p>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Enroll in this course</h5>
                <form action="/courses/{{ $course->id }}/enroll" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-block">Enroll Now</button>
                </form>
            </div>
        </div>
    </div>
</div>
</div>
@endsection

<p>Category: {{ $course->category }}</p>
<p>{{ $course->description }}</p>

<form action="/courses/{{ $course->id }}/enroll" method="POST">
    @csrf
    <button type="submit">Enroll in this course</button>
</form>
@endsection
