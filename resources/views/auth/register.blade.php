<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Înregistrare</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    @vite(['resources/css/register.css'])
</head>
<body>
    <div class="auth-container">
        <div class="logo">
            <h1>UTCB Airways</h1>
            <p>Creează-ți contul</p>
        </div>
        
        <form method="POST" action="/register">
            @csrf
            
            <div class="form-group">
                <label for="name">Nume</label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       value="{{ old('name') }}" 
                       class="{{ $errors->has('name') ? 'input-error' : '' }}" 
                       required>
                @error('name')
                    <div class="error-field">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       value="{{ old('email') }}" 
                       class="{{ $errors->has('email') ? 'input-error' : '' }}" 
                       required>
                @error('email')
                    <div class="error-field">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="password">Parola</label>
                <input type="password" 
                       id="password" 
                       name="password" 
                       class="{{ $errors->has('password') ? 'input-error' : '' }}" 
                       required>
                @error('password')
                    <div class="error-field">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="password_confirmation">Confirmă parola</label>
                <input type="password" 
                       id="password_confirmation" 
                       name="password_confirmation" 
                       class="{{ $errors->has('password_confirmation') ? 'input-error' : '' }}" 
                       required>
                @error('password_confirmation')
                    <div class="error-field">{{ $message }}</div>
                @enderror
            </div>
            
            <button type="submit" class="submit-btn">Creează cont</button>
        </form>
        
        <div class="auth-links">
            <p>Ai deja cont? <a href="/login">Loghează-te aici</a></p>
            <p><a href="/home">Înapoi la pagina principală</a></p>
        </div>
    </div>
</body>
</html>