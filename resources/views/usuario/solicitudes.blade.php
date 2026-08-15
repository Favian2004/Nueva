<!DOCTYPE html>
<html lang="es" class="has-aside-left has-aside-mobile-transition has-navbar-fixed-top has-aside-expanded">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Solicitudes · Dashboard</title>

  <link rel="stylesheet" href="{{ asset('assets/usuario/css/main.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/usuario/css/styles.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/usuario/css/switch.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.9.95/css/materialdesignicons.min.css">
  <link rel="icon" type="img/" href="{{ asset('assets/usuario/img/icono.png') }}">

  <style>
    .filtro-solicitudes {
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
      margin-bottom: 20px;
      background: #fff;
      padding: 14px 18px;
      border-radius: 12px;
      border: 1px solid #e8eaed;
    }
    .filtro-solicitudes select {
      padding: 8px 14px;
      border-radius: 10px;
      border: 1.5px solid #e5e7eb;
      font-size: 13px;
      outline: none;
      background: #fff;
    }
    .filtro-solicitudes .btn-filtrar {
      padding: 8px 20px;
      border: none;
      border-radius: 10px;
      background: linear-gradient(45deg, #ff7a18, #ffb347);
      color: #fff;
      font-weight: 600;
      font-size: 13px;
      cursor: pointer;
    }

    .solicitudes-stats {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 12px;
      margin-bottom: 20px;
    }
    .solicitudes-stats .stat-item {
      background: #fff;
      border-radius: 10px;
      padding: 12px 16px;
      text-align: center;
      border: 1px solid #e8eaed;
    }
    .solicitudes-stats .stat-item .stat-number { font-size: 22px; font-weight: 800; color: #1a1a2e; line-height: 1.2; }
    .solicitudes-stats .stat-item .stat-label { font-size: 11px; color: #5f6368; margin-top: 2px; }
    .solicitudes-stats .stat-item .stat-icon {
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
    .stat-icon.icon-aceptadas { background: #e6f4ea; color: #1e7e34; }
    .stat-icon.icon-finalizadas { background: #e8f0fe; color: #1a73e8; }

    .solicitudes-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 16px;
    }
    .solicitud-card {
      background: #fff;
      border-radius: 14px;
      padding: 18px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
      border: 1px solid #f0f0f0;
      transition: 0.3s;
    }
    .solicitud-card:hover { transform: translateY(-4px); box-shadow: 0 8px 25px rgba(0, 0, 0, 0.10); border-color: #ffb347; }
    .solicitud-header { display: flex; align-items: center; gap: 14px; margin-bottom: 10px; }
    .solicitud-avatar {
      width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center;
      justify-content: center; font-size: 18px; font-weight: 700; color: #fff; flex-shrink: 0;
    }
    .solicitud-nombre { font-weight: 700; font-size: 15px; color: #1a1a2e; }
    .solicitud-servicio { font-size: 12px; color: #666; }
    .solicitud-status { font-size: 10px; font-weight: 600; padding: 2px 12px; border-radius: 20px; text-transform: uppercase; letter-spacing: .3px; display: inline-block; }
    .status-pendiente { background: #fef7e0; color: #e37400; }
    .status-aceptado { background: #e6f4ea; color: #1e7e34; }
    .status-finalizado { background: #e8f0fe; color: #1a73e8; }
    .status-cancelado { background: #fce8e6; color: #d93025; }

    .solicitud-actions { display: flex; gap: 6px; margin-top: 12px; flex-wrap: wrap; }
    .solicitud-actions button, .solicitud-actions a {
      flex: 1; min-width: 45%; border: none; padding: 7px 10px; border-radius: 8px; font-weight: 600;
      font-size: 11px; cursor: pointer; transition: .2s; display: flex; align-items: center; justify-content: center; gap: 4px;
    }
    .btn-aceptar { background: #dcfce7; color: #16a34a; }
    .btn-aceptar:hover { background: #bbf7d0; }
    .btn-cancelar { background: #fee2e2; color: #dc2626; }
    .btn-cancelar:hover { background: #fecaca; }
    .btn-finalizar { background: #dbeafe; color: #2563eb; }
    .btn-finalizar:hover { background: #bfdbfe; }

    .sin-solicitudes {
      text-align: center; padding: 40px 20px; background: #fff; border-radius: 12px; border: 1px solid #e8eaed; grid-column: 1 / -1;
    }
    .sin-solicitudes .icon { font-size: 48px; color: #ccc; margin-bottom: 12px; }

    @media (max-width: 992px) { .solicitudes-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 768px) {
      .solicitudes-grid { grid-template-columns: 1fr; }
      .solicitudes-stats { grid-template-columns: repeat(2, 1fr); gap: 8px; }
      .filtro-solicitudes { flex-direction: column; }
      .filtro-solicitudes select, .filtro-solicitudes .btn-filtrar { width: 100%; }
    }
  </style>
  <link rel="stylesheet" href="{{ asset('assets/usuario/css/ads-widget.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/usuario/css/theme-conectaya.css') }}">



  <style>
    /* ===== BANNER "¿TIENES UN NEGOCIO?" ===== */
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
    .banner-texto h2 {
      font-size: 24px;
      margin: 0;
    }
    .banner-texto p {
      margin: 0;
      font-size: 14px;
    }
    .banner-destaca h2 {
      margin: 0;
      font-size: 22px;
    }
    .banner-destaca h1 {
      margin: 0;
      font-size: 32px;
      color: #ffcf33;
      font-weight: bold;
    }
    .banner-icono img {
      width: 70px;
    }
    .btn-anunciar {
      background: #ffc107;
      color: #000;
      text-decoration: none;
      padding: 10px 18px;
      border-radius: 8px;
      font-weight: bold;
      display: inline-block;
    }
    .banner-boton small {
      display: block;
      text-align: center;
      margin-top: 3px;
      color: #fff;
    }
    .banner-persona {
      height: 90px;
    }
    .banner-persona img {
      height: 90px;
      width: auto;
      object-fit: cover;
      border-radius: 999px;
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
            <span class="crumb-page">Solicitudes</span>
          </div>
        </div>
        <div class="navbar-end">
          <a title="Cerrar sesión" href="#" id="logoutBtn" class="navbar-item is-desktop-icon-only">
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
          <span><b> Modo Empleado</b></span>
        </div>
      </div>

      <div class="sidebar-role-switch">
        <div class="switch-row" id="switchRow">
          <div class="active-bg worker-bg" id="activeBg"></div>
          <span class="role-text worker-text active" id="labelWorker">TRABAJADOR</span>
          <span class="role-text employer-text" id="labelEmployer">EMPLEADOR</span>
        </div>
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
          <li><a href="/usuario" class="has-icon"><span class="icon"><i class="mdi mdi-desktop-mac"></i></span><span class="menu-item-label">Inicio</span></a></li>
        </ul>
        <p class="menu-label">Otros</p>
        <ul class="menu-list">
          <li id="sidebarVerEmpleos"><a href="/usuario/verEmpleos" class="has-icon"><span class="icon"><i class="mdi mdi-briefcase"></i></span><span class="menu-item-label">Ver empleos</span></a></li>
          <li id="sidebarPublicar"><a href="/usuario/publicarEmpleo" class="has-icon"><span class="icon"><i class="mdi mdi-square-edit-outline"></i></span><span class="menu-item-label">Publicar empleo</span></a></li>
          <li id="sidebarMisEmpleos"><a href="/usuario/misEmpleos" class="has-icon"><span class="icon"><i class="mdi mdi-format-list-bulleted"></i></span><span class="menu-item-label">Mis empleos</span></a></li>
          <li id="sidebarSolicitudes"><a href="/usuario/solicitudes" class="is-active router-link-active has-icon"><span class="icon has-update-mark"><i class="mdi mdi-account-clock"></i></span><span class="menu-item-label">Solicitudes</span></a></li>
          <li id="sidebarBuscarTalento" style="display:none;"><a href="/usuario/buscar-talento" class="has-icon"><span class="icon"><i class="mdi mdi-magnify"></i></span><span class="menu-item-label">Buscar trabajo</span></a></li>
          <li id="sidebarMisVacantes" style="display:none;"><a href="/usuario/mis-vacantes" class="has-icon"><span class="icon"><i class="mdi mdi-briefcase-outline"></i></span><span class="menu-item-label">Mis vacantes</span></a></li>
          <li id="sidebarPostulantes" style="display:none;"><a href="/usuario/postulantes" class="has-icon"><span class="icon"><i class="mdi mdi-account-group"></i></span><span class="menu-item-label">Postulantes</span></a></li>
          <li id="sidebarPublicarVacante" style="display:none;"><a href="/usuario/publicar-vacante" class="has-icon"><span class="icon"><i class="mdi mdi-plus-circle"></i></span><span class="menu-item-label">Publicar vacante</span></a></li>
          <li><a href="/usuario/profile" class="has-icon"><span class="icon"><i class="mdi mdi-account-circle"></i></span><span class="menu-item-label">Perfil</span></a></li>
        </ul>
      </div>
    </aside>

    <!-- CONTENIDO -->
    <section class="section is-main-section">
      <div class="container">

        <div class="role-notice" id="roleNotice">
          <span class="notice-icon">👷</span>
          <span>Estás en modo <strong class="notice-role worker" id="roleNameDisplay">Trabajador</strong> · Aquí puedes ver quién solicitó tus servicios.</span>
        </div>

        <!-- PESTAÑAS -->
        <div style="display:flex; gap:8px; margin-bottom:18px;">
          <button type="button" onclick="mostrarTab('recibidas')" id="tabRecibidas" class="tab-solicitudes activa">
            <i class="mdi mdi-inbox-arrow-down"></i> Recibidas
          </button>
          <button type="button" onclick="mostrarTab('enviadas')" id="tabEnviadas" class="tab-solicitudes">
            <i class="mdi mdi-send-outline"></i> Enviadas
          </button>
        </div>

        <style>
          .tab-solicitudes {
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
          .tab-solicitudes.activa {
            background: linear-gradient(45deg, #ff7a18, #ffb347);
            color: #fff;
            border-color: transparent;
          }
        </style>

        <div id="seccionRecibidas">

        <!-- FILTROS -->
        <form method="GET" action="/usuario/solicitudes" class="filtro-solicitudes">
          <select name="estado" onchange="this.form.submit()">
            <option value="">Todos los estados</option>
            <option value="pendiente" {{ $estado === 'pendiente' ? 'selected' : '' }}>Pendientes</option>
            <option value="aceptado" {{ $estado === 'aceptado' ? 'selected' : '' }}>Aceptadas</option>
            <option value="finalizado" {{ $estado === 'finalizado' ? 'selected' : '' }}>Finalizadas</option>
            <option value="cancelado" {{ $estado === 'cancelado' ? 'selected' : '' }}>Canceladas</option>
          </select>
        </form>

        <!-- ESTADÍSTICAS -->
        <div class="solicitudes-stats">
          <div class="stat-item">
            <span class="stat-icon icon-total"><i class="mdi mdi-format-list-bulleted"></i></span>
            <div class="stat-number">{{ $statTotal }}</div>
            <div class="stat-label">Total</div>
          </div>
          <div class="stat-item">
            <span class="stat-icon icon-pendientes"><i class="mdi mdi-clock-outline"></i></span>
            <div class="stat-number">{{ $statPendientes }}</div>
            <div class="stat-label">Pendientes</div>
          </div>
          <div class="stat-item">
            <span class="stat-icon icon-aceptadas"><i class="mdi mdi-check-circle-outline"></i></span>
            <div class="stat-number">{{ $statAceptadas }}</div>
            <div class="stat-label">Aceptadas</div>
          </div>
          <div class="stat-item">
            <span class="stat-icon icon-finalizadas"><i class="mdi mdi-flag-checkered"></i></span>
            <div class="stat-number">{{ $statFinalizadas }}</div>
            <div class="stat-label">Finalizadas</div>
          </div>
        </div>

        <div style="background:#fff7ef; border:1px solid #ffd8ae; border-radius:12px; padding:14px 18px; margin-bottom:20px; display:flex; align-items:center; gap:12px;">
          <i class="mdi mdi-lightbulb-on-outline" style="font-size:1.4rem; color:#ff7a18; flex-shrink:0;"></i>
          <p style="font-size:.85rem; color:#7a5a3a; margin:0;">
            <strong>Antes de aceptar:</strong> comunícate con el trabajador para ponerse de acuerdo en fecha, horario y detalles del servicio, y confirmar que podrá asistir.
          </p>
        </div>

        <!-- GRID DE SOLICITUDES -->
        <div class="solicitudes-grid">

          @forelse ($solicitudes as $s)
            @php
              $nombre = $s->contratante->nombre ?? 'Usuario eliminado';
              $iniciales = collect(explode(' ', $nombre))->map(fn($w) => mb_strtoupper(mb_substr($w, 0, 1)))->take(2)->implode('');
              $colores = ['#f57c00,#ffb74d', '#0d9488,#2dd4bf', '#d97706,#fbbf24', '#b91c1c,#f87171', '#7c3aed,#a78bfa', '#15803d,#86efac', '#ec4899,#f9a8d4'];
              $color = $colores[$s->id % count($colores)];
            @endphp
            <div class="solicitud-card">
              <div class="solicitud-header">
                <div class="solicitud-avatar" style="background:linear-gradient(135deg,{{ $color }});">{{ $iniciales }}</div>
                <div style="flex:1;">
                  <div class="solicitud-nombre">{{ $nombre }}</div>
                  <div class="solicitud-servicio">{{ $s->servicio->titulo ?? 'Servicio eliminado' }}</div>
                </div>
                <span class="solicitud-status status-{{ $s->estado }}">{{ ucfirst($s->estado) }}</span>
              </div>
              <div style="font-size:12px; color:#666; margin-bottom:6px;">
                🕐 {{ $s->created_at->diffForHumans() }}
              </div>
              <div style="font-size:12px; color:#666;">
                📧 {{ $s->contratante->email ?? '—' }}<br>
                📞 {{ $s->contratante->telefono ?? '—' }}
              </div>

              <div class="solicitud-actions">
                @if ($s->estado === 'pendiente')
                  <button class="btn-aceptar" onclick="cambiarEstado({{ $s->id }}, 'aceptado')"><i class="mdi mdi-check"></i> Aceptar</button>
                  <button class="btn-cancelar" onclick="cambiarEstado({{ $s->id }}, 'cancelado')"><i class="mdi mdi-close"></i> Rechazar</button>
                @elseif ($s->estado === 'aceptado')
                  @php $telContratante = $s->contratante->whatsapp ?? $s->contratante->telefono ?? null; @endphp
                  @if ($telContratante)
                    <a href="https://wa.me/52{{ preg_replace('/\D/', '', $telContratante) }}" target="_blank" style="flex:1; text-decoration:none; background:#25D366; color:#fff; padding:7px 10px; border-radius:8px; font-size:11px; font-weight:600; text-align:center; display:flex; align-items:center; justify-content:center; gap:4px;"><i class="mdi mdi-whatsapp"></i> WhatsApp</a>
                    <a href="tel:{{ $s->contratante->telefono }}" style="flex:1; text-decoration:none; background:#e8f0fe; color:#1a73e8; padding:7px 10px; border-radius:8px; font-size:11px; font-weight:600; text-align:center; display:flex; align-items:center; justify-content:center; gap:4px;"><i class="mdi mdi-phone"></i> Llamar</a>
                  @endif
                  <button class="btn-finalizar" onclick="cambiarEstado({{ $s->id }}, 'finalizado')"><i class="mdi mdi-flag-checkered"></i> Marcar finalizado</button>
                  <button class="btn-cancelar" onclick="cambiarEstado({{ $s->id }}, 'cancelado')"><i class="mdi mdi-close"></i> Cancelar</button>
                @else
                  <button disabled style="opacity:.5; cursor:default; flex:1; border:none; padding:7px 10px; border-radius:8px; font-size:11px; background:#f1f3f4; color:#5f6368;">
                    {{ $s->estado === 'finalizado' ? '🏁 Finalizado' : '❌ Cancelado' }}
                  </button>
                @endif
              </div>
            </div>
          @empty
            <div class="sin-solicitudes">
              <div class="icon"><i class="mdi mdi-account-clock-outline"></i></div>
              <h3>No hay solicitudes todavía</h3>
              <p>Cuando alguien solicite uno de tus servicios, va a aparecer aquí.</p>
            </div>
          @endforelse

        </div>
        </div>
        <!-- ===== SECCIÓN: ENVIADAS ===== -->
        <div id="seccionEnviadas" style="display:none;">
          <div class="solicitudes-grid">
            @forelse ($enviadas as $s)
              @php
                $nombreTrab = $s->trabajador->nombre ?? 'Usuario eliminado';
                $inicialesTrab = collect(explode(' ', $nombreTrab))->map(fn($w) => mb_strtoupper(mb_substr($w, 0, 1)))->take(2)->implode('');
                $coloresE = ['#0d9488,#2dd4bf', '#7c3aed,#a78bfa', '#f57c00,#ffb74d', '#15803d,#86efac', '#b91c1c,#f87171', '#ec4899,#f9a8d4', '#d97706,#fbbf24'];
                $colorE = $coloresE[$s->id % count($coloresE)];
              @endphp
              <div class="solicitud-card">
                <div class="solicitud-header">
                  <div class="solicitud-avatar" style="background:linear-gradient(135deg,{{ $colorE }});">{{ $inicialesTrab }}</div>
                  <div style="flex:1;">
                    <div class="solicitud-nombre">{{ $nombreTrab }}</div>
                    <div class="solicitud-servicio">{{ $s->servicio->titulo ?? 'Servicio eliminado' }}</div>
                  </div>
                  <span class="solicitud-status status-{{ $s->estado }}">{{ ucfirst($s->estado) }}</span>
                </div>
                <div style="font-size:12px; color:#666; margin-bottom:6px;">
                  🕐 Solicitado {{ $s->created_at->diffForHumans() }}
                </div>
                @if ($s->estado === 'pendiente')
                  <p style="font-size:12px; color:#e37400; margin-top:8px;"><i class="mdi mdi-clock-outline"></i> Esperando respuesta del trabajador.</p>
                @elseif ($s->estado === 'aceptado')
                  <p style="font-size:12px; color:#16a34a; margin-top:8px;"><i class="mdi mdi-check-circle"></i> ¡Aceptado! Contacta al trabajador para coordinar.</p>
                  @php $telTrabajador = $s->trabajador->whatsapp ?? $s->trabajador->telefono ?? null; @endphp
                  @if ($telTrabajador)
                    <div style="display:flex; gap:6px; margin-top:8px;">
                      <a href="https://wa.me/52{{ preg_replace('/\D/', '', $telTrabajador) }}" target="_blank" style="flex:1; text-decoration:none; background:#25D366; color:#fff; padding:7px 10px; border-radius:8px; font-size:11px; font-weight:600; text-align:center; display:flex; align-items:center; justify-content:center; gap:4px;"><i class="mdi mdi-whatsapp"></i> WhatsApp</a>
                      <a href="tel:{{ $s->trabajador->telefono }}" style="flex:1; text-decoration:none; background:#e8f0fe; color:#1a73e8; padding:7px 10px; border-radius:8px; font-size:11px; font-weight:600; text-align:center; display:flex; align-items:center; justify-content:center; gap:4px;"><i class="mdi mdi-phone"></i> Llamar</a>
                    </div>
                  @endif
                @elseif ($s->estado === 'finalizado')
                  <p style="font-size:12px; color:#2563eb; margin-top:8px;"><i class="mdi mdi-flag-checkered"></i> Este trabajo ya se marcó como finalizado.</p>
                @else
                  <p style="font-size:12px; color:#dc2626; margin-top:8px;"><i class="mdi mdi-close-circle"></i> Esta solicitud fue cancelada.</p>
                @endif
              </div>
            @empty
              <div class="sin-solicitudes">
                <div class="icon"><i class="mdi mdi-send-outline"></i></div>
                <h3>No has solicitado ningún servicio todavía</h3>
                <p>Cuando pidas un servicio desde "Ver empleos", va a aparecer aquí.</p>
              </div>
            @endforelse
          </div>
        </div>

      </div>
    </section>

    <!-- ========================= -->
    <!-- ANUNCIOS INFERIOR -->
    <!-- ========================= -->

    <div class="container mb-4 d-none d-md-block" style="margin-top: 250px;">

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
          <a href="/anunciar" class="btn-anunciar">
            ¡ANÚNCIATE AQUÍ!
          </a>
          <small>Más información →</small>
        </div>

        <div class="banner-persona">
          <img src="{{ asset('img/anuncios/persona.png') }}" alt="Anunciante">
        </div>

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
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    function mostrarTab(tab) {
      document.getElementById('seccionRecibidas').style.display = tab === 'recibidas' ? '' : 'none';
      document.getElementById('seccionEnviadas').style.display = tab === 'enviadas' ? '' : 'none';
      document.getElementById('tabRecibidas').classList.toggle('activa', tab === 'recibidas');
      document.getElementById('tabEnviadas').classList.toggle('activa', tab === 'enviadas');
    }

    function cambiarEstado(id, estado) {
      const mensajes = {
        aceptado: '¿Aceptar esta solicitud?',
        cancelado: '¿Rechazar/cancelar esta solicitud?',
        finalizado: '¿Marcar este trabajo como finalizado?',
      };
      if (!confirm(mensajes[estado] || '¿Confirmar cambio?')) return;

      fetch(`/usuario/solicitudes/${id}`, {
        method: 'PATCH',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
        body: JSON.stringify({ estado: estado }),
      })
        .then(res => { if (!res.ok) throw new Error(); location.reload(); })
        .catch(() => alert('Ocurrió un error al actualizar la solicitud.'));
    }
  </script>

  <script>
    (function () {
      const logoutBtn = document.getElementById('logoutBtn');
      if (!logoutBtn) return;
      logoutBtn.addEventListener('click', function (e) {
        e.preventDefault();
        if (!confirm('¿Seguro que quieres cerrar sesión?')) return;
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        const csrfTokenLogout = csrfMeta ? csrfMeta.content : '';
        fetch('/logout', {
          method: 'POST',
          headers: { 'X-CSRF-TOKEN': csrfTokenLogout, 'Accept': 'application/json' },
        }).finally(() => { window.location.href = '/'; });
      });
    })();
  </script>

  <script src="{{ asset('assets/usuario/js/ads-widget.js') }}"></script>
</body>

</html>
