<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rezervă zborul {{ $flight->flight_number }} - UTCB Airways</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    @vite(['resources/css/booking.css'])    
</head>
<body>
    <div class="container">
        <a href="/display-model" class="back-link">← Înapoi la zboruri</a>

        <div class="booking-container">
            <h1>Rezervă zborul</h1>

            <div class="flight-info">
                <div class="flight-header">
                    <div class="flight-number">{{ $flight->flight_number }}</div>
                    <div class="flight-price">${{ number_format($flight->price, 2) }}/persoană</div>
                </div>
                <div class="flight-route">{{ $flight->departure_city }} → {{ $flight->arrival_city }}</div>
                <div class="flight-details">
                    <div>
                        <strong>Plecare:</strong><br>
                        {{ \Carbon\Carbon::parse($flight->departure_time)->format('d/m/Y H:i') }}
                    </div>
                    <div>
                        <strong>Sosire:</strong><br>
                        {{ \Carbon\Carbon::parse($flight->arrival_time)->format('d/m/Y H:i') }}
                    </div>
                    <div>
                        <strong>Aeronava:</strong><br>
                        {{ $flight->aircraft_type }}
                    </div>
                    <div>
                        <strong>Locuri disponibile:</strong><br>
                        {{ $flight->available_seats }}
                    </div>
                </div>
            </div>

            @if($errors->any())
                <div class="error-message">
                    <strong>Vă rugăm rezolvați următoarele erori:</strong>
                    <ul style="margin: 10px 0 0 0; padding-left: 20px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('reservations.store') }}" id="bookingForm">
                @csrf
                <input type="hidden" name="flight_id" value="{{ $flight->id }}">
                <input type="hidden" id="flight-price" value="{{ $flight->price }}">

                <div class="form-grid">
                    <div class="form-group">
                        <label for="passenger_name">Numele pasagerului principal *</label>
                        <input type="text" id="passenger_name" name="passenger_name" 
                               value="{{ old('passenger_name', Auth::user()->name) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="passenger_email">Email *</label>
                        <input type="email" id="passenger_email" name="passenger_email" 
                               value="{{ old('passenger_email', Auth::user()->email) }}" required>
                    </div>

                    <div class="form-group">
                        <label for="passenger_phone">Telefon *</label>
                        <input type="tel" id="passenger_phone" name="passenger_phone" 
                               value="{{ old('passenger_phone') }}" placeholder="Ex: +40712345678" required>
                    </div>

                    <div class="form-group">
                        <label for="number_of_passengers">Numărul de pasageri *</label>
                        <select id="number_of_passengers" name="number_of_passengers" required onchange="updatePrice()">
                            @for($i = 1; $i <= min(10, $flight->available_seats); $i++)
                                <option value="{{ $i }}" {{ old('number_of_passengers', 1) == $i ? 'selected' : '' }}>
                                    {{ $i }} {{ $i == 1 ? 'pasager' : 'pasageri' }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <div class="form-group full-width">
                        <label for="special_requests">Cerințe speciale (opțional)</label>
                        <textarea id="special_requests" name="special_requests" rows="3" 
                                  placeholder="Ex: Loc la fereastră, masă vegetariană, etc.">{{ old('special_requests') }}</textarea>
                    </div>
                </div>

                <div class="price-calculation">
                    <div class="price-row">
                        <span>Preț per pasager:</span>
                        <span>${{ number_format($flight->price, 2) }}</span>
                    </div>
                    <div class="price-row">
                        <span>Numărul de pasageri:</span>
                        <span id="passengers-display">1</span>
                    </div>
                    <div class="price-row total-price">
                        <span>Total de plată:</span>
                        <span id="total-price">${{ number_format($flight->price, 2) }}</span>
                    </div>
                </div>

                <button type="submit" class="submit-btn">Confirmă rezervarea</button>
            </form>
        </div>
    </div>

    <script>
        function updatePrice() {
            const flightPriceInput = document.getElementById('flight-price');
            const passengersSelect = document.getElementById('number_of_passengers');
            
            if (flightPriceInput && passengersSelect) {
                const passengers = parseInt(passengersSelect.value);
                const pricePerPerson = parseFloat(flightPriceInput.value);
                const totalPrice = passengers * pricePerPerson;
                
                const passengersDisplay = document.getElementById('passengers-display');
                const totalPriceDisplay = document.getElementById('total-price');
                
                if (passengersDisplay) {
                    passengersDisplay.textContent = passengers;
                }
                
                if (totalPriceDisplay) {
                    totalPriceDisplay.textContent = '$' + totalPrice.toFixed(2);
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            updatePrice();
        });
    </script>
</body>
</html>