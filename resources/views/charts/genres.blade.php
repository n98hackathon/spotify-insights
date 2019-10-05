<div class="title m-b-md">
    Length of recently played Tracks
</div>
<canvas id="recent-tracks"></canvas>
<script>
    var ctx = document.getElementById('recent-tracks').getContext('2d');
    var chart = new Chart(ctx, {
        // The type of chart we want to create
        type: 'radar',

        // The data for our dataset
        data: {
            labels: [
                12,
                @for($i = 1; $i < 12; $i++)
                    "{{ $i }}",
                @endfor
            ],
            datasets: [
                @foreach($recentGenres as $index => $played)
                {
                    label: "{!! $index  !!}",
                    fill: false,
                    borderColor: [
                        'rgb({{ rand(0, 255) }}, {{ rand(0, 255) }}, {{ rand(0, 255) }})'
                    ],
                    data: {!! json_encode(array_values($played)) !!}
                },
                @endforeach
            ]
        },

        // Configuration options go here
        options: {

        }
    });
</script>
