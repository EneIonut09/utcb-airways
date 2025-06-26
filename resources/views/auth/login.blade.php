<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    @vite(['resources/css/login.css'])
</head>
<body>
    <div class="auth-container">
        <div class="logo">
            <h1>UTCB Airways</h1>
            <p>Bun venit înapoi!</p>
        </div>
        
        @if($errors->any())
            <div class="error-message">
                Datele de autentificare sunt incorecte.
            </div>
        @endif
        
        <form method="POST" action="/login">
            @csrf
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       value="{{ old('email') }}" 
                       required>
            </div>
            
            <div class="form-group">
                <label for="password">Parola</label>
                <input type="password" 
                       id="password" 
                       name="password" 
                       required>
            </div>
            
            <div class="remember-me">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">Ține-mă minte</label>
            </div>
            
            <button type="submit" class="submit-btn">Loghează-te</button>
        </form>
        
        <div class="auth-links">
            <p>Nu ai cont? <a href="/register">Înregistrează-te aici</a></p>
            <p><a href="/home">Înapoi la pagina principală</a></p>
        </div>
    </div>
</body>
</html>