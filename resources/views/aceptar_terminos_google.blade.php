<!doctype html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Un último paso · ¡SINTECZATE!</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
      background: linear-gradient(135deg, #6b1021, #b12d25);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0;
      font-family: 'Nunito', Arial, sans-serif;
    }
    .card-paso {
      background: #fff;
      border-radius: 20px;
      max-width: 420px;
      width: 92%;
      padding: 36px 30px;
      box-shadow: 0 20px 50px rgba(0,0,0,.25);
      text-align: center;
    }
    .card-paso i.icono-google {
      font-size: 40px;
      color: #ff7a18;
      margin-bottom: 10px;
    }
    .card-paso h3 {
      font-weight: 800;
      color: #1a1a2e;
      margin-bottom: 6px;
    }
    .card-paso p.saludo {
      color: #777;
      font-size: 14px;
      margin-bottom: 24px;
    }
    .form-check {
      text-align: left;
      background: #fafafa;
      border-radius: 12px;
      padding: 14px 16px;
      margin-bottom: 22px;
    }
    .form-check-label {
      font-size: 13.5px;
      color: #444;
    }
    .form-check-label a {
      color: #ff7a18;
      font-weight: 700;
    }
    .btn-continuar {
      background: linear-gradient(45deg, #ff7a18, #ffb347);
      color: #fff;
      border: none;
      border-radius: 12px;
      padding: 13px;
      width: 100%;
      font-weight: 700;
      font-size: 15px;
    }
    .btn-continuar:hover { opacity: .92; color: #fff; }
    .error-msg {
      background: #fdeaea;
      color: #c0392b;
      padding: 10px 14px;
      border-radius: 8px;
      margin-bottom: 16px;
      font-size: 13.5px;
    }
  </style>
</head>

<body>

  <div class="card-paso">
    <i class="bi bi-google icono-google"></i>
    <h3>¡Ya casi, {{ explode(' ', $nombre)[0] }}!</h3>
    <p class="saludo">Solo falta un paso para crear tu cuenta con Google.</p>

    @if ($errors->any())
      <div class="error-msg">
        @foreach ($errors->all() as $error)
          <div>{{ $error }}</div>
        @endforeach
      </div>
    @endif

    <form action="/completar-registro-google" method="POST">
      @csrf
      <div class="form-check">
        <input class="form-check-input" type="checkbox" name="terminos" id="checkTerminosGoogle" required>
        <label class="form-check-label" for="checkTerminosGoogle">
          He leído y acepto los <a href="/terminos" target="_blank">Términos y Condiciones</a> de ¡SINTECZATE!
        </label>
      </div>

      <button type="submit" class="btn-continuar">
        <i class="bi bi-check-circle me-1"></i> Aceptar y crear mi cuenta
      </button>
    </form>
  </div>

</body>
</html>
