<canvas id="recent-tracks"></canvas>
<script>
    var ctx = document.getElementById('recent-tracks').getContext('2d');
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
                label: 'Recent Tracks',
                backgroundColor: [
                    @foreach($recentTracks as $recentTrack)
                        'rgb(0,{{ $recentTrack->track->popularity * (255/100) }},0)',
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
