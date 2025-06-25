<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formular</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    @vite(['resources/css/flight-form.css'])
</head>
<body>
    <div class="container">
        <a href="/display-model" class="back-link">Înapoi</a>

        <div class="form-container">
            <h1>Adaugă zbor</h1>

            @if(session('success'))
                <div class="success-message"> 
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="error-message">
                    <strong>Vă rugăm rezolvați următoarele erori:</strong>
                    <ul style="margin: 10px 0 0 0; padding-left: 10px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="/formular">
                @csrf

                <div class="form-grid"> 
                    <div class="form-group"> 
                        <label for="flight-number">Numărul zborului *</label>
                        <input type="text" id="flight_number" name="flight_number"
                            placeholder="Ex: UT123" value="{{ old('flight_number') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="aircraft_type">Tipul Aeronavei *</label>
                        <select id="aircraft_type" name="aircraft_type" required>
                            <option value="">Selectează aeronava</option>
                            <option value="Boeing 737" @if(old('aircraft_type') == 'Boeing 737') selected @endif>Boeing 737</option>
                            <option value="Boeing 787" @if(old('aircraft_type') == 'Boeing 787') selected @endif>Boeing 787</option>
                            <option value="Airbus A320" @if(old('aircraft_type') == 'Airbus A320') selected @endif>Airbus A320</option>
                            <option value="Airbus A321" @if(old('aircraft_type') == 'Airbus A321') selected @endif>Airbus A321</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="departure_city">Orașul de plecare *</label>
                        <input type="text" id="departure_city" name="departure_city" 
                               placeholder="Ex: București" value="{{ old('departure_city') }}" required>
                    </div>

                    <div class="form-group"> 
                        <label for="arrival_city">Destinația *</label>
                        <input type="text" id="arrival_city" name="arrival_city" 
                               placeholder="Ex: Paris" value="{{ old('arrival_city') }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="departure_time">Data și ora plecării *</label>
                        <input type="datetime-local" id="departure_time" name="departure_time" 
                               value="{{ old('departure_time') }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="arrival_time">Data și ora sosirii *</label>
                        <input type="datetime-local" id="arrival_time" name="arrival_time" 
                               value="{{ old('arrival_time') }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="price">Prețul ($) *</label>
                        <input type="number" id="price" name="price" step="0.01" min="0" 
                               placeholder="Ex: 299.99" value="{{ old('price') }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="available_seats">Locuri disponibile *</label>
                        <input type="number" id="available_seats" name="available_seats" min="1" max="500" 
                               placeholder="Ex: 150" value="{{ old('available_seats') }}" required>
                    </div>
                </div>
                
                <button type="submit" class="submit-btn">Adaugă zbor</button>
            </form>
        </div>
    </div>
</body>
</html>