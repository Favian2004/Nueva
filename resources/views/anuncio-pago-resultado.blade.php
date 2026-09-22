<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Resultado del pago · ¡SINTECZATE!</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <style>
    * { box-sizing: border-box; }
    body {
      margin: 0;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Nunito', Arial, sans-serif;
      background-image: url('{{ asset('img/fondos/fondoSintec.png') }}');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      background-attachment: fixed;
      padding: 20px;
    }
    .resultado-card {
      background: #fff;
      border-radius: 22px;
      max-width: 460px;
      width: 100%;
      padding: 44px 34px;
      text-align: center;
      box-shadow: 0 20px 60px rgba(0,0,0,.25);
    }
    .resultado-icono {
      width: 84px;
      height: 84px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 20px;
      box-shadow: 0 8px 20px rgba(0,0,0,.20);
    }
    .resultado-icono i { font-size: 38px; color: #fff; }
    .icono-exito { background: linear-gradient(135deg, #16a34a, #4ade80); }
    .icono-fallo { background: linear-gradient(135deg, #d93025, #f36a5a); }
    .icono-pendiente { background: linear-gradient(135deg, #ff7a18, #ffb347); }
    .resultado-titulo { font-size: 22px; font-weight: 800; color: #1a1a2e; margin: 0 0 12px; }
    .resultado-mensaje { font-size: 14.5px; color: #666; line-height: 1.6; margin-bottom: 28px; }
    .resultado-btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      text-decoration: none;
      font-weight: 700;
      font-size: 14.5px;
      padding: 12px 28px;
      border-radius: 999px;
      background: linear-gradient(45deg, #ff7a18, #ffb347);
      color: #fff;
      box-shadow: 0 4px 14px rgba(255,122,24,.30);
    }
    .resultado-btn:hover { opacity: .92; color: #fff; }
  </style>
</head>
<body>
  <div class="resultado-card">
    @if ($tipo === 'exito')
      <div class="resultado-icono icono-exito"><i class="bi bi-check-lg"></i></div>
      <h1 class="resultado-titulo">¡Pago recibido!</h1>
    @elseif ($tipo === 'fallo')
      <div class="resultado-icono icono-fallo"><i class="bi bi-x-lg"></i></div>
      <h1 class="resultado-titulo">El pago no se completó</h1>
    @else
      <div class="resultado-icono icono-pendiente"><i class="bi bi-hourglass-split"></i></div>
      <h1 class="resultado-titulo">Pago pendiente</h1>
    @endif

    <p class="resultado-mensaje">{{ $mensaje }}</p>

    <a href="/" class="resultado-btn">
      <i class="bi bi-house-door"></i> Volver al inicio
    </a>
  </div>
</body>
</html>
