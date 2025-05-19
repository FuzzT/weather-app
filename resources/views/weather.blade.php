<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather Application</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { margin-bottom: 60px; }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            height: 60px;
            background-color: #f5f5f5;
        }
        p.card-text { margin-top: -10px; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
    <div class="container">
        <a class="navbar-brand" href="#">Weather App</a>
    </div>
</nav>

<div class="container mt-5">
    <h1 class="mb-4">Weather Application</h1>

    <form action="{{ route('weather.form') }}" method="post" class="form-inline mb-4">
        @csrf
        <div class="d-flex">
            <div class="form-group me-3">
                <select class="form-select" name="city" id="city">
                    <option value="-1">-- Select City --</option>
                    <option value="Amaravati" {{ (old('city') == 'Amaravati') ? 'selected' : '' }}>Amaravati</option>
                    <option value="Ahmedabad" {{ (old('city') == 'Ahmedabad') ? 'selected' : '' }}>Ahmedabad</option>
                    <option value="Itanagar" {{ (old('city') == 'Itanagar') ? 'selected' : '' }}>Itanagar</option>
                    <option value="Barauni" {{ (old('city') == 'Barauni') ? 'selected' : '' }}>Barauni</option>
                    <option value="Jamalpur" {{ (old('city') == 'Jamalpur') ? 'selected' : '' }}>Jamalpur</option>
                </select>
            </div>
            <button class="btn btn-primary">Search</button>
        </div>
    </form>

    @if(isset($data))
        
        <div class="row mb-4">

            {{-- Looks Like --}}
            @if(isset($data['current_observation']['condition']['text']))
                <div class="col-md-4">
                    <p><strong>Looks like:</strong> {{ $data['current_observation']['condition']['text'] }}</p>

                    @php
                        $text = strtolower($data['current_observation']['condition']['text']);
                        $icon = match(true) {
                            str_contains($text, 'cloud') => 'cloudy.png',
                            str_contains($text, 'rain') => 'rain.png',
                            str_contains($text, 'thunder') => 'thunderstorm.png',
                            str_contains($text, 'sun'), str_contains($text, 'clear') => 'sunny.png',
                            default => 'default.png',
                        };
                    @endphp

                    <img src="{{ asset('images/weather/' . $icon) }}" alt="Weather icon" style="width: 50%; display: block; margin-top: 5px;">
                </div>
            @else
                <p class="mt-3"><strong>Looks like:</strong> Weather condition unavailable</p>
            @endif



            {{-- Location --}}
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Location Details</h5>
                        <p class="card-text">Country: <b>{{ $data['location']['country'] ?? '--' }}</b></p>
                        <p class="card-text">City: <b>{{ $data['location']['city'] ?? '--' }}</b></p>
                        <p class="card-text">Latitude: <b>{{ $data['location']['lat'] ?? '--' }}</b></p>
                        <p class="card-text">Longitude: <b>{{ $data['location']['long'] ?? '--' }}</b></p>
                        <p class="card-text">Sunrise: <b>{{ $data['current_observation']['astronomy']['sunrise'] ?? '--' }}</b></p>
                        <p class="card-text">Sunset: <b>{{ $data['current_observation']['astronomy']['sunset'] ?? '--' }}</b></p>
                    </div>
                </div>
            </div>

            {{-- Temperature --}}
            <div class="col-md-4">
                <div class="card bg-warning">
                    <div class="card-body">
                        <h5 class="card-title">Temperature (°F)</h5>
                        <p class="card-text">Current: <b>{{ $data['current_observation']['condition']['temperature'] ?? '--' }}</b></p>
                        <p class="card-text">Feels Like: <b>{{ $data['current_observation']['wind']['chill'] ?? '--' }}</b></p>
                        <p class="card-text">Min: <b>{{ $data['forecasts'][0]['low'] ?? '--' }}</b></p>
                        <p class="card-text">Max: <b>{{ $data['forecasts'][0]['high'] ?? '--' }}</b></p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Atmosphere --}}
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card bg-light">
                    <div class="card-body">
                        <h5 class="card-title">Atmosphere</h5>
                        <p class="card-text">Humidity: <b>{{ $data['current_observation']['atmosphere']['humidity'] ?? '--' }}%</b></p>
                        <p class="card-text">Pressure: <b>{{ $data['current_observation']['atmosphere']['pressure'] ?? '--' }}</b></p>
                        <p class="card-text">Visibility: <b>{{ $data['current_observation']['atmosphere']['visibility'] ?? '--' }}</b></p>
                    </div>
                </div>
            </div>

            {{-- Wind --}}
            <div class="col-md-4">
                <div class="card bg-secondary text-white">
                    <div class="card-body">
                        <h5 class="card-title">Wind</h5>
                        <p class="card-text">Speed: <b>{{ $data['current_observation']['wind']['speed'] ?? '--' }} mph</b></p>
                        <p class="card-text">Direction: <b>{{ $data['current_observation']['wind']['direction'] ?? '--' }}</b></p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Forecast --}}
        @if(isset($data['forecasts']) && is_array($data['forecasts']))
        <div class="row mt-4">
            <h4>Upcoming Forecast</h4>
            @foreach($data['forecasts'] as $forecast)
                <div class="col-md-3 mb-3">
                    <div class="card border-info">
                        <div class="card-body">
                            <h6 class="card-title">{{ $forecast['day'] ?? '--' }}</h6>
                            <p class="card-text">{{ $forecast['text'] ?? '--' }}</p>
                            <p class="card-text">High: <b>{{ $forecast['high'] ?? '--' }}°F</b></p>
                            <p class="card-text">Low: <b>{{ $forecast['low'] ?? '--' }}°F</b></p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        @endif

    @endif

</div>

<footer class="footer text-center pt-3">
    <p class="text-muted">© {{ date('Y') }} Weather App</p>
</footer>

</body>
</html>
