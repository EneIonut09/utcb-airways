<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UTCB Airways</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/home.css'])
</head>
<body>
    @if(session('success'))
        <div class="success-message" id="successMessage">
            {{ session('success') }}
        </div>
    @endif

    <header class="header">
        <div class="header-content">
            <a href="/home" class="logo">
                <i class="fas fa-plane"></i> UTCB Airways
            </a>
            <nav>
                <ul class="nav-menu">
                    <li><a href="#">Zboruri</a></li>
                    <li><a href="#">Hoteluri</a></li>
                    <li><a href="#">Mașini</a></li>
                    <li><a href="#">Găsește rezervarea</a></li>
                    <li><a href="#">Check-in</a></li>
                    <li><a href="#">Contact</a></li>

                    @auth
                        {{-- Verifică dacă utilizatorul este admin --}}
                        @if(Auth::user()->role === 'admin')
                            <li><a href="/admin/dashboard" class="admin-link">Admin Panel</a></li>
                        @endif
                        
                        <li class="user-dropdown">
                            <a href="#" class="user-name" onclick="toggleDropdown(event)">
                                {{ Auth::user()->name }}
                                <span class="dropdown-arrow">▼</span>
                            </a>
                            <div class="dropdown-menu" id="userDropdown">
                                <a href="{{ route('my-reservations') }}" class="dropdown-item">
                                    <i class=""></i> Rezervările mele
                                </a>
                                <form method="POST" action="/logout" style="margin: 0;" onsubmit="return confirmLogout()">
                                    @csrf
                                    <button type="submit" class="dropdown-item logout-btn">
                                        <i class="fas fa-sign-out-alt"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </li>
                    @else
                        <li><a href="/register">Cont</a></li>
                    @endauth
                </ul>
            </nav>
        </div>
    </header>

    <main>
        <section class="hero-section">
            <div class="hero-content">
                <div class="search-container">
                    <div class="search-tabs">
                        <button class="tab active">
                            <i class="fas fa-plane"></i> Zboruri
                        </button>
                        <button class="tab">
                            <i class="fas fa-hotel"></i> Hoteluri
                        </button>
                        <button class="tab">
                            <i class="fas fa-car"></i> Mașini
                        </button>
                    </div>

                    <div class="trip-type">
                        <div class="radio-group">
                            <input type="radio" id="roundtrip" name="trip_type" value="roundtrip" checked>
                            <label for="roundtrip">Dus-întors</label>
                        </div>
                        <div class="radio-group">
                            <input type="radio" id="oneway" name="trip_type" value="oneway">
                            <label for="oneway">Dus</label>
                        </div>
                    </div>

                    <form class="search-form">
                        <div class="form-group">
                            <label for="departure">Orașul de plecare</label>
                            <input type="text" id="departure" name="departure" placeholder="Orașul de plecare" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="destination">Destinația</label>
                            <input type="text" id="destination" name="destination" placeholder="Destinația" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="departure_date">Plecare</label>
                            <input type="date" id="departure_date" name="departure_date" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="passengers">Pasageri</label>
                            <select id="passengers" name="passengers">
                                <option value="1">1 Adult</option>
                                <option value="2">2 Adulți</option>
                                <option value="3">3 Adulți</option>
                                <option value="4">4 Adulți</option>
                                <option value="5">5+ Adulți</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="search-btn">
                            <i class="fas fa-search"></i>
                            Căutați
                        </button>
                    </form>
                </div>

                <div class="promo-content">
                    <h1 class="promo-title">
                        @auth
                            Bun venit înapoi, {{ Auth::user()->name }}!
                        @else
                            Descoperă următoarea aventură!
                        @endauth
                    </h1>
                    <p class="promo-subtitle">
                        @auth
                            Unde vrei să călătorești astăzi? Explorează ofertele noastre speciale și rezervă următoarea ta aventură.
                        @else
                            Alege UTCB Airways și vei avea parte de experiențe premium la prețuri imbatabile. 
                            Călătoria ta începe aici.
                        @endauth
                    </p>
                    <a href="/display-model" class="cta-button">
                        Explorează destinații
                    </a>
                </div>
            </div>
        </section>

        <section class="features-section">
            <div class="features-container">
                <h2 class="features-title">De ce să alegi UTCB Airways?</h2>
                <div class="features-grid">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-dollar-sign"></i>
                        </div>
                        <h3 class="feature-title">Prețuri Avantajoase</h3>
                        <p class="feature-description">
                            Vă garantăm cele mai mici prețuri cu politica noastră de price-matching. 
                            Economisiți mai mult la fiecare rezervare.
                        </p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h3 class="feature-title">Asistență 24/7</h3>
                        <p class="feature-description">
                            Echipa noastră de asistență este disponibilă 24/7 pentru a vă ajuta.
                        </p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h3 class="feature-title">Siguranță și Securitate</h3>
                        <p class="feature-description">
                            Siguranța ta este prioritatea noastră. Menținem cele mai înalte standarde de securitate
                            și garantăm un sistem de rezervare sigur.
                        </p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-globe"></i>
                        </div>
                        <h3 class="feature-title">Rețea Globală</h3>
                        <p class="feature-description">
                            Conectează-te la peste 200 de destinații din întreaga lume prin rețeaua noastră extinsă de rute
                            și companiile aeriene partenere.
                        </p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-mobile-alt"></i>
                        </div>
                        <h3 class="feature-title">Compatibil cu Mobilul</h3>
                        <p class="feature-description">
                            Rezervă și gestionează zborurile oriunde te-ai afla, prin designul nostru adaptabil și aplicația mobilă.
                        </p>
                    </div>
                    
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <h3 class="feature-title">Servicii Premium</h3>
                        <p class="feature-description">
                            Bucură-te de servicii excepționale din momentul rezervării până la aterizare,
                            oferite de echipajul nostru dedicat și facilitățile premium.
                        </p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script>
        function toggleDropdown(event) {
            event.preventDefault();
            const dropdown = document.getElementById('userDropdown');
            const arrow = document.querySelector('.dropdown-arrow');
            
            if (dropdown.style.display === 'block') {
                dropdown.style.display = 'none';
                arrow.style.transform = 'rotate(0deg)';
            } else {
                dropdown.style.display = 'block';
                arrow.style.transform = 'rotate(180deg)';
            }
        }

        document.addEventListener('click', function(event) {
            const dropdown = document.getElementById('userDropdown');
            const userDropdown = document.querySelector('.user-dropdown');
            const arrow = document.querySelector('.dropdown-arrow');
            
            if (userDropdown && !userDropdown.contains(event.target)) {
                dropdown.style.display = 'none';
                if (arrow) arrow.style.transform = 'rotate(0deg)';
            }
        });

        const successMessage = document.getElementById('successMessage');
        if (successMessage) {
            setTimeout(() => {
                successMessage.style.opacity = '0';
                setTimeout(() => {
                    successMessage.remove();
                }, 300);
            }, 3000);
        }

        const departureDateInput = document.getElementById('departure_date');
        if (departureDateInput) {
            const today = new Date().toISOString().split('T')[0];
            departureDateInput.min = today;
        }
    </script>
</body>
</html>