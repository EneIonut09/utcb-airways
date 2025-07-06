<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalii Rezervare {{ $reservation->booking_reference }} - UTCB Airways</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    @vite(['resources/css/my-reservations.css', 'resources/css/show.css'])
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Detalii Rezervare</h1>
            <a href="{{ route('my-reservations') }}" class="back-link">← Înapoi la rezervările mele</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        <div class="reservation-card">
            <div class="reservation-header">
                <div class="flight-info">
                    <div class="flight-number">{{ $reservation->flight->flight_number }}</div>
                    <div class="route-info">
                        <div class="route">{{ $reservation->flight->departure_city }} → {{ $reservation->flight->arrival_city }}</div>
                        <div class="times">
                            {{ \Carbon\Carbon::parse($reservation->flight->departure_time)->format('d/m/Y H:i') }} - 
                            {{ \Carbon\Carbon::parse($reservation->flight->arrival_time)->format('d/m/Y H:i') }}
                        </div>
                    </div>
                    <div class="status-price">
                        <div class="status {{ $reservation->status }}">
                            @switch($reservation->status)
                                @case('confirmed')
                                    ✓ Confirmată
                                    @break
                                @case('pending')
                                    ⏳ În așteptare
                                    @break
                                @case('cancelled')
                                    ✗ Anulată
                                    @break
                                @default
                                    {{ ucfirst($reservation->status) }}
                            @endswitch
                        </div>
                        <div class="price">${{ number_format($reservation->total_price, 2) }}</div>
                    </div>
                </div>
            </div>

            <div class="reservation-details">
                <div class="details-grid">
                    <div class="detail-section">
                        <h3>Informații rezervare</h3>
                        <div class="booking-info">
                            <div class="info-row">
                                <span class="info-label">Cod rezervare:</span>
                                <span class="info-value"><strong>{{ $reservation->booking_reference }}</strong></span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Data rezervării:</span>
                                <span class="info-value">{{ $reservation->booking_date->format('d/m/Y H:i') }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Status:</span>
                                <span class="info-value">
                                    <span class="status {{ $reservation->status }}">
                                        @switch($reservation->status)
                                            @case('confirmed')
                                                Confirmată
                                                @break
                                            @case('pending')
                                                În așteptare
                                                @break
                                            @case('cancelled')
                                                Anulată
                                                @break
                                            @default
                                                {{ ucfirst($reservation->status) }}
                                        @endswitch
                                    </span>
                                </span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Clasa:</span>
                                <span class="info-value">
                                    @switch($reservation->booking_class)
                                        @case('economy')
                                            Economy
                                            @break
                                        @case('business')
                                            Business
                                            @break
                                        @case('first')
                                            First Class
                                            @break
                                        @default
                                            {{ ucfirst($reservation->booking_class) }}
                                    @endswitch
                                </span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Număr pasageri:</span>
                                <span class="info-value">{{ $reservation->passengers->count() }} {{ $reservation->passengers->count() == 1 ? 'pasager' : 'pasageri' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="detail-section">
                        <h3>Detalii zbor</h3>
                        <div class="booking-info">
                            <div class="info-row">
                                <span class="info-label">Aeronava:</span>
                                <span class="info-value">{{ $reservation->flight->aircraft_type }}</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Durată zbor:</span>
                                <span class="info-value">
                                    {{ \Carbon\Carbon::parse($reservation->flight->departure_time)->diffInHours(\Carbon\Carbon::parse($reservation->flight->arrival_time)) }}h 
                                    {{ \Carbon\Carbon::parse($reservation->flight->departure_time)->diffInMinutes(\Carbon\Carbon::parse($reservation->flight->arrival_time)) % 60 }}min
                                </span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Preț de bază:</span>
                                <span class="info-value">${{ number_format($reservation->flight->price, 2) }}/persoană</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Multiplicator clasă:</span>
                                <span class="info-value">
                                    @switch($reservation->booking_class)
                                        @case('business')
                                            x2.0 (+100%)
                                            @break
                                        @case('first')
                                            x3.5 (+250%)
                                            @break
                                        @default
                                            x1.0 (Standard)
                                    @endswitch
                                </span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Preț per pasager:</span>
                                <span class="info-value">
                                    ${{ number_format($reservation->total_price / $reservation->passengers->count(), 2) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="detail-section passengers-section">
                        <h3>Pasageri ({{ $reservation->passengers->count() }})</h3>
                        @foreach($reservation->passengers as $index => $passenger)
                            <div class="passenger-card">
                                <div class="passenger-header">
                                    <div class="passenger-name">{{ $passenger->full_name }}</div>
                                    @if($index === 0)
                                        <div class="passenger-type">Principal</div>
                                    @endif
                                </div>
                                
                                <div class="passenger-details">
                                    <div class="passenger-detail">
                                        <strong>Email:</strong> {{ $passenger->email }}
                                    </div>
                                    <div class="passenger-detail">
                                        <strong>Telefon:</strong> {{ $passenger->phone }}
                                    </div>
                                    @if($passenger->date_of_birth)
                                        <div class="passenger-detail">
                                            <strong>Data nașterii:</strong> 
                                            {{ $passenger->date_of_birth->format('d/m/Y') }}
                                        </div>
                                    @endif
                                    @if($passenger->gender)
                                        <div class="passenger-detail">
                                            <strong>Gen:</strong> 
                                            @switch($passenger->gender)
                                                @case('male')
                                                    Masculin
                                                    @break
                                                @case('female')
                                                    Feminin
                                                    @break
                                                @case('other')
                                                    Altul
                                                    @break
                                                @default
                                                    {{ $passenger->gender }}
                                            @endswitch
                                        </div>
                                    @endif
                                    @if($passenger->passport_number)
                                        <div class="passenger-detail">
                                            <strong>Pașaport:</strong> {{ $passenger->passport_number }}
                                        </div>
                                    @endif
                                    @if($passenger->nationality)
                                        <div class="passenger-detail">
                                            <strong>Naționalitate:</strong> {{ $passenger->nationality }}
                                        </div>
                                    @endif
                                </div>

                                @if($passenger->special_requests)
                                    <div class="special-requests">
                                        <strong>Cerințe speciale:</strong> {{ $passenger->special_requests }}
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="actions">
                    <a href="{{ route('my-reservations') }}" class="btn btn-secondary">
                        Înapoi la rezervări
                    </a>
                    
                    @if($reservation->status === 'confirmed' && now() < $reservation->flight->departure_time)
                        <a href="{{ route('reservations.edit', $reservation->id) }}" class="btn btn-primary">
                            Modifică rezervarea
                        </a>
                        
                        <form method="POST" action="{{ route('reservations.cancel', $reservation->id) }}" 
                              style="display: inline;"
                              onsubmit="return confirm('Sigur doriți să anulați această rezervare? Această acțiune nu poate fi anulată.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                Anulează rezervarea
                            </button>
                        </form>
                    @elseif($reservation->status === 'cancelled')
                        <span class="btn btn-secondary" style="opacity: 0.6; cursor: not-allowed;">
                            Rezervare anulată
                        </span>
                    @elseif(now() >= $reservation->flight->departure_time)
                        <span class="btn btn-secondary" style="opacity: 0.6; cursor: not-allowed;">
                            Zborul a plecat
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</body>
</html>