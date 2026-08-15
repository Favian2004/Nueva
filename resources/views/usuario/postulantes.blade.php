<!DOCTYPE html>
<html lang="es" class="has-aside-left has-aside-mobile-transition has-navbar-fixed-top has-aside-expanded">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Postulantes · Empleador</title>

  <link rel="stylesheet" href="{{ asset('assets/usuario/css/main.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/usuario/css/styles.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/usuario/css/switch.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/usuario/css/employer-dashboard.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.9.95/css/materialdesignicons.min.css">
  <link rel="icon" type="img/" href="{{ asset('assets/usuario/img/icono.png') }}">

  <style>
    /* ============================================================
       POSTULANTES - ESTILO MEJORADO
       ============================================================ */

    .vacante-selector {
      background: #fff;
      border-radius: 12px;
      padding: 16px 20px;
      border: 1px solid #e8eaed;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 16px;
      flex-wrap: wrap;
    }

    .vacante-selector label {
      font-weight: 600;
      color: #1a1a2e;
      font-size: 14px;
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .vacante-selector select {
      padding: 8px 14px;
      border-radius: 10px;
      border: 1.5px solid #e5e7eb;
      font-size: 13px;
      outline: none;
      background: #fff;
      min-width: 250px;
      flex: 1;
    }

    .vacante-selector select:focus {
      border-color: #ff7a18;
      box-shadow: 0 0 0 3px rgba(255, 122, 24, 0.12);
    }

    .vacante-selector .vacante-info {
      font-size: 12px;
      color: #5f6368;
      display: flex;
      align-items: center;
      gap: 12px;
      flex-wrap: wrap;
    }

    .vacante-selector .vacante-info .badge {
      background: #e6f4ea;
      color: #1e7e34;
      padding: 2px 12px;
      border-radius: 20px;
      font-weight: 600;
      font-size: 11px;
    }

    .vacante-selector .vacante-info .badge.pending {
      background: #fef7e0;
      color: #e37400;
    }

    .vacante-selector .vacante-info .badge.closed {
      background: #f1f3f4;
      color: #5f6368;
    }

    .vacante-stats {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 12px;
      margin-bottom: 20px;
    }

    .vacante-stats .stat-item {
      background: #fff;
      border-radius: 10px;
      padding: 12px 16px;
      text-align: center;
      border: 1px solid #e8eaed;
      transition: 0.2s;
    }

    .vacante-stats .stat-item:hover {
      border-color: #ffb347;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
    }

    .vacante-stats .stat-item .stat-number {
      font-size: 22px;
      font-weight: 800;
      color: #1a1a2e;
      line-height: 1.2;
    }

    .vacante-stats .stat-item .stat-label {
      font-size: 11px;
      color: #5f6368;
      margin-top: 2px;
    }

    .vacante-stats .stat-item .stat-icon {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 8px;
      font-size: 20px;
    }
    .stat-icon.icon-total { background: #eef2ff; color: #4f46e5; }
    .stat-icon.icon-pendientes { background: #fef7e0; color: #e37400; }
    .stat-icon.icon-contratados { background: #e6f4ea; color: #1e7e34; }
    .stat-icon.icon-rechazados { background: #fce8e6; color: #d93025; }

    .filtro-postulantes {
      display: grid;
      grid-template-columns: 1.5fr 1fr 1.5fr auto;
      gap: 14px;
      align-items: end;
      margin-bottom: 20px;
      background: #fff;
      padding: 18px 20px;
      border-radius: 12px;
      border: 1px solid #e8eaed;
      box-shadow: 0 1px 4px rgba(0,0,0,.04);
    }

    .filtro-postulantes .campo-filtro {
      display: flex;
      flex-direction: column;
      gap: 5px;
    }

    .filtro-postulantes .campo-filtro label {
      font-size: 11px;
      font-weight: 700;
      color: #5f6368;
      text-transform: uppercase;
      letter-spacing: .3px;
    }

    .filtro-postulantes select,
    .filtro-postulantes input {
      padding: 9px 14px;
      border-radius: 10px;
      border: 1.5px solid #e5e7eb;
      font-size: 13px;
      outline: none;
      background: #fff;
      width: 100%;
    }

    .filtro-postulantes select:focus,
    .filtro-postulantes input:focus {
      border-color: #ff7a18;
      box-shadow: 0 0 0 3px rgba(255, 122, 24, 0.12);
    }

    .filtro-postulantes .btn-filtrar {
      padding: 9px 24px;
      border: none;
      border-radius: 10px;
      background: linear-gradient(45deg, #ff7a18, #ffb347);
      color: #fff;
      font-weight: 700;
      font-size: 13px;
      cursor: pointer;
      transition: 0.3s;
      display: flex;
      align-items: center;
      gap: 6px;
      white-space: nowrap;
    }

    .filtro-postulantes .btn-filtrar:hover {
      opacity: 0.88;
      transform: translateY(-1px);
    }

    @media (max-width: 768px) {
      .filtro-postulantes {
        grid-template-columns: 1fr;
      }
    }

    .postulantes-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 16px;
    }

    .postulante-card {
      background: #fff;
      border-radius: 14px;
      padding: 18px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
      border: 1px solid #f0f0f0;
      transition: all 0.3s ease;
    }

    .postulante-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.10);
      border-color: #ffb347;
    }

    .postulante-card .postulante-header {
      display: flex;
      align-items: center;
      gap: 14px;
      margin-bottom: 10px;
    }

    .postulante-card .postulante-avatar {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 18px;
      font-weight: 700;
      color: #fff;
      flex-shrink: 0;
    }

    .postulante-card .postulante-nombre {
      font-weight: 700;
      font-size: 15px;
      color: #1a1a2e;
    }

    .postulante-card .postulante-cargo {
      font-size: 12px;
      color: #666;
    }

    .postulante-card .postulante-status {
      font-size: 10px;
      font-weight: 600;
      padding: 2px 12px;
      border-radius: 20px;
      text-transform: uppercase;
      letter-spacing: 0.3px;
      display: inline-block;
    }

    .status-pendiente {
      background: #fef7e0;
      color: #e37400;
    }

    .status-contratado {
      background: #e6f4ea;
      color: #1e7e34;
    }

    .status-rechazado {
      background: #fce8e6;
      color: #d93025;
    }

    .postulante-card .postulante-mensaje {
      font-size: 12px;
      color: #555;
      background: #f8fafc;
      border-radius: 8px;
      padding: 8px 10px;
      margin-bottom: 8px;
      font-style: italic;
    }

    .postulante-card .postulante-meta {
      display: flex;
      justify-content: space-between;
      font-size: 12px;
      color: #888;
      padding-top: 10px;
      border-top: 1px solid #f0f0f0;
      margin-top: 10px;
      flex-wrap: wrap;
      gap: 4px;
    }

    .postulante-card .postulante-actions {
      display: flex;
      gap: 6px;
      margin-top: 10px;
      flex-wrap: wrap;
    }

    .postulante-card .postulante-actions button {
      flex: 1;
      border: none;
      padding: 6px 10px;
      border-radius: 8px;
      font-weight: 600;
      font-size: 11px;
      cursor: pointer;
      transition: 0.2s;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 4px;
    }

    .btn-contratar {
      background: #dcfce7;
      color: #16a34a;
    }
    .btn-contratar:hover {
      background: #bbf7d0;
    }

    .btn-rechazar {
      background: #fee2e2;
      color: #dc2626;
    }
    .btn-rechazar:hover {
      background: #fecaca;
    }

    .btn-perfil {
      background: #eff6ff;
      color: #2563eb;
    }
    .btn-perfil:hover {
      background: #dbeafe;
    }

    .sin-postulantes {
      text-align: center;
      padding: 40px 20px;
      background: #fff;
      border-radius: 12px;
      border: 1px solid #e8eaed;
      grid-column: 1 / -1;
    }

    .sin-postulantes .icon {
      font-size: 48px;
      color: #ccc;
      margin-bottom: 12px;
    }

    .sin-postulantes h3 {
      font-size: 18px;
      color: #1a1a2e;
      margin-bottom: 6px;
    }

    .sin-postulantes p {
      font-size: 14px;
      color: #888;
    }

    /* ============================================================
       RESPONSIVE
       ============================================================ */
    @media (max-width: 1024px) {
      .vacante-stats {
        grid-template-columns: repeat(2, 1fr);
      }
    }

    @media (max-width: 992px) {
      .postulantes-grid {
        grid-template-columns: repeat(2, 1fr);
      }
    }

    @media (max-width: 768px) {
      .postulantes-grid {
        grid-template-columns: 1fr;
      }
      .vacante-selector {
        flex-direction: column;
        align-items: stretch;
      }
      .vacante-selector select {
        min-width: auto;
      }
      .vacante-stats {
        grid-template-columns: repeat(2, 1fr);
        gap: 8px;
      }
      .vacante-stats .stat-item {
        padding: 10px 12px;
      }
      .vacante-stats .stat-item .stat-number {
        font-size: 18px;
      }
      .filtro-postulantes {
        flex-direction: column;
      }
      .filtro-postulantes select,
      .filtro-postulantes input {
        width: 100%;
      }
      .filtro-postulantes .btn-filtrar {
        width: 100%;
      }
    }

    @media (max-width: 480px) {
      .vacante-stats {
        grid-template-columns: 1fr 1fr;
      }
      .postulante-card .postulante-actions {
        flex-wrap: wrap;
      }
      .postulante-card .postulante-actions button {
        min-width: 45%;
      }
    }

    /* ===== BANNER "¿TIENES UN NEGOCIO?" - RESPONSIVO ===== */
@media (max-width: 767px) {
  .banner-negocio {
    flex-direction: column;
    align-items: center;
    text-align: center;
    padding: 30px 22px 26px;
    gap: 10px;
    position: relative;
    overflow: visible;
  }

  /* Ícono de la tienda en un circulito destacado, arriba de todo */
  .banner-icono {
    order: -3;
    background: rgba(255, 255, 255, 0.16);
    border: 1.5px solid rgba(255, 207, 51, 0.4);
    width: 68px;
    height: 68px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 4px;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.15);
  }

  .banner-icono img {
    width: 38px;
    filter: drop-shadow(0 2px 3px rgba(0, 0, 0, 0.2));
  }

  /* Título principal */
  .banner-texto {
    order: -2;
  }

  .banner-texto h2 {
    font-size: 19px;
    font-weight: 800;
    letter-spacing: 0.3px;
    margin-bottom: 4px;
  }

  .banner-texto p {
    font-size: 12.5px;
    opacity: 0.88;
    max-width: 260px;
    margin: 0 auto;
    line-height: 1.45;
  }

  /* Línea divisoria decorativa */
  .banner-destaca {
    order: -1;
    margin-top: 10px;
    padding-top: 12px;
    border-top: 1px solid rgba(255, 255, 255, 0.18);
    width: 100%;
  }

  .banner-destaca h2 {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 1.2px;
    text-transform: uppercase;
    opacity: 0.75;
    margin-bottom: 2px;
  }

  .banner-destaca h1 {
    font-size: 25px;
    font-weight: 800;
    text-shadow: 0 2px 8px rgba(255, 207, 51, 0.35);
  }

  /* Botón, con más presencia */
  .banner-boton {
    width: 100%;
    margin-top: 6px;
  }

  .btn-anunciar {
    display: block;
    width: 100%;
    padding: 13px;
    font-size: 14.5px;
    letter-spacing: 0.3px;
    border-radius: 30px;
    text-align: center;
    box-shadow: 0 6px 16px rgba(255, 193, 7, 0.35);
    transition: transform 0.15s;
  }

  .btn-anunciar:active {
    transform: scale(0.97);
  }

  .banner-boton small {
    display: block;
    margin-top: 8px;
    font-size: 11px;
    opacity: 0.7;
  }

  /* Foto de la persona: chiquita, como un detalle final, no la quitamos */
  .banner-persona {
    order: 1;
    margin-top: 14px;
    height: auto;
  }

  .banner-persona img {
    height: 54px;
    width: 54px;
    border: 2px solid rgba(255, 255, 255, 0.5);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
  }
}
  </style>
  <link rel="stylesheet" href="{{ asset('assets/usuario/css/ads-widget.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/usuario/css/theme-conectaya.css') }}">

  <style>
    .banner-negocio {
      width: 100%;
      background: linear-gradient(90deg, #6b1021, #8f1d2f, #b12d25);
      border-radius: 12px;
      padding: 8px 25px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      color: white;
      overflow: hidden;
      box-shadow: 0 4px 10px rgba(0, 0, 0, .15);
    }
    .banner-texto h2 { font-size: 24px; margin: 0; }
    .banner-texto p { margin: 0; font-size: 14px; }
    .banner-destaca h2 { margin: 0; font-size: 22px; }
    .banner-destaca h1 { margin: 0; font-size: 32px; color: #ffcf33; font-weight: bold; }
    .banner-icono img { width: 70px; }
    .btn-anunciar { background: #ffc107; color: #000; text-decoration: none; padding: 10px 18px; border-radius: 8px; font-weight: bold; display: inline-block; }
    .banner-boton small { display: block; text-align: center; margin-top: 3px; color: #fff; }
    .banner-persona { height: 90px; }
    .banner-persona img { height: 90px; width: auto; object-fit: cover; border-radius: 999px; }
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
            <span class="crumb-section">Empleador</span>
            <i class="mdi mdi-chevron-right crumb-sep"></i>
            <span class="crumb-page">Postulantes</span>
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
          <span><b> Modo Empleador</b></span>
        </div>
      </div>

      <div class="sidebar-role-switch">
        <div class="switch-row" id="switchRow">
          <div class="active-bg employer-bg" id="activeBg"></div>
          <span class="role-text worker-text" id="labelWorker">TRABAJADOR</span>
          <span class="role-text employer-text active" id="labelEmployer">EMPLEADOR</span>
        </div>
        <div class="role-tooltip-sidebar" id="roleTooltip">
          <div class="tooltip-title">
            <span id="tooltipRoleName">🏢 <span class="highlight-employer">Modo Empleador</span></span>
          </div>
          <div class="tooltip-desc" id="tooltipDesc">Panel exclusivo para contratar talento</div>
        </div>
      </div>

      <div class="menu is-menu-main">
        <p class="menu-label">Panel</p>
        <ul class="menu-list">
          <li>
            <a href="/usuario/empleador" class="has-icon">
              <span class="icon"><i class="mdi mdi-desktop-mac"></i></span>
              <span class="menu-item-label">Inicio</span>
            </a>
          </li>
          <p class="menu-label">Acciones</p>
          <li>
            <a href="/usuario/buscar-talento" class="has-icon">
              <span class="icon"><i class="mdi mdi-magnify"></i></span>
              <span class="menu-item-label">Buscar trabajo</span>
            </a>
          </li>
          <li>
            <a href="/usuario/mis-vacantes" class="has-icon">
              <span class="icon"><i class="mdi mdi-briefcase"></i></span>
              <span class="menu-item-label">Mis vacantes</span>
            </a>
          </li>
          <li>
            <a href="/usuario/postulantes" class="is-active router-link-active has-icon">
              <span class="icon has-update-mark"><i class="mdi mdi-account-group"></i></span>
              <span class="menu-item-label">Postulantes</span>
            </a>
          </li>
        </ul>

        <ul class="menu-list">
          <li>
            <a href="/usuario/publicar-vacante" class="has-icon">
              <span class="icon"><i class="mdi mdi-plus-circle"></i></span>
              <span class="menu-item-label">Publicar vacante</span>
            </a>
          </li>
          <li>
            <a href="/usuario/profile" class="has-icon">
              <span class="icon"><i class="mdi mdi-account-circle"></i></span>
              <span class="menu-item-label">Perfil</span>
            </a>
          </li>
        </ul>
      </div>
    </aside>

    <!-- TITLE BAR -->
    <section class="section is-title-bar">
      <div class="level">
        <div class="level-right">
          <div class="level-item">
            <span class="has-text-grey" style="font-size: 14px;">
              <i class="mdi mdi-account-group"></i> {{ $statTotal }} postulantes
            </span>
          </div>
        </div>
      </div>
    </section>

    <!-- CONTENIDO -->
    <section class="section is-main-section">
      <div class="container">

        <div class="role-notice">
          <span class="notice-icon">🏢</span>
          <span>Estás en modo <strong class="notice-role employer">Empleador</strong> · Revisa los postulantes a tus vacantes.</span>
        </div>

        <!-- PESTAÑAS -->
        <div style="display:flex; gap:8px; margin-bottom:18px;">
          <button type="button" onclick="mostrarTabPost('recibidas')" id="tabPostRecibidas" class="tab-postulantes activa">
            <i class="mdi mdi-inbox-arrow-down"></i> Recibidas
          </button>
          <button type="button" onclick="mostrarTabPost('enviadas')" id="tabPostEnviadas" class="tab-postulantes">
            <i class="mdi mdi-send-outline"></i> Enviadas
          </button>
        </div>

        <style>
          .tab-postulantes {
            padding: 10px 20px;
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            background: #fff;
            color: #666;
            font-weight: 600;
            font-size: 13px;
            cursor: pointer;
            transition: .15s;
          }
          .tab-postulantes.activa {
            background: linear-gradient(45deg, #ff7a18, #ffb347);
            color: #fff;
            border-color: transparent;
          }
        </style>

        <div id="seccionPostRecibidas">

        <!-- ===== FILTROS ===== -->
        <form method="GET" action="/usuario/postulantes" class="filtro-postulantes">
          <div class="campo-filtro">
            <label for="filtroVacante">Vacante</label>
            <select name="vacante" id="filtroVacante">
              <option value="">Todas las vacantes</option>
              @foreach ($vacantes as $v)
                <option value="{{ $v->id }}" {{ (string) $vacanteId === (string) $v->id ? 'selected' : '' }}>{{ $v->titulo }}</option>
              @endforeach
            </select>
          </div>
          <div class="campo-filtro">
            <label for="filtroEstado">Estado</label>
            <select name="estado" id="filtroEstado">
              <option value="">Todos</option>
              <option value="pendiente" {{ $estado === 'pendiente' ? 'selected' : '' }}>Pendientes</option>
              <option value="contratado" {{ $estado === 'contratado' ? 'selected' : '' }}>Contratados</option>
              <option value="rechazado" {{ $estado === 'rechazado' ? 'selected' : '' }}>Rechazados</option>
            </select>
          </div>
          <div class="campo-filtro">
            <label for="filtroBusqueda">Buscar postulante</label>
            <input type="text" name="q" id="filtroBusqueda" value="{{ $q }}" placeholder="Nombre del postulante...">
          </div>
          <button type="submit" class="btn-filtrar"><i class="mdi mdi-magnify"></i> Buscar</button>
        </form>

        <!-- ===== ESTADÍSTICAS ===== -->
        <div class="vacante-stats">
          <div class="stat-item">
            <span class="stat-icon icon-total"><i class="mdi mdi-account-group"></i></span>
            <div class="stat-number">{{ $statTotal }}</div>
            <div class="stat-label">Total Postulantes</div>
          </div>
          <div class="stat-item">
            <span class="stat-icon icon-pendientes"><i class="mdi mdi-clock-outline"></i></span>
            <div class="stat-number">{{ $statPendientes }}</div>
            <div class="stat-label">Pendientes</div>
          </div>
          <div class="stat-item">
            <span class="stat-icon icon-contratados"><i class="mdi mdi-check-circle-outline"></i></span>
            <div class="stat-number">{{ $statContratados }}</div>
            <div class="stat-label">Contratados</div>
          </div>
          <div class="stat-item">
            <span class="stat-icon icon-rechazados"><i class="mdi mdi-close-circle-outline"></i></span>
            <div class="stat-number">{{ $statRechazados }}</div>
            <div class="stat-label">Rechazados</div>
          </div>
        </div>

        <!-- ===== GRID DE POSTULANTES ===== -->
        <div class="postulantes-grid">

          @forelse ($postulaciones as $p)
            @php
              $nombre = $p->postulante->nombre ?? 'Usuario eliminado';
              $iniciales = collect(explode(' ', $nombre))->map(fn($w) => mb_strtoupper(mb_substr($w, 0, 1)))->take(2)->implode('');
              $colores = ['#f57c00,#ffb74d', '#0d9488,#2dd4bf', '#d97706,#fbbf24', '#b91c1c,#f87171', '#7c3aed,#a78bfa', '#15803d,#86efac', '#ec4899,#f9a8d4'];
              $color = $colores[$p->id % count($colores)];
            @endphp
            <div class="postulante-card">
              <div class="postulante-header">
                <div class="postulante-avatar" style="background:linear-gradient(135deg,{{ $color }});">{{ $iniciales }}</div>
                <div style="flex:1;">
                  <div class="postulante-nombre">{{ $nombre }}</div>
                  <div class="postulante-cargo">{{ $p->vacante->titulo ?? 'Vacante eliminada' }}</div>
                </div>
                <span class="postulante-status status-{{ $p->estado }}">{{ ucfirst($p->estado) }}</span>
              </div>
              <div style="font-size:12px; color:#666; margin-bottom:6px;">
                📍 {{ $p->postulante->localidad->nombre ?? '—' }} · 🕐 {{ $p->created_at->diffForHumans() }}
              </div>
              @if ($p->mensaje)
                <div class="postulante-mensaje">💬 "{{ $p->mensaje }}"</div>
              @endif
              <div class="postulante-actions">
                @if ($p->estado === 'contratado')
                  <button class="btn-contratar" disabled style="opacity:0.5; cursor:not-allowed;"><i class="mdi mdi-check"></i> Contratado</button>
                  @php $telPostulante = $p->postulante->whatsapp ?? $p->postulante->telefono ?? null; @endphp
                  @if ($telPostulante)
                    <a href="https://wa.me/52{{ preg_replace('/\D/', '', $telPostulante) }}" target="_blank" style="flex:1; text-decoration:none; background:#25D366; color:#fff; padding:7px 10px; border-radius:8px; font-size:11px; font-weight:600; text-align:center; display:flex; align-items:center; justify-content:center; gap:4px;"><i class="mdi mdi-whatsapp"></i> WhatsApp</a>
                  @endif
                @else
                  <button class="btn-contratar" onclick="cambiarEstado({{ $p->id }}, 'contratado')"><i class="mdi mdi-check"></i> Contratar</button>
                @endif
                @if ($p->estado === 'rechazado')
                  <button class="btn-rechazar" disabled style="opacity:0.5; cursor:not-allowed;"><i class="mdi mdi-close"></i> Rechazado</button>
                @elseif ($p->estado !== 'contratado')
                  <button class="btn-rechazar" onclick="cambiarEstado({{ $p->id }}, 'rechazado')"><i class="mdi mdi-close"></i></button>
                @endif
              </div>
              <div class="postulante-meta">
                <span>📧 {{ $p->postulante->email ?? '—' }}</span>
                <span>📞 {{ $p->postulante->telefono ?? '—' }}</span>
              </div>
            </div>
          @empty
            <div class="sin-postulantes">
              <div class="icon"><i class="mdi mdi-account-group-outline"></i></div>
              <h3>No hay postulantes todavía</h3>
              <p>Cuando alguien se postule a tus vacantes, aparecerá aquí.</p>
            </div>
          @endforelse

        </div>

        </div>
        <!-- ===== SECCIÓN: ENVIADAS ===== -->
        <div id="seccionPostEnviadas" style="display:none;">
          <div class="postulantes-grid">
            @forelse ($misPostulaciones as $p)
              @php
                $nombreEmp = $p->vacante->empleador->nombre ?? 'Usuario eliminado';
                $inicialesEmp = collect(explode(' ', $nombreEmp))->map(fn($w) => mb_strtoupper(mb_substr($w, 0, 1)))->take(2)->implode('');
                $coloresP = ['#0d9488,#2dd4bf', '#7c3aed,#a78bfa', '#f57c00,#ffb74d', '#15803d,#86efac', '#b91c1c,#f87171', '#ec4899,#f9a8d4', '#d97706,#fbbf24'];
                $colorP = $coloresP[$p->id % count($coloresP)];
              @endphp
              <div class="postulante-card">
                <div class="postulante-header">
                  <div class="postulante-avatar" style="background:linear-gradient(135deg,{{ $colorP }});">{{ $inicialesEmp }}</div>
                  <div style="flex:1;">
                    <div class="postulante-nombre">{{ $nombreEmp }}</div>
                    <div class="postulante-cargo">{{ $p->vacante->titulo ?? 'Vacante eliminada' }}</div>
                  </div>
                  <span class="postulante-status status-{{ $p->estado }}">{{ ucfirst($p->estado) }}</span>
                </div>
                <div style="font-size:12px; color:#666; margin-bottom:6px;">
                  🕐 Postulado {{ $p->created_at->diffForHumans() }}
                </div>
                @if ($p->mensaje)
                  <div class="postulante-mensaje">💬 "{{ $p->mensaje }}"</div>
                @endif
                @if ($p->estado === 'pendiente')
                  <p style="font-size:12px; color:#e37400; margin-top:8px;"><i class="mdi mdi-clock-outline"></i> Esperando respuesta del empleador.</p>
                @elseif ($p->estado === 'contratado')
                  <p style="font-size:12px; color:#16a34a; margin-top:8px;"><i class="mdi mdi-check-circle"></i> ¡Te contrataron! Contacta al empleador para coordinar.</p>
                  @php $telEmpleo = $p->vacante->whatsapp ?? $p->vacante->telefono ?? null; @endphp
                  @if ($telEmpleo)
                    <div style="display:flex; gap:6px; margin-top:8px;">
                      <a href="https://wa.me/52{{ preg_replace('/\D/', '', $telEmpleo) }}" target="_blank" style="flex:1; text-decoration:none; background:#25D366; color:#fff; padding:7px 10px; border-radius:8px; font-size:11px; font-weight:600; text-align:center; display:flex; align-items:center; justify-content:center; gap:4px;"><i class="mdi mdi-whatsapp"></i> WhatsApp</a>
                      <a href="tel:{{ $p->vacante->telefono }}" style="flex:1; text-decoration:none; background:#e8f0fe; color:#1a73e8; padding:7px 10px; border-radius:8px; font-size:11px; font-weight:600; text-align:center; display:flex; align-items:center; justify-content:center; gap:4px;"><i class="mdi mdi-phone"></i> Llamar</a>
                    </div>
                  @endif
                @else
                  <p style="font-size:12px; color:#dc2626; margin-top:8px;"><i class="mdi mdi-close-circle"></i> Esta postulación fue rechazada.</p>
                @endif
              </div>
            @empty
              <div class="sin-postulantes">
                <div class="icon"><i class="mdi mdi-send-outline"></i></div>
                <h3>No te has postulado a ninguna vacante todavía</h3>
                <p>Cuando te postules a un trabajo, va a aparecer aquí.</p>
              </div>
            @endforelse
          </div>
        </div>

      </div>
    </section>

    <div class="container my-4 d-none d-md-block">
      <div class="banner-negocio">
        <div class="banner-texto">
          <h2>¿TIENES UN NEGOCIO?</h2>
          <p>Promociona tu negocio y llega a más clientes en tu municipio</p>
        </div>
        <div class="banner-icono">
          <img src="{{ asset('img/anuncios/tienda.png') }}" alt="Tienda">
        </div>
        <div class="banner-destaca">
          <h2>DESTACA TU NEGOCIO</h2>
          <h1>EN ¡CONECTAYA!</h1>
        </div>
        <div class="banner-boton">
          <a href="/anunciar" class="btn-anunciar">¡ANÚNCIATE AQUÍ!</a>
          <small>Más información →</small>
        </div>
        <div class="banner-persona">
          <img src="{{ asset('img/anuncios/persona.png') }}" alt="Anunciante">
        </div>
      </div>
    </div>

    <!-- FOOTER -->
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
  <script>
    (function() {
      const labelWorker = document.getElementById('labelWorker');
      const labelEmployer = document.getElementById('labelEmployer');
      const activeBg = document.getElementById('activeBg');
      const tooltip = document.getElementById('roleTooltip');
      let isWorker = false;
      let tooltipTimeout;

      function showTooltip() {
        clearTimeout(tooltipTimeout);
        tooltip.classList.add('show');
        tooltipTimeout = setTimeout(() => {
          tooltip.classList.remove('show');
        }, 2500);
      }

      function updateUI() {
        labelWorker.classList.toggle('active', isWorker);
        labelEmployer.classList.toggle('active', !isWorker);
        activeBg.className = 'active-bg ' + (isWorker ? 'worker-bg' : 'employer-bg');
        if (isWorker) {
          document.getElementById('tooltipRoleName').innerHTML = '👷 <span class="highlight-worker">Modo Trabajador</span>';
          document.getElementById('tooltipDesc').textContent = 'Publica y gestiona tus servicios';
        } else {
          document.getElementById('tooltipRoleName').innerHTML = '🏢 <span class="highlight-employer">Modo Empleador</span>';
          document.getElementById('tooltipDesc').textContent = 'Panel exclusivo para contratar talento';
        }
      }

      function toggleRole(e) {
        e.stopPropagation();
        isWorker = !isWorker;
        updateUI();
        showTooltip();
        localStorage.setItem('userRole', isWorker ? 'worker' : 'employer');
        if (isWorker) window.location.href = '/usuario';
      }

      labelWorker.addEventListener('click', function(e) { if (!isWorker) toggleRole(e); });
      labelEmployer.addEventListener('click', function(e) { if (isWorker) toggleRole(e); });

      const switchRow = document.querySelector('.switch-row');
      switchRow.addEventListener('mouseenter', function() { tooltip.classList.add('show'); });
      switchRow.addEventListener('mouseleave', function() {
        clearTimeout(tooltipTimeout);
        setTimeout(() => tooltip.classList.remove('show'), 200);
      });

      const savedRole = localStorage.getItem('userRole');
      if (savedRole === 'worker') {
        isWorker = true;
        window.location.href = '/usuario';
      }
      updateUI();
    })();

    // ===== Aceptar / Rechazar postulante (guarda de verdad en la base de datos) =====
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    function mostrarTabPost(tab) {
      document.getElementById('seccionPostRecibidas').style.display = tab === 'recibidas' ? '' : 'none';
      document.getElementById('seccionPostEnviadas').style.display = tab === 'enviadas' ? '' : 'none';
      document.getElementById('tabPostRecibidas').classList.toggle('activa', tab === 'recibidas');
      document.getElementById('tabPostEnviadas').classList.toggle('activa', tab === 'enviadas');
    }

    function cambiarEstado(id, estado) {
      const mensaje = estado === 'contratado' ? '¿Contratar a este postulante?' : '¿Rechazar a este postulante?';
      if (!confirm(mensaje)) return;

      fetch(`/usuario/postulantes/${id}`, {
        method: 'PATCH',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
        body: JSON.stringify({ estado: estado }),
      })
        .then(res => { if (!res.ok) throw new Error(); location.reload(); })
        .catch(() => alert('Ocurrió un error al actualizar al postulante.'));
    }
  </script>

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
</body>

</html>
