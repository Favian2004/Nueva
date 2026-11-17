<!DOCTYPE html>
<html lang="es" class="has-aside-left has-aside-mobile-transition has-navbar-fixed-top has-aside-expanded">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Dashboard · Inicio</title>

  <link rel="stylesheet" href="{{ asset('assets/usuario/css/main.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/usuario/css/styles.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/usuario/css/switch.css') }}">
  <link rel="dns-prefetch" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.9.95/css/materialdesignicons.min.css">
  <!-- ===== FAVICON CON IMAGEN PNG PERSONALIZADA ===== -->
  <link rel="icon" type="img/" href="{{ asset('assets/usuario/img/icono.png') }}">

  <link rel="stylesheet" href="{{ asset('assets/usuario/css/ads-widget.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/usuario/css/theme-conectaya.css') }}">
  <link rel="stylesheet" href="{{ asset('css/anuncio-negocio.css') }}">

  <style>
    /* ===== LOGO (barra de título) - solo en esta página.
   En computadora se queda como lo dejaste (200x170, se ve bien).
   En celular se reduce a la mitad, guardando la misma proporción
   (200:170), para que no se vea enorme ni se encime con el título. ===== */
@media (min-width: 768px) {
  .logo-header {
    height: 170px !important;
    width: 200px !important;
    display: block;
    margin-left: -50px !important;
  }
}

/* Celular: el titulo ocupa el espacio que sobra (y baja de linea si hace falta)
   y el logo cuadrado se queda a la derecha, SIEMPRE dentro de la tarjeta.
   Antes el titulo tenia un ancho fijo y en pantallas de ~320-390px
   empujaba al logo fuera del borde derecho. */
