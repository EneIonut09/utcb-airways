<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionare Utilizatori - UTCB Airways Admin</title>
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
                <a href="{{ route('admin.users') }}" class="active">
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
            <i class="fas fa-users"></i> Gestionare Utilizatori
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

        <div class="admin-table">
            <div class="table-header">
                <span><i class="fas fa-users"></i> Utilizatori Înregistrați ({{ $users->total() }})</span>
                <span>Pagina {{ $users->currentPage() }} din {{ $users->lastPage() }}</span>
            </div>

            @if($users->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nume</th>
                            <th>Email</th>
                            <th>Înregistrat la</th>
                            <th>Acțiuni</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>
                                    <i class="fas fa-user"></i> {{ $user->name }}
                                </td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->created_at->format('d.m.Y H:i') }}</td>
                                <td>
                                    <form method="POST" action="{{ route('admin.users.delete', $user->id) }}" 
                                          style="display: inline;"
                                          onsubmit="return confirm('Sigur doriți să ștergeți utilizatorul {{ $user->name }}?')">
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
                    {{ $users->links() }}
                </div>
            @else
                <div style="padding: 2rem; text-align: center; color: #666;">
                    <i class="fas fa-users" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.3;"></i>
                    <p>Nu există utilizatori înregistrați încă.</p>
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