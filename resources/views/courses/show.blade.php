@extends('layouts.app')

@section('content')
<h1>{{ $course->name }}</h1>
<p>Category: {{ $course->category }}</p>
<p>{{ $course->description }}</p>

<form action="/courses/{{ $course->id }}/enroll" method="POST">
    @csrf
    <button type="submit">Enroll in this course</button>
</form>
@endsection
