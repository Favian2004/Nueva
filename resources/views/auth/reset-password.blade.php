<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Restablecer contraseña · Empleos Zacapoaxtla</title>
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
  <style>
    * { box-sizing: border-box; }
    body {
      margin: 0;
      font-family: 'Nunito', sans-serif;
      background: linear-gradient(135deg, #6b1021, #8f1d2f, #b12d25);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }
    .card {
      background: rgba(255,255,255,0.08);
      backdrop-filter: blur(6px);
      border-radius: 20px;
      padding: 36px;
      max-width: 420px;
      width: 100%;
      box-shadow: 0 10px 40px rgba(0,0,0,0.3);
    }
    .card h2 {
      color: #fff;
      text-align: center;
      margin-bottom: 6px;
    }
    .card p.subtitle {
      color: #f0d0d0;
      text-align: center;
      font-size: 14px;
      margin-bottom: 24px;
    }
    label {
      color: #fff;
      font-size: 14px;
      font-weight: 600;
      display: block;
      margin-bottom: 6px;
    }
    .field { margin-bottom: 18px; }
    input[type="email"],
    input[type="password"] {
      width: 100%;
      padding: 12px 14px;
      border-radius: 10px;
      border: none;
      font-size: 15px;
      outline: none;
    }
    input[readonly] {
      background: rgba(255,255,255,0.6);
      color: #555;
    }
    .btn-submit {
      width: 100%;
      padding: 13px;
      background: linear-gradient(45deg, #ff7a18, #ffb347);
      color: #fff;
      border: none;
      border-radius: 12px;
      font-size: 16px;
      font-weight: 700;
      cursor: pointer;
      margin-top: 8px;
    }
    .btn-submit:hover { opacity: 0.9; }
    .error-box {
      background: #fdeaea;
      color: #c0392b;
      padding: 10px 14px;
      border-radius: 8px;
      margin-bottom: 16px;
      font-size: 14px;
    }
    .back-link {
      display: block;
      text-align: center;
      color: #f0d0d0;
      margin-top: 18px;
      font-size: 13px;
      text-decoration: none;
    }
    .back-link:hover { color: #fff; }
  </style>
</head>
<body>
  <div class="card">
    <h2>Restablecer contraseña</h2>
    <p class="subtitle">Crea una nueva contraseña para tu cuenta.</p>

    @if ($errors->any())
      <div class="error-box">
        @foreach ($errors->all() as $error)
          <div>{{ $error }}</div>
        @endforeach
      </div>
    @endif

    <form action="/reset-password" method="POST">
      @csrf
      <input type="hidden" name="token" value="{{ $token }}">

      <div class="field">
        <label>Correo electrónico</label>
        <input type="email" name="email" value="{{ old('email', $email) }}" readonly>
      </div>

      <div class="field">
        <label>Nueva contraseña</label>
        <input type="password" name="password" placeholder="Mínimo 8 caracteres" required minlength="8">
      </div>

      <div class="field">
        <label>Confirmar contraseña</label>
        <input type="password" name="password_confirmation" placeholder="Repite la contraseña" required minlength="8">
      </div>

      <button type="submit" class="btn-submit">Guardar nueva contraseña</button>
    </form>

    <a href="/acceso" class="back-link">← Volver a Acceso</a>
  </div>
</body>
</html>