@media (max-width: 767px) {
  .custom-bar .level {
    justify-content: space-between;
    gap: 10px;
  }
  .custom-bar .level-left {
    flex: 1 1 0;
    min-width: 0;
  }
  .custom-bar .level-right {
    flex: 0 0 auto;
    margin-top: 0;
  }
  .custom-bar .titulo-header {
    max-width: 100%;
    min-width: 0;
  }
  .custom-bar .title-bar-text {
    font-size: clamp(20px, 6.6vw, 26px);
    overflow-wrap: anywhere;
  }
  .logo-header {
    width: 64px !important;
    height: 64px !important;
    object-fit: contain;
    display: block;
    margin-left: 0 !important;
  }
}
  </style>
  <style>
    /* Insignia "Verificado" de las tarjetas: en computadora igual que siempre;
       en celular ocupa su propia línea debajo del título (si no hay insignia, no ocupa nada). */
    .ins-verificado { background:#dcfce7; color:#16a34a; padding:1px 8px; border-radius:20px; font-size:.65rem; font-weight:700; vertical-align:middle; white-space:nowrap; }
    @media (max-width: 768px) {
      .ins-verificado { display:table; margin-top:4px; }
    }
  </style>
  <style>
    /* ===== INVITACIÓN (banner) + TARJETAS DE SERVICIOS (prefijo "tc-") ===== */
    .tc-stats-btn { display:flex; width:fit-content; margin:14px 0 0 auto; align-items:center; gap:6px; padding:7px 14px; font-size:13px; font-weight:700;
      color:var(--tc-naranja-osc); background:#fff; border:2px solid #ffd9b8; border-radius:999px; cursor:pointer; transition:background .2s, border-color .2s; }
    .tc-stats-btn:hover { background:#fff4ea; border-color:var(--tc-naranja); }
    .tc-stats-btn .tc-flecha { transition:transform .2s; }
    .tc-stats-btn[aria-expanded="true"] .tc-flecha { transform:rotate(180deg); }
    .tc-oculto { display:none !important; }
    :root { --tc-vino:#8a1232; --tc-vino-osc:#6a0d26; --tc-precio:#a10f33; --tc-naranja:#ff7a18; --tc-naranja-osc:#e8650a; }

    .tc-banner { position:relative; display:flex; align-items:center; gap:18px; margin:0 0 22px; padding:14px 22px;
      border-radius:22px; color:#fff; overflow:hidden;
      background:linear-gradient(100deg,#5e0a20 0%,#8a1232 55%,#a3173d 100%);
      box-shadow:0 10px 26px rgba(110,12,38,.28); }
    .tc-banner::before { content:""; position:absolute; inset:0; pointer-events:none;
      background:radial-gradient(circle at 10% 120%,rgba(255,201,51,.30),transparent 45%),
                 radial-gradient(circle at 80% -30%,rgba(255,255,255,.14),transparent 50%); }
    .tc-banner > * { position:relative; }
    .tc-banner-logo { flex:0 0 auto; width:92px; height:92px; object-fit:contain; filter:drop-shadow(0 4px 8px rgba(0,0,0,.35)); }
    .tc-banner-texto { flex:1 1 0; min-width:0; }
    .tc-banner .tc-banner-texto h3 { margin:0; font-size:clamp(17px,2vw,25px); font-weight:800; line-height:1.2; color:#fff; }
    .tc-banner .tc-banner-texto h3 em { font-style:normal; color:#ffc933; }
    .tc-banner .tc-banner-texto p { margin:5px 0 0; font-size:14px; color:rgba(255,255,255,.88); }
    .tc-banner-botones { display:flex; align-items:center; gap:10px; flex:0 0 auto; }
    .tc-tile { display:flex; flex-direction:column; align-items:center; justify-content:center; gap:3px; width:96px; min-height:78px; padding:8px 6px;
      border-radius:16px; background:#fff; color:var(--tc-vino); text-align:center; font-size:13px; font-weight:800; line-height:1.15;
      text-decoration:none; box-shadow:0 4px 10px rgba(0,0,0,.18); transition:transform .15s, box-shadow .15s; }
    .tc-tile i { font-size:28px; line-height:1; }
    .tc-tile:hover { color:var(--tc-vino-osc); transform:translateY(-2px); box-shadow:0 8px 16px rgba(0,0,0,.25); }
    .tc-promo { flex:0 0 auto; max-width:170px; padding:10px 16px; border-radius:16px; text-align:center; text-decoration:none;
      font-size:14px; font-weight:800; line-height:1.2; color:#6a0d26; transform:rotate(-3deg);
      background:linear-gradient(135deg,#ffd54a,#ffb300); box-shadow:0 6px 14px rgba(0,0,0,.25); transition:transform .15s; }
    .tc-promo:hover { color:#6a0d26; transform:rotate(-3deg) scale(1.04); }

    #listProducts .product { position:relative; display:flex; flex-direction:column; overflow:hidden; border-radius:18px; background:#fff;
      box-shadow:0 6px 18px rgba(0,0,0,.08); transition:transform .2s, box-shadow .2s; cursor:pointer; }
    #listProducts .product:hover { transform:translateY(-4px); box-shadow:0 14px 28px rgba(255,122,24,.2); }
    #listProducts .tc-foto { position:relative; flex:0 0 auto; height:170px; overflow:hidden; background:#fbe9dc; }
    #listProducts .tc-foto img { display:block; width:100%; height:100%; margin:0; object-fit:cover; border-radius:0; }
    .tc-estado { position:absolute; left:10px; top:10px; z-index:1; display:inline-flex; align-items:center; gap:5px; padding:4px 11px;
      border-radius:999px; font-size:12px; font-weight:700; color:#fff; background:#16a34a; box-shadow:0 2px 6px rgba(0,0,0,.25); }
    #listProducts .ve-tag-cerca { left:auto; right:10px; }
    #listProducts .product-info { flex:1 1 auto; display:flex; flex-direction:column; padding:14px 16px 16px; }
    .tc-titulo { margin:0 0 6px; font-size:17px; font-weight:800; line-height:1.25; color:#222; }
    .tc-titulo a.tc-link { color:inherit; text-decoration:none; }
    .tc-titulo a.tc-link::after { content:""; position:absolute; inset:0; z-index:1; }
    .tc-titulo a.tc-link:focus-visible { outline:none; }
    .tc-titulo a.tc-link:focus-visible::after { outline:3px solid #ffb347; outline-offset:-3px; border-radius:18px; }
    .tc-lugar { display:flex; align-items:center; gap:4px; margin:0 0 8px; font-size:12.5px; color:#777; }
    .tc-lugar i { color:#ff7a18; }
    .tc-lugar span { overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
    #listProducts .tc-desc { margin:0 0 8px; font-size:13.5px; line-height:1.45; color:#555;
      display:-webkit-box; -webkit-line-clamp:3; -webkit-box-orient:vertical; overflow:hidden; }
    .tc-pie { margin-top:auto; padding-top:6px; }
    .tc-precio { display:flex; align-items:baseline; justify-content:space-between; gap:6px; margin:6px 0 10px; }
    .tc-precio small { font-size:13px; color:#888; }
    .tc-precio strong { font-size:22px; font-weight:800; color:var(--tc-precio); }
    .tc-precio strong em { font-size:12px; font-style:normal; font-weight:700; color:#888; }
    .tc-acciones { display:flex; align-items:center; gap:10px; }
    #listProducts a.tc-contratame { position:relative; z-index:2; flex:1 1 auto; display:inline-flex; align-items:center; justify-content:center; gap:8px;
      padding:11px 12px; border-radius:999px; background:linear-gradient(45deg,#ff7a18,#ffb347); color:#fff; font-size:14px; font-weight:700; white-space:nowrap;
      text-decoration:none; box-shadow:0 4px 10px rgba(255,122,24,.35); transition:filter .2s, transform .15s; }
    #listProducts a.tc-contratame:hover { background:linear-gradient(45deg,#ff7a18,#ffb347); filter:brightness(.93); color:#fff; transform:translateY(-1px); }
    #listProducts a.tc-contratame i { font-size:18px; }
    #listProducts .favorite-wrapper { position:relative; z-index:2; flex:0 0 auto; }
    #listProducts .favorite-wrapper .btn-fav { color:var(--tc-naranja); }

    @media (max-width: 900px) {
      .tc-banner { flex-wrap:wrap; padding:14px 16px; }
      .tc-banner-texto { flex:1 1 calc(100% - 120px); }
      .tc-banner-botones { flex:1 1 100%; }
      .tc-tile { flex:1 1 0; width:auto; }
      .tc-promo { margin-left:auto; }
    }
    @media (max-width: 560px) {
      .tc-banner-logo { width:64px; height:64px; }
      .tc-banner-texto { flex-basis:calc(100% - 84px); }
      .tc-banner-texto p { font-size:12.5px; }
      .tc-promo { flex:1 1 100%; max-width:none; transform:none; }
      .tc-promo:hover { transform:scale(1.02); }
      #listProducts .tc-foto { height:112px; }
      .tc-estado { left:6px; top:6px; padding:3px 8px; font-size:11px; gap:3px; }
      #listProducts .ve-tag-cerca { right:auto; left:6px; top:32px; font-size:10px; padding:2px 8px; }
      #listProducts .product-info { padding:10px 10px 12px; }
      .tc-titulo { margin-bottom:4px; font-size:14px; line-height:1.2; }
      .tc-titulo .ins-verificado { font-size:.58rem; }
      .tc-lugar { margin-bottom:5px; font-size:11px; }
      #listProducts .tc-desc { margin-bottom:6px; font-size:12px; line-height:1.35; -webkit-line-clamp:2; }
      #listProducts .rating { display:flex; align-items:center; gap:1px; margin:0; }
      #listProducts .rating .icon-star { font-size:11px; }
      #listProducts .rating small { display:none; }
      .tc-precio { flex-direction:column; align-items:flex-start; gap:0; margin:6px 0 8px; }
      .tc-precio small { font-size:11px; }
      .tc-precio strong { font-size:18px; white-space:nowrap; }
      .tc-precio strong em { font-size:10px; }
      .tc-acciones { display:block; }
      #listProducts a.tc-contratame { box-sizing:border-box; display:flex; width:100%; min-width:0; gap:5px; padding:9px 6px; font-size:12px; line-height:1.15; text-align:center; white-space:normal; box-shadow:0 3px 8px rgba(255,122,24,.35); }
      #listProducts a.tc-contratame i { flex:0 0 auto; font-size:15px; }
      #listProducts .favorite-wrapper { position:absolute; top:6px; right:6px; z-index:3; width:30px; height:30px; display:flex; align-items:center; justify-content:center;
        background:rgba(255,255,255,.94); border-radius:50%; box-shadow:0 2px 6px rgba(0,0,0,.25); }
      #listProducts .favorite-wrapper .btn-fav { font-size:1.05rem !important; line-height:1; }
    }
  </style>
  <style>
    /* ===== Encabezado "Contrata a alguien de tu comunidad": franja vino compacta ===== */
    .ve-hero { position:relative; overflow:hidden; display:flex; align-items:center; justify-content:space-between; gap:18px;
      margin:0; padding:18px 26px; border-radius:22px; color:#fff;
      background:linear-gradient(110deg,#5e0a20 0%,#8a1232 55%,#a3173d 100%);
      box-shadow:0 10px 26px rgba(110,12,38,.25); }
    .ve-hero::before { content:""; position:absolute; inset:0; pointer-events:none;
      background:radial-gradient(circle at 8% 130%,rgba(255,201,51,.32),transparent 45%),
                 radial-gradient(circle at 85% -40%,rgba(255,255,255,.16),transparent 55%); }
    .ve-hero > * { position:relative; }
    .ve-hero-text { min-width:0; }
    .ve-eyebrow { display:inline-flex; align-items:center; gap:6px; margin-bottom:6px; padding:3px 11px; border-radius:999px;
      font-size:11.5px; font-weight:700; letter-spacing:.5px; text-transform:uppercase; color:#ffd9a8;
      background:rgba(255,255,255,.12); border:1px solid rgba(255,255,255,.22); }
    .ve-hero h1 { margin:0; font-size:clamp(1.35rem,2.8vw,2.1rem); font-weight:800; line-height:1.15; color:#fff; }
    .ve-hero h1 em { font-style:normal; color:#ffc933; }
    .ve-hero p { margin:6px 0 0; max-width:640px; font-size:clamp(.85rem,1.3vw,1rem); font-weight:500; line-height:1.35; color:rgba(255,255,255,.9); }
    .ve-hero-logo { flex:0 0 auto; display:block; width:92px; height:92px; padding:6px; object-fit:contain; border-radius:50%;
      background:rgba(255,255,255,.96); box-shadow:0 6px 16px rgba(0,0,0,.28); }
    @media (max-width: 768px) {
      .ve-hero { gap:12px; padding:14px 16px; border-radius:18px; }
      .ve-eyebrow { margin-bottom:4px; padding:2px 9px; font-size:10px; }
      .ve-hero h1 { font-size:1.2rem; }
      .ve-hero p { margin-top:4px; font-size:.8rem; }
      .ve-hero-logo { width:60px; height:60px; padding:4px; }
    }
    @media (max-width: 380px) {
      .ve-hero h1 { font-size:1.08rem; }
      .ve-hero-logo { width:52px; height:52px; }
    }
    /* menos aire entre el encabezado, el botón de estadísticas y el aviso */
    .tc-stats-btn { margin:10px 0 0 auto; }
    .tc-banner-aviso { margin:12px 0 14px; }
    /* menos espacio en blanco entre "¿Qué quieres hacer?" y "Servicios Disponibles"
       (antes se sumaban 40px + 40px de styles.css) */
    #mainContent .acciones-rapidas { margin:16px 0 8px; }
    #mainContent .acciones-titulo { margin-bottom:14px; }
    #mainContent #listProducts { padding:8px 0 24px; }
    #mainContent #listProducts > h2 { margin-bottom:18px; }
  </style>
</head>

<body>
  <div id="app">

    <!-- NAVBAR -->
    <nav id="navbar-main" class="navbar is-fixed-top">
      <div class="navbar-brand">
        <a class="navbar-item is-hidden-desktop jb-aside-mobile-toggle">
          <span class="icon"><i class="mdi mdi-forwardburger mdi-24px"></i></span>
        </a>
      </div>
      <div class="navbar-brand is-right">
        <a class="navbar-item is-hidden-desktop jb-navbar-menu-toggle" data-target="navbar-menu">
          <span class="icon"><i class="mdi mdi-dots-vertical"></i></span>
        </a>
      </div>
      <div class="navbar-menu fadeIn animated faster" id="navbar-menu">
        <div class="navbar-start">
          <div class="navbar-item breadcrumb-nav">
            <i class="mdi mdi-map-marker-outline"></i>
            <span class="crumb-section">Usuario</span>
            <i class="mdi mdi-chevron-right crumb-sep"></i>
            <span class="crumb-page">Inicio</span>
          </div>
        </div>
        <div class="navbar-end">
          <a title="Log out" href="#" id="logoutBtn" class="navbar-item is-desktop-icon-only">
            <span class="icon"><i class="mdi mdi-logout"></i></span>
            <span>Cerrar Sesión</span>
          </a>
        </div>
      </div>
    </nav>

    <!-- SIDEBAR -->
    <aside class="aside is-placed-left is-expanded" id="mainSidebar">
      <div class="aside-tools">
        <div class="aside-tools-label">
          <span><b> Modo Trabajador</b></span>
        </div>
      </div>

      <!-- ===== INTERRUPTOR AJUSTADO ===== -->
      <div class="sidebar-role-switch">
        <div class="switch-row" id="switchRow">
          <div class="active-bg worker-bg" id="activeBg"></div>
          <span class="role-text worker-text active" id="labelWorker">TRABAJADOR</span>
          <span class="role-text employer-text" id="labelEmployer">EMPLEADOR</span>
        </div>

        <!-- Tooltip -->
        <div class="role-tooltip-sidebar" id="roleTooltip">
          <div class="tooltip-title">
            <span id="tooltipRoleName">👷 <span class="highlight-worker">Modo Trabajador</span></span>
          </div>
          <div class="tooltip-desc" id="tooltipDesc">Publica y gestiona tus servicios</div>
        </div>
      </div>

      <div class="menu is-menu-main">
        <p class="menu-label">General</p>
        <ul class="menu-list">
          <li>
            <a href="/usuario" class="is-active router-link-active has-icon" id="sidebarIndex">
              <span class="icon has-update-mark"><i class="mdi mdi-desktop-mac"></i></span>
              <span class="menu-item-label">Inicio</span>
            </a>
          </li>
        </ul>
        <p class="menu-label">Otros</p>
        <ul class="menu-list">
          <li>
            <a href="/usuario/verEmpleos" class="has-icon" id="sidebarVerEmpleos">
              <span class="icon"><i class="mdi mdi-briefcase"></i></span>
              <span class="menu-item-label">Contratar un servicio</span>
            </a>
          </li>
          <li>
            <a href="/usuario/publicarEmpleo" class="has-icon" id="sidebarPublicar">
              <span class="icon"><i class="mdi mdi-square-edit-outline"></i></span>
              <span class="menu-item-label">Ofrecer mi servicio</span>
            </a>
          </li>
          <li>
            <a href="/usuario/misEmpleos" class="has-icon" id="sidebarMisEmpleos">
              <span class="icon"><i class="mdi mdi-format-list-bulleted"></i></span>
              <span class="menu-item-label">Mis servicios</span>
            </a>
          </li>
          <li>
            <a href="/usuario/solicitudes" class="has-icon" id="sidebarSolicitudes">
              <span class="icon"><i class="mdi mdi-account-clock"></i></span>
              <span class="menu-item-label">Solicitudes</span>
            </a>
          </li>
          <li>
            <a href="/usuario/profile" class="has-icon" id="sidebarProfile">
              <span class="icon"><i class="mdi mdi-account-circle"></i></span>
              <span class="menu-item-label">Perfil</span>
            </a>
          </li>
        </ul>
      </div>
    </aside>

    <!-- CONTENIDO PRINCIPAL -->
    <section class="section is-main-section" id="mainContent">
      <div class="container">


        <!-- ENCABEZADO + LOGO -->
        <section class="ve-hero">
          <div class="ve-hero-text">
            <span class="ve-eyebrow"><i class="mdi mdi-map-marker"></i> Zacapoaxtla, Puebla</span>
            <h1>Contrata a alguien de <em>tu comunidad</em></h1>
            <p>Encuentra al profesional que necesitas en Zacapoaxtla y contáctalo directo</p>
          </div>
          <img src="{{ asset('assets/usuario/img/icono.png') }}" alt="Logo Empleos Zacapoaxtla" class="ve-hero-logo" width="92" height="92">
        </section>

        <!-- ESTADÍSTICAS -->
        <button type="button" class="tc-stats-btn" id="tcStatsBtn" aria-expanded="false" aria-controls="tcStats">
          <i class="mdi mdi-chart-box-outline"></i> <span id="tcStatsTxt">Ver estadísticas</span> <i class="mdi mdi-chevron-down tc-flecha"></i>
        </button>
        <div class="columns estadisticas-row tc-oculto" id="tcStats" style="margin-top:.75rem;">
          <div class="column">
            <div class="box has-text-centered">
              <span class="icon is-large"><i class="mdi mdi-briefcase mdi-36px"></i></span>
              <h2 class="title has-text-success">{{ $serviciosActivosCount }}</h2>
              <p class="has-text-grey">Servicios Activos</p>
            </div>
          </div>
          <div class="column">
            <div class="box has-text-centered">
              <span class="icon is-large"><i class="mdi mdi-office-building mdi-36px"></i></span>
              <h2 class="title has-text-success">{{ $empresasActivasCount }}</h2>
              <p class="has-text-grey">Empresas Activas</p>
            </div>
          </div>
          <div class="column">
            <div class="box has-text-centered">
              <span class="icon is-large"><i class="mdi mdi-account mdi-36px"></i></span>
              <h2 class="title has-text-success">{{ $misServiciosCount }}</h2>
              <p class="has-text-grey">Mis Servicios</p>
            </div>
          </div>
        </div>

        <div class="tc-banner-aviso" style="border-radius:14px; padding:12px 16px; background:#fff8f0; border:1px solid #ffe0bf; color:#5b4636; line-height:1.5;">
          <strong>Estás en modo Trabajador.</strong> Aquí <strong>ofreces tu trabajo</strong> (plomero, maestra, cocinera, albañil...) para que la gente te contacte, y también puedes <strong>contratar el servicio</strong> que otra persona ofrece.
          Si lo que necesitas es <strong>buscar personal para tu negocio</strong>, cambia a <strong>Empleador</strong> en el selector del menú de la izquierda.
        </div>

        <!-- ACCIONES RÁPIDAS -->
        <div class="acciones-rapidas">
          <h3 class="acciones-titulo">¿Qué quieres hacer?</h3>
          <div class="acciones-grid">
            <a href="/usuario/publicarEmpleo" class="accion-card" id="actionPublicar">
              <span class="icon"><i class="mdi mdi-plus-circle mdi-36px"></i></span>
              <span>Ofrecer mi servicio<small style="display:block; font-weight:400; font-size:12.5px; color:#777; margin-top:4px;">Publica lo que sabes hacer</small></span>
            </a>
            <a href="/usuario/verEmpleos" class="accion-card" id="actionVer">
              <span class="icon"><i class="mdi mdi-magnify mdi-36px"></i></span>
              <span>Contratar un servicio<small style="display:block; font-weight:400; font-size:12.5px; color:#777; margin-top:4px;">Busca a quien necesitas</small></span>
            </a>
            <a href="/usuario/misEmpleos" class="accion-card" id="actionMis">
              <span class="icon"><i class="mdi mdi-format-list-bulleted mdi-36px"></i></span>
              <span>Mis servicios<small style="display:block; font-weight:400; font-size:12.5px; color:#777; margin-top:4px;">Lo que ya publicaste</small></span>
            </a>
          </div>
        </div>

        <!-- SERVICIOS DISPONIBLES -->
        <section class="products" id="listProducts">
          <h2>Servicios Disponibles</h2>
          <div class="products-grid container">
            @forelse ($servicios as $s)
              <div class="product">
                <div class="tc-foto">
                  <img src="{{ $s->imagen ?? asset('assets/usuario/img/services/plomero.jpg') }}" alt="{{ $s->titulo }}" loading="lazy">
                  <span class="tc-estado"><i class="mdi mdi-check-circle"></i> Disponible</span>
                </div>
                <div class="product-info">
                  <h4 class="tc-titulo"><a href="/usuario/ver_servicio/{{ $s->id }}" class="tc-link">{{ $s->titulo }}</a>
                    @if ($s->usuario && $s->usuario->verificacion_estado === 'aprobado')
                      <span class="ins-verificado" title="Identidad verificada">
                        <i class="mdi mdi-check-decagram"></i> Verificado
                      </span>
                    @endif
                  </h4>
                  @if (trim((string) $s->ubicacion) !== '')
                    <p class="tc-lugar" title="{{ $s->ubicacion }}"><i class="mdi mdi-map-marker"></i> <span>{{ $s->ubicacion }}</span></p>
                  @endif
                  <p class="product-text tc-desc">{{ \Illuminate\Support\Str::limit($s->descripcion, 140) }}</p>
                  <div class="rating">
                    @php $prom = round($s->calificaciones_avg_estrellas ?? 0); @endphp
                    @for ($i = 1; $i <= 5; $i++)
                      <i class="fa-solid fa-star icon-star" style="color:{{ $i <= $prom ? '#ffb347' : '#ddd' }};"></i>
                    @endfor
                    <small style="color:#999; margin-left:4px;">
                      {{ $s->calificaciones_avg_estrellas ? number_format($s->calificaciones_avg_estrellas, 1) : 'Sin reseñas' }}
                      @if ($s->calificaciones_count) ({{ $s->calificaciones_count }}) @endif
                    </small>
                  </div>
                  <div class="tc-pie">
                    <div class="tc-precio">
                      <small>Desde</small>
                      <strong>${{ number_format($s->precio, 0) }} <em>MXN</em></strong>
                    </div>
                    <div class="tc-acciones">
                      <a href="/usuario/ver_servicio/{{ $s->id }}" class="tc-contratame"><i class="mdi mdi-briefcase"></i> ¡Contratar ahora!</a>
                      <div class="favorite-wrapper position-relative">
                        <i class="bi bi-heart btn-fav" style="font-size: 1.3rem; cursor: pointer;"></i>
                        <div class="fav-dropdown" style="display: none;">
                          <p>Agregado a favoritos ❤️</p>
                          <button class="btn btn-sm btn-danger clear-fav">Eliminar</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            @empty
              <p class="has-text-grey has-text-centered" style="grid-column: 1 / -1;">Todavía no hay servicios disponibles. ¡Sé el primero en publicar uno!</p>
            @endforelse
          </div>
        </section>

        <!-- ========================= -->
        <!-- ANUNCIOS INFERIOR -->
        <!-- ========================= -->
        <div class="my-4">
          @include('partials.anuncio-negocio')
        </div>

      </div>
    </section>

    <!-- MODAL: Vista rápida del servicio -->
    <div id="modal-servicio" class="modal">
      <div class="modal-background" onclick="document.getElementById('modal-servicio').classList.remove('is-active')"></div>
      <div class="modal-card" style="max-width:560px; width:92%;">
        <header class="modal-card-head" style="background: linear-gradient(135deg, #1e3a5f, #2d5a8f); border: none;">
          <div style="width:100%; color:white;">
            <div class="tags mb-2">
              <span class="tag" id="modalCategoria" style="background:rgba(255,255,255,.2); color:white;"></span>
              <span class="tag" id="modalSubcategoria" style="background:rgba(255,255,255,.2); color:white;"></span>
            </div>
            <p class="modal-card-title" style="color:white; margin-bottom:2px;" id="modalTitulo"></p>
            <p style="opacity:.9; font-size:.9rem; margin:0;"><i class="mdi mdi-account"></i> Publicado por: <span id="modalPublicante"></span></p>
          </div>
          <button class="delete" aria-label="close" onclick="document.getElementById('modal-servicio').classList.remove('is-active')"></button>
        </header>
        <section class="modal-card-body">
          <div class="columns is-mobile mb-4" style="background:#f8fafc; border-radius:12px; padding:12px 6px; margin:0 0 1rem;">
            <div class="column has-text-centered">
              <div style="font-size:.75rem; color:#888;"><i class="mdi mdi-map-marker"></i> Ubicación</div>
              <strong id="modalUbicacion"></strong>
            </div>
            <div class="column has-text-centered">
              <div style="font-size:.75rem; color:#888;"><i class="mdi mdi-cash"></i> Precio</div>
              <strong id="modalPrecio"></strong>
            </div>
            <div class="column has-text-centered">
              <div style="font-size:.75rem; color:#888;"><i class="mdi mdi-calendar"></i> Publicado</div>
              <strong id="modalFecha"></strong>
            </div>
          </div>

          <h3 class="title is-6">Descripción</h3>
          <p id="modalDescripcion" style="line-height:1.6;"></p>

          <h3 class="title is-6 mt-4">Información de contacto</h3>
          <div id="modalContactos"></div>
        </section>
      </div>
    </div>

    <footer class="footer">
      <div class="container-fluid">
        <div class="level">
          <div class="level-left">
            <div class="level-item">© 2025 · Empleos Zacapoaxtla · Tu próxima oportunidad laboral</div>
          </div>
        </div>
      </div>
    </footer>

  </div>

  <script src="{{ asset('assets/usuario/js/main.js') }}"></script>
  <script src="{{ asset('assets/usuario/js/interruptor.js') }}"></script>
  <script>
    function abrirModalServicio(btn) {
      const d = btn.dataset;
      document.getElementById('modalCategoria').textContent = d.categoria;
      document.getElementById('modalSubcategoria').textContent = d.subcategoria;
      document.getElementById('modalTitulo').textContent = d.titulo;
      document.getElementById('modalPublicante').textContent = d.publicante;
      document.getElementById('modalUbicacion').textContent = d.ubicacion;
      document.getElementById('modalPrecio').textContent = '$' + d.precio + ' MXN';
      document.getElementById('modalFecha').textContent = d.fecha;
      document.getElementById('modalDescripcion').textContent = d.descripcion;

      let contactosHtml = '';
      if (d.telefono) {
        contactosHtml += `
          <div class="level is-mobile" style="background:#f8fafc; border-radius:10px; padding:10px 14px; margin-bottom:8px;">
            <div class="level-left"><span class="icon" style="color:#e91e63;"><i class="mdi mdi-phone"></i></span>&nbsp;<div><small style="color:#888;">Teléfono</small><br><strong>${d.telefono}</strong></div></div>
            <div class="level-right"><a class="button is-success is-small" href="tel:${d.telefono}">Llamar</a></div>
          </div>`;
      }
      if (d.whatsapp) {
        contactosHtml += `
          <div class="level is-mobile" style="background:#f8fafc; border-radius:10px; padding:10px 14px; margin-bottom:8px;">
            <div class="level-left"><span class="icon" style="color:#25D366;"><i class="mdi mdi-whatsapp"></i></span>&nbsp;<div><small style="color:#888;">WhatsApp</small><br><strong>${d.whatsapp}</strong></div></div>
            <div class="level-right"><a class="button is-success is-small" target="_blank" href="https://wa.me/52${d.whatsapp.replace(/\D/g,'')}">WhatsApp</a></div>
          </div>`;
      }
      if (d.email) {
        contactosHtml += `
          <div class="level is-mobile" style="background:#f8fafc; border-radius:10px; padding:10px 14px;">
            <div class="level-left"><span class="icon" style="color:#5c6bc0;"><i class="mdi mdi-email"></i></span>&nbsp;<div><small style="color:#888;">Email</small><br><strong>${d.email}</strong></div></div>
            <div class="level-right"><a class="button is-info is-small" href="mailto:${d.email}">Enviar Email</a></div>
          </div>`;
      }
      if (!contactosHtml) {
        contactosHtml = '<p class="has-text-grey">Este publicante todavía no agregó datos de contacto.</p>';
      }
      document.getElementById('modalContactos').innerHTML = contactosHtml;

      document.getElementById('modal-servicio').classList.add('is-active');
    }
  </script>
  <script src="{{ asset('assets/usuario/js/dashboard-data.js') }}"></script>
  <script src="{{ asset('assets/usuario/js/ads-widget.js') }}"></script>


  <script>
    (function () {
      const logoutBtn = document.getElementById('logoutBtn');
      if (!logoutBtn) return;
      logoutBtn.addEventListener('click', function (e) {
        e.preventDefault();
        if (!confirm('¿Seguro que quieres cerrar sesión?')) return;
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        const csrfToken = csrfMeta ? csrfMeta.content : '';
        fetch('/logout', {
          method: 'POST',
          headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
        }).finally(() => { window.location.href = '/'; });
      });
    })();
  </script>
  <script>
    // Estadísticas ocultas por defecto; la persona decide si las quiere ver (se recuerda su elección).
    (function () {
      var btn = document.getElementById('tcStatsBtn'), caja = document.getElementById('tcStats'), txt = document.getElementById('tcStatsTxt');
      if (!btn || !caja) return;
      function poner(ver) {
        caja.classList.toggle('tc-oculto', !ver);
        btn.setAttribute('aria-expanded', ver ? 'true' : 'false');
        txt.textContent = ver ? 'Ocultar estadísticas' : 'Ver estadísticas';
      }
      var ver = false;
      try { ver = localStorage.getItem('tcVerStats') === '1'; } catch (e) {}
      poner(ver);
      btn.addEventListener('click', function () {
        ver = !ver; poner(ver);
        try { localStorage.setItem('tcVerStats', ver ? '1' : '0'); } catch (e) {}
      });
    })();
  </script>
</body>

</html>
