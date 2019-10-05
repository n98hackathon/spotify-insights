@extends('layouts.app')

@section('head')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
@endsection

@section('content')
<div class="position-ref full-height">

        <div class="content">
            @include('charts.genres')
        </div>
    </div>
@endsection
