<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Página caducada · ¡SINTECZATE!</title>
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
    .error-card {
      background: #fff;
      border-radius: 22px;
      max-width: 460px;
      width: 100%;
      padding: 44px 34px;
      text-align: center;
      box-shadow: 0 20px 60px rgba(0,0,0,.25);
    }
    .error-icono {
      width: 84px;
      height: 84px;
      border-radius: 50%;
      background: linear-gradient(135deg, #ff7a18, #ffb347);
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 20px;
      box-shadow: 0 8px 20px rgba(255,122,24,.35);
    }
    .error-icono i { font-size: 38px; color: #fff; }
    .error-codigo {
      font-size: 13px;
      font-weight: 800;
      letter-spacing: 1.5px;
      color: #ff7a18;
      margin-bottom: 6px;
      text-transform: uppercase;
    }
    .error-titulo { font-size: 22px; font-weight: 800; color: #1a1a2e; margin: 0 0 12px; }
    .error-mensaje { font-size: 14.5px; color: #666; line-height: 1.6; margin-bottom: 28px; }
    .error-acciones { display: flex; flex-direction: column; gap: 10px; }
    .error-btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      text-decoration: none;
      font-weight: 700;
      font-size: 14.5px;
      padding: 12px 28px;
      border-radius: 999px;
      border: none;
      cursor: pointer;
    }
    .error-btn-principal {
      background: linear-gradient(45deg, #ff7a18, #ffb347);
      color: #fff;
      box-shadow: 0 4px 14px rgba(255,122,24,.30);
    }
    .error-btn-principal:hover { opacity: .92; color: #fff; transform: translateY(-1px); }
    .error-btn-secundario {
      background: #fff;
      color: #888;
      border: 1.5px solid #e0e0e0;
    }
    .error-btn-secundario:hover { background: #f7f7f7; color: #555; }
  </style>
</head>
<body>
  <div class="error-card">
    <div class="error-icono"><i class="bi bi-clock-history"></i></div>
    <div class="error-codigo">Error 419</div>
    <h1 class="error-titulo">Tu página se quedó abierta mucho tiempo</h1>
    <p class="error-mensaje">Por seguridad, las sesiones caducan después de un rato de inactividad. Esto no significa que perdiste tu cuenta — solo necesitas volver a intentar la acción.</p>
    <div class="error-acciones">
      <a href="{{ url()->previous() }}" class="error-btn error-btn-principal">
        <i class="bi bi-arrow-clockwise"></i> Recargar e intentar de nuevo
      </a>
      <a href="/" class="error-btn error-btn-secundario">
        <i class="bi bi-house-door"></i> Volver al inicio
      </a>
    </div>
  </div>
</body>
</html>
