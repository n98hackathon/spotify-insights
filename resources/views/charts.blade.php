<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
            /*html, body {*/
                /*background-color: #fff;*/
                /*color: #636b6f;*/
                /*font-family: 'Nunito', sans-serif;*/
                /*font-weight: 200;*/
                /*height: 100vh;*/
                /*margin: 0;*/
            /*}*/

            /*.full-height {*/
                /*height: 100vh;*/
            /*}*/

            /*.flex-center {*/
                /*align-items: center;*/
                /*display: flex;*/
                /*justify-content: center;*/
            /*}*/

            /*.position-ref {*/
                /*position: relative;*/
            /*}*/

            /*.top-right {*/
                /*position: absolute;*/
                /*right: 10px;*/
                /*top: 18px;*/
            /*}*/

            /*.content {*/
                /*text-align: center;*/
            /*}*/

            /*.title {*/
                /*font-size: 84px;*/
            /*}*/

            /*.links > a {*/
                /*color: #636b6f;*/
                /*padding: 0 25px;*/
                /*font-size: 13px;*/
                /*font-weight: 600;*/
                /*letter-spacing: .1rem;*/
                /*text-decoration: none;*/
                /*text-transform: uppercase;*/
            /*}*/

            /*.m-b-md {*/
                /*margin-bottom: 30px;*/
            /*}*/
        </style>
    </head>
    <body>
        <div class="flex-center position-ref full-height">

            <div class="content">
                <canvas id="myChart"></canvas>

            <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
                <script>
                    var ctx = document.getElementById('myChart').getContext('2d');
                    var chart = new Chart(ctx, {
                        // The type of chart we want to create
                        type: 'bar',

                        // The data for our dataset
                        data: {
                            labels: [
                                @foreach($recentTracks as $index => $recentTrack)
                                '{{ $recentTrack->track->name }}',
                                @endforeach
                            ],
                            datasets: [{
                                label: 'My First dataset',
                                backgroundColor: [
                                    @foreach($recentTracks as $recentTrack)
                                        'rgb({{ rand(0, 255) }}, {{ rand(0, 255) }}, {{ rand(0, 255) }})',
                                    @endforeach
                                ],
                                // borderColor: 'rgb(255, 99, 132)',
                                data: [
                                    @foreach($recentTracks as $recentTrack)
                                        '{{ $recentTrack->track->duration_ms/1000 }}',
                                    @endforeach
                                    ]
                            }]
                        },

                        // Configuration options go here
                        options: {

                        }
                    });
                </script>

                {{--{{ print_r($recent_tracks) }}--}}
            </div>
        </div>
    </body>
</html>
