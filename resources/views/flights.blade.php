<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Zboruri</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    @vite(['resources/css/flights.css'])
</head>
<body>
    <div class="container">
        <a href="/home" class="back-link">Înapoi</a>
        <a href="/insert-model" class="insert-link">Refresh</a>
        
        <h1>Zboruri disponibile</h1>    
        
        @if(count($flights) == 0)
            <p style="text-align: center; font-size: 1.2rem; color: #666;">
                Nu au fost găsite zboruri.
            </p>
        @else
            <div class="flights-grid">
                @foreach($flights as $flight)
                <div class="flight-card">
                    <div class="flight-number">{{ $flight->flight_number }}</div>
                    <div class="route">{{ $flight->departure_city }} → {{ $flight->arrival_city }}</div>
                    
                    <div class="details">
                        <div class="detail-item">
                            <span class="detail-label">Orașul de plecare:</span><br>
                            {{ \Carbon\Carbon::parse($flight->departure_time)->format('d/m/Y H:i') }}
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Destinația:</span><br>
                            {{ \Carbon\Carbon::parse($flight->arrival_time)->format('d/m/Y H:i') }}
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Aeronava:</span><br>
                            {{ $flight->aircraft_type }}
                        </div>
                        <div class="detail-item">
                            <span class="detail-label">Locuri disponibile:</span><br>
                            {{ $flight->available_seats }}
                        </div>
                    </div>
                    
                    <div class="price">${{ number_format($flight->price, 2) }}</div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</body>
</html>