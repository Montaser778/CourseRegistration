@extends('layouts.app')

@section('content')
<h1>Available Courses</h1>
<ul>
    @foreach($courses as $course)
        <li>
            {{ $course->name }} - {{ $course->category }}
            <form action="/courses/{{ $course->id }}/enroll" method="POST">
                @csrf
                <button type="submit">Enroll</button>
            </form>
        </li>
    @endforeach
</ul>
@endsection
