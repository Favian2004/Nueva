<!DOCTYPE html>
<!-- profile.html - Perfil profesional mejorado -->
<html lang="es" class="has-aside-left has-aside-mobile-transition has-navbar-fixed-top has-aside-expanded">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Mi Perfil · Dashboard de Servicios</title>

  <!-- CSS Base -->
  <link rel="stylesheet" href="{{ asset('assets/usuario/css/main.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/usuario/css/styles.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/usuario/css/switch.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.9.95/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="icon" type="img/" href="{{ asset('assets/usuario/img/icono.png') }}">

  <link rel="stylesheet" href="{{ asset('assets/usuario/css/ads-widget.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/usuario/css/theme-conectaya.css') }}">
  <link rel="stylesheet" href="{{ asset('css/anuncio-negocio.css') }}">
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

  <!-- SIDEBAR (con el ítem "Perfil" activo) -->
  <aside class="aside is-placed-left is-expanded">
    <div class="aside-tools">
      <span><b id="sidebarTitulo" data-txt-trab=" Perfil General" data-txt-emp=" Modo Empleador"> Perfil General</b></span>
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
      <p class="menu-label" id="sidebarLabelGeneral" data-txt-trab="General" data-txt-emp="Panel">General</p>
      <ul class="menu-list">
        <li id="sidebarInicio" data-href-trab="/usuario" data-href-emp="/usuario/empleador"><a href="/usuario" class="has-icon"><span class="icon"><i class="mdi mdi-desktop-mac"></i></span><span class="menu-item-label">Inicio</span></a></li>
      </ul>
      <p class="menu-label" id="sidebarLabelOtros" data-txt-trab="Otros" data-txt-emp="Acciones">Otros</p>
      <ul class="menu-list">
        <li id="sidebarVerEmpleos"><a href="/usuario/verEmpleos" class="has-icon"><span class="icon"><i class="mdi mdi-briefcase"></i></span><span class="menu-item-label">Contratar un servicio</span></a></li>
        <li id="sidebarPublicar"><a href="/usuario/publicarEmpleo" class="has-icon"><span class="icon"><i class="mdi mdi-square-edit-outline"></i></span><span class="menu-item-label">Ofrecer mi servicio</span></a></li>
        <li id="sidebarMisEmpleos"><a href="/usuario/misEmpleos" class="has-icon"><span class="icon"><i class="mdi mdi-format-list-bulleted"></i></span><span class="menu-item-label">Mis servicios</span></a></li>
        <li id="sidebarSolicitudes"><a href="/usuario/solicitudes" class="has-icon"><span class="icon"><i class="mdi mdi-account-clock"></i></span><span class="menu-item-label">Solicitudes</span></a></li>
        <li id="sidebarBuscarTalento" style="display:none;"><a href="/usuario/buscar-talento" class="has-icon"><span class="icon"><i class="mdi mdi-magnify"></i></span><span class="menu-item-label">Buscar trabajo</span></a></li>
        <li id="sidebarMisVacantes" style="display:none;"><a href="/usuario/mis-vacantes" class="has-icon"><span class="icon"><i class="mdi mdi-briefcase"></i></span><span class="menu-item-label">Mis vacantes</span></a></li>
        <li id="sidebarPostulantes" style="display:none;"><a href="/usuario/postulantes" class="has-icon"><span class="icon"><i class="mdi mdi-account-group"></i></span><span class="menu-item-label">Postulantes</span></a></li>
        <li id="sidebarPublicarVacante" style="display:none;"><a href="/usuario/publicar-vacante" class="has-icon"><span class="icon"><i class="mdi mdi-plus-circle"></i></span><span class="menu-item-label">Publicar vacante</span></a></li>
        <li id="sidebarPronostico" style="display:none;"><a href="/usuario/pronostico" class="has-icon"><span class="icon"><i class="mdi mdi-chart-line"></i></span><span class="menu-item-label">Pronóstico</span></a></li>
        <li><a href="/usuario/profile" class="is-active router-link-active has-icon"><span class="icon has-update-mark"><i class="mdi mdi-account-circle"></i></span><span class="menu-item-label">Perfil</span></a></li>
      </ul>
    </div>
  </aside>

  <!-- CONTENIDO PRINCIPAL MEJORADO -->
  <section class="section is-main-section">
    <div class="container">


      <!-- Toast de notificación -->
      <div id="profileToast" class="notification is-success" style="display: none; position: fixed; top: 70px; right: 20px; z-index: 1000; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);"></div>

      @if (session('status'))
        <div class="notification is-warning" style="border-radius: 14px; background: #fff7e6; border: 1.5px solid #ffd8a8; color: #8a5a00; display: flex; align-items: center; gap: 10px; margin-bottom: 20px;">
          <i class="mdi mdi-clipboard-text-clock mdi-24px"></i>
          <span><strong>{{ session('status') }}</strong> Entre más completa esté tu información, más confianza generas con quienes vean tu perfil.</span>
        </div>
      @endif

      <!-- Estadísticas rápidas (más pro) -->
      <div class="columns">
        <div class="column">
          <div class="stats-card">
            <span class="icon is-large"><i class="mdi mdi-briefcase mdi-36px" style="color:#ff7a18"></i></span>
            <div class="stat-number">{{ $serviciosPublicados }}</div>
            <p class="has-text-grey">Servicios publicados</p>
          </div>
        </div>
        <div class="column">
          <div class="stats-card">
            <span class="icon is-large"><i class="mdi mdi-star mdi-36px" style="color:#ffb347"></i></span>
            <div class="stat-number">{{ $calificacionPromedio ?? '—' }}</div>
            <p class="has-text-grey">Calificación promedio{{ $totalCalificaciones ? " ({$totalCalificaciones} " . ($totalCalificaciones === 1 ? 'reseña' : 'reseñas') . ")" : '' }}</p>
          </div>
        </div>
        <div class="column">
          <div class="stats-card">
            <span class="icon is-large"><i class="mdi mdi-account-group mdi-36px" style="color:#ff7a18"></i></span>
            <div class="stat-number">{{ $clientesAtendidos }}</div>
            <p class="has-text-grey">Clientes atendidos</p>
          </div>
        </div>
      </div>

      <!-- TABS para organizar la información -->
      <div class="tabs is-boxed is-centered" id="profileTabs">
        <ul>
          <li class="is-active" data-tab="tab-info"><a><span class="icon"><i class="mdi mdi-account"></i></span> Información personal</a></li>
          <li data-tab="tab-stats"><a><span class="icon"><i class="mdi mdi-chart-line"></i></span> Estadísticas</a></li>
          <li data-tab="tab-docs"><a><span class="icon"><i class="mdi mdi-folder-account"></i></span> Documentos</a></li>
        </ul>
      </div>

      <!-- PANEL 1: Información personal (editable, con datos reales) -->
      <div id="tab-info" class="tab-content" style="display: block;">
        <div class="tile is-ancestor">
          <!-- Columna izquierda: Avatar + estado -->
          <div class="tile is-parent is-4">
            <div class="card tile is-child">
              <div class="card-content has-text-centered">
                <div class="profile-avatar-large" id="avatarCircle"
                  @if ($usuario->foto_perfil) style="background-image:url('{{ $usuario->foto_perfil }}'); background-size:cover; background-position:center;" @endif>
                  @if (!$usuario->foto_perfil)
                    {{ collect(explode(' ', $usuario->nombre))->map(fn($w) => mb_strtoupper(mb_substr($w, 0, 1)))->take(2)->implode('') }}
                  @endif
                </div>
                <div class="mt-3">
                  <span id="verificationBadge" class="verification-badge {{ $usuario->verificacion_estado === 'aprobado' ? 'aprobado' : 'pending' }}">
                    <i class="mdi mdi-shield-check"></i>
                    {{ $usuario->verificacion_estado === 'aprobado' ? 'Cuenta verificada' : 'Verificación pendiente' }}
                  </span>
                </div>
                <div class="mt-3">
                  <button id="uploadPhotoBtn" class="button is-small btn-outline-orange"><i class="mdi mdi-camera"></i> Cambiar foto</button>
                  <input type="file" id="photoInput" accept="image/*" style="display: none;">
                </div>
                <hr>
                <div class="info-row"><i class="mdi mdi-email"></i> <span id="displayEmailStatic">{{ $usuario->email }}</span></div>
                <div class="info-row"><i class="mdi mdi-phone"></i> <span id="displayPhoneStatic">{{ $usuario->telefono ?? 'Sin teléfono' }}</span></div>
                <div class="info-row"><i class="mdi mdi-map-marker"></i> <span id="displayMunicipioStatic">{{ $usuario->localidad->nombre ?? '—' }}</span></div>
                <div class="info-row"><i class="mdi mdi-calendar"></i> Miembro desde: <strong>{{ $usuario->created_at->translatedFormat('F Y') }}</strong></div>
              </div>
            </div>
          </div>

          <!-- Columna derecha: Formulario de edición -->
