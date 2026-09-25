<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Anúnciate aquí · ¡SINTECZATE!</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,700;9..144,800&family=Nunito+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <link rel="icon" href="{{ asset('img/template/home.png') }}" type="image/png" sizes="192x192">

  <style>
    :root {
      --vino: #6b1021;
      --vino-oscuro: #4a0b17;
      --ambar: #ff7a18;
      --ambar-claro: #ffb347;
      --crema: #fdf6ee;
      --tinta: #1a1a2e;
    }

    body {
      background: var(--crema);
      font-family: 'Nunito Sans', sans-serif;
    }

    h1, h2, h3, .font-display {
      font-family: 'Fraunces', serif;
    }

    /* ===== HERO ===== */
    .anunciar-hero {
      background: linear-gradient(135deg, var(--vino), #b12d25);
      color: #fff;
      padding: 54px 0 66px;
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
      margin: 0 auto 16px;
      font-size: 30px;
      color: #ffcf33;
      border: 2px solid rgba(255,207,51,.4);
    }
    .anunciar-hero h1 { font-weight: 700; font-size: 36px; margin-bottom: 10px; }
    .anunciar-hero p { font-size: 16px; opacity: .92; max-width: 580px; margin: 0 auto; line-height: 1.5; }

    .costo-diario {
      background: #fff;
      border-radius: 16px;
      max-width: 700px;
      margin: -34px auto 48px;
      box-shadow: 0 10px 30px rgba(0,0,0,.10);
      padding: 22px 28px;
      text-align: center;
      position: relative;
    }
    .costo-diario i { color: var(--ambar-claro); font-size: 26px; }
    .costo-diario strong { color: var(--ambar); }

    .section-title {
      text-align: center;
      max-width: 620px;
      margin: 0 auto 32px;
    }
    .section-title h2 { font-size: 30px; font-weight: 700; color: var(--tinta); margin-bottom: 8px; letter-spacing: -0.3px; }
    .section-title p { font-size: 15px; color: #6b6b6b; line-height: 1.6; }

    /* ===== POR QUÉ ANUNCIARTE ===== */
    .porque-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 20px;
      max-width: 940px;
      margin: 0 auto 60px;
    }
    @media (max-width: 768px) { .porque-grid { grid-template-columns: 1fr; } }
    .porque-card {
      background: #fff;
      border-radius: 18px;
      padding: 32px 24px;
      border: 1px solid #f0e9df;
      text-align: center;
      transition: transform .2s, box-shadow .2s;
    }
    .porque-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 14px 30px rgba(107,16,33,.08);
    }
    .porque-card i {
      font-size: 26px;
      color: var(--ambar);
      background: linear-gradient(135deg, #fff2e5, #ffe4cc);
      width: 58px;
      height: 58px;
      border-radius: 16px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 18px;
    }
    .porque-card h6 { font-family: 'Fraunces', serif; font-weight: 700; color: var(--tinta); font-size: 19px; margin-bottom: 10px; line-height: 1.3; }
    .porque-card p { font-size: 14.5px; color: #6b6b6b; line-height: 1.65; margin: 0; }
    .porque-intro {
      max-width: 700px;
      margin: 0 auto 36px;
      text-align: center;
      font-size: 18px;
      color: var(--tinta);
      line-height: 1.7;
      font-weight: 500;
    }
    .porque-intro strong { color: var(--vino); font-weight: 700; }

    /* ===== PLANES ===== */
    .planes-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 14px;
      max-width: 720px;
      margin: 0 auto 20px;
      align-items: stretch;
    }
    @media (max-width: 640px) { .planes-grid { grid-template-columns: 1fr; } }
    .plan-card {
      background: #fff;
      border-radius: 18px;
      padding: 22px 18px 20px;
      border: 2px solid #f0e9df;
      display: flex;
      flex-direction: column;
    }
    .plan-card.destacado {
      border-color: var(--ambar);
      box-shadow: 0 12px 28px rgba(255,122,24,.14);
      position: relative;
    }
    .plan-card .badge-ahorro {
      position: absolute;
      top: -12px;
      left: 50%;
      transform: translateX(-50%);
      background: linear-gradient(45deg, var(--ambar), var(--ambar-claro));
      color: #fff;
      font-size: 11px;
      font-weight: 700;
      padding: 4px 14px;
      border-radius: 20px;
      white-space: nowrap;
    }
    .plan-card h5 { font-weight: 700; color: var(--tinta); margin-bottom: 2px; font-size: 17px; }
    .plan-card .precio { font-size: 30px; font-weight: 800; color: var(--vino); margin-top: 6px; }
    .plan-card .precio small { font-size: 13px; font-weight: 600; color: #999; }
    .plan-card .por-dia { font-size: 12px; color: #999; margin-bottom: 16px; }
    .plan-card ul { list-style: none; padding: 0; margin: 0 0 18px; flex-grow: 1; }
    .plan-card ul li {
      font-size: 13.5px;
      font-weight: 600;
      color: #444;
      display: flex;
      gap: 8px;
      align-items: flex-start;
      margin-bottom: 10px;
      line-height: 1.4;
    }
    .plan-card ul li i { color: var(--ambar); margin-top: 2px; flex-shrink: 0; }
    .plan-card ul li.no-incluido { color: #b5b5b5; }
    .plan-card ul li.no-incluido i { color: #d8d8d8; }

    /* ===== BENEFICIOS (qué incluye) ===== */
    .beneficios-wrap { max-width: 900px; margin: 0 auto 56px; }
    .beneficios-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 16px;
    }
    @media (max-width: 768px) { .beneficios-grid { grid-template-columns: 1fr; } }
    .beneficio-card {
      background: var(--tinta);
      color: #fff;
      border-radius: 16px;
      padding: 24px 20px;
    }
    .beneficio-card .num {
      font-family: 'Fraunces', serif;
      font-size: 26px;
      color: var(--ambar-claro);
      margin-bottom: 10px;
      display: block;
    }
    .beneficio-card h6 { font-weight: 700; font-size: 14.5px; margin-bottom: 8px; }
    .beneficio-card p { font-size: 12.5px; opacity: .85; line-height: 1.6; margin: 0; }

    /* ===== COMPARACIÓN EN TARJETAS ===== */
    .comparacion-wrap { max-width: 900px; margin: 0 auto 56px; }
    .comparacion-grid {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 12px;
    }
    @media (max-width: 768px) { .comparacion-grid { grid-template-columns: 1fr 1fr; } }
    .comparacion-card {
      background: #fff;
      border: 1px solid #f0e9df;
      border-radius: 14px;
      padding: 18px 14px;
      text-align: center;
    }
    .comparacion-card.ganador {
      background: var(--vino);
      border-color: var(--vino);
      color: #fff;
    }
    .comparacion-card .alt-nombre { font-size: 12.5px; font-weight: 700; margin-bottom: 10px; min-height: 32px; }
    .comparacion-card .alt-costo { font-size: 19px; font-weight: 800; }
    .comparacion-card.ganador .alt-costo { color: #ffcf33; }
    .comparacion-card .alt-nota { font-size: 10.5px; opacity: .7; margin-top: 4px; }

    /* ===== FORMULARIO ===== */
    .form-anunciar {
      max-width: 720px;
      margin: 0 auto 60px;
      background: #fff;
      border-radius: 18px;
      box-shadow: 0 4px 24px rgba(0,0,0,.06);
      padding: 32px 30px;
    }
    .form-anunciar h5 { font-weight: 700; color: var(--tinta); margin-bottom: 4px; }
    .form-anunciar .subtitulo { font-size: 13px; color: #888; margin-bottom: 22px; }

    .form-anunciar .form-label {
      color: #333 !important;
      font-weight: 700 !important;
      font-size: 14px !important;
      display: block !important;
      margin-bottom: 4px !important;
    }
    .form-anunciar .campo-ayuda {
      font-size: 12px;
      color: #888;
      display: block;
      margin-top: 4px;
      line-height: 1.5;
    }

    .plan-card.seleccionado {
      box-shadow: 0 0 0 3px rgba(107,16,33,.18);
    }
    .btn-elegir-plan {
      margin-top: auto;
      width: 100%;
      background: #fff;
      border: 2px solid var(--ambar);
      color: var(--ambar);
      font-weight: 700;
      font-size: 13px;
      padding: 9px;
      border-radius: 10px;
      cursor: pointer;
      transition: .15s;
    }
    .btn-elegir-plan:hover { background: #fff7ef; }
    .btn-elegir-plan.elegido {
      background: var(--vino);
      border-color: var(--vino);
      color: #fff;
    }

    .plan-elegido-box {
      background: #fff7ef;
      border: 1.5px solid #ffd8ae;
      border-radius: 12px;
      padding: 12px 16px;
    }
    .plan-elegido-resumen {
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-size: 14px;
      color: var(--tinta);
    }
    .plan-elegido-resumen strong { color: var(--vino); }
    .plan-elegido-cambiar {
      font-size: 12.5px;
      font-weight: 700;
      color: var(--ambar);
      text-decoration: none;
    }
    .plan-elegido-cambiar:hover { text-decoration: underline; }

    .btn-enviar-anuncio {
      background: linear-gradient(45deg, var(--ambar), var(--ambar-claro));
      color: #fff;
      border: none;
      padding: 13px;
      border-radius: 10px;
      font-weight: 700;
      width: 100%;
      font-size: 15px;
    }

    .back-button {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: #fff;
      border: 1.5px solid #ffd8ae;
      color: var(--ambar);
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
      background: var(--ambar);
      border-color: var(--ambar);
      color: #fff;
      transform: translateX(-3px);
      box-shadow: 0 4px 12px rgba(255,122,24,0.25);
    }

    /* ===== VISTA PREVIA "AL DARLE CLICK" ===== */
    .preview-wrap {
      background: #f4f0eb;
      border-radius: 16px;
      padding: 22px;
    }
    .preview-tabs {
      display: flex;
      gap: 6px;
      margin-bottom: 16px;
      justify-content: center;
    }
    .preview-tabs button {
      border: none;
      background: #fff;
      color: #888;
      font-size: 12px;
      font-weight: 700;
      padding: 6px 14px;
      border-radius: 20px;
      cursor: default;
    }
    .preview-tabs button.activo {
      background: var(--vino);
      color: #fff;
    }
    .preview-columnas {
      display: flex;
      gap: 24px;
      align-items: center;
      justify-content: center;
      flex-wrap: wrap;
    }
    .preview-mini { text-align: center; }
    .preview-mini-label { font-size: 11px; color: #999; margin-bottom: 6px; font-weight: 700; }
    #previewAdBox {
      width: 190px;
      height: 171px;
      border-radius: 12px;
      overflow: hidden;
      background: #e5e0d8;
      box-shadow: 0 4px 15px rgba(0,0,0,.12);
      position: relative;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: transform .15s;
    }
    #previewAdBox:hover { transform: scale(1.03); }
    #previewAdBox img { display: none; width: 100%; height: 100%; object-fit: cover; }
    #previewAdBox .click-hint {
      position: absolute;
      inset: 0;
      background: rgba(0,0,0,0);
      display: flex;
      align-items: center;
      justify-content: center;
      opacity: 0;
      transition: .15s;
      color: #fff;
      font-size: 11px;
      font-weight: 700;
    }
    #previewAdBox:hover .click-hint { background: rgba(0,0,0,.35); opacity: 1; }
    #previewEslogan {
      display: none;
      background: #fff;
      border-radius: 8px;
      padding: 6px 10px;
      margin-top: 8px;
      text-align: center;
      font-size: 11.5px;
      font-weight: 700;
      color: #333;
      box-shadow: 0 2px 8px rgba(0,0,0,.08);
      max-width: 190px;
    }
    .flecha-preview {
      font-size: 22px;
      color: #c9c2b4;
    }
    @media (max-width: 576px) { .flecha-preview { transform: rotate(90deg); } }

    /* Lightbox grande */
    .lightbox-overlay {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(15,10,10,.82);
      z-index: 1050;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }
    .lightbox-overlay.activo { display: flex; }
    .lightbox-card {
      background: #fff;
      border-radius: 18px;
      max-width: 460px;
      width: 100%;
      overflow: hidden;
      text-align: center;
    }
    .lightbox-img-box {
      width: 100%;
      aspect-ratio: 1 / 1;
      background: #e5e0d8;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
    }
    .lightbox-img-box img { display: none; width: 100%; height: 100%; object-fit: cover; }
    .lightbox-body { padding: 18px 20px 22px; }
    .lightbox-body h6 { font-weight: 800; color: var(--tinta); margin-bottom: 4px; font-size: 15px; }
    .lightbox-body p { font-size: 12.5px; color: #777; margin-bottom: 14px; }
    .lightbox-btn-link {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: var(--vino);
      color: #fff;
      border: none;
      padding: 9px 18px;
      border-radius: 10px;
      font-size: 12.5px;
      font-weight: 700;
      text-decoration: none;
    }
    .lightbox-btn-ubicacion {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: #fff;
      color: var(--vino);
      border: 1.5px solid var(--vino);
      padding: 9px 18px;
      border-radius: 10px;
      font-size: 12.5px;
      font-weight: 700;
      text-decoration: none;
      margin-left: 8px;
    }
    .lightbox-cerrar {
      position: absolute;
      top: 14px;
      right: 18px;
      color: #fff;
      font-size: 26px;
      cursor: pointer;
      line-height: 1;
    }
    .preview-placeholder-txt {
      color: #999;
      font-size: 12px;
      text-align: center;
      padding: 0 14px;
    }

    /* ===== MAPA DE UBICACIÓN ===== */
    #mapaUbicacion {
      width: 100%;
      height: 280px;
      border-radius: 12px;
      z-index: 1;
    }
    .ubicacion-box {
      background: #f9f7f4;
      border: 1px solid #eee0cf;
      border-radius: 14px;
      padding: 16px;
    }
    .ubicacion-coords {
      font-size: 12.5px;
      color: #666;
      margin-top: 8px;
      display: flex;
      align-items: center;
      gap: 8px;
      flex-wrap: wrap;
    }
    .ubicacion-coords.marcada { color: #16a34a; font-weight: 700; }
    .btn-mi-ubicacion {
      background: #fff;
      border: 1.5px solid var(--ambar);
      color: var(--ambar);
      font-weight: 700;
      font-size: 12px;
      padding: 6px 14px;
      border-radius: 8px;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }
    .btn-mi-ubicacion:hover { background: #fff7ef; }
    .btn-quitar-ubicacion {
      background: none;
      border: none;
      color: #b91c1c;
      font-size: 12px;
      font-weight: 700;
      cursor: pointer;
      text-decoration: underline;
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
    <h1>¡Dale publicidad a tu negocio!</h1>
    <p>Anúnciate en ¡SINTECZATE! y llega a toda la gente de tu municipio que ya está buscando servicios y negocios como el tuyo.</p>
  </div>

  <div class="container">

    <!-- COSTO POR DÍA -->
    <div class="costo-diario">
      <i class="bi bi-lightbulb-fill"></i>
      <p class="mb-0 mt-2" style="font-size:15px;">
        Por menos de <strong>$2 pesos al día</strong>, tu negocio aparece en la página que ve toda la gente de tu municipio.
      </p>
    </div>

    <!-- MENSAJE DE ÉXITO O ERROR -->
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

    <!-- POR QUÉ ANUNCIARTE -->
    <div class="section-title">
      <h2>¿Por qué anunciarte aquí?</h2>
    </div>
    <p class="porque-intro">
      ¡SINTECZATE! es la <strong>bolsa de trabajo y directorio de servicios de Zacapoaxtla</strong>: la gente entra a buscar empleo, a quién contratar para un trabajo, o qué negocios hay cerca. Tu anuncio queda justo frente a esas personas.
    </p>
    <div class="porque-grid">
      <div class="porque-card">
        <i class="bi bi-people-fill"></i>
        <h6>Gente que ya está buscando</h6>
        <p>No es un volante que se tira. Tu anuncio lo ve alguien que ya entró al sitio a buscar trabajo o servicios en tu municipio.</p>
      </div>
      <div class="porque-card">
        <i class="bi bi-graph-up-arrow"></i>
        <h6>Se paga solo, con un cliente</h6>
        <p>Con lo que te compre UN cliente nuevo que llegue por ver tu anuncio, ya recuperaste lo que pagaste ese mes. Todo lo que venda después de eso, es ganancia extra.</p>
      </div>
      <div class="porque-card">
        <i class="bi bi-clock-history"></i>
        <h6>Anuncia tu negocio, sin contratar a nadie</h6>
        <p>Es como tener a una persona parada anunciando tu negocio todo el día, todos los días — pero sin sueldo, sin que le des indicaciones, y sin que se canse. Tu anuncio hace ese trabajo solo.</p>
      </div>
    </div>

    <!-- COMPARACIÓN -->
    <div class="comparacion-wrap">
      <div class="section-title">
        <h2>Comparado con otras formas de anunciarte</h2>
        <p>Un mes de anuncio aquí cuesta menos que una sola tanda de volantes, y se ve todos los días.</p>
      </div>
      <div class="comparacion-grid">
        <div class="comparacion-card">
          <div class="alt-nombre">Volantes impresos<br>(500 pzas)</div>
          <div class="alt-costo">$400–800</div>
          <div class="alt-nota">Se acaban, se tiran</div>
        </div>
        <div class="comparacion-card">
          <div class="alt-nombre">Radio local<br>(1 mes)</div>
          <div class="alt-costo">$1,500+</div>
          <div class="alt-nota">Solo mientras suena</div>
        </div>
        <div class="comparacion-card">
          <div class="alt-nombre">Espectacular<br>/ lona</div>
          <div class="alt-costo">$2,000+</div>
          <div class="alt-nota">Un solo lugar fijo</div>
        </div>
        <div class="comparacion-card ganador">
          <div class="alt-nombre">¡SINTECZATE!<br>(1 mes)</div>
          <div class="alt-costo">$49</div>
          <div class="alt-nota">Visible cada día, en todo el sitio</div>
        </div>
      </div>
    </div>

    <!-- CÓMO SE PAGA -->
    <div class="deposito-box" style="max-width:900px; margin:0 auto 56px; background:var(--tinta); color:#fff; border-radius:16px; padding:24px 28px;">
      <h5 style="font-weight:700; margin-bottom:14px;"><i class="bi bi-credit-card"></i> Pago seguro con Mercado Pago</h5>
      <p class="mb-0" style="font-size:13px; opacity:.9;">
        Llena el formulario de abajo, elige tu plan, y al enviarlo te llevamos directo a Mercado Pago para completar tu pago con tarjeta.
      </p>
      <div style="margin-top:16px; padding-top:14px; border-top:1px solid rgba(255,255,255,.12);">
        <p class="mb-2" style="font-size:13px; font-weight:700; color:#ffcf33;">
          <i class="bi bi-check-circle-fill"></i> Puedes pagar con:
        </p>
        <div style="display:flex; flex-wrap:wrap; gap:8px;">
          @foreach (['Tarjeta de débito','Tarjeta de crédito'] as $formaPago)
            <span style="background:rgba(255,255,255,.12); padding:4px 12px; border-radius:20px; font-size:12px;">{{ $formaPago }}</span>
          @endforeach
        </div>
        <p class="mb-0 mt-2" style="font-size:11.5px; opacity:.75;">
          En cuanto Mercado Pago confirme tu pago (al instante), tu solicitud queda lista para su revisión final — usualmente en menos de 24 horas.
        </p>
      </div>
    </div>

    <!-- PLANES (justo antes del formulario) -->
    <div class="section-title">
      <h2>Elige tu plan</h2>
      <p>Los tres incluyen tu imagen en la columna de negocios destacados. Mensual y Anual además muestran un eslogan corto debajo de tu foto.</p>
    </div>
    <div class="planes-grid" id="planesGrid">
      <div class="plan-card" data-plan="basico">
        <h5>Básico</h5>
        <div class="precio">$29 <small>/15 días</small></div>
        <div class="por-dia">≈ $1.93 al día</div>
        <ul>
          <li><i class="bi bi-check-circle-fill"></i> Tu imagen en negocios destacados</li>
          <li><i class="bi bi-check-circle-fill"></i> Se ve en todas las páginas del sitio</li>
          <li class="no-incluido"><i class="bi bi-x-circle"></i> Sin eslogan debajo de la imagen</li>
        </ul>
        <button type="button" class="btn-elegir-plan" data-plan="basico" onclick="selectPlan('basico')">Elegir este plan</button>
      </div>
      <div class="plan-card destacado seleccionado" data-plan="mensual">
        <span class="badge-ahorro">EL MÁS ELEGIDO</span>
        <h5>Mensual</h5>
        <div class="precio">$49 <small>/mes</small></div>
        <div class="por-dia">≈ $1.63 al día</div>
        <ul>
          <li><i class="bi bi-check-circle-fill"></i> Tu imagen en negocios destacados</li>
          <li><i class="bi bi-check-circle-fill"></i> Se ve en todas las páginas del sitio</li>
          <li><i class="bi bi-check-circle-fill"></i> Eslogan debajo de tu imagen</li>
          <li><i class="bi bi-check-circle-fill"></i> Link a tu página o red social</li>
          <li><i class="bi bi-check-circle-fill"></i> Ubicación con mapa (cómo llegar)</li>
        </ul>
        <button type="button" class="btn-elegir-plan elegido" data-plan="mensual" onclick="selectPlan('mensual')">✓ Plan elegido</button>
      </div>
      <div class="plan-card" data-plan="anual">
        <h5>Anual</h5>
        <div class="precio">$490 <small>/año</small></div>
        <div class="por-dia">≈ $1.34 al día · 2 meses gratis</div>
        <ul>
          <li><i class="bi bi-check-circle-fill"></i> Tu imagen en negocios destacados</li>
          <li><i class="bi bi-check-circle-fill"></i> Se ve en todas las páginas del sitio</li>
          <li><i class="bi bi-check-circle-fill"></i> Eslogan debajo de tu imagen</li>
          <li><i class="bi bi-check-circle-fill"></i> Link a tu página o red social</li>
          <li><i class="bi bi-check-circle-fill"></i> Ubicación con mapa (cómo llegar)</li>
          <li><i class="bi bi-check-circle-fill"></i> Todo lo del plan Mensual, un año completo</li>
        </ul>
        <button type="button" class="btn-elegir-plan" data-plan="anual" onclick="selectPlan('anual')">Elegir este plan</button>
      </div>
    </div>

    <!-- FORMULARIO -->
    <div class="form-anunciar">
      <h5><i class="bi bi-pencil-square"></i> Datos de tu negocio</h5>
      <p class="subtitulo">Te pedimos estos datos para armar tu anuncio y para poder contactarte si algo necesita ajustarse antes de publicarlo.</p>

      <form action="{{ route('anunciar.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
          <label class="form-label">Nombre del negocio <span class="text-danger">*</span></label>
          <input type="text" name="nombre_negocio" class="form-control" value="{{ old('nombre_negocio') }}" placeholder="Ej. Taquería Doña Lupe" required>
          <span class="campo-ayuda">Así aparecerá tu negocio en el sitio.</span>
        </div>

        <div class="mb-3">
          <label class="form-label">Nombre de quien atiende / encargado <span class="text-danger">*</span></label>
          <input type="text" name="nombre_encargado" class="form-control" value="{{ old('nombre_encargado') }}" placeholder="Ej. María López" required>
          <span class="campo-ayuda">Para saber con quién coordinar si necesitamos contactarte sobre tu anuncio.</span>
        </div>

        <div class="mb-3">
          <label class="form-label">Describe tu negocio <span class="text-danger">*</span></label>
          <textarea name="descripcion" class="form-control" rows="3" placeholder="¿Qué ofreces? ¿Qué te hace diferente?" required>{{ old('descripcion') }}</textarea>
          <span class="campo-ayuda">Este texto es solo para nuestro equipo; no se muestra en el anuncio público.</span>
        </div>

        <div class="mb-3">
          <label class="form-label">Dirección <span class="text-danger">*</span></label>
          <input type="text" name="direccion" class="form-control" value="{{ old('direccion') }}" placeholder="Ej. Calle Morelos #12, Col. Centro" required>
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
          <span class="campo-ayuda">Te escribimos aquí en cuanto tu anuncio quede activo.</span>
        </div>

        <div class="mb-3" id="campoLinkExterno">
          <label class="form-label">Link a tu página o red social (opcional)</label>
          <input type="url" name="link_externo" class="form-control" value="{{ old('link_externo') }}" placeholder="https://facebook.com/tu-negocio">
          <span class="campo-ayuda">Si lo agregas, tu anuncio tendrá un botón que lleva directo ahí — mira la vista previa más abajo.</span>
        </div>

        <!-- UBICACIÓN (solo Mensual y Anual) -->
        <div class="mb-3" id="campoUbicacion">
          <label class="form-label">Ubicación de tu negocio (opcional)</label>
          <span class="campo-ayuda" style="margin-bottom:8px;">Así la gente puede llegar directo a tu negocio con un botón de "Cómo llegar". Si no la agregas, tu anuncio simplemente no tendrá ese botón.</span>

          <div class="ubicacion-box">
            <ol style="font-size:12.5px; color:#555; padding-left:18px; margin-bottom:12px; line-height:1.8;">
              <li>Dale clic al botón de abajo — se abre Google Maps en otra pestaña.</li>
              <li>Busca tu negocio (si ya aparece ahí) o tu dirección, en el buscador de Google Maps.</li>
              <li>Con el pin correcto seleccionado, dale clic a <strong>"Compartir"</strong> y luego <strong>"Copiar enlace"</strong>.</li>
              <li>Regresa aquí y pega el enlace en el campo de abajo.</li>
            </ol>

            <a href="https://www.google.com/maps" target="_blank" class="btn-mi-ubicacion" style="text-decoration:none; margin-bottom:12px;">
              <i class="bi bi-google"></i> Buscar mi negocio en Google Maps
            </a>

            <input type="url" name="link_ubicacion" id="inputLinkUbicacion" class="form-control" value="{{ old('link_ubicacion') }}" placeholder="Pega aquí el enlace que copiaste de Google Maps">

            <div class="ubicacion-coords" id="ubicacionCoordsTexto">
              <i class="bi bi-info-circle"></i> Aún no has pegado ningún enlace.
            </div>
          </div>
        </div>

        <div class="mb-3 plan-elegido-box" id="planElegidoBox">
          <label class="form-label mb-2">Tu plan</label>
          <div class="plan-elegido-resumen">
            <div><strong id="planElegidoNombre">Mensual</strong> · <span id="planElegidoPrecio">$49</span></div>
            <a href="#planesGrid" class="plan-elegido-cambiar">Cambiar plan</a>
          </div>
          <input type="hidden" name="plan" id="planSeleccionadoInput" value="mensual">
        </div>

        <div class="mb-3" id="campoEslogan">
          <label class="form-label">Eslogan (aparece debajo de tu imagen) <span class="text-danger">*</span></label>
          <input type="text" name="eslogan" class="form-control" value="{{ old('eslogan') }}" placeholder="Ej. Los mejores tacos de la región" maxlength="150">
          <span class="campo-ayuda">Una frase corta que resuma tu negocio. Se ve reflejada abajo, en la vista previa.</span>
        </div>

        <div class="mb-4">
          <label class="form-label" id="labelImagen">Sube la imagen de tu anuncio <span class="text-danger">*</span></label>
          <input type="file" name="imagen_negocio" id="campoImagen" class="form-control" accept="image/*" required>
          <span class="campo-ayuda" id="ayudaImagen">Esta imagen es obligatoria en los tres planes; es lo que se muestra en tu anuncio.</span>
          <span class="campo-ayuda"><i class="bi bi-aspect-ratio"></i> Tamaño de imagen: entre 400x360 px y 1200x1200 px, y máximo 4 MB de peso.</span>
          <span class="campo-ayuda">Si tu imagen no cumple con esto, puedes recortarla o comprimirla con cualquier editor de imágenes de tu computadora o celular, y subirla de nuevo aquí.</span>
          <div id="avisoTamano" class="mt-2" style="display:none; background:#fee2e2; color:#b91c1c; border-radius:8px; padding:8px 12px; font-size:12.5px;">
            <i class="bi bi-exclamation-triangle-fill"></i> <span id="avisoTamanoTexto"></span>
          </div>
        </div>

        <!-- VISTA PREVIA -->
        <div class="mb-4">
          <label class="form-label"><i class="bi bi-eye-fill"></i> Así se vería tu anuncio</label>
          <span class="campo-ayuda" style="margin-bottom:12px;">A la izquierda, como se ve entre los demás negocios. A la derecha, lo que la gente ve al tocarlo. Haz clic en la imagen para probarlo.</span>

          <div class="preview-wrap">
            <div class="preview-tabs">
              <button type="button" class="activo" id="tabPlanActivo">Plan: Mensual</button>
            </div>
            <div class="preview-columnas">
              <div class="preview-mini">
                <div class="preview-mini-label">EN LA COLUMNA DE ANUNCIOS</div>
                <div id="previewAdBox">
                  <img id="previewImg" alt="Vista previa">
                  <span id="previewPlaceholder" class="preview-placeholder-txt">
                    <i class="bi bi-image" style="font-size:26px; display:block; margin-bottom:6px;"></i>
                    Sube una imagen para ver la vista previa
                  </span>
                  <div class="click-hint"><i class="bi bi-arrows-fullscreen me-1"></i> Ver en grande</div>
                </div>
                <div id="previewEslogan"></div>
              </div>
              <i class="bi bi-arrow-right flecha-preview"></i>
              <div class="preview-mini">
                <div class="preview-mini-label">AL DARLE CLIC</div>
                <div style="width:130px; height:130px; border-radius:12px; background:#fff; border:2px dashed #d8d0c2; display:flex; align-items:center; justify-content:center; font-size:11px; color:#aaa; text-align:center; padding:8px;">
                  Se abre en grande, con eslogan y botón a tu link (según tu plan)
                </div>
              </div>
            </div>
          </div>
        </div>

        <button type="submit" class="btn-enviar-anuncio" id="btnPagar">
          <i class="bi bi-credit-card-fill"></i> Pagar con Mercado Pago
        </button>
      </form>
    </div>

  </div>

  <!-- LIGHTBOX: cómo se ve al abrir en grande -->
  <div class="lightbox-overlay" id="lightboxOverlay">
    <span class="lightbox-cerrar" id="lightboxCerrar">&times;</span>
    <div class="lightbox-card">
      <div class="lightbox-img-box">
        <img id="lightboxImg" alt="Anuncio en grande">
      </div>
      <div class="lightbox-body">
        <h6 id="lightboxNombre">Tu negocio</h6>
        <p id="lightboxEslogan">Tu eslogan aparecería aquí</p>
        <a href="#" id="lightboxBoton" class="lightbox-btn-link" onclick="return false;">
          <i class="bi bi-box-arrow-up-right"></i> Visitar página
        </a>
        <a href="#" id="lightboxBotonUbicacion" class="lightbox-btn-ubicacion" style="display:none;" target="_blank">
          <i class="bi bi-geo-alt-fill"></i> Cómo llegar
        </a>
      </div>
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
    const campoEslogan = document.getElementById('campoEslogan');
    const campoLinkExterno = document.getElementById('campoLinkExterno');
    const campoUbicacion = document.getElementById('campoUbicacion');
    const campoImagen = document.getElementById('campoImagen');
    const labelImagen = document.getElementById('labelImagen');
    const ayudaImagen = document.getElementById('ayudaImagen');
    const avisoTamano = document.getElementById('avisoTamano');
    const avisoTamanoTexto = document.getElementById('avisoTamanoTexto');
    const previewImg = document.getElementById('previewImg');
    const previewPlaceholder = document.getElementById('previewPlaceholder');
    const previewEslogan = document.getElementById('previewEslogan');
    const previewAdBox = document.getElementById('previewAdBox');
    const tabPlanActivo = document.getElementById('tabPlanActivo');
    const btnPagar = document.getElementById('btnPagar');
    const formAnunciar = document.querySelector('form[action="{{ route('anunciar.store') }}"]');
    const nombreNegocioInput = document.querySelector('input[name="nombre_negocio"]');
    const linkExternoInput = document.querySelector('input[name="link_externo"]');
    const inputLinkUbicacion = document.getElementById('inputLinkUbicacion');
    const ubicacionCoordsTexto = document.getElementById('ubicacionCoordsTexto');

    const ANCHO_MIN = 400, ALTO_MIN = 360, ANCHO_MAX = 1200, ALTO_MAX = 1200;
    const PESO_MAX_MB = 4;
    const PESO_MAX_BYTES = PESO_MAX_MB * 1024 * 1024;
    let imagenValida = true;
    let planActual = 'mensual';
    let imagenCargada = false;

    const nombresPlan = { basico: 'Básico', mensual: 'Mensual', anual: 'Anual' };

    function actualizarCamposPorPlan(plan) {
      planActual = plan;
      tabPlanActivo.textContent = 'Plan: ' + nombresPlan[plan];

      // La imagen es obligatoria en los tres planes.
      campoImagen.required = true;

      if (plan === 'basico') {
        campoEslogan.style.display = 'none';
        campoEslogan.querySelector('input').required = false;
        previewEslogan.style.display = 'none';

        // El plan Básico no incluye link a página/red social ni mapa de ubicación.
        campoLinkExterno.style.display = 'none';
        campoUbicacion.style.display = 'none';
      } else {
        campoEslogan.style.display = '';
        campoEslogan.querySelector('input').required = true;
        actualizarPreviewEslogan();

        campoLinkExterno.style.display = '';
        campoUbicacion.style.display = '';
      }
    }

    const preciosPlan = { basico: '$29 · 15 días', mensual: '$49 / mes', anual: '$490 / año' };

    function selectPlan(plan, scrollToForm = true) {
      actualizarCamposPorPlan(plan);

      document.getElementById('planSeleccionadoInput').value = plan;
      document.getElementById('planElegidoNombre').textContent = nombresPlan[plan];
      document.getElementById('planElegidoPrecio').textContent = preciosPlan[plan];

      document.querySelectorAll('.plan-card').forEach(card => {
        card.classList.toggle('seleccionado', card.dataset.plan === plan);
      });
      document.querySelectorAll('.btn-elegir-plan').forEach(btn => {
        const esEste = btn.dataset.plan === plan;
        btn.textContent = esEste ? '✓ Plan elegido' : 'Elegir este plan';
        btn.classList.toggle('elegido', esEste);
      });

      if (scrollToForm) {
        document.querySelector('.form-anunciar').scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    }

    function actualizarPreviewEslogan() {
      const texto = campoEslogan.querySelector('input').value.trim();
      if (planActual !== 'basico' && texto) {
        previewEslogan.textContent = texto;
        previewEslogan.style.display = 'block';
      } else {
        previewEslogan.style.display = 'none';
      }
    }
    campoEslogan.querySelector('input').addEventListener('input', actualizarPreviewEslogan);

    campoImagen.addEventListener('change', function () {
      const file = this.files[0];

      if (!file) {
        previewImg.style.display = 'none';
        previewPlaceholder.style.display = 'block';
        avisoTamano.style.display = 'none';
        imagenValida = true;
        imagenCargada = false;
        return;
      }

      if (file.size > PESO_MAX_BYTES) {
        imagenValida = false;
        imagenCargada = false;
        previewImg.style.display = 'none';
        previewPlaceholder.style.display = 'block';
        avisoTamanoTexto.textContent = `Tu imagen pesa ${(file.size / (1024 * 1024)).toFixed(1)} MB — el máximo permitido es ${PESO_MAX_MB} MB. Comprime la imagen o elige otra.`;
        avisoTamano.style.display = 'block';
        campoImagen.value = '';
        return;
      }

      const lector = new FileReader();
      lector.onload = function (e) {
        const img = new Image();
        img.onload = function () {
          previewImg.src = e.target.result;
          previewImg.style.display = 'block';
          previewPlaceholder.style.display = 'none';
          imagenCargada = true;

          if (img.width < ANCHO_MIN || img.height < ALTO_MIN) {
            imagenValida = false;
            avisoTamanoTexto.textContent = `Tu imagen es de ${img.width} x ${img.height} px — necesitas al menos ${ANCHO_MIN} x ${ALTO_MIN} px para que se vea nítida. Sube una imagen más grande.`;
            avisoTamano.style.display = 'block';
          } else if (img.width > ANCHO_MAX || img.height > ALTO_MAX) {
            imagenValida = false;
            avisoTamanoTexto.textContent = `Tu imagen es de ${img.width} x ${img.height} px — el máximo permitido es ${ANCHO_MAX} x ${ALTO_MAX} px. Sube una imagen más pequeña.`;
            avisoTamano.style.display = 'block';
          } else {
            imagenValida = true;
            avisoTamano.style.display = 'none';
          }
        };
        img.src = e.target.result;
      };
      lector.readAsDataURL(file);
    });

    // ===== UBICACIÓN: extrae coordenadas de un link de Google Maps pegado =====
    // Solo confirmamos visualmente que se ve como un link de Google Maps —
    // guardamos el link TAL CUAL lo pegaron, sin intentar convertirlo.
    function actualizarAvisoUbicacion() {
      const texto = inputLinkUbicacion.value.trim();

      if (!texto) {
        ubicacionCoordsTexto.innerHTML = '<i class="bi bi-info-circle"></i> Aún no has pegado ningún enlace.';
        ubicacionCoordsTexto.classList.remove('marcada');
        return;
      }

      const pareceLinkDeGoogle = /google\.com\/maps|goo\.gl/.test(texto);
      if (pareceLinkDeGoogle) {
        ubicacionCoordsTexto.innerHTML = '<i class="bi bi-check-circle-fill"></i> Listo, este link se usará para el botón "Cómo llegar".';
        ubicacionCoordsTexto.classList.add('marcada');
      } else {
        ubicacionCoordsTexto.innerHTML = '<i class="bi bi-exclamation-triangle-fill"></i> Eso no parece un link de Google Maps. Pega el enlace que copiaste ahí.';
        ubicacionCoordsTexto.classList.remove('marcada');
      }
    }
    inputLinkUbicacion.addEventListener('input', actualizarAvisoUbicacion);
    actualizarAvisoUbicacion(); // Por si ya había un valor guardado (old()).

    // ===== Lightbox: cómo se ve al darle clic, según el plan =====
    const lightboxOverlay = document.getElementById('lightboxOverlay');
    const lightboxImg = document.getElementById('lightboxImg');
    const lightboxNombre = document.getElementById('lightboxNombre');
    const lightboxEslogan = document.getElementById('lightboxEslogan');
    const lightboxBoton = document.getElementById('lightboxBoton');
    const lightboxBotonUbicacion = document.getElementById('lightboxBotonUbicacion');

    previewAdBox.addEventListener('click', function () {
      if (!imagenCargada) {
        alert('Primero sube una imagen para ver cómo se vería en grande.');
        return;
      }
      lightboxImg.src = previewImg.src;
      lightboxImg.style.display = 'block';
      lightboxNombre.textContent = nombreNegocioInput.value.trim() || 'Tu negocio';

      if (planActual === 'basico') {
        lightboxEslogan.style.display = 'none';
      } else {
        const texto = campoEslogan.querySelector('input').value.trim();
        lightboxEslogan.textContent = texto || 'Tu eslogan aparecería aquí';
        lightboxEslogan.style.display = 'block';
      }

      // El plan Básico no incluye link externo ni mapa de ubicación, así
      // que nunca mostramos esos botones en la vista previa aunque los
      // campos tuvieran un valor guardado de un cambio de plan anterior.
      const link = (planActual !== 'basico') ? linkExternoInput.value.trim() : '';
      if (link) {
        lightboxBoton.style.display = 'inline-flex';
        lightboxBoton.href = link;
        lightboxBoton.onclick = null;
        lightboxBoton.target = '_blank';
      } else {
        lightboxBoton.style.display = 'none';
      }

      const tieneUbicacion = (planActual !== 'basico') && inputLinkUbicacion.value.trim();
      if (tieneUbicacion) {
        lightboxBotonUbicacion.style.display = 'inline-flex';
        lightboxBotonUbicacion.href = inputLinkUbicacion.value.trim();
      } else {
        lightboxBotonUbicacion.style.display = 'none';
      }

      lightboxOverlay.classList.add('activo');
    });

    document.getElementById('lightboxCerrar').addEventListener('click', () => lightboxOverlay.classList.remove('activo'));
    lightboxOverlay.addEventListener('click', function (e) {
      if (e.target === lightboxOverlay) lightboxOverlay.classList.remove('activo');
    });

    if (formAnunciar) {
      formAnunciar.addEventListener('submit', function (e) {
        if (!imagenValida) {
          e.preventDefault();
          avisoTamano.style.display = 'block';
          avisoTamano.scrollIntoView({ behavior: 'smooth', block: 'center' });
          alert('❌ El tamaño de tu imagen no es válido. Debe estar entre ' + ANCHO_MIN + 'x' + ALTO_MIN + ' px y ' + ANCHO_MAX + 'x' + ALTO_MAX + ' px antes de continuar al pago.');
        }
      });
    }

    actualizarCamposPorPlan('mensual');
  </script>
</body>
</html>
