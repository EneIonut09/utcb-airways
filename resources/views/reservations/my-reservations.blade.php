<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rezervările mele - UTCB Airways</title>
    @vite(['resources/css/my-reservations.css'])
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="nav">
                <div class="logo">
                    <h1><a href="{{ route('home') }}" style="color: white; text-decoration: none;">UTCB Airways</a></h1>
                </div>
                <nav class="nav-links">
                    <a href="{{ route('home') }}">Acasă</a>
                    @auth
                        <a href="{{ route('my-reservations') }}" class="active">Rezervările mele</a>
                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('admin.users') }}">Admin</a>
                        @endif
                        <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="logout-btn">Deconectare ({{ Auth::user()->name }})</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}">Conectare</a>
                        <a href="{{ route('register') }}">Înregistrare</a>
                    @endauth
                </nav>
            </div>
        </div>
    </header>

    <section class="hero-section">
        <div class="container">
            <div class="hero-content">
                <h1>Rezervările mele</h1>
                <p>Bună, <strong>{{ Auth::user()->name }}</strong>! Gestionează-ți rezervările de zbor aici.</p>
            </div>
        </div>
    </section>

    <main class="main-content">
        <div class="container">
            @if(session('success'))
                <div class="alert alert-success">
                    <i class="icon">✓</i>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-error">
                    <i class="icon">⚠</i>
                    {{ session('error') }}
                </div>
            @endif

            @if($reservations->count() > 0)
                <div class="reservations-section">
                    <div class="section-header">
                        <h2>Rezervările tale</h2>
                        <p>{{ $reservations->total() }} {{ $reservations->total() == 1 ? 'rezervare găsită' : 'rezervări găsite' }}</p>
                    </div>

                    <div class="reservations-grid">
                        @foreach($reservations as $reservation)
                            <div class="reservation-card">
                                <div class="card-header">
                                    <div class="flight-badge">
                                        <span class="flight-number">{{ $reservation->flight ? $reservation->flight->flight_number : 'N/A' }}</span>
                                    </div>
                                    <div class="status-badge status-{{ $reservation->status }}">
                                        @switch($reservation->status)
                                            @case('confirmed')
                                                <i class="status-icon">✓</i> Confirmată
                                                @break
                                            @case('pending')
                                                <i class="status-icon">⏳</i> În așteptare
                                                @break
                                            @case('cancelled')
                                                <i class="status-icon">✕</i> Anulată
                                                @break
                                            @default
                                                {{ ucfirst($reservation->status) }}
                                        @endswitch
                                    </div>
                                </div>

                                <div class="flight-route">
                                    <div class="route-info">
                                        <div class="city departure">
                                            <span class="city-name">{{ $reservation->flight ? $reservation->flight->departure_city : 'N/A' }}</span>
                                            <span class="time">{{ $reservation->flight && $reservation->flight->departure_time ? $reservation->flight->departure_time->format('H:i') : 'N/A' }}</span>
                                        </div>
                                        <div class="route-line">
                                            <div class="plane-icon">✈</div>
                                            <div class="line"></div>
                                        </div>
                                        <div class="city arrival">
                                            <span class="city-name">{{ $reservation->flight ? $reservation->flight->arrival_city : 'N/A' }}</span>
                                            <span class="time">{{ $reservation->flight && $reservation->flight->arrival_time ? $reservation->flight->arrival_time->format('H:i') : 'N/A' }}</span>
                                        </div>
                                    </div>
                                    <div class="flight-date">
                                        {{ $reservation->flight && $reservation->flight->departure_date ? $reservation->flight->departure_date->format('d M Y') : 'N/A' }}
                                    </div>
                                </div>

                                <div class="reservation-info">
                                    <div class="info-grid">
                                        <div class="info-item">
                                            <span class="label">Pasageri</span>
                                            <span class="value">{{ $reservation->number_of_passengers }}</span>
                                        </div>
                                        <div class="info-item">
                                            <span class="label">Preț total</span>
                                            <span class="value price">{{ number_format($reservation->total_price, 2) }} $</span>
                                        </div>
                                        <div class="info-item">
                                            <span class="label">Rezervat la</span>
                                            <span class="value">{{ $reservation->created_at->format('d.m.Y') }}</span>
                                        </div>
                                        @if($reservation->special_requests)
                                            <div class="info-item full-width">
                                                <span class="label">Cerințe speciale</span>
                                                <span class="value">{{ $reservation->special_requests }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="card-actions">
                                    <a class="btn btn-outline">
                                        Vezi detalii
                                    </a>
                                    @if($reservation->status === 'confirmed')
                                        <button class="btn btn-secondary">Modifică</button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="pagination-wrapper">
                        {{ $reservations->links() }}
                    </div>
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-illustration">
                        <div class="plane-illustration">✈️</div>
                        <div class="clouds">☁️ ☁️</div>
                    </div>
                    <h2>Nu ai încă rezervări</h2>
                    <p>Începe să explorezi destinațiile noastre și rezervă primul tău zbor!</p>
                    <a href="{{ route('home') }}" class="btn btn-primary">
                        <span>Explorează destinațiile</span>
                        <i class="arrow">→</i>
                    </a>
                </div>
            @endif
        </div>
    </main>
</body>
</html>