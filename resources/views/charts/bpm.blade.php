<div class="title m-b-md">
    Average BPM of recently played Tracks
</div>
<canvas id="recent-bpm"></canvas>
<script>
    var ctx = document.getElementById('recent-bpm').getContext('2d');
    var gradientStroke = ctx.createLinearGradient(2000, 0, 100, 0);
    gradientStroke.addColorStop(0, "#{{ str_pad(random_int(0, 999999), 6) }}");
    gradientStroke.addColorStop(1, "#{{ str_pad(random_int(0, 999999), 6) }}");
    var chart = new Chart(ctx, {
        // The type of chart we want to create
        type: 'line',

        // The data for our dataset
        data: {
            labels: [
                24,
                @for($i = 1; $i < 24; $i++)
                    "{{ $i }}",
                @endfor
            ],
            datasets: [
                {
                    label: 'bpm',
                    borderColor:               gradientStroke,
                    pointBorderColor:          gradientStroke,
                    pointBackgroundColor:      gradientStroke,
                    pointHoverBackgroundColor: gradientStroke,
                    pointHoverBorderColor:     gradientStroke,
                    backgroundColor: gradientStroke,
                    data: [
                        @foreach($recentBpm as $bpm)
                            {{ $bpm['avg'] }},
                        @endforeach
                    ]
                }
            ]
        },

        // Configuration options go here
        options: {

        }
    });
</script>
