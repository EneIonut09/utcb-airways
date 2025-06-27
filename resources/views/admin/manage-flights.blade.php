<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionare Zboruri - UTCB Airways Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/admin.css'])
</head>
<body>
    <header class="admin-header">
        <div class="admin-header-content">
            <a href="{{ route('admin.dashboard') }}" class="admin-logo">
                <i class="fas fa-plane"></i> UTCB Airways Admin
            </a>
            <nav class="admin-nav">
                <a href="{{ route('admin.dashboard') }}">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                <a href="{{ route('admin.users') }}">
                    <i class="fas fa-users"></i> Utilizatori
                </a>
                <a href="{{ route('admin.flights') }}" class="active">
                    <i class="fas fa-plane"></i> Zboruri
                </a>
                <a href="/formular">
                    <i class="fas fa-plus"></i> Adaugă Zbor
                </a>
                <form method="POST" action="/logout" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-danger" style="margin-left: 1rem;">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </nav>
        </div>
    </header>

    <main class="admin-container">
        <h1 class="admin-title">
            <i class="fas fa-plane"></i> Gestionare Zboruri
        </h1>

        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif

        <div style="margin-bottom: 2rem;">
            <a href="/formular" class="btn btn-success">
                <i class="fas fa-plus"></i> Adaugă Zbor Nou
            </a>
            <a href="/insert-model" class="btn btn-primary">
                <i class="fas fa-database"></i> Inserează Date Demo
            </a>
        </div>

        <div class="admin-table">
            <div class="table-header">
                <span><i class="fas fa-plane"></i> Zboruri Disponibile ({{ $flights->total() }})</span>
                <span>Pagina {{ $flights->currentPage() }} din {{ $flights->lastPage() }}</span>
            </div>

            @if($flights->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>Numărul Zborului</th>
                            <th>Ruta</th>
                            <th>Data & Ora</th>
                            <th>Preț</th>
                            <th>Locuri</th>
                            <th>Aeronava</th>
                            <th>Acțiuni</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($flights as $flight)
                            <tr>
                                <td>
                                    <strong>{{ $flight->flight_number }}</strong>
                                </td>
                                <td>
                                    <i class="fas fa-plane-departure"></i> {{ $flight->departure_city }}
                                    <br>
                                    <i class="fas fa-plane-arrival"></i> {{ $flight->arrival_city }}
                                </td>
                                <td>
                                    <div>
                                        <strong>Plecare:</strong> {{ \Carbon\Carbon::parse($flight->departure_time)->format('d.m.Y H:i') }}
                                    </div>
                                    <div>
                                        <strong>Sosire:</strong> {{ \Carbon\Carbon::parse($flight->arrival_time)->format('d.m.Y H:i') }}
                                    </div>
                                </td>
                                <td>
                                    <strong style="color: #4caf50;">{{ number_format($flight->price, 2) }} $</strong>
                                </td>
                                <td>
                                    <span class="badge {{ $flight->available_seats > 50 ? 'badge-success' : ($flight->available_seats > 10 ? 'badge-warning' : 'badge-danger') }}">
                                        {{ $flight->available_seats }} locuri
                                    </span>
                                </td>
                                <td>{{ $flight->aircraft_type }}</td>
                                <td>
                                    <form method="POST" action="{{ route('admin.flights.delete', $flight->id) }}" 
                                          style="display: inline;"
                                          onsubmit="return confirm('Sigur doriți să ștergeți zborul {{ $flight->flight_number }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fas fa-trash"></i> Șterge
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div style="padding: 1rem; text-align: center;">
                    {{ $flights->links() }}
                </div>
            @else
                <div style="padding: 2rem; text-align: center; color: #666;">
                    <i class="fas fa-plane" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.3;"></i>
                    <p>Nu există zboruri disponibile încă.</p>
                    <a href="/formular" class="btn btn-success" style="margin-top: 1rem;">
                        <i class="fas fa-plus"></i> Adaugă primul zbor
                    </a>
                </div>
            @endif
        </div>

        <div style="margin-top: 2rem;">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">
                <i class="fas fa-arrow-left"></i> Înapoi la Dashboard
            </a>
        </div>
    </main>
</body>
</html>