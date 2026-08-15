<!DOCTYPE html>
<html lang="es" class="has-aside-left has-aside-mobile-transition has-navbar-fixed-top has-aside-expanded">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Empleador · Panel</title>

  <link rel="stylesheet" href="{{ asset('assets/usuario/css/main.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/usuario/css/styles.css') }}">
  <link rel="icon" type="image/png" href="{{ asset('assets/usuario/img/favicon.png') }}">
  <link rel="stylesheet" href="{{ asset('assets/usuario/css/switch.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/usuario/css/employer-dashboard.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.9.95/css/materialdesignicons.min.css">
  <link rel="icon" type="img/" href="{{ asset('assets/usuario/img/icono.png') }}">

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
          <span><b> Modo Empleador</b></span>
        </div>
      </div>

      <!-- ===== INTERRUPTOR ===== -->
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
            <a href="/usuario/empleador" class="is-active router-link-active has-icon">
              <span class="icon has-update-mark"><i class="mdi mdi-desktop-mac"></i></span>
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
            <a href="/usuario/postulantes" class="has-icon">
              <span class="icon"><i class="mdi mdi-account-group"></i></span>
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

    <!-- CONTENIDO PRINCIPAL - DASHBOARD EMPLEADOR -->
    <section class="section is-main-section">
      <div class="container">

        <div class="role-notice">
          <span class="notice-icon">🏢</span>
          <span>Estás en modo <strong class="notice-role employer">Empleador</strong> · Gestiona tus vacantes
            publicadas.</span>
        </div>

        <!-- ===== DASHBOARD ===== -->
        <div class="employer-dashboard">

          <!-- ===== ESTADÍSTICAS ===== -->
          <div class="estadisticas-row">
            <div class="column">
              <div class="box has-text-centered">
                <span class="icon is-large">
                  <i class="mdi mdi-briefcase mdi-36px" style="color: #ff7a18;"></i>
                </span>
                <h2 class="title" style="color: #ff7a18;">{{ $vacantesActivas }}</h2>
                <p class="has-text-grey">Vacantes Activas</p>
              </div>
            </div>
            <div class="column">
              <div class="box has-text-centered">
                <span class="icon is-large">
                  <i class="mdi mdi-account-group mdi-36px" style="color: #1a73e8;"></i>
                </span>
                <h2 class="title" style="color: #1a73e8;">{{ $postulantesActivos }}</h2>
                <p class="has-text-grey">Postulantes Activos</p>
              </div>
            </div>
            <div class="column">
              <div class="box has-text-centered">
                <span class="icon is-large">
                  <i class="mdi mdi-account-check mdi-36px" style="color: #2e7d32;"></i>
                </span>
                <h2 class="title" style="color: #2e7d32;">{{ $contratadosActivos }}</h2>
                <p class="has-text-grey">Contratados Activos</p>
              </div>
            </div>
          </div>
          <!-- ===== ACCIONES RÁPIDAS ===== -->
          <div class="acciones-rapidas">
            <h3 class="acciones-titulo">⚡ Acciones Rápidas</h3>
            <div class="acciones-grid">
              <a href="/usuario/publicar-vacante" class="accion-card" id="actionPublicar">
                <span class="icon"><i class="mdi mdi-plus-circle mdi-36px"></i></span>
                <span>Publicar trabajo</span>
              </a>
              <a href="/usuario/buscar-talento" class="accion-card" id="actionBuscar">
                <span class="icon"><i class="mdi mdi-magnify mdi-36px"></i></span>
                <span>Buscar trabajo</span>
              </a>
              <a href="/usuario/mis-vacantes" class="accion-card" id="actionMis">
                <span class="icon"><i class="mdi mdi-format-list-bulleted mdi-36px"></i></span>
                <span>Mis Vacantes</span>
              </a>
              <a href="/usuario/postulantes" class="accion-card" id="actionPostulantes">
                <span class="icon"><i class="mdi mdi-account-group mdi-36px"></i></span>
                <span>Postulantes</span>
              </a>
            </div>
          </div>

          <!-- ===== EMPLEOS DISPONIBLES (reales, de toda la plataforma) ===== -->
          <section class="products" id="listJobs">
            <h2>Empleos Disponibles</h2>
            <div class="products-list">

              @forelse ($vacantes as $v)
                @php $yaPostulado = in_array($v->id, $misPostulacionesIds); @endphp
                <div class="product-row">
                  <img class="product-img" src="{{ $v->imagen ?? asset('assets/usuario/img/services/frijol.jpg') }}" alt="{{ $v->titulo }}"
                    onerror="this.src='https://via.placeholder.com/120x120/ff7a18/fff?text=💼'">
                  <div class="product-info">
                    <div>
                      <div class="job-title">{{ $v->titulo }}</div>

                      <div class="job-publisher">
                        <i class="mdi mdi-account"></i> {{ $v->publicante }} · <i class="mdi mdi-map-marker"></i> {{ $v->ubicacion }}
                      </div>

                      <div class="job-details">
                        <span><i class="mdi mdi-account-multiple"></i> {{ $v->trabajadores_requeridos }} trabajador(es)</span>
                        <span><i class="mdi mdi-currency-usd"></i> {{ $v->tipo_pago }}</span>
                      </div>

                      <div class="job-extras">
                        <span><i class="mdi mdi-school"></i> <span class="exp-badge">{{ $v->experiencia }}</span></span>
                        <span><i class="mdi mdi-currency-usd"></i> <span class="salary-badge">{{ $v->salario }}</span></span>
                        <span><i class="mdi mdi-file-document"></i> <span class="contract-badge">{{ $v->contrato }}</span></span>
                      </div>

                      <div class="job-description">
                        <i class="mdi mdi-information"></i> {{ $v->descripcion }}
                      </div>

                      @if ($v->beneficios && count($v->beneficios))
                        <div class="job-benefits">
                          @foreach ($v->beneficios as $beneficio)
                            <span><i class="mdi mdi-check-circle" style="color:#2e7d32;"></i> {{ $beneficio }}</span>
                          @endforeach
                        </div>
                      @endif

                      <div class="job-dates">
                        <div class="job-date-work">
                          <i class="mdi mdi-calendar-clock"></i>
                          <span><strong>Día del trabajo:</strong> {{ $v->fecha_trabajo }}</span>
                        </div>
                        @if ($v->duracion)
                          <div class="job-date-duration">
                            <i class="mdi mdi-timer"></i>
                            <span><strong>Duración de trabajo:</strong> {{ $v->duracion }}</span>
                          </div>
                        @endif
                        @if ($v->fecha_limite)
                          <div class="job-date-limit">
                            <i class="mdi mdi-clock-alert"></i>
                            <span><strong>Límite para postular:</strong> {{ $v->fecha_limite->format('d/m/Y') }}</span>
                          </div>
                        @endif
                      </div>

                      <div class="job-contact">
                        <i class="mdi mdi-phone"></i> <span class="contact-text">{{ $v->telefono }}</span>
                        @if ($v->whatsapp)
                          <i class="mdi mdi-whatsapp" style="color:#25D366; margin-left: 12px;"></i> <span class="contact-text">{{ $v->whatsapp }}</span>
                        @endif
                      </div>
                    </div>

                    <!-- ACCIONES -->
                    <div class="job-actions">

                      <div class="left">
                        <span class="postulados">
                          <i class="mdi mdi-account"></i> {{ $v->postulaciones_count }} postulados
                        </span>

                        <span class="job-status active">
                          Activo
                        </span>
                      </div>

                      <div class="job-buttons">

                        @if ($v->whatsapp)
                          <a href="https://wa.me/52{{ preg_replace('/\D/', '', $v->whatsapp) }}?text={{ urlencode('Hola, me interesa el trabajo de ' . $v->titulo) }}"
                            target="_blank" class="btn-whatsapp">
                            <i class="mdi mdi-whatsapp"></i>
                            WhatsApp
                          </a>
                        @endif

                        @if ($yaPostulado)
                          <button class="btn-add" type="button" disabled style="opacity:.6; cursor:default;">
                            <i class="mdi mdi-check-circle"></i>
                            Ya te postulaste
                          </button>
                        @else
                          <button class="btn-add" type="button" onclick="abrirModalPostular({{ $v->id }}, '{{ addslashes($v->titulo) }}')">
                            <i class="mdi mdi-account-plus"></i>
                            Postularme
                          </button>
                        @endif

                        <button class="btn-comments" type="button" onclick="abrirModalComentarios('vacante', {{ $v->id }}, '{{ addslashes($v->titulo) }}')">
                          <i class="mdi mdi-comment-text-outline"></i>
                          Comentarios
                        </button>

                        @if ($v->empleador_id !== auth()->id())
                          <button class="btn-report" type="button" onclick="abrirModalReportar('vacante', {{ $v->id }})">
                            <i class="mdi mdi-flag-outline"></i>
                            Reportar
                          </button>
                        @endif

                      </div>

                    </div>
                  </div>
                </div>
              @empty
                <p class="has-text-grey has-text-centered">No hay vacantes activas en este momento. <a href="/usuario/publicar-vacante">Publica la primera</a>.</p>
              @endforelse

            </div>
          </section>

        </div>

      </div>
    </section>

    <!-- MODAL: Postularse a una vacante -->
    <div id="modal-postular" class="modal" style="position:fixed; top:0; left:0; right:0; bottom:0;">
      <div class="modal-background" onclick="document.getElementById('modal-postular').classList.remove('is-active')"></div>
      <div class="modal-card">
        <header class="modal-card-head">
          <p class="modal-card-title">Postularte a <span id="modalPostularTitulo"></span></p>
          <button class="delete" aria-label="close" onclick="document.getElementById('modal-postular').classList.remove('is-active')"></button>
        </header>
        <section class="modal-card-body">
          <div class="field">
            <label class="label">Mensaje / carta de presentación (opcional)</label>
            <div class="control">
              <textarea id="modalPostularMensaje" class="textarea" rows="4" placeholder="Cuéntale al empleador por qué eres una buena opción..."></textarea>
            </div>
          </div>
        </section>
        <footer class="modal-card-foot">
          <button class="button is-success" onclick="enviarPostulacion()"><i class="mdi mdi-send"></i>&nbsp;Enviar postulación</button>
          <button class="button" onclick="document.getElementById('modal-postular').classList.remove('is-active')">Cancelar</button>
        </footer>
      </div>
    </div>

    <!-- MODAL: Comentarios (estilo chat) -->
    <div id="modal-comentarios" class="modal" style="position:fixed; top:0; left:0; right:0; bottom:0;">
      <div class="modal-background" onclick="document.getElementById('modal-comentarios').classList.remove('is-active')"></div>
      <div class="modal-card" style="max-width:480px; border-radius:18px; overflow:hidden;">
        <header class="modal-card-head" style="background:linear-gradient(135deg,#ff7a18,#ffb347); border:none; padding:16px 20px;">
          <p class="modal-card-title" style="color:#fff; font-size:1rem;">
            <i class="mdi mdi-comment-multiple-outline"></i> Comentarios de <span id="comentariosTitulo"></span>
          </p>
          <button class="delete" aria-label="close" onclick="document.getElementById('modal-comentarios').classList.remove('is-active')"></button>
        </header>
        <section class="modal-card-body" style="background:#f0f2f5; padding:16px;">
          <div id="comentariosLista" style="max-height:360px; overflow-y:auto; display:flex; flex-direction:column; gap:10px; padding-right:4px;">
            <p class="has-text-grey has-text-centered">Cargando comentarios...</p>
          </div>
        </section>
        <footer class="modal-card-foot" style="background:#fff; border-top:1px solid #e8eaed; padding:12px 16px; display:flex; gap:8px; align-items:center;">
          <input type="text" id="comentarioNuevo" maxlength="500" placeholder="Escribe un mensaje..."
            style="flex:1; border:1px solid #dcdfe4; border-radius:20px; padding:10px 16px; outline:none; font-size:14px;"
            onkeydown="if(event.key==='Enter'){event.preventDefault(); enviarComentario();}">
          <button onclick="enviarComentario()" title="Enviar"
            style="background:linear-gradient(45deg,#ff7a18,#ffb347); border:none; color:#fff; width:40px; height:40px; border-radius:50%; display:flex; align-items:center; justify-content:center; cursor:pointer; flex-shrink:0;">
            <i class="mdi mdi-send" style="font-size:18px;"></i>
          </button>
        </footer>
      </div>
    </div>

    <!-- MODAL: Mini perfil (al darle clic al avatar de un comentario) -->
    <div id="modal-perfil-comentario" class="modal" style="position:fixed; top:0; left:0; right:0; bottom:0;" onclick="document.getElementById('modal-perfil-comentario').classList.remove('is-active')">
      <div class="modal-background" style="background:rgba(107,16,33,0.90);"></div>
      <div class="modal-card" style="max-width:340px; border-radius:20px; overflow:hidden; box-shadow:0 20px 50px rgba(0,0,0,.3);">
        <div style="background:linear-gradient(135deg,#6b1021,#b12d25); padding:24px 20px 42px; text-align:center; position:relative;">
          <button onclick="document.getElementById('modal-perfil-comentario').classList.remove('is-active')"
            style="position:absolute; top:12px; right:12px; width:32px; height:32px; border-radius:50%; background:rgba(255,255,255,0.25); border:none; color:#fff; font-size:18px; font-weight:700; cursor:pointer; display:flex; align-items:center; justify-content:center; line-height:1;">
            &times;
          </button>
        </div>

        <div style="background:#fdf0e2; padding-top:0;">
          <div style="text-align:center; margin-top:-38px; padding:0 24px;">
            <img id="perfilComentarioFoto" src="" style="width:84px !important; height:84px !important; max-width:none !important; min-width:84px; border-radius:50%; object-fit:cover; border:4px solid #fdf0e2; background:#6b1021; box-shadow:0 4px 12px rgba(0,0,0,.2); display:block; margin:0 auto;">
          </div>

          <div style="padding:10px 24px 24px; text-align:center;">
            <h3 id="perfilComentarioNombre" style="margin-top:6px; font-weight:800; font-size:1.15rem; color:#1a1a2e;"></h3>
            <div id="perfilComentarioVerificado" style="display:none; margin-top:6px;">
              <span style="background:#dcfce7; color:#16a34a; padding:3px 12px; border-radius:20px; font-size:.78rem; font-weight:700;">
                <i class="mdi mdi-check-decagram"></i> Identidad verificada
              </span>
            </div>

            <p id="perfilComentarioDescripcion" style="color:#6b5d52; font-size:.85rem; margin-top:12px; line-height:1.5; font-style:italic;"></p>

            <div style="display:flex; justify-content:center; gap:24px; margin-top:16px; padding:14px 10px; background:#fff; border-radius:14px;">
              <div>
                <div style="font-weight:800; font-size:1.15rem; color:#ff7a18;" id="perfilComentarioServicios">0</div>
                <div style="font-size:.72rem; color:#8a8d91; margin-top:2px;">Servicios</div>
              </div>
              <div style="width:1px; background:#eee;"></div>
              <div>
                <div style="font-weight:700; font-size:.85rem; color:#1a1a2e;" id="perfilComentarioLocalidad">—</div>
                <div style="font-size:.72rem; color:#8a8d91; margin-top:2px;"><i class="mdi mdi-map-marker"></i> Localidad</div>
              </div>
            </div>

            <p id="perfilComentarioMiembro" style="color:#a89c8f; font-size:.75rem; margin-top:14px; margin-bottom:0;"></p>
          </div>
        </div>
      </div>
    </div>

    <!-- MODAL: Reportar -->
    <div id="modal-reportar" class="modal" style="position:fixed; top:0; left:0; right:0; bottom:0;">
      <div class="modal-background" onclick="document.getElementById('modal-reportar').classList.remove('is-active')"></div>
      <div class="modal-card" style="max-width:460px; border-radius:18px; overflow:hidden;">
        <header class="modal-card-head" style="background:linear-gradient(135deg,#d93025,#f36a5a); border:none; padding:16px 20px;">
          <p class="modal-card-title" style="color:#fff; font-size:1rem;">
            <i class="mdi mdi-flag-outline"></i> Reportar publicación
          </p>
          <button class="delete" aria-label="close" onclick="document.getElementById('modal-reportar').classList.remove('is-active')"></button>
        </header>
        <section class="modal-card-body" style="padding:20px; background:#e4e6eb;">
          <p style="color:#666; font-size:13px; margin-bottom:16px;">Cuéntanos qué está mal con esta publicación. Un administrador la va a revisar.</p>

          <div id="reporteMotivos" style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:16px;">
            <label class="reporte-motivo-opcion">
              <input type="radio" name="reporte-motivo" value="Información falsa" style="display:none;">
              <span><i class="mdi mdi-alert-circle-outline"></i> Información falsa</span>
            </label>
            <label class="reporte-motivo-opcion">
              <input type="radio" name="reporte-motivo" value="Contenido inapropiado" style="display:none;">
              <span><i class="mdi mdi-eye-off-outline"></i> Contenido inapropiado</span>
            </label>
            <label class="reporte-motivo-opcion">
              <input type="radio" name="reporte-motivo" value="Posible fraude o estafa" style="display:none;">
              <span><i class="mdi mdi-cash-remove"></i> Fraude o estafa</span>
            </label>
            <label class="reporte-motivo-opcion">
              <input type="radio" name="reporte-motivo" value="Spam o publicidad" style="display:none;">
              <span><i class="mdi mdi-email-remove-outline"></i> Spam / publicidad</span>
            </label>
            <label class="reporte-motivo-opcion" style="grid-column: span 2;">
              <input type="radio" name="reporte-motivo" value="Otro" style="display:none;">
              <span><i class="mdi mdi-dots-horizontal"></i> Otro motivo</span>
            </label>
          </div>

          <div class="field">
            <label class="label" style="font-size:13px;">Describe el problema (opcional)</label>
            <div class="control">
              <textarea id="reporteDescripcion" class="textarea" rows="3" placeholder="Cuéntanos qué pasó..." maxlength="1000" style="border-radius:10px;"></textarea>
            </div>
          </div>
        </section>
        <footer class="modal-card-foot" style="border-top:1px solid #e8eaed; background:#fff;">
          <button class="button" onclick="enviarReporte()" style="background:linear-gradient(45deg,#d93025,#f36a5a); color:#fff; border:none; border-radius:10px; font-weight:600;">
            <i class="mdi mdi-flag"></i>&nbsp;Enviar reporte
          </button>
          <button class="button" onclick="document.getElementById('modal-reportar').classList.remove('is-active')" style="border-radius:10px;">Cancelar</button>
        </footer>
      </div>
    </div>

    <style>
      .reporte-motivo-opcion {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 12px;
        border: 1.5px solid #e5e7eb;
        border-radius: 10px;
        cursor: pointer;
        font-size: 13px;
        color: #444;
        transition: 0.15s;
      }
      .reporte-motivo-opcion:hover {
        border-color: #ffb347;
        background: #fff7ef;
      }
      .reporte-motivo-opcion.selected {
        border-color: #ff7a18;
        background: #fff1e0;
        color: #ff7a18;
        font-weight: 700;
      }
      .reporte-motivo-opcion i {
        font-size: 16px;
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

  <!-- SCRIPTS -->
  <script src="{{ asset('assets/usuario/js/main.js') }}"></script>
  <script>
    (function () {
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

      labelWorker.addEventListener('click', function (e) {
        if (!isWorker) toggleRole(e);
      });
      labelEmployer.addEventListener('click', function (e) {
        if (isWorker) toggleRole(e);
      });

      const switchRow = document.querySelector('.switch-row');
      switchRow.addEventListener('mouseenter', function () {
        tooltip.classList.add('show');
      });
      switchRow.addEventListener('mouseleave', function () {
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

    // ===== Postularse (abre el modal y manda la postulación real) =====
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    let vacanteIdActual = null;

    function abrirModalPostular(vacanteId, titulo) {
      vacanteIdActual = vacanteId;
      document.getElementById('modalPostularTitulo').textContent = titulo;
      document.getElementById('modalPostularMensaje').value = '';
      document.getElementById('modal-postular').classList.add('is-active');
    }

    function enviarPostulacion() {
      const mensaje = document.getElementById('modalPostularMensaje').value.trim();

      fetch(`/usuario/empleador/postularse/${vacanteIdActual}`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
        body: JSON.stringify({ mensaje: mensaje }),
      })
        .then(res => res.json().then(data => ({ ok: res.ok, data })))
        .then(({ ok, data }) => {
          if (!ok) {
            alert('❌ ' + (data.error || 'Ocurrió un error al postularte.'));
            return;
          }
          document.getElementById('modal-postular').classList.remove('is-active');
          alert('✅ ¡Postulación enviada! El empleador podrá revisarla y aceptarla o rechazarla.');
          location.reload();
        })
        .catch(() => alert('❌ Ocurrió un error de conexión. Intenta de nuevo.'));
    }

    // ===== Comentarios (funciona para servicios y vacantes) =====
    let comentariosTipoActual = null;
    let comentariosIdActual = null;

    function abrirModalComentarios(tipo, id, titulo) {
      comentariosTipoActual = tipo;
      comentariosIdActual = id;
      document.getElementById('comentariosTitulo').textContent = titulo;
      document.getElementById('comentarioNuevo').value = '';
      document.getElementById('comentariosLista').innerHTML = '<p class="has-text-grey has-text-centered">Cargando comentarios...</p>';
      document.getElementById('modal-comentarios').classList.add('is-active');

      fetch(`/usuario/comentarios/${tipo}/${id}`)
        .then(res => res.json())
        .then(data => pintarComentarios(data.comentarios))
        .catch(() => {
          document.getElementById('comentariosLista').innerHTML = '<p class="has-text-danger has-text-centered">No se pudieron cargar los comentarios.</p>';
        });
    }

    function pintarComentarios(comentarios) {
      const cont = document.getElementById('comentariosLista');
      if (!comentarios.length) {
        cont.innerHTML = '<p class="has-text-grey has-text-centered" style="margin-top:20px;">Todavía no hay comentarios.<br>¡Sé el primero en escribir uno!</p>';
        return;
      }
      const ordenados = [...comentarios].reverse();
      cont.innerHTML = ordenados.map(c => burbujaComentario(c)).join('');
      cont.scrollTop = cont.scrollHeight;
    }

    function inicialesDe(nombre) {
      return (nombre || '?').split(' ').filter(Boolean).slice(0, 2).map(p => p[0].toUpperCase()).join('');
    }

    function burbujaComentario(c) {
      const esMio = c.esMio;
      if (esMio) {
        return `
          <div id="comentario-${c.id}" style="align-self:flex-end; max-width:78%; display:flex; flex-direction:column; align-items:flex-end;">
            <div class="burbuja-contenido" style="background:linear-gradient(45deg,#ff7a18,#ffb347); color:#fff; padding:8px 14px; border-radius:16px 16px 4px 16px; font-size:14px; line-height:1.4; word-break:break-word; position:relative;">
              <span class="burbuja-texto">${escaparHtml(c.contenido)}</span>
            </div>
            <div style="display:flex; gap:10px; margin-top:2px;">
              <small style="color:#8a8d91; font-size:11px;">${c.fecha}</small>
              <button onclick="editarComentario(${c.id})" title="Editar" style="background:none; border:none; cursor:pointer; padding:0; color:#8a8d91; font-size:12px;"><i class="mdi mdi-pencil-outline"></i></button>
              <button onclick="eliminarComentario(${c.id})" title="Eliminar" style="background:none; border:none; cursor:pointer; padding:0; color:#c0392b; font-size:12px;"><i class="mdi mdi-trash-can-outline"></i></button>
            </div>
          </div>`;
      }
      return `
        <div style="align-self:flex-start; max-width:78%; display:flex; gap:8px; align-items:flex-end;">
          <div onclick="verPerfilComentario(${c.usuario_id}, '${escapeJs(c.nombre)}', '${escapeJs(c.foto || '')}', ${c.verificado ? 'true' : 'false'}, '${escapeJs(c.miembro_desde || '')}', '${escapeJs(c.localidad || '')}', '${escapeJs(c.descripcion || '')}', ${c.servicios_count || 0})"
            style="width:30px; height:30px; border-radius:50%; background:#6b1021; color:#fff; display:flex; align-items:center; justify-content:center; font-size:11px; font-weight:700; flex-shrink:0; cursor:pointer; overflow:hidden;"
            title="Ver perfil de ${escaparHtml(c.nombre)}">
            ${c.foto ? `<img src="${c.foto}" style="width:100%; height:100%; object-fit:cover;">` : inicialesDe(c.nombre)}
          </div>
          <div style="display:flex; flex-direction:column; align-items:flex-start;">
            <small style="color:#8a8d91; font-size:11px; margin-bottom:2px;">${escaparHtml(c.nombre)}</small>
            <div style="background:#fff; color:#050505; padding:8px 14px; border-radius:16px 16px 16px 4px; font-size:14px; line-height:1.4; box-shadow:0 1px 2px rgba(0,0,0,.08); word-break:break-word;">
              ${escaparHtml(c.contenido)}
            </div>
            <small style="color:#8a8d91; font-size:11px; margin-top:2px;">${c.fecha}</small>
          </div>
        </div>`;
    }

    function escapeJs(texto) {
      return String(texto).replace(/\\/g, '\\\\').replace(/'/g, "\\'");
    }

    function verPerfilComentario(usuarioId, nombre, foto, verificado, miembroDesde, localidad, descripcion, serviciosCount) {
      document.getElementById('perfilComentarioFoto').src = foto || 'https://ui-avatars.com/api/?background=6b1021&color=fff&name=' + encodeURIComponent(nombre);
      document.getElementById('perfilComentarioNombre').textContent = nombre;
      document.getElementById('perfilComentarioVerificado').style.display = verificado ? 'block' : 'none';
      document.getElementById('perfilComentarioDescripcion').textContent = descripcion || 'Este usuario todavía no ha agregado una descripción.';
      document.getElementById('perfilComentarioServicios').textContent = serviciosCount || 0;
      document.getElementById('perfilComentarioLocalidad').textContent = localidad || '—';
      document.getElementById('perfilComentarioMiembro').textContent = miembroDesde ? ('Miembro desde ' + miembroDesde) : '';
      document.getElementById('modal-perfil-comentario').classList.add('is-active');
    }

    function editarComentario(id) {
      const wrapper = document.getElementById(`comentario-${id}`);
      const span = wrapper.querySelector('.burbuja-texto');
      const textoActual = span.textContent;

      wrapper.querySelector('.burbuja-contenido').innerHTML = `
        <input type="text" value="${textoActual.replace(/"/g, '&quot;')}" id="edit-input-${id}"
          style="border:none; outline:none; background:rgba(255,255,255,.25); color:#fff; padding:2px 6px; border-radius:6px; width:100%; font-size:14px;"
          onkeydown="if(event.key==='Enter'){event.preventDefault(); guardarEdicion(${id});} if(event.key==='Escape'){cancelarEdicion(${id}, \`${textoActual.replace(/`/g, '\\`')}\`);}">
      `;
      document.getElementById(`edit-input-${id}`).focus();

      // Cambia los botones a Guardar/Cancelar mientras edita
      const acciones = wrapper.querySelector('div:last-child');
      acciones.innerHTML = `
        <button onclick="guardarEdicion(${id})" style="background:none; border:none; cursor:pointer; padding:0; color:#16a34a; font-size:11px; font-weight:700;">Guardar</button>
        <button onclick="cancelarEdicion(${id}, \`${textoActual.replace(/`/g, '\\`')}\`)" style="background:none; border:none; cursor:pointer; padding:0; color:#8a8d91; font-size:11px;">Cancelar</button>
      `;
    }

    function cancelarEdicion(id, textoOriginal) {
      abrirModalComentarios(comentariosTipoActual, comentariosIdActual, document.getElementById('comentariosTitulo').textContent);
    }

    function guardarEdicion(id) {
      const input = document.getElementById(`edit-input-${id}`);
      const nuevoContenido = input.value.trim();
      if (!nuevoContenido) return;

      fetch(`/usuario/comentarios/${id}`, {
        method: 'PATCH',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
        body: JSON.stringify({ contenido: nuevoContenido }),
      })
        .then(res => res.json())
        .then(data => {
          if (!data.ok) {
            alert('❌ Ocurrió un error al editar.');
            return;
          }
          abrirModalComentarios(comentariosTipoActual, comentariosIdActual, document.getElementById('comentariosTitulo').textContent);
        })
        .catch(() => alert('❌ Ocurrió un error de conexión.'));
    }

    function eliminarComentario(id) {
      if (!confirm('¿Eliminar este comentario?')) return;

      fetch(`/usuario/comentarios/${id}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': csrfToken },
      })
        .then(res => res.json())
        .then(data => {
          if (!data.ok) {
            alert('❌ Ocurrió un error al eliminar.');
            return;
          }
          const el = document.getElementById(`comentario-${id}`);
          if (el) el.remove();
        })
        .catch(() => alert('❌ Ocurrió un error de conexión.'));
    }

    function escaparHtml(texto) {
      const div = document.createElement('div');
      div.textContent = texto;
      return div.innerHTML;
    }

    function enviarComentario() {
      const input = document.getElementById('comentarioNuevo');
      const contenido = input.value.trim();
      if (!contenido) return;

      fetch(`/usuario/comentarios/${comentariosTipoActual}/${comentariosIdActual}`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
        body: JSON.stringify({ contenido: contenido }),
      })
        .then(res => res.json())
        .then(data => {
          if (!data.ok) {
            alert('❌ Ocurrió un error al comentar.');
            return;
          }
          input.value = '';
          const cont = document.getElementById('comentariosLista');
          if (cont.querySelector('p')) cont.innerHTML = '';
          cont.insertAdjacentHTML('beforeend', burbujaComentario(data.comentario));
          cont.scrollTop = cont.scrollHeight;
        })
        .catch(() => alert('❌ Ocurrió un error de conexión.'));
    }

    // ===== Reportar (funciona para servicios y vacantes) =====
    let reporteTipoActual = null;
    let reporteIdActual = null;

    function abrirModalReportar(tipo, id) {
      reporteTipoActual = tipo;
      reporteIdActual = id;
      document.querySelectorAll('input[name="reporte-motivo"]').forEach(r => r.checked = false);
      document.querySelectorAll('.reporte-motivo-opcion').forEach(op => op.classList.remove('selected'));
      document.getElementById('reporteDescripcion').value = '';
      document.getElementById('modal-reportar').classList.add('is-active');
    }

    // Resalta la tarjeta del motivo seleccionado
    document.querySelectorAll('.reporte-motivo-opcion').forEach(opcion => {
      opcion.addEventListener('click', function () {
        document.querySelectorAll('.reporte-motivo-opcion').forEach(op => op.classList.remove('selected'));
        this.classList.add('selected');
        this.querySelector('input[type="radio"]').checked = true;
      });
    });

    function enviarReporte() {
      const motivoInput = document.querySelector('input[name="reporte-motivo"]:checked');
      const descripcion = document.getElementById('reporteDescripcion').value.trim();

      if (!motivoInput) {
        alert('Elige un motivo para el reporte.');
        return;
      }
      const motivo = motivoInput.value;

      fetch(`/usuario/reportar/${reporteTipoActual}/${reporteIdActual}`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
        body: JSON.stringify({ motivo: motivo, descripcion: descripcion }),
      })
        .then(res => res.json().then(data => ({ ok: res.ok, data })))
        .then(({ ok, data }) => {
          if (!ok) {
            alert('❌ ' + (data.error || 'Ocurrió un error al reportar.'));
            return;
          }
          document.getElementById('modal-reportar').classList.remove('is-active');
          alert('✅ Gracias, tu reporte fue enviado. Un administrador lo revisará.');
        })
        .catch(() => alert('❌ Ocurrió un error de conexión.'));
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
