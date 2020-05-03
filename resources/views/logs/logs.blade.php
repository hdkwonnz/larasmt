@extends('layouts.admin')

@section('content')

{{-- <link href="/myCss/wholeReading.css" rel="stylesheet"> --}}

<div class="container mt-5">

    <div class="mb-2">
        <h4>System Logs</h4>
    </div>

    <form action="{{ route('logs') }}">
        <input type="date" name='date' value="{{ $date ? $date->format('Y-m-d') : today()->format('Y-m-d') }}">
        <button type="submit">Get</button>
    </form>

    @if(empty($data['file']))
        <div>
            <h3>No Logs Found</h3>
        </div>
    @else
        <div>
            <h5>Updated On: <b>{{ $data['lastModified']->format('Y-m-d h:i a') }}</b></h5>
            <h5>File Size: <b>{{ round($data['size'] / 1024) }} KB</b></h5>
            <pre>{{ $data['file'] }}</pre>
        </div>
    @endif
</div>

@endsection
