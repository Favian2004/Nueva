<!doctype html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $vacante->titulo }} · ¡SINTECZATE!</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <link rel="icon" href="{{ asset('img/template/home.png') }}" type="image/png" sizes="192x192">

  <style>
    body {
      background-image: url('{{ asset('img/fondos/fondoSintec.png') }}');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      background-attachment: fixed;
      min-height: 100vh;
    }
    .vista-previa-wrap {
      max-width: 720px;
      margin: 40px auto;
      padding: 0 16px 60px;
    }
    .volver-link {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: linear-gradient(45deg, #ff7a18, #ffb347);
      color: #fff;
      font-weight: 700;
      text-decoration: none;
      margin-bottom: 18px;
      font-size: 14px;
      padding: 10px 20px;
      border-radius: 999px;
      box-shadow: 0 4px 14px rgba(255,122,24,.30);
      transition: 0.2s;
    }
    .volver-link:hover {
      color: #fff;
      opacity: .92;
      transform: translateY(-1px);
    }

    .tarjeta-preview {
      background: #fff;
      border-radius: 18px;
      box-shadow: 0 8px 30px rgba(0,0,0,.08);
      overflow: hidden;
    }
    .tarjeta-preview img {
      width: 100%;
      height: 280px;
      object-fit: cover;
    }
    .tarjeta-preview-body { padding: 28px 30px; }

    .tp-titulo { font-size: 26px; font-weight: 800; color: #1a1a2e; margin-bottom: 4px; }
    .tp-publicante {
      display: inline-flex; align-items: center; gap: 6px;
      background: #fff3e6; color: #c96410; padding: 6px 14px;
      border-radius: 20px; font-size: 13px; margin: 10px 0 20px;
    }

    .tp-datos {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 16px;
      margin-bottom: 22px;
    }
    .tp-dato-label {
      font-size: 11px; text-transform: uppercase; letter-spacing: .5px;
      color: #999; font-weight: 700; margin-bottom: 3px;
    }
    .tp-dato-valor { font-size: 14.5px; color: #333; }

    .tp-descripcion-titulo { font-weight: 700; color: #1a1a2e; margin-bottom: 6px; font-size: 15px; }
    .tp-descripcion-texto { color: #555; line-height: 1.7; font-size: 14.5px; }

    .tp-beneficios span {
      display: inline-flex; align-items: center; gap: 5px;
      background: #e6f4ea; color: #1e7e34; font-size: 12.5px; font-weight: 600;
      padding: 5px 12px; border-radius: 20px; margin: 0 8px 8px 0;
    }

    .tp-acciones {
      margin-top: 28px;
      padding-top: 22px;
      border-top: 1px solid #f0f0f0;
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
    }
    .tp-btn {
      flex: 1 1 200px;
      text-align: center;
      padding: 13px 20px;
      border-radius: 12px;
      font-weight: 700;
      font-size: 14.5px;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      transition: 0.2s;
    }
    .tp-btn-principal {
      background: linear-gradient(45deg, #ff7a18, #ffb347);
      color: #fff;
      box-shadow: 0 4px 14px rgba(255,122,24,.30);
    }
    .tp-btn-principal:hover { opacity: .92; color: #fff; }
    .tp-btn-secundario {
      background: #fff;
      color: #25D366;
      border: 1.5px solid #25D366;
    }
    .tp-btn-secundario:hover { background: #f0fdf4; color: #25D366; }

    .tp-aviso {
      text-align: center;
      font-size: 12.5px;
      color: #999;
      margin-top: 14px;
    }

    @media (max-width: 576px) {
      .tp-datos { grid-template-columns: 1fr; }
      .tarjeta-preview-body { padding: 22px 20px; }
    }
  </style>
</head>

<body>

  <div class="vista-previa-wrap">

    <a href="/" class="volver-link"><i class="bi bi-arrow-left"></i> Volver al inicio</a>

    <div class="tarjeta-preview">
      <img src="{{ $vacante->imagen ?? asset('img/services/plomero.jpg') }}" alt="{{ $vacante->titulo }}">

      <div class="tarjeta-preview-body">
        <div class="tp-titulo">{{ $vacante->titulo }}</div>

        <div class="tp-publicante">
          <i class="bi bi-briefcase"></i> Publicado por: {{ $vacante->publicante ?? 'Empleador de ¡SINTECZATE!' }}
        </div>

        <div class="tp-datos">
          @if ($vacante->ubicacion ?? null)
            <div>
              <div class="tp-dato-label">Ubicación</div>
              <div class="tp-dato-valor">{{ $vacante->ubicacion }}</div>
            </div>
          @endif
          @if ($vacante->salario ?? null)
            <div>
              <div class="tp-dato-label">Salario</div>
              <div class="tp-dato-valor">{{ $vacante->salario }}</div>
            </div>
          @endif
          @if ($vacante->contrato ?? null)
            <div>
              <div class="tp-dato-label">Contrato</div>
              <div class="tp-dato-valor">{{ $vacante->contrato }}</div>
            </div>
          @endif
          @if ($vacante->experiencia ?? null)
            <div>
              <div class="tp-dato-label">Experiencia</div>
              <div class="tp-dato-valor">{{ $vacante->experiencia }}</div>
            </div>
          @endif
          @if ($vacante->trabajadores_requeridos ?? null)
            <div>
              <div class="tp-dato-label">Trabajadores requeridos</div>
              <div class="tp-dato-valor">{{ $vacante->trabajadores_requeridos }}</div>
            </div>
          @endif
        </div>

        @if ($vacante->beneficios ?? null)
          <div class="tp-beneficios mb-3">
            @foreach ((array) $vacante->beneficios as $beneficio)
              <span><i class="bi bi-check-circle"></i> {{ $beneficio }}</span>
            @endforeach
          </div>
        @endif

        <div class="tp-descripcion-titulo">Descripción</div>
        <div class="tp-descripcion-texto">{{ $vacante->descripcion }}</div>

        <div class="tp-acciones">
          <a href="/acceso" class="tp-btn tp-btn-principal"><i class="bi bi-send"></i> Postularme</a>
          <a href="/acceso" class="tp-btn tp-btn-secundario"><i class="bi bi-whatsapp"></i> WhatsApp</a>
        </div>
        <p class="tp-aviso">Necesitas una cuenta gratuita para postularte — toma menos de un minuto.</p>
      </div>
    </div>

  </div>

</body>
</html>
