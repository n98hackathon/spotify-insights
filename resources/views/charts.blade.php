@extends('layouts.app')

@section('head')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
@endsection

@section('content')
<div class="flex-center position-ref full-height">

        <div class="content">
            @include('charts.recent-tracks')
        </div>
    </div>
@endsection
