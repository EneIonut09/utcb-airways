<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rezervările mele - UTCB Airways</title>
    @vite(['resources/css/my-reservations.css'])
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Rezervările mele</h1>
            <a href="/display-model" class="back-link">← Înapoi la zboruri</a>
        </div>

        @if($reservations->count() > 0)
            @foreach($reservations as $reservation)
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
                                <h3>Detalii rezervare</h3>
                                <div class="booking-info">
                                    <div class="info-row">
                                        <span class="info-label">Cod rezervare:</span>
                                        <span class="info-value">{{ $reservation->booking_reference }}</span>
                                    </div>
                                    <div class="info-row">
                                        <span class="info-label">Data rezervării:</span>
                                        <span class="info-value">{{ $reservation->booking_date->format('d/m/Y H:i') }}</span>
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
                                        <span class="info-value">{{ $reservation->passengers->count() }}</span>
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
                                        <span class="info-label">Durată:</span>
                                        <span class="info-value">
                                            {{ \Carbon\Carbon::parse($reservation->flight->departure_time)->diffInHours(\Carbon\Carbon::parse($reservation->flight->arrival_time)) }}h 
                                        </span>
                                    </div>
                                    <div class="info-row">
                                        <span class="info-label">Preț de bază:</span>
                                        <span class="info-value">${{ number_format($reservation->flight->price, 2) }}/pers</span>
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
                                                    <strong>Data nașterii:</strong> {{ $passenger->date_of_birth->format('d/m/Y') }}
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
                            <a href="#" class="btn btn-primary">Vezi detalii</a>
                            @if($reservation->status === 'confirmed')
                                <a href="#" class="btn btn-secondary">Modifică</a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach

            @if($reservations->hasPages())
                <div style="background: white; padding: 20px; border-radius: 12px; text-align: center;">
                    {{ $reservations->links() }}
                </div>
            @endif
        @else
            <div class="no-reservations">
                <h2>Nu aveți rezervări</h2>
                <p>Nu aveți încă nicio rezervare efectuată.</p>
                <a href="/display-model" class="btn btn-primary">Caută zboruri</a>
            </div>
        @endif
    </div>
</body>
</html>