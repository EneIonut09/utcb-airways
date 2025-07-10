<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>În curând - UTCB Airways</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    @vite(['resources/css/booking.css', 'resources/css/coming-soon.css'])  
</head>
<body>
    <div class="coming-soon-container">
        <div class="header-section">
            <div class="logo">UTCB Airways</div>
            <div class="plane-icon">✈️</div>
            <h1 class="title">În curând</h1>
            <p class="subtitle">
                Lucrăm din greu pentru a vă aduce această funcționalitate.<br>
                Vă mulțumim pentru răbdare!
            </p>
        </div>

        <div class="content-section">
            <div class="status-badge">
                ✓ În dezvoltare activă
            </div>

            <div class="progress-section">
                <div class="progress-bar">
                    <div class="progress-fill"></div>
                </div>
                <div class="status-text">Dezvoltare în progres...</div>
            </div>

            <div class="info-card">
                <div class="info-card-title">
                    Informație
                </div>
                <div class="info-card-text">
                    Această funcționalitate este în curs de dezvoltare și va fi disponibilă în curând. 
                    Echipa noastră lucrează pentru a vă oferi cea mai bună experiență posibilă.
                </div>
            </div>
            </div>

            <div class="actions">
                <a href="{{ url()->previous() }}" class="btn btn-success">
                    ← Înapoi
                </a>
                <a href="/display-model" class="btn btn-primary">
                    Vezi zborurile
                </a>
                <a href="/my-reservations" class="btn btn-secondary">
                    Rezervările mele
                </a>
            </div>
        </div>
    </div>
</body>
</html>