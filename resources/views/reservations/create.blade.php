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
    @php
        $passengerCount = (int) request('passenger_count', 1);
        $maxPassengers = min(10, $flight->available_seats);
        
        $bookingClass = request('booking_class', 'economy');
        $classMultipliers = [
            'economy' => 1.0,
            'business' => 2.0,
            'first' => 3.5
        ];
        $classNames = [
            'economy' => 'Economy (Standard)',
            'business' => 'Business (+100%)',
            'first' => 'First Class (+250%)'
        ];
        
        $multiplier = $classMultipliers[$bookingClass] ?? 1.0;
        $pricePerPassenger = $flight->price * $multiplier;
        $totalPrice = $pricePerPassenger * $passengerCount;
    @endphp

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

                <div class="section-title">Detalii rezervare</div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="booking_class">Clasa de zbor *</label>
                        <select id="booking_class" name="booking_class" required onchange="this.form.submit()">
                            <option value="economy" {{ $bookingClass == 'economy' ? 'selected' : '' }}>
                                Economy (Standard)
                            </option>
                            <option value="business" {{ $bookingClass == 'business' ? 'selected' : '' }}>
                                Business (+100%)
                            </option>
                            <option value="first" {{ $bookingClass == 'first' ? 'selected' : '' }}>
                                First Class (+250%)
                            </option>
                        </select>
                        <input type="hidden" name="passenger_count" value="{{ $passengerCount }}">
                        @for($i = 0; $i < $passengerCount; $i++)
                            @foreach(['first_name', 'last_name', 'email', 'phone', 'date_of_birth', 'gender', 'passport_number', 'nationality', 'special_requests'] as $field)
                                @if(request("passengers.{$i}.{$field}"))
                                    <input type="hidden" name="passengers[{{ $i }}][{{ $field }}]" value="{{ request("passengers.{$i}.{$field}") }}">
                                @endif
                            @endforeach
                        @endfor
                    </div>
                </div>

                <div class="section-title">Informații pasageri</div>
                
                <div class="passenger-actions">
                    @if($passengerCount < $maxPassengers)
                        <a href="{{ request()->fullUrlWithQuery(['passenger_count' => $passengerCount + 1]) }}" class="add-passenger">
                            + Adaugă pasager ({{ $passengerCount + 1 }}/{{ $maxPassengers }})
                        </a>
                    @endif
                    
                    @if($passengerCount > 1)
                        <a href="{{ request()->fullUrlWithQuery(['passenger_count' => $passengerCount - 1]) }}" class="remove-passenger">
                            - Elimină pasager
                        </a>
                    @endif
                </div>

                @for($i = 0; $i < $passengerCount; $i++)
                    <div class="passenger-section">
                        <div class="passenger-header">
                            <span class="passenger-title">
                                Pasagerul {{ $i + 1 }} {{ $i == 0 ? '(Principal)' : '' }}
                            </span>
                        </div>
                        <div class="form-grid">
                            <div class="form-group">
                                <label>Prenume *</label>
                                <input type="text" name="passengers[{{ $i }}][first_name]" 
                                       value="{{ old("passengers.{$i}.first_name", $i == 0 ? (explode(' ', Auth::user()->name)[0] ?? '') : '') }}" required>
                            </div>
                            <div class="form-group">
                                <label>Nume *</label>
                                <input type="text" name="passengers[{{ $i }}][last_name]" 
                                       value="{{ old("passengers.{$i}.last_name", $i == 0 ? (explode(' ', Auth::user()->name)[1] ?? '') : '') }}" required>
                            </div>
                            <div class="form-group">
                                <label>Email *</label>
                                <input type="email" name="passengers[{{ $i }}][email]" 
                                       value="{{ old("passengers.{$i}.email", $i == 0 ? Auth::user()->email : '') }}" required>
                            </div>
                            <div class="form-group">
                                <label>Telefon *</label>
                                <input type="tel" name="passengers[{{ $i }}][phone]" 
                                       value="{{ old("passengers.{$i}.phone") }}" placeholder="Ex: +40712345678" required>
                            </div>
                            <div class="form-group">
                                <label>Data nașterii</label>
                                <input type="date" name="passengers[{{ $i }}][date_of_birth]" 
                                       value="{{ old("passengers.{$i}.date_of_birth") }}">
                            </div>
                            <div class="form-group">
                                <label>Gen</label>
                                <select name="passengers[{{ $i }}][gender]">
                                    <option value="">Selectează</option>
                                    <option value="male" {{ old("passengers.{$i}.gender") == 'male' ? 'selected' : '' }}>Masculin</option>
                                    <option value="female" {{ old("passengers.{$i}.gender") == 'female' ? 'selected' : '' }}>Feminin</option>
                                    <option value="other" {{ old("passengers.{$i}.gender") == 'other' ? 'selected' : '' }}>Altul</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Numărul pașaportului</label>
                                <input type="text" name="passengers[{{ $i }}][passport_number]" 
                                       value="{{ old("passengers.{$i}.passport_number") }}" placeholder="Ex: RO123456789">
                            </div>
                            <div class="form-group">
                                <label>Naționalitatea</label>
                                <input type="text" name="passengers[{{ $i }}][nationality]" 
                                       value="{{ old("passengers.{$i}.nationality") }}" placeholder="Ex: Română">
                            </div>
                            <div class="form-group full-width">
                                <label>Cerințe speciale</label>
                                <textarea name="passengers[{{ $i }}][special_requests]" rows="2" 
                                          placeholder="Ex: Loc la fereastră, masă vegetariană, etc.">{{ old("passengers.{$i}.special_requests") }}</textarea>
                            </div>
                        </div>
                    </div>
                @endfor

                <div class="price-calculation">
                    <div class="price-row">
                        <span>Preț de bază per pasager:</span>
                        <span>${{ number_format($flight->price, 2) }}</span>
                    </div>
                    <div class="price-row">
                        <span>Clasa selectată:</span>
                        <span>{{ $classNames[$bookingClass] }}</span>
                    </div>
                    <div class="price-row">
                        <span>Preț per pasager (cu clasa):</span>
                        <span>${{ number_format($pricePerPassenger, 2) }}</span>
                    </div>
                    <div class="price-row">
                        <span>Numărul de pasageri:</span>
                        <span>{{ $passengerCount }}</span>
                    </div>
                    <div class="price-row total-price">
                        <span>Total de plată:</span>
                        <span>${{ number_format($totalPrice, 2) }}</span>
                    </div>
                </div>

                <button type="submit" class="submit-btn">Confirmă rezervarea</button>
            </form>
        </div>
    </div>

</body>
</html>