<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Verifica tu correo · Empleos Zacapoaxtla</title>
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
      padding: 40px;
      max-width: 460px;
      width: 100%;
      text-align: center;
      box-shadow: 0 10px 40px rgba(0,0,0,0.3);
    }
    .icon { font-size: 56px; margin-bottom: 10px; }
    .card h2 { color: #fff; margin-bottom: 10px; }
    .card p { color: #f0d0d0; font-size: 15px; line-height: 1.6; }
    .card strong { color: #fff; }
    .status-box {
      background: #dcfce7;
      color: #16a34a;
      padding: 10px 14px;
      border-radius: 8px;
      margin: 16px 0;
      font-size: 14px;
    }
    .btn-resend {
      width: 100%;
      padding: 13px;
      background: linear-gradient(45deg, #ff7a18, #ffb347);
      color: #fff;
      border: none;
      border-radius: 12px;
      font-size: 15px;
      font-weight: 700;
      cursor: pointer;
      margin-top: 18px;
    }
    .btn-resend:hover { opacity: 0.9; }
    .logout-link {
      display: block;
      margin-top: 16px;
      color: #f0d0d0;
      font-size: 13px;
      text-decoration: none;
      background: none;
      border: none;
      cursor: pointer;
    }
    .logout-link:hover { color: #fff; }
  </style>
</head>
<body>
  <div class="card">
    <div class="icon">📧</div>
    <h2>Verifica tu correo</h2>
    <p>Te mandamos un link de confirmación a <strong>{{ Auth::user()->email }}</strong>. Ábrelo para activar tu cuenta y poder entrar al dashboard.</p>

    @if (session('status'))
      <div class="status-box">{{ session('status') }}</div>
    @endif

    <form action="{{ route('verification.send') }}" method="POST">
      @csrf
      <button type="submit" class="btn-resend">Reenviar correo de verificación</button>
    </form>

    <form action="/logout" method="POST" style="margin-top:10px;">
      @csrf
      <button type="submit" class="logout-link">Cerrar sesión</button>
    </form>
  </div>
</body>
</html>