<div class="tile is-parent">
  <div class="card tile is-child perfil-card">

    <header class="card-header perfil-header">
      <p class="card-header-title">
        <span class="icon">
          <i class="mdi mdi-account-edit"></i>
        </span>
        Editar perfil
      </p>
    </header>

    <div class="card-content">
      <form id="formPerfil">

        <div class="field">
          <label class="label">Nombre completo</label>
          <div class="control">
            <input type="text" id="inputNombre" name="nombre" class="input perfil-input"
              value="{{ $usuario->nombre }}" required>
          </div>
        </div>

        <div class="field">
          <label class="label">Correo electrónico</label>
          <div class="control">
            <input type="email" id="inputEmail" name="email" class="input perfil-input"
              value="{{ $usuario->email }}" required>
          </div>
        </div>

        <div class="field">
          <label class="label">Teléfono</label>
          <div class="control">
            <input type="tel" id="inputPhone" name="telefono" class="input perfil-input"
              value="{{ $usuario->telefono }}" placeholder="Ej. 2221234567"
              inputmode="numeric" pattern="[0-9]{10}"
              title="Escribe exactamente 10 números, sin espacios.">
          </div>
          <p class="help">10 dígitos, solo números.</p>
        </div>

        <div class="field">
          <label class="label">Localidad</label>
          <div class="control">
            <div class="select is-fullwidth">
              <select id="inputLocalidad" name="localidad_id" class="perfil-input" required>
                @foreach ($localidades as $loc)
                  <option value="{{ $loc->id }}" {{ $usuario->localidad_id === $loc->id ? 'selected' : '' }}>{{ $loc->nombre }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <p class="help">Municipio: {{ $usuario->localidad->municipio->nombre ?? 'Zacapoaxtla' }}.</p>
        </div>

        <div class="field">
          <label class="label">Descripción / Biografía</label>
          <div class="control">
            <textarea id="inputDescripcion" name="descripcion"
              class="textarea perfil-input"
              rows="4"
              placeholder="Cuéntanos sobre ti...">{{ $usuario->descripcion }}</textarea>
          </div>
        </div>

        <div class="field mt-5">
          <button type="submit" class="btn-guardar-perfil">
            <i class="mdi mdi-content-save"></i>
            Guardar cambios
          </button>
        </div>

      </form>
    </div>

  </div>
</div>
        </div>

        <!-- Cambiar/crear contraseña -->
        <div class="card mt-4">
          <header class="card-header"><p class="card-header-title"><span class="icon"><i class="mdi mdi-lock-reset"></i></span>{{ $usuario->password ? 'Cambiar contraseña' : 'Crear una contraseña' }}</p></header>
          <div class="card-content">
            @if (!$usuario->password)
              <div class="notification is-info is-light">
                <i class="mdi mdi-information"></i> Entraste con Google, así que todavía no tienes contraseña. Puedes crear una aquí para también poder iniciar sesión con tu correo y contraseña.
              </div>
            @endif
            <form id="formPass">
              @if ($usuario->password)
                <div class="field"><label class="label">Contraseña actual</label><div class="control" style="position:relative;"><input type="password" id="passActual" name="password_actual" class="input" placeholder="••••••••" style="padding-right:2.5em;" required><span onclick="verPasswordPerfil('passActual', this)" style="position:absolute; right:12px; top:50%; transform:translateY(-50%); cursor:pointer; color:#999;"><i class="mdi mdi-eye"></i></span></div></div>
              @endif
              <div class="field"><label class="label">Nueva contraseña</label><div class="control" style="position:relative;"><input type="password" id="passNueva" name="password_nueva" class="input" placeholder="Mínimo 8 caracteres" style="padding-right:2.5em;" required><span onclick="verPasswordPerfil('passNueva', this)" style="position:absolute; right:12px; top:50%; transform:translateY(-50%); cursor:pointer; color:#999;"><i class="mdi mdi-eye"></i></span></div></div>
              <div class="field"><label class="label">Confirmar contraseña</label><div class="control" style="position:relative;"><input type="password" id="passConfirm" name="password_nueva_confirmation" class="input" placeholder="Repite la nueva contraseña" style="padding-right:2.5em;" required><span onclick="verPasswordPerfil('passConfirm', this)" style="position:absolute; right:12px; top:50%; transform:translateY(-50%); cursor:pointer; color:#999;"><i class="mdi mdi-eye"></i></span></div></div>
              <div class="field"><button type="submit" class="button is-primary" style="background: linear-gradient(45deg, #ff7a18, #ffb347); border: none;">{{ $usuario->password ? 'Actualizar contraseña' : 'Crear contraseña' }}</button></div>
            </form>
          </div>
        </div>
      </div>

      <!-- PANEL 2: Estadísticas (reseñas, actividad reciente, solicitudes por mes y, si publicas vacantes, cómo van) -->
      <div id="tab-stats" class="tab-content" style="display: none;">
        @php
          $est = $estadisticas;
          $tar = $est['tarjetas'];
          $res = $est['resenas'];
          $emp = $est['empleador'];
          $estrellas = fn($n) => str_repeat('★', (int) $n) . str_repeat('☆', max(0, 5 - (int) $n));
        @endphp

        <style>
          .est-tiles { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin: 18px 0; }
          #tab-stats .card .title.is-5 { margin-bottom: 14px; }
          .est-tile { background: #fff; border-radius: 16px; box-shadow: 0 2px 10px rgba(0,0,0,.06); padding: 16px 18px; display: flex; gap: 14px; align-items: center; }
          .est-ico { width: 46px; height: 46px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 24px; flex: none; }
          .est-o { background: #fff1e3; color: #ff7a18; } .est-g { background: #e3f7ec; color: #1b9a59; }
          .est-b { background: #e6efff; color: #2b6fe0; } .est-y { background: #fff7d6; color: #d99a00; }
          .est-num { font-size: 28px; font-weight: 800; line-height: 1; color: #363636; }
          .est-lab { font-size: 13.5px; color: #7a7a7a; margin-top: 4px; }
          .est-sub { font-size: 12px; color: #1b9a59; font-weight: 700; margin-top: 2px; }
          .est-cols { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px; align-items: start; }
          .est-cols .card, .est-bloque { height: 100%; }
          .est-bloque { margin-bottom: 16px; }
          .est-vacio { color: #8a8a8a; font-size: 14px; background: #fafafa; border-radius: 10px; padding: 14px 16px; }
          .est-prom { display: flex; gap: 22px; align-items: center; margin-bottom: 8px; }
          .est-big { font-size: 44px; font-weight: 800; line-height: 1; }
          .est-stars { color: #f5a623; letter-spacing: 1px; }
          .est-small { font-size: 12.5px; color: #8a8a8a; }
          .est-bar { display: flex; align-items: center; gap: 9px; font-size: 13px; margin: 5px 0; color: #666; }
          .est-bar .t { flex: 1; height: 9px; background: #f0f0f0; border-radius: 6px; overflow: hidden; }
          .est-bar .t span { display: block; height: 100%; background: linear-gradient(90deg, #ff7a18, #ffb347); border-radius: 6px; }
          .est-bar b { width: 22px; text-align: right; color: #444; }
          .est-rev { border-top: 1px solid #eee; padding: 11px 0 2px; margin-top: 10px; }
          .est-rev .quien { font-weight: 700; font-size: 14px; }
          .est-rev p { margin: 3px 0 0; font-size: 13.5px; color: #555; word-break: break-word; }
          .est-tl { position: relative; margin-left: 4px; }
          .est-tl:before { content: ""; position: absolute; left: 15px; top: 8px; bottom: 8px; width: 2px; background: #f0e3d6; }
          .est-it { display: flex; gap: 13px; align-items: flex-start; margin-bottom: 15px; position: relative; }
          .est-it:last-child { margin-bottom: 0; }
          .est-dot { width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 16px; flex: none; border: 3px solid #fff; }
          .est-it .t1 { font-size: 14px; font-weight: 700; word-break: break-word; }
          .est-it .t2 { font-size: 12.5px; color: #8a8a8a; margin-top: 1px; }
          .est-chart { display: flex; align-items: flex-end; gap: 14px; height: 150px; padding: 6px 10px 0; }
          .est-col { flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: flex-end; height: 100%; font-size: 12px; color: #777; gap: 5px; }
          .est-col .v { font-size: 12.5px; font-weight: 800; color: #444; }
          .est-col .p { width: 100%; max-width: 54px; border-radius: 8px 8px 0 0; background: linear-gradient(180deg, #ffb347, #ff7a18); }
          .est-col .p.s { background: linear-gradient(180deg, #d8dde6, #bcc4d1); }
          .est-nota { margin-top: 12px; font-size: 13px; color: #666; text-align: center; }
          .est-chips { display: flex; flex-wrap: wrap; gap: 10px; margin-bottom: 14px; }
          .est-chip { background: #fafafa; border: 1px solid #eee; border-radius: 12px; padding: 8px 14px; min-width: 110px; }
          .est-chip b { display: block; font-size: 22px; line-height: 1.1; }
          .est-chip span { font-size: 12.5px; color: #7a7a7a; }
          .est-venc { display: flex; justify-content: space-between; align-items: center; gap: 10px; padding: 9px 12px; border-radius: 10px; background: #fff4e5; border: 1px solid #f5c38a; margin-top: 8px; font-size: 13.5px; color: #9a4a00; }
          .est-venc a { color: #ff7a18; font-weight: 700; white-space: nowrap; }
          @media (max-width: 980px) { .est-tiles { grid-template-columns: repeat(2, 1fr); } .est-cols { grid-template-columns: 1fr; } }
          @media (max-width: 480px) { .est-tiles { grid-template-columns: 1fr; } .est-chart { gap: 8px; padding: 6px 0 0; } }
        </style>

        <!-- Resumen -->
        <div class="est-tiles">
          <div class="est-tile">
            <div class="est-ico est-o"><i class="mdi mdi-inbox-arrow-down"></i></div>
            <div><div class="est-num">{{ $tar['solicitudes'] }}</div><div class="est-lab">Solicitudes recibidas</div>
              @if ($tar['solicitudes_mes'] > 0)<div class="est-sub">▲ {{ $tar['solicitudes_mes'] }} este mes</div>@endif</div>
          </div>
          <div class="est-tile">
            <div class="est-ico est-g"><i class="mdi mdi-check-decagram"></i></div>
            <div><div class="est-num">{{ $tar['terminados'] }}</div><div class="est-lab">Trabajos terminados</div></div>
          </div>
          <div class="est-tile">
            <div class="est-ico est-b"><i class="mdi mdi-send"></i></div>
            <div><div class="est-num">{{ $tar['postulaciones'] }}</div><div class="est-lab">Postulaciones enviadas</div>
              @if ($tar['contrataron'] > 0)<div class="est-sub">{{ $tar['contrataron'] }} {{ $tar['contrataron'] === 1 ? 'te contrató' : 'te contrataron' }}</div>@endif</div>
          </div>
          @if ($emp)
            <div class="est-tile">
              <div class="est-ico est-y"><i class="mdi mdi-briefcase"></i></div>
              <div><div class="est-num">{{ $emp['abiertas'] }}</div><div class="est-lab">{{ $emp['abiertas'] === 1 ? 'Vacante abierta' : 'Vacantes abiertas' }}</div>
                <div class="est-sub">{{ $emp['postulantes'] }} {{ $emp['postulantes'] === 1 ? 'postulante' : 'postulantes' }}</div></div>
            </div>
          @else
            <div class="est-tile">
              <div class="est-ico est-y"><i class="mdi mdi-star"></i></div>
              <div><div class="est-num">{{ $res['total'] }}</div><div class="est-lab">{{ $res['total'] === 1 ? 'Reseña recibida' : 'Reseñas recibidas' }}</div></div>
            </div>
          @endif
        </div>

        <div class="est-cols">
          <!-- Reseñas -->
          <div class="card">
            <div class="card-content">
              <h3 class="title is-5"><i class="mdi mdi-star-circle"></i> Reseñas</h3>
              @if ($res['total'])
                <div class="est-prom">
                  <div>
                    <div class="est-big">{{ $res['promedio'] }}</div>
                    <div class="est-stars" style="font-size:19px;">{{ $estrellas(round($res['promedio'])) }}</div>
                    <div class="est-small">{{ $res['total'] }} {{ $res['total'] === 1 ? 'reseña' : 'reseñas' }}</div>
                  </div>
                  <div style="flex:1">
                    @foreach ($res['desglose'] as $n => $cuantas)
                      <div class="est-bar">{{ $n }}★<div class="t"><span style="width: {{ $res['total'] ? round($cuantas / $res['total'] * 100) : 0 }}%"></span></div><b>{{ $cuantas }}</b></div>
                    @endforeach
                  </div>
                </div>
                @foreach ($res['recientes'] as $r)
                  <div class="est-rev">
                    <div class="quien">{{ $r->usuario->nombre ?? 'Alguien' }} <span class="est-stars" style="font-size:14px;">{{ $estrellas($r->estrellas) }}</span>
                      <span class="est-small">· {{ $r->created_at?->locale('es')->diffForHumans() }}@if ($r->servicio) · {{ $r->servicio->titulo }}@endif</span></div>
                    @if ($r->comentario)<p>“{{ $r->comentario }}”</p>@endif
                  </div>
                @endforeach
              @else
                <p class="est-vacio">Todavía no tienes reseñas. Cuando alguien califique alguno de tus servicios, aquí verás tu promedio y lo que opinan de tu trabajo.</p>
              @endif
            </div>
          </div>

          <!-- Actividad reciente -->
          <div class="card">
            <div class="card-content">
              <h3 class="title is-5"><i class="mdi mdi-chart-timeline-variant"></i> Actividad reciente</h3>
              @if (count($est['actividad']))
                <div class="est-tl">
                  @foreach ($est['actividad'] as $ev)
                    <div class="est-it">
                      <div class="est-dot est-{{ $ev['color'] }}"><i class="mdi {{ $ev['icono'] }}"></i></div>
                      <div>
                        <div class="t1">{{ $ev['texto'] }}</div>
                        <div class="t2" title="{{ $ev['fecha']->format('d/m/Y H:i') }}">{{ $ev['fecha']->locale('es')->diffForHumans() }}</div>
                      </div>
                    </div>
                  @endforeach
                </div>
              @else
                <p class="est-vacio">Todavía no hay actividad. Cuando te soliciten un servicio, te califiquen o te postules a una vacante, aparecerá aquí.</p>
              @endif
            </div>
          </div>
        </div>

        <!-- Solicitudes por mes -->
        <div class="card est-bloque">
          <div class="card-content">
            <h3 class="title is-5"><i class="mdi mdi-chart-bar"></i> Solicitudes por mes</h3>
            <div class="est-chart">
              @foreach ($est['meses'] as $m)
                <div class="est-col"><div class="v">{{ $m['total'] }}</div><div class="p {{ $m['total'] ? '' : 's' }}" style="height: {{ $m['altura'] }}%"></div>{{ $m['nombre'] }}</div>
              @endforeach
            </div>
            @if ($est['mas_solicitado'])
              <p class="est-nota"><i class="mdi mdi-trophy-outline" style="color:#ff7a18"></i> Tu servicio más solicitado: <strong>«{{ $est['mas_solicitado']['titulo'] }}»</strong> ({{ $est['mas_solicitado']['total'] }} {{ $est['mas_solicitado']['total'] === 1 ? 'solicitud' : 'solicitudes' }})</p>
            @else
              <p class="est-nota">Aún no te han solicitado ningún servicio.</p>
            @endif
          </div>
        </div>

        @if ($emp)
          <!-- Como empleador -->
          <div class="card est-bloque">
            <div class="card-content">
              <h3 class="title is-5"><i class="mdi mdi-briefcase-outline"></i> Tus vacantes</h3>
              <div class="est-chips">
                <div class="est-chip"><b>{{ $emp['abiertas'] }}</b><span>Abiertas</span></div>
                <div class="est-chip"><b>{{ $emp['vencidas'] }}</b><span>Vencidas</span></div>
                <div class="est-chip"><b>{{ $emp['cerradas'] }}</b><span>Cerradas</span></div>
                <div class="est-chip"><b>{{ $emp['postulantes'] }}</b><span>Postulantes recibidos</span></div>
                <div class="est-chip"><b>{{ $emp['pendientes'] }}</b><span>Por revisar</span></div>
                <div class="est-chip"><b>{{ $emp['contratados'] }}</b><span>Contratados</span></div>
              </div>
              @forelse ($emp['por_vencer'] as $v)
                <div class="est-venc">
                  <span><i class="mdi mdi-clock-alert-outline"></i> «{{ $v['titulo'] }}» {{ $v['dias'] <= 0 ? 'vence hoy' : ($v['dias'] === 1 ? 'vence mañana' : 'vence en ' . $v['dias'] . ' días') }} ({{ $v['limite']->format('d/m/Y') }})</span>
                  <a href="/usuario/mis-vacantes/{{ $v['id'] }}/editar">Renovar</a>
                </div>
              @empty
                <p class="est-nota" style="text-align:left;margin-top:0;">Ninguna de tus vacantes vence en los próximos 3 días.</p>
              @endforelse
              <p class="est-nota" style="text-align:left;"><a href="/usuario/mis-vacantes" style="color:#ff7a18;font-weight:700;">Ver mis vacantes →</a></p>
            </div>
          </div>
        @endif
      </div>

      <!-- PANEL 3: Documentos de verificación (ahora reales) -->
      <div id="tab-docs" class="tab-content" style="display: none;">
        <div class="card">
          <div class="card-content">
            <h3 class="title is-5"><i class="mdi mdi-folder-upload"></i> Documentos para verificación</h3>

            <div class="document-card">
              <span><i class="mdi mdi-card-account-details"></i> Identificación oficial (INE)</span>
              <span class="tag {{ $docIne && $docIne->estado === 'aprobado' ? 'is-success' : ($docIne && $docIne->estado === 'rechazado' ? 'is-danger' : 'is-warning') }}">
                {{ $docIne ? ucfirst($docIne->estado) : 'No subido' }}
              </span>
              <button class="button is-small btn-outline-orange" onclick="document.getElementById('inputIne').click()">Subir</button>
              <input type="file" id="inputIne" accept="image/*" style="display:none" onchange="subirDocumento('ine', this)">
            </div>

            <div class="document-card">
              <span><i class="mdi mdi-face-recognition"></i> Selfie con identificación</span>
              <span class="tag {{ $docSelfie && $docSelfie->estado === 'aprobado' ? 'is-success' : ($docSelfie && $docSelfie->estado === 'rechazado' ? 'is-danger' : 'is-warning') }}">
                {{ $docSelfie ? ucfirst($docSelfie->estado) : 'No subido' }}
              </span>
              <button class="button is-small btn-outline-orange" onclick="document.getElementById('inputSelfie').click()">Subir</button>
              <input type="file" id="inputSelfie" accept="image/*" style="display:none" onchange="subirDocumento('selfie', this)">
            </div>

            <div class="notification is-info is-light mt-4"><i class="mdi mdi-information"></i> Los documentos verificados te darán la insignia "Verificado" y mayor confianza. Un administrador los revisa manualmente.</div>
          </div>
        </div>
      </div>
    </div>
  </section>



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

  // Control de pestañas
  const tabs = document.querySelectorAll('#profileTabs li');
  const contents = {
    'tab-info': document.getElementById('tab-info'),
    'tab-stats': document.getElementById('tab-stats'),
    'tab-docs': document.getElementById('tab-docs')
  };
  tabs.forEach(tab => {
    tab.addEventListener('click', () => {
      const target = tab.dataset.tab;
      tabs.forEach(t => t.classList.remove('is-active'));
      tab.classList.add('is-active');
      Object.values(contents).forEach(c => c.style.display = 'none');
      contents[target].style.display = 'block';
    });
  });

  function verPasswordPerfil(inputId, iconSpan) {
    const input = document.getElementById(inputId);
    const icon = iconSpan.querySelector('i');
    if (input.type === 'password') {
      input.type = 'text';
      icon.classList.remove('mdi-eye');
      icon.classList.add('mdi-eye-off');
    } else {
      input.type = 'password';
      icon.classList.remove('mdi-eye-off');
      icon.classList.add('mdi-eye');
    }
  }

  function mostrarToast(msg, type) {
    let toast = document.getElementById('profileToast');
    toast.textContent = msg;
    toast.className = `notification ${type}`;
    toast.style.display = 'block';
    setTimeout(() => { toast.style.display = 'none'; }, 3000);
  }

  // ===== Guardar información personal (guarda de verdad en la base de datos) =====
  const formPerfil = document.getElementById('formPerfil');
  let fotoPendiente = null;

  // Teléfono: solo números y máximo 10 dígitos.
  const inputPhone = document.getElementById('inputPhone');
  function soloNumeros(max) {
    const limpio = inputPhone.value.replace(/\D+/g, '').slice(0, max);
    if (limpio !== inputPhone.value) inputPhone.value = limpio;
  }
  inputPhone.addEventListener('input', () => soloNumeros(10));
  // Si ya traía espacios o guiones guardados, se muestran solo los números.
  soloNumeros(Infinity);

  formPerfil.addEventListener('submit', (e) => {
    e.preventDefault();

    // Teléfono: si escribió algo, deben ser exactamente 10 números.
    const telefono = inputPhone.value.trim();
    if (telefono !== '' && !/^\d{10}$/.test(telefono)) {
      mostrarToast('❌ El teléfono debe tener exactamente 10 números.', 'is-danger');
      inputPhone.focus();
      return;
    }

    const formData = new FormData(formPerfil);
    if (fotoPendiente) formData.append('foto', fotoPendiente);

    // PHP no procesa bien multipart/form-data en peticiones PATCH/PUT.
    // Lo mandamos como POST con _method=PATCH para que Laravel lo trate como PATCH.
    formData.append('_method', 'PATCH');

    fetch('/usuario/profile', {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': csrfToken,
        'Accept': 'application/json',
      },
      body: formData,
    })
      .then(res => res.json().then(data => ({ ok: res.ok, data })))
      .then(({ ok, data }) => {
        if (!ok) {
          let msg = data.message || 'Ocurrió un error al guardar.';
          if (data.errors) {
            msg = Object.values(data.errors).flat().join(' ');
          }
          mostrarToast('❌ ' + msg, 'is-danger');
          return;
        }
        mostrarToast('✅ Perfil actualizado correctamente', 'is-success');
        document.getElementById('displayEmailStatic').textContent = data.usuario.email;
        document.getElementById('displayPhoneStatic').textContent = data.usuario.telefono || 'Sin teléfono';
        if (data.usuario.localidad) {
          document.getElementById('displayMunicipioStatic').textContent = data.usuario.localidad.nombre;
        }
      })
      .catch((err) => {
        console.error('Error al guardar el perfil:', err);
        mostrarToast('❌ Ocurrió un error de conexión.', 'is-danger');
      });
  });

  // ===== Cambiar/crear contraseña (guarda de verdad en la base de datos) =====
  const formPass = document.getElementById('formPass');
  formPass.addEventListener('submit', (e) => {
    e.preventDefault();

    const passActualEl = document.getElementById('passActual');
    const body = {
      password_nueva: document.getElementById('passNueva').value,
      password_nueva_confirmation: document.getElementById('passConfirm').value,
    };
    if (passActualEl) {
      body.password_actual = passActualEl.value;
    }

    fetch('/usuario/profile/password', {
      method: 'PATCH',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrfToken,
        'Accept': 'application/json',
      },
      body: JSON.stringify(body),
    })
      .then(res => res.json().then(data => ({ ok: res.ok, data })))
      .then(({ ok, data }) => {
        if (!ok) {
          let msg = data.message || 'Ocurrió un error al actualizar la contraseña.';
          if (data.errors) {
            msg = Object.values(data.errors).flat().join(' ');
          }
          mostrarToast('❌ ' + msg, 'is-danger');
          return;
        }
        mostrarToast(data.creada ? '✅ Contraseña creada. Ya puedes entrar también con correo y contraseña.' : '✅ Contraseña actualizada correctamente.', 'is-success');
        formPass.reset();
      })
      .catch((err) => {
        console.error('Error al actualizar la contraseña:', err);
        mostrarToast('❌ Ocurrió un error de conexión.', 'is-danger');
      });
  });

  // ===== Subir foto de perfil (guarda de verdad al enviar el formulario de arriba) =====
  const uploadBtn = document.getElementById('uploadPhotoBtn');
  const photoInput = document.getElementById('photoInput');
  const avatarCircle = document.getElementById('avatarCircle');
  uploadBtn.addEventListener('click', () => photoInput.click());
  photoInput.addEventListener('change', (e) => {
    const file = e.target.files[0];
    if (file) {
      fotoPendiente = file;
      const reader = new FileReader();
      reader.onload = function (ev) {
        avatarCircle.style.backgroundImage = `url(${ev.target.result})`;
        avatarCircle.style.backgroundSize = 'cover';
        avatarCircle.style.backgroundPosition = 'center';
        avatarCircle.textContent = '';
        mostrarToast('🖼️ Foto lista — dale "Guardar cambios" para que se suba de verdad.', 'is-info');
      };
      reader.readAsDataURL(file);
    }
  });

  // ===== Subir documentos de verificación (guarda de verdad) =====
  function subirDocumento(tipo, input) {
    const file = input.files[0];
    if (!file) return;

    const formData = new FormData();
    formData.append('archivo', file);

    fetch(`/usuario/profile/documento/${tipo}`, {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': csrfToken },
      body: formData,
    })
      .then(res => res.json().then(data => ({ ok: res.ok, data })))
      .then(({ ok, data }) => {
        if (!ok || !data.ok) {
          let msg = data.message || 'Ocurrió un error al subir el documento.';
          if (data.errors) {
            msg = Object.values(data.errors).flat().join(' ');
          }
          mostrarToast('❌ ' + msg, 'is-danger');
          return;
        }
        mostrarToast('📄 Documento subido, en espera de revisión.', 'is-success');
        setTimeout(() => location.reload(), 1200);
      })
      .catch(() => mostrarToast('❌ Ocurrió un error al subir el documento.', 'is-danger'));
  }</script>
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
</body>
</html>
