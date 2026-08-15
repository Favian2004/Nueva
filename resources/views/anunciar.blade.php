<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Anúnciate aquí · ¡SINTECZATE!</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <link rel="icon" href="{{ asset('img/template/home.png') }}" type="image/png" sizes="192x192">

  <style>
    body { background: #fdf6ee; }

    .anunciar-hero {
      background: linear-gradient(135deg, #6b1021, #b12d25);
      color: #fff;
      padding: 50px 0 60px;
      text-align: center;
    }
    .anunciar-hero-icono {
      width: 68px;
      height: 68px;
      border-radius: 50%;
      background: rgba(255,255,255,.15);
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 14px;
      font-size: 30px;
      color: #ffcf33;
      border: 2px solid rgba(255,207,51,.4);
    }
    .anunciar-hero h1 { font-weight: 800; font-size: 32px; margin-bottom: 8px; }
    .anunciar-hero p { font-size: 15px; opacity: .92; max-width: 560px; margin: 0 auto; }

    .costo-diario {
      background: #fff;
      border-radius: 16px;
      max-width: 700px;
      margin: -34px auto 40px;
      box-shadow: 0 10px 30px rgba(0,0,0,.10);
      padding: 22px 28px;
      text-align: center;
      position: relative;
    }
    .costo-diario i { color: #ffb347; font-size: 26px; }
    .costo-diario strong { color: #ff7a18; }

    .planes-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 18px;
      max-width: 700px;
      margin: 0 auto 40px;
    }
    .plan-card {
      background: #fff;
      border-radius: 16px;
      padding: 24px 20px;
      text-align: center;
      border: 2px solid #f0f0f0;
      box-shadow: 0 4px 15px rgba(0,0,0,.05);
    }
    .plan-card.destacado {
      border-color: #ff7a18;
      position: relative;
    }
    .plan-card .badge-ahorro {
      position: absolute;
      top: -12px;
      left: 50%;
      transform: translateX(-50%);
      background: linear-gradient(45deg, #ff7a18, #ffb347);
      color: #fff;
      font-size: 11px;
      font-weight: 700;
      padding: 4px 14px;
      border-radius: 20px;
      white-space: nowrap;
    }
    .plan-card h5 { font-weight: 700; color: #1a1a2e; margin-bottom: 4px; }
    .plan-card .precio { font-size: 30px; font-weight: 800; color: #6b1021; }
    .plan-card .precio small { font-size: 13px; font-weight: 500; color: #888; }
    .plan-card .por-dia { font-size: 12px; color: #999; margin-top: 4px; }

    .comparacion-tabla {
      max-width: 700px;
      margin: 0 auto 40px;
      background: #fff;
      border-radius: 16px;
      overflow: hidden;
      box-shadow: 0 4px 15px rgba(0,0,0,.05);
    }
    .comparacion-tabla table { margin: 0; }
    .comparacion-tabla th { background: #6b1021; color: #fff; font-size: 13px; padding: 12px 16px; }
    .comparacion-tabla td { font-size: 13px; padding: 10px 16px; vertical-align: middle; }
    .comparacion-tabla tr:last-child td { background: #fff7ef; font-weight: 700; color: #ff7a18; }

    .deposito-box {
      max-width: 700px;
      margin: 0 auto 40px;
      background: #1a1a2e;
      color: #fff;
      border-radius: 16px;
      padding: 24px 28px;
    }
    .deposito-box h5 { font-weight: 700; margin-bottom: 14px; }
    .deposito-box .dato { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid rgba(255,255,255,.1); font-size: 14px; }
    .deposito-box .dato:last-child { border-bottom: none; }
    .deposito-box .dato .valor { font-weight: 700; color: #ffcf33; }
    .deposito-box .copiar { cursor: pointer; color: #ffb347; font-size: 12px; margin-left: 8px; }

    .form-anunciar {
      max-width: 700px;
      margin: 0 auto 60px;
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 4px 20px rgba(0,0,0,.06);
      padding: 30px 28px;
    }
    .form-anunciar h5 { font-weight: 700; color: #1a1a2e; margin-bottom: 18px; }

    .form-anunciar .form-label {
      color: #333 !important;
      font-weight: 600 !important;
      font-size: 14px !important;
      display: block !important;
      margin-bottom: 6px !important;
    }

    .plan-radio {
      display: flex;
      gap: 12px;
      margin-bottom: 18px;
    }
    .plan-radio label {
      flex: 1;
      border: 2px solid #e5e7eb;
      border-radius: 12px;
      padding: 12px;
      text-align: center;
      cursor: pointer;
      font-size: 14px;
      font-weight: 600;
      color: #555;
      transition: 0.15s;
    }
    .plan-radio input { display: none; }
    .plan-radio label.seleccionado {
      border-color: #ff7a18;
      background: #fff7ef;
      color: #ff7a18;
    }

    .btn-enviar-anuncio {
      background: linear-gradient(45deg, #ff7a18, #ffb347);
      color: #fff;
      border: none;
      padding: 12px;
      border-radius: 10px;
      font-weight: 700;
      width: 100%;
    }

    .back-button {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: #fff;
      border: 1.5px solid #ffd8ae;
      color: #ff7a18;
      font-weight: 600;
      font-size: 0.9rem;
      padding: 8px 18px;
      border-radius: 30px;
      text-decoration: none;
      transition: 0.2s;
      box-shadow: 0 2px 6px rgba(0,0,0,0.04);
      cursor: pointer;
    }
    .back-button:hover {
      background: #ff7a18;
      border-color: #ff7a18;
      color: #fff;
      transform: translateX(-3px);
      box-shadow: 0 4px 12px rgba(255,122,24,0.25);
    }
  </style>
</head>

<body>

  <!-- HEADER -->
  <div class="header__top">
    <div class="container d-flex justify-content-between align-items-center">
      <div class="header__left">
        <ul class="d-flex list-unstyled mb-0">
          <li class="me-3"><a href="#"><i class="fab fa-facebook-f"></i></a></li>
          <li class="me-3"><a href="#"><i class="fab fa-instagram"></i></a></li>
          <li><a href="#"><i class="fab fa-youtube"></i></a></li>
        </ul>
      </div>
      <div class="header__right d-flex align-items-center">
        <div><i class="bi bi-telephone"></i> Línea directa: <strong>2331014306</strong></div>
      </div>
    </div>
  </div>

  <!-- NAVBAR -->
  <nav class="navbar navbar-expand-sm navbar-dark bg-custom">
    <div class="container-fluid">
      <a class="navbar-brand" href="/">
        <img src="{{ asset('img/template/logo.png') }}" width="75">
        <span class="brand-text">¡SINTECZATE!</span>
      </a>
      <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#menu">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="menu">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="/"><i class="bi bi-house-door me-1"></i> Inicio</a></li>
          <li class="nav-item"><a class="nav-link" href="/servicio-cliente"><i class="fas fa-headset me-1"></i> Servicio al cliente</a></li>
          <li class="nav-item"><a class="nav-link active" href="/anunciar"><i class="bi bi-megaphone me-1"></i> Anúnciate aquí</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- BOTÓN REGRESAR -->
  <div class="container mt-3">
    <button type="button" onclick="window.history.length > 1 ? window.history.back() : window.location.href='/'" class="back-button">
      <i class="bi bi-arrow-left"></i> Regresar
    </button>
  </div>

  <!-- HERO -->
  <div class="anunciar-hero">
    <div class="anunciar-hero-icono">
      <i class="bi bi-megaphone-fill"></i>
    </div>
    <h1>Anuncia tu negocio en ¡SINTECZATE!</h1>
    <p>Llega a toda la gente de tu municipio que ya está buscando servicios y negocios como el tuyo.</p>
  </div>

  <div class="container">

    <!-- COSTO POR DÍA -->
    <div class="costo-diario">
      <i class="bi bi-lightbulb-fill"></i>
      <p class="mb-0 mt-2" style="font-size:15px;">
        Por menos de <strong>$2 pesos al día</strong>, tu negocio aparece en la página que ve toda la gente de tu municipio.
      </p>
    </div>

    <!-- ¡MENSAJE DE ÉXITO O ERROR! -->
    @if (session('exito'))
      <div class="alert alert-success text-center" style="max-width:700px; margin:0 auto 24px;">
        <i class="bi bi-check-circle-fill"></i> {{ session('exito') }}
      </div>
    @endif
    @if ($errors->any())
      <div class="alert alert-danger" style="max-width:700px; margin:0 auto 24px;">
        <ul class="mb-0">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <!-- PLANES -->
    <div class="planes-grid">
      <div class="plan-card">
        <h5>Mensual</h5>
        <div class="precio">$49 <small>/mes</small></div>
        <div class="por-dia">≈ $1.63 al día</div>
      </div>
      <div class="plan-card destacado">
        <span class="badge-ahorro">2 MESES GRATIS</span>
        <h5>Anual</h5>
        <div class="precio">$490 <small>/año</small></div>
        <div class="por-dia">≈ $1.34 al día</div>
      </div>
    </div>

    <!-- COMPARACIÓN -->
    <div class="comparacion-tabla">
      <table class="table mb-0">
        <thead>
          <tr><th>Alternativa</th><th class="text-end">Costo aproximado</th></tr>
        </thead>
        <tbody>
          <tr><td>Volantes impresos (500 pzas)</td><td class="text-end">$400 – $800</td></tr>
          <tr><td>Anuncio en radio local (1 mes)</td><td class="text-end">$1,500 – $3,000+</td></tr>
          <tr><td>Espectacular / lona</td><td class="text-end">$2,000+</td></tr>
          <tr><td>Tu anuncio en ¡SINTECZATE! (1 mes)</td><td class="text-end">$49</td></tr>
        </tbody>
      </table>
    </div>

    <!-- DATOS DE DEPÓSITO -->
    <div class="deposito-box">
      <h5><i class="bi bi-bank"></i> Datos para tu transferencia</h5>
      <div class="dato"><span>Banco</span><span class="valor">{{ $banco }}</span></div>
      <div class="dato"><span>CLABE</span><span class="valor">{{ $clabe }}</span></div>
      <div class="dato"><span>A nombre de</span><span class="valor">{{ $titular }}</span></div>
      <p class="mb-0 mt-3" style="font-size:12.5px; opacity:.85;">
        Haz tu transferencia por el plan que elegiste, toma una foto o captura de pantalla del comprobante, y súbela en el formulario de abajo. En cuanto confirmemos tu pago, publicamos tu anuncio.
      </p>

      <div style="margin-top:16px; padding-top:14px; border-top:1px solid rgba(255,255,255,.12);">
        <p class="mb-2" style="font-size:13px; font-weight:700; color:#ffcf33;">
          <i class="bi bi-check-circle-fill"></i> Aceptamos transferencia desde cualquier banco o app:
        </p>
        <div style="display:flex; flex-wrap:wrap; gap:8px;">
          @foreach (['BBVA','Santander','Banorte','HSBC','Banamex','Banco Azteca','Mercado Pago','Nu'] as $bancoAceptado)
            <span style="background:rgba(255,255,255,.12); padding:4px 12px; border-radius:20px; font-size:12px;">{{ $bancoAceptado }}</span>
          @endforeach
        </div>
        <p class="mb-0 mt-2" style="font-size:11.5px; opacity:.75;">
          Solo necesitas la app de tu propio banco — busca "Transferir" o "SPEI" y pega la CLABE de arriba. No se acepta efectivo en OXXO ni en cajeros.
        </p>
      </div>
    </div>

    <!-- FORMULARIO -->
    <div class="form-anunciar">
      <h5><i class="bi bi-pencil-square"></i> Datos de tu negocio</h5>

      <form action="{{ route('anunciar.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
          <label class="form-label">Nombre del negocio <span class="text-danger">*</span></label>
          <input type="text" name="nombre_negocio" class="form-control" value="{{ old('nombre_negocio') }}" placeholder="Ej. Taquería Doña Lupe" required>
        </div>

        <div class="mb-3">
          <label class="form-label">Describe tu negocio <span class="text-danger">*</span></label>
          <textarea name="descripcion" class="form-control" rows="3" placeholder="¿Qué ofreces? ¿Qué te hace diferente?" required>{{ old('descripcion') }}</textarea>
        </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">Teléfono <span class="text-danger">*</span></label>
            <input type="tel" name="telefono" class="form-control" value="{{ old('telefono') }}" placeholder="10 dígitos" required>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">WhatsApp (opcional)</label>
            <input type="tel" name="whatsapp" class="form-control" value="{{ old('whatsapp') }}" placeholder="Si es diferente al teléfono">
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">Correo (opcional)</label>
          <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="Para avisarte cuando se publique">
        </div>

        <div class="mb-3">
          <label class="form-label">Elige tu plan <span class="text-danger">*</span></label>
          <div class="plan-radio" id="planRadioGroup">
            <label class="seleccionado" data-plan="mensual">
              <input type="radio" name="plan" value="mensual" checked>
              <span>Mensual · $49</span>
            </label>
            <label data-plan="anual">
              <input type="radio" name="plan" value="anual">
              <span>Anual · $490 (2 meses gratis)</span>
            </label>
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">¿Ya tienes tu diseño del anuncio? (opcional)</label>
          <input type="file" name="imagen_negocio" class="form-control" accept="image/*">
          <small class="text-muted">Si no subes nada, te ayudamos a armar uno con tu descripción de arriba.</small>
        </div>

        <div class="mb-4">
          <label class="form-label">Sube tu comprobante de transferencia <span class="text-danger">*</span></label>
          <input type="file" name="comprobante_pago" class="form-control" accept="image/*,.pdf" required>
          <small class="text-muted">Puede ser una foto, captura de pantalla, o el PDF que te dio tu banco.</small>
        </div>

        <button type="submit" class="btn-enviar-anuncio">
          <i class="bi bi-send-fill"></i> Enviar solicitud
        </button>
      </form>
    </div>

  </div>

  <footer class="footer-pro mt-5">
    <div class="container py-5">
      <div class="row g-4">
        <div class="col-md-4 text-center text-md-start">
          <h4 class="fw-bold text-white">¡SINTECZATE!</h4>
          <p class="text-light">Conectamos personas que ofrecen y buscan servicios de forma rápida y segura.</p>
        </div>
        <div class="col-md-4 text-center">
          <h5 class="text-white mb-3">Navegación</h5>
          <ul class="list-unstyled">
            <li><a href="/">Inicio</a></li>
            <li><a href="/terminos">Términos</a></li>
            <li><a href="/acerca-de">Acerca de</a></li>
            <li><a href="/acceso">Acceso</a></li>
            <li><a href="/servicio-cliente">Servicio al cliente</a></li>
          </ul>
        </div>
        <div class="col-md-4 text-center text-md-end">
          <h5 class="text-white mb-3">Contacto</h5>
          <p><i class="bi bi-telephone"></i> 2331014306</p>
          <p><i class="bi bi-geo-alt"></i> Teziutlán, Puebla</p>
          <p><i class="bi bi-envelope"></i> contacto@conectaya.com</p>
        </div>
      </div>
      <hr class="border-light mt-4">
      <div class="text-center text-light small">© 2026 ¡SINTECZATE! | Todos los derechos reservados</div>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    // Marca visualmente cuál plan está seleccionado (mensual/anual)
    document.querySelectorAll('#planRadioGroup label').forEach(label => {
      label.addEventListener('click', function () {
        document.querySelectorAll('#planRadioGroup label').forEach(l => l.classList.remove('seleccionado'));
        this.classList.add('seleccionado');
        this.querySelector('input[type="radio"]').checked = true;
      });
    });
  </script>
</body>
</html>
