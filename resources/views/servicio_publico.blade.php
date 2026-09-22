<!doctype html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $servicio->titulo }} · ¡SINTECZATE!</title>
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
    .tp-subtitulo { color: #ff7a18; font-weight: 600; font-size: 14px; margin-bottom: 14px; }
    .tp-publicante {
      display: inline-flex; align-items: center; gap: 6px;
      background: #f7f3ee; color: #6b5d52; padding: 6px 14px;
      border-radius: 20px; font-size: 13px; margin-bottom: 20px;
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

    .tp-estrellas i { color: #ffb347; }
    .tp-estrellas i.vacia { color: #ddd; }

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
      <img src="{{ $servicio->imagen ?? asset('img/services/plomero.jpg') }}" alt="{{ $servicio->titulo }}">

      <div class="tarjeta-preview-body">
        <div class="tp-titulo">
          {{ $servicio->titulo }}
          @if ($servicio->usuario && $servicio->usuario->verificacion_estado === 'aprobado')
            <span title="Identidad verificada" style="background:#dcfce7; color:#16a34a; padding:2px 10px; border-radius:20px; font-size:.6rem; font-weight:700; vertical-align:middle;">
              <i class="bi bi-patch-check-fill"></i> Verificado
            </span>
          @endif
        </div>

        @php $nombreCategoria = $categorias->firstWhere('id', $servicio->categoria_id)->nombre ?? null; @endphp
        @if ($nombreCategoria)
          <div class="tp-subtitulo">{{ $nombreCategoria }}</div>
        @endif

        <div class="tp-publicante">
          <i class="bi bi-person-circle"></i> Publicado por: {{ $servicio->usuario->nombre ?? 'Usuario de ¡SINTECZATE!' }}
        </div>

        <div class="tp-estrellas mb-3">
          @php $prom = round($servicio->calificaciones_avg_estrellas ?? 0); @endphp
          @for ($i = 1; $i <= 5; $i++)
            <i class="bi bi-star-fill {{ $i > $prom ? 'vacia' : '' }}"></i>
          @endfor
          <span style="color:#999; font-size:13px; margin-left:4px;">
            {{ $servicio->calificaciones_avg_estrellas ? number_format($servicio->calificaciones_avg_estrellas, 1) : 'Sin reseñas' }}
            @if ($servicio->calificaciones_count) ({{ $servicio->calificaciones_count }}) @endif
          </span>
        </div>

        <div class="tp-datos">
          @if ($servicio->ubicacion ?? null)
            <div>
              <div class="tp-dato-label">Ubicación</div>
              <div class="tp-dato-valor">{{ $servicio->ubicacion }}</div>
            </div>
          @endif
          @if ($nombreCategoria)
            <div>
              <div class="tp-dato-label">Categoría</div>
              <div class="tp-dato-valor">{{ $nombreCategoria }}</div>
            </div>
          @endif
          <div>
            <div class="tp-dato-label">Precio</div>
            <div class="tp-dato-valor">${{ number_format($servicio->precio, 2) }} MXN</div>
          </div>
        </div>

        <div class="tp-descripcion-titulo">Descripción</div>
        <div class="tp-descripcion-texto">{{ $servicio->descripcion }}</div>

        <div class="tp-acciones">
          <a href="/acceso" class="tp-btn tp-btn-principal"><i class="bi bi-send-check"></i> Solicitar este servicio</a>
          <a href="/acceso" class="tp-btn tp-btn-secundario"><i class="bi bi-whatsapp"></i> WhatsApp</a>
        </div>
        <p class="tp-aviso">Necesitas una cuenta gratuita para solicitar este servicio — toma menos de un minuto.</p>
      </div>
    </div>

  </div>

</body>
</html>
