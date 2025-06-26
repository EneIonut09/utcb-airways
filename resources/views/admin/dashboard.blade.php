<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - UTCB Airways</title>
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
                <a href="{{ route('admin.dashboard') }}" class="active">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                <a href="{{ route('admin.users') }}">
                    <i class="fas fa-users"></i> Utilizatori
                </a>
                <a href="{{ route('admin.flights') }}">
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
            <i class="fas fa-tachometer-alt"></i> Dashboard Administrativ
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

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-number">{{ $stats['total_users'] }}</div>
                <div class="stat-label">Utilizatori Înregistrați</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-plane"></i>
                </div>
                <div class="stat-number">{{ $stats['total_flights'] }}</div>
                <div class="stat-label">Zboruri Disponibile</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-user-shield"></i>
                </div>
                <div class="stat-number">{{ $stats['total_admins'] }}</div>
                <div class="stat-label">Administratori</div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-calendar-day"></i>
                </div>
                <div class="stat-number">{{ date('d') }}</div>
                <div class="stat-label">{{ date('F Y') }}</div>
            </div>
        </div>

        <div class="admin-table">
            <div class="table-header">
                <span><i class="fas fa-bolt"></i> Acțiuni Rapide</span>
            </div>
            <div style="padding: 2rem;">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;">
                    <a href="{{ route('admin.users') }}" class="btn btn-primary" style="padding: 1rem; text-align: center;">
                        <i class="fas fa-users"></i><br>
                        Gestionează Utilizatori
                    </a>
                    <a href="{{ route('admin.flights') }}" class="btn btn-primary" style="padding: 1rem; text-align: center;">
                        <i class="fas fa-plane"></i><br>
                        Gestionează Zboruri
                    </a>
                    <a href="/formular" class="btn btn-success" style="padding: 1rem; text-align: center;">
                        <i class="fas fa-plus"></i><br>
                        Adaugă Zbor Nou
                    </a>
                    <a href="/display-model" class="btn btn-primary" style="padding: 1rem; text-align: center;">
                        <i class="fas fa-eye"></i><br>
                        Vezi Toate Zborurile
                    </a>
                </div>
            </div>
        </div>

        <div class="admin-table">
            <div class="table-header">
                <span><i class="fas fa-info-circle"></i> Informații</span>
            </div>
            <div style="padding: 1rem;">
                <table>
                    <tr>
                        <td><strong>Administrator conectat:</strong></td>
                        <td>{{ Auth::user()->name }} ({{ Auth::user()->email }})</td>
                    </tr>
                    <tr>
                        <td><strong>Ultima conectare:</strong></td>
                        <td>{{ now()->format('d.m.Y H:i:s') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </main>
</body>
</html>