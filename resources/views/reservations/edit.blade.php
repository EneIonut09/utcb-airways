<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifică Rezervarea {{ $reservation->booking_reference }} - UTCB Airways</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    @vite(['resources/css/booking.css', 'resources/css/edit.css'])    
</head>
<body>
    @php
        $passengerCount = count($reservation->passengers);
        $bookingClass = old('booking_class', $reservation->booking_class);
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
        $pricePerPassenger = $reservation->flight->price * $multiplier;
        $totalPrice = $pricePerPassenger * $passengerCount;
    @endphp

    <div class="container">
        <a href="{{ route('reservations.show', $reservation->id) }}" class="back-link">← Înapoi la detalii</a>

        <div class="booking-container">
            <h1>Modifică rezervarea</h1>

            <div class="flight-info">
                <div class="flight-header">
                    <div class="flight-number">{{ $reservation->flight->flight_number }}</div>
                    <div class="flight-price">${{ number_format($reservation->flight->price, 2) }}/persoană</div>
                </div>
                <div class="flight-route">{{ $reservation->flight->departure_city }} → {{ $reservation->flight->arrival_city }}</div>
                <div class="flight-details">
                    <div>
                        <strong>Plecare:</strong><br>
                        {{ \Carbon\Carbon::parse($reservation->flight->departure_time)->format('d/m/Y H:i') }}
                    </div>
                    <div>
                        <strong>Sosire:</strong><br>
                        {{ \Carbon\Carbon::parse($reservation->flight->arrival_time)->format('d/m/Y H:i') }}
                    </div>
                    <div>
                        <strong>Aeronava:</strong><br>
                        {{ $reservation->flight->aircraft_type }}
                    </div>
                    <div>
                        <strong>Cod rezervare:</strong><br>
                        {{ $reservation->booking_reference }}
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

            @if(session('error'))
                <div class="error-message">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('reservations.update', $reservation->id) }}" id="editForm">
                @csrf
                @method('PUT')

                <div class="section-title">Detalii rezervare</div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="booking_class">Clasa de zbor *</label>
                        <select id="booking_class" name="booking_class" required>
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
                    </div>
                </div>

                <div class="section-title">Informații pasageri</div>
                
                <div style="background: #fff3cd; border: 1px solid #ffeaa7; border-radius: 8px; padding: 15px; margin-bottom: 20px;">
                    <strong>Notă:</strong> 
                    Poți modifica informațiile pasagerilor existenți și clasa de zbor. 
                    Pentru a schimba numărul de pasageri, te rugăm să anulezi această rezervare și să faci una nouă.
                </div>

                @foreach($reservation->passengers as $index => $passenger)
                    <div class="passenger-section">
                        <div class="passenger-header">
                            <span class="passenger-title">
                                Pasagerul {{ $index + 1 }} {{ $index == 0 ? '(Principal)' : '' }}
                            </span>
                        </div>
                        <div class="form-grid">
                            <div class="form-group">
                                <label>Prenume *</label>
                                <input type="text" name="passengers[{{ $index }}][first_name]" 
                                       value="{{ old("passengers.{$index}.first_name", $passenger->first_name) }}" required>
                            </div>
                            <div class="form-group">
                                <label>Nume *</label>
                                <input type="text" name="passengers[{{ $index }}][last_name]" 
                                       value="{{ old("passengers.{$index}.last_name", $passenger->last_name) }}" required>
                            </div>
                            <div class="form-group">
                                <label>Email *</label>
                                <input type="email" name="passengers[{{ $index }}][email]" 
                                       value="{{ old("passengers.{$index}.email", $passenger->email) }}" required>
                            </div>
                            <div class="form-group">
                                <label>Telefon *</label>
                                <input type="tel" name="passengers[{{ $index }}][phone]" 
                                       value="{{ old("passengers.{$index}.phone", $passenger->phone) }}" required>
                            </div>
                            <div class="form-group">
                                <label>Data nașterii</label>
                                <input type="date" name="passengers[{{ $index }}][date_of_birth]" 
                                       value="{{ old("passengers.{$index}.date_of_birth", $passenger->date_of_birth?->format('Y-m-d')) }}">
                            </div>
                            <div class="form-group">
                                <label>Gen</label>
                                <select name="passengers[{{ $index }}][gender]">
                                    <option value="">Selectează</option>
                                    <option value="male" {{ old("passengers.{$index}.gender", $passenger->gender) == 'male' ? 'selected' : '' }}>Masculin</option>
                                    <option value="female" {{ old("passengers.{$index}.gender", $passenger->gender) == 'female' ? 'selected' : '' }}>Feminin</option>
                                    <option value="other" {{ old("passengers.{$index}.gender", $passenger->gender) == 'other' ? 'selected' : '' }}>Altul</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Numărul pașaportului</label>
                                <input type="text" name="passengers[{{ $index }}][passport_number]" 
                                       value="{{ old("passengers.{$index}.passport_number", $passenger->passport_number) }}">
                            </div>
                            <div class="form-group">
                                <label>Naționalitatea</label>
                                <input type="text" name="passengers[{{ $index }}][nationality]" 
                                       value="{{ old("passengers.{$index}.nationality", $passenger->nationality) }}">
                            </div>
                            <div class="form-group full-width">
                                <label>Cerințe speciale</label>
                                <textarea name="passengers[{{ $index }}][special_requests]" rows="2">{{ old("passengers.{$index}.special_requests", $passenger->special_requests) }}</textarea>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="price-calculation">
                    <div class="price-row">
                        <span>Preț de bază per pasager:</span>
                        <span>${{ number_format($reservation->flight->price, 2) }}</span>
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
                    
                    @if($totalPrice != $reservation->total_price)
                        <div class="price-row" style="color: #856404; background: #fff3cd; padding: 10px; border-radius: 5px; margin-top: 10px;">
                            <span>Prețul original:</span>
                            <span>${{ number_format($reservation->total_price, 2) }}</span>
                        </div>
                        @if($totalPrice > $reservation->total_price)
                            <div class="price-row" style="color: #dc3545; font-weight: bold;">
                                <span>Diferența:</span>
                                <span>+${{ number_format($totalPrice - $reservation->total_price, 2) }}</span>
                            </div>
                        @else
                            <div class="price-row" style="color: #28a745; font-weight: bold;">
                                <span>Diferența:</span>
                                <span>-${{ number_format($reservation->total_price - $totalPrice, 2) }}</span>
                            </div>
                        @endif
                    @endif
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary btn-save">
                        Salvează modificările
                    </button>
                    <a href="{{ route('reservations.show', $reservation->id) }}" class="btn btn-secondary btn-cancel">
                        Anulează
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>