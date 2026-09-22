<!DOCTYPE html>
<html lang="es" class="has-aside-left has-aside-mobile-transition has-navbar-fixed-top has-aside-expanded">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ $vacante->titulo }} · Empleos Zacapoaxtla</title>

  <!-- CSS Base -->
  <link rel="stylesheet" href="{{ asset('assets/usuario/css/main.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/usuario/css/styles.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.9.95/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="icon" type="img/" href="{{ asset('assets/usuario/img/icono.png') }}">

  <style>
    .detail-card {
      background: white;
      border-radius: 28px;
      overflow: hidden;
      box-shadow: 0 10px 30px rgba(0,0,0,0.08);
      margin-bottom: 2rem;
    }
    .service-image {
      width: 100%;
      height: 280px;
      object-fit: cover;
      display: block;
    }
    .detail-header {
      background: linear-gradient(135deg, #6b1021, #b12d25);
      padding: 1.8rem 2rem;
      color: white;
    }
    .detail-header h1 {
      font-size: 1.8rem;
      font-weight: 800;
      margin-bottom: 0.5rem;
    }
    .company {
      font-size: 1.1rem;
      opacity: 0.9;
      margin-bottom: 0.5rem;
    }
    .author {
      display: flex;
      align-items: center;
      gap: 10px;
      font-size: 0.9rem;
      opacity: 0.9;
      flex-wrap: wrap;
    }
    .info-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 1rem;
      background: #f8fafc;
      padding: 1.5rem;
      border-radius: 20px;
      margin: 1.5rem 0;
    }
    .info-item {
      display: flex;
      align-items: center;
      gap: 0.75rem;
    }
    .info-icon {
      font-size: 1.5rem;
      min-width: 32px;
      color: #ff7a18;
    }
    .contact-buttons {
      display: flex;
      flex-wrap: wrap;
      gap: 1rem;
      margin-top: 1rem;
    }
    .btn-contact {
      background: linear-gradient(45deg, #ff7a18, #ffb347);
      border: none;
      padding: 10px 20px;
      border-radius: 40px;
      font-weight: 600;
      color: white;
      cursor: pointer;
      transition: 0.2s;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      gap: 8px;
    }
    .btn-contact-outline {
      background: white;
      border: 1.5px solid #ff7a18;
      color: #ff7a18;
    }
    .btn-contact-outline:hover {
      background: #ff7a18;
      color: white;
    }
    .btn-contact:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 12px rgba(0,0,0,0.15);
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
    }
    .back-button:hover {
      background: #ff7a18;
      border-color: #ff7a18;
      color: #fff;
      transform: translateX(-3px);
      box-shadow: 0 4px 12px rgba(255,122,24,0.25);
    }
    .pide-box {
      background: #fff3e6;
      border-radius: 14px;
      padding: 14px 18px;
      margin-bottom: 1.5rem;
    }
    .pide-box p { font-size: 14px; color: #8a6d1f; font-weight: 700; margin: 0; }
    .beneficios-box span {
      display: inline-flex; align-items: center; gap: 5px;
      background: #e6f4ea; color: #1e7e34; font-size: 13px; font-weight: 600;
      padding: 6px 14px; border-radius: 20px; margin: 0 8px 8px 0;
    }
    .fechas-box { background: #f8fafc; border-radius: 16px; padding: 16px 20px; margin-bottom: 1.5rem; }
    .fechas-box p { font-size: 14.5px; margin: 0 0 8px; color: #444; }
    .fechas-box p:last-child { margin-bottom: 0; }
  </style>
  <link rel="stylesheet" href="{{ asset('assets/usuario/css/ads-widget.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/usuario/css/theme-conectaya.css') }}">
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
          <span class="crumb-page">Vacante</span>
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

  <!-- ==================== SIDEBAR ==================== -->
  <aside class="aside is-placed-left is-expanded">
    <div class="aside-tools">
      <div class="aside-tools-label"><span><b>Modo Empleador</b></span></div>
    </div>
    <div class="menu is-menu-main">
      <p class="menu-label">Panel</p>
      <ul class="menu-list">
        <li><a href="/usuario/empleador" class="has-icon"><span class="icon"><i class="mdi mdi-desktop-mac"></i></span><span class="menu-item-label">Inicio</span></a></li>
      </ul>
      <p class="menu-label">Acciones</p>
      <ul class="menu-list">
        <li><a href="/usuario/buscar-talento" class="has-icon"><span class="icon"><i class="mdi mdi-magnify"></i></span><span class="menu-item-label">Buscar trabajo</span></a></li>
        <li><a href="/usuario/mis-vacantes" class="has-icon"><span class="icon"><i class="mdi mdi-briefcase"></i></span><span class="menu-item-label">Mis vacantes</span></a></li>
        <li><a href="/usuario/postulantes" class="has-icon"><span class="icon"><i class="mdi mdi-account-group"></i></span><span class="menu-item-label">Postulantes</span></a></li>
        <li><a href="/usuario/publicar-vacante" class="has-icon"><span class="icon"><i class="mdi mdi-plus-circle"></i></span><span class="menu-item-label">Publicar vacante</span></a></li>
        <li><a href="/usuario/profile" class="has-icon"><span class="icon"><i class="mdi mdi-account-circle"></i></span><span class="menu-item-label">Perfil</span></a></li>
      </ul>
    </div>
  </aside>

  <!-- ==================== CONTENIDO PRINCIPAL ==================== -->
  <section class="section is-main-section">
    <div class="container">

      <div class="mb-4">
        <a href="/usuario/empleador" class="back-button">
          <span class="icon"><i class="mdi mdi-arrow-left"></i></span>
          <span>Volver a vacantes</span>
        </a>
      </div>

      <!-- Tarjeta de detalle de la vacante -->
      <div class="detail-card">
        <img class="service-image" src="{{ $vacante->imagen ?? asset('assets/usuario/img/services/frijol.jpg') }}" alt="{{ $vacante->titulo }}">

        <div class="detail-header">
          <h1>{{ $vacante->titulo }}</h1>
          <div class="company">{{ $vacante->contrato }} · {{ $vacante->tipo_pago }}</div>
          <div class="author">
            <i class="mdi mdi-briefcase"></i>
            <span>Publicado por: {{ $vacante->publicante }}</span>
          </div>
          <div style="display:flex; gap:10px; margin-top:10px; align-items:center;">
            <span style="background:rgba(255,255,255,.18); color:#fff; font-size:12.5px; padding:4px 12px; border-radius:20px;">
              <i class="mdi mdi-account"></i> {{ $vacante->postulaciones_count }} postulados
            </span>
            <span style="background:{{ $vacante->estado === 'activa' ? '#dcfce7' : '#f0f0f0' }}; color:{{ $vacante->estado === 'activa' ? '#16a34a' : '#888' }}; font-size:12.5px; font-weight:700; padding:4px 12px; border-radius:20px;">
              {{ ucfirst($vacante->estado === 'activa' ? 'Activo' : $vacante->estado) }}
            </span>
          </div>
        </div>

        <div class="card-content" style="padding: 2rem;">
          <!-- Información rápida -->
          <div class="info-grid">
            <div class="info-item">
              <span class="info-icon"><i class="mdi mdi-map-marker"></i></span>
              <div><strong>Ubicación</strong><br>{{ $vacante->ubicacion }}</div>
            </div>
            <div class="info-item">
              <span class="info-icon"><i class="mdi mdi-cash"></i></span>
              <div><strong>Salario</strong><br>{{ $vacante->salario }}</div>
            </div>
            <div class="info-item">
              <span class="info-icon"><i class="mdi mdi-school"></i></span>
              <div><strong>Experiencia</strong><br>{{ $vacante->experiencia }}</div>
            </div>
            <div class="info-item">
              <span class="info-icon"><i class="mdi mdi-account-multiple"></i></span>
              <div><strong>Trabajadores requeridos</strong><br>{{ $vacante->trabajadores_requeridos }}</div>
            </div>
          </div>

          @if ($vacante->requiere_cv || $vacante->requiere_solicitud_empleo)
            <div class="pide-box">
              <p><i class="mdi mdi-file-check-outline"></i> Este empleador pide:
                @if ($vacante->requiere_cv) CV @endif
                @if ($vacante->requiere_cv && $vacante->requiere_solicitud_empleo) y @endif
                @if ($vacante->requiere_solicitud_empleo) Solicitud de Empleo @endif
              </p>
            </div>
          @endif

          <!-- Descripción -->
          <h3 class="title is-5"><i class="mdi mdi-text-box"></i> Descripción</h3>
          <p style="line-height: 1.6; margin-bottom: 1.5rem;">
            {{ $vacante->descripcion }}
          </p>

          @if ($vacante->beneficios && count($vacante->beneficios))
            <h3 class="title is-5"><i class="mdi mdi-check-decagram"></i> Beneficios</h3>
            <div class="beneficios-box" style="margin-bottom: 1.5rem;">
              @foreach ($vacante->beneficios as $beneficio)
                <span><i class="mdi mdi-check-circle"></i> {{ $beneficio }}</span>
              @endforeach
            </div>
          @endif

          <div class="fechas-box">
            <p><i class="mdi mdi-calendar-clock" style="color:#ff7a18;"></i> <strong>Día del trabajo:</strong> {{ $vacante->fecha_trabajo }}</p>
            @if ($vacante->duracion)
              <p><i class="mdi mdi-timer" style="color:#ff7a18;"></i> <strong>Duración:</strong> {{ $vacante->duracion }}</p>
            @endif
            @if ($vacante->fecha_limite)
              <p><i class="mdi mdi-clock-alert" style="color:#ff7a18;"></i> <strong>Límite para postular:</strong> {{ $vacante->fecha_limite->format('d/m/Y') }}</p>
            @endif
          </div>

          @if ($vacante->empleador_id !== auth()->id())
            <!-- Sección de contacto -->
            <h3 class="title is-5"><i class="mdi mdi-phone"></i> Información de Contacto</h3>
            <div class="contact-buttons">
              <a href="tel:{{ $vacante->telefono }}" class="btn-contact"><i class="mdi mdi-phone"></i> Llamar</a>
              @if ($vacante->whatsapp)
                <a href="https://wa.me/52{{ preg_replace('/\D/', '', $vacante->whatsapp) }}?text={{ urlencode('Hola, me interesa el trabajo de ' . $vacante->titulo) }}" target="_blank" class="btn-contact" style="background:#25D366;"><i class="mdi mdi-whatsapp"></i> WhatsApp</a>
              @endif
            </div>
          @endif

          <!-- Reportar -->
          <div class="has-text-right" style="margin-top:1.5rem; margin-bottom:0;">
            @if ($vacante->empleador_id !== auth()->id())
              <button type="button" onclick="abrirModalReportar('vacante', {{ $vacante->id }})" style="background:none; border:none; color:#c0392b; font-size:13px; cursor:pointer;">
                <i class="mdi mdi-flag-outline"></i> Reportar esta vacante
              </button>
            @endif
          </div>

          <!-- Comentarios -->
          <div style="margin-top: 1rem;">
            <h3 class="title is-5"><i class="mdi mdi-comment-multiple-outline"></i> Comentarios</h3>
            <div id="comentariosLista" style="max-height:400px; overflow-y:auto; display:flex; flex-direction:column; gap:10px; margin-bottom:14px; background:#f8fafc; border-radius:12px; padding:16px;">
              <p class="has-text-grey has-text-centered">Cargando comentarios...</p>
            </div>
            <div style="display:flex; gap:8px; align-items:center;">
              <input type="text" id="comentarioNuevo" placeholder="Escribe un comentario..." maxlength="500"
                style="flex:1; border:1px solid #dcdfe4; border-radius:20px; padding:10px 16px; outline:none; font-size:14px;"
                onkeydown="if(event.key==='Enter'){event.preventDefault(); enviarComentario();}">
              <button onclick="enviarComentario()" title="Enviar"
                style="background:linear-gradient(45deg,#ff7a18,#ffb347); border:none; color:#fff; width:40px; height:40px; border-radius:50%; display:flex; align-items:center; justify-content:center; cursor:pointer; flex-shrink:0;">
                <i class="mdi mdi-send" style="font-size:18px;"></i>
              </button>
            </div>
          </div>

          <!-- Botón de acción principal -->
          <div class="has-text-centered mt-5">
            @if ($vacante->empleador_id === auth()->id())
              <p class="has-text-grey">Esta es tu propia vacante.</p>
              <a href="/usuario/mis-vacantes/{{ $vacante->id }}/editar" class="button is-primary btn-accion-principal" style="background: linear-gradient(45deg, #ff7a18, #ffb347); border: none; border-radius: 50px; font-weight: bold; color: #fff;">
                <i class="mdi mdi-pencil"></i> Editar vacante
              </a>
            @elseif ($yaPostulado)
              <button class="button is-large btn-accion-principal" disabled style="border-radius: 50px; font-weight: bold;">
                Ya te postulaste a esta vacante
              </button>
            @else
              <button class="button is-primary is-large btn-accion-principal" id="btnPostularme" style="background: linear-gradient(45deg, #ff7a18, #ffb347); border: none; border-radius: 50px; font-weight: bold;">
                Postularme
              </button>
            @endif
          </div>
        </div>
      </div>
    </div>

    <style>
      .btn-accion-principal { padding: 12px 40px; white-space: normal; height: auto; }
      @media (max-width: 480px) {
        .btn-accion-principal { width: 100%; max-width: 320px; padding: 12px 20px; font-size: 0.95rem; }
      }
    </style>
  </section>

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

<!-- MODAL: Postularse -->
<div id="modal-postular" class="modal modal-postular" style="position:fixed; top:0; left:0; right:0; bottom:0;">
  <div class="modal-background" onclick="document.getElementById('modal-postular').classList.remove('is-active')" style="background:rgba(26,26,46,0.55); backdrop-filter:blur(2px);"></div>
  <div class="modal-card">
    <header class="modal-card-head" style="background:linear-gradient(135deg,#ff7a18,#ffb347); border:none; padding:20px 22px;">
      <p class="modal-card-title" style="color:#fff; font-size:1.05rem; display:flex; align-items:center; gap:8px;">
        <i class="mdi mdi-send-circle-outline" style="font-size:1.3rem;"></i>
        Postularte a <span id="modalPostularTitulo" style="font-weight:800;"></span>
      </p>
      <button class="delete" aria-label="close" onclick="document.getElementById('modal-postular').classList.remove('is-active')" style="background:rgba(255,255,255,0.3);"></button>
    </header>
    <section class="modal-card-body" style="background:#fffaf5; padding:22px;">
      <div id="modalPostularDocs"></div>
      <div class="field">
        <label class="label" style="color:#3c2f2f; font-size:0.85rem;"><i class="mdi mdi-message-text-outline" style="color:#ff7a18;"></i>&nbsp;Mensaje / carta de presentación (opcional)</label>
        <div class="control">
          <textarea id="modalPostularMensaje" class="textarea" rows="4" placeholder="Cuéntale al empleador por qué eres una buena opción..." style="border-radius:12px; border:1.5px solid #f0d9c0; resize:vertical;"></textarea>
        </div>
      </div>
    </section>
    <footer class="modal-card-foot" style="background:#fff; border-top:1px solid #f5ebe0; padding:16px 22px; display:flex; gap:10px;">
      <button id="btnEnviarPostulacion" onclick="enviarPostulacion()" style="background:linear-gradient(45deg,#ff7a18,#ffb347); border:none; color:#fff; padding:10px 22px; border-radius:30px; font-weight:700; font-size:13.5px; cursor:pointer; display:inline-flex; align-items:center; gap:6px; box-shadow:0 4px 12px rgba(255,122,24,0.3);">
        <i class="mdi mdi-send"></i> Enviar postulación
      </button>
      <button onclick="document.getElementById('modal-postular').classList.remove('is-active')" style="background:#fff; border:1.5px solid #e5e7eb; color:#5f6368; padding:10px 22px; border-radius:30px; font-weight:600; font-size:13.5px; cursor:pointer;">
        Cancelar
      </button>
    </footer>
  </div>
</div>
<style>
  .modal-postular .modal-card { border-radius: 20px; overflow: hidden; box-shadow: 0 24px 60px rgba(255, 122, 24, 0.25); }
</style>

<!-- MODAL: Mini perfil -->
<div id="modal-perfil-comentario" class="modal" style="position:fixed; top:0; left:0; right:0; bottom:0;" onclick="document.getElementById('modal-perfil-comentario').classList.remove('is-active')">
  <div class="modal-background" onclick="document.getElementById('modal-perfil-comentario').classList.remove('is-active')" style="background:rgba(107,16,33,0.90);"></div>
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
      <p class="modal-card-title" style="color:#fff; font-size:1rem;"><i class="mdi mdi-flag-outline"></i> Reportar publicación</p>
      <button class="delete" aria-label="close" onclick="document.getElementById('modal-reportar').classList.remove('is-active')"></button>
    </header>
    <section class="modal-card-body" style="padding:20px;">
      <p style="color:#666; font-size:13px; margin-bottom:16px;">Cuéntanos qué está mal con esta publicación. Un administrador la va a revisar.</p>
      <div id="reporteMotivos" style="display:grid; grid-template-columns:1fr 1fr; gap:10px; margin-bottom:16px;">
        <label class="reporte-motivo-opcion"><input type="radio" name="reporte-motivo" value="Información falsa" style="display:none;"><span><i class="mdi mdi-alert-circle-outline"></i> Información falsa</span></label>
        <label class="reporte-motivo-opcion"><input type="radio" name="reporte-motivo" value="Contenido inapropiado" style="display:none;"><span><i class="mdi mdi-eye-off-outline"></i> Contenido inapropiado</span></label>
        <label class="reporte-motivo-opcion"><input type="radio" name="reporte-motivo" value="Posible fraude o estafa" style="display:none;"><span><i class="mdi mdi-cash-remove"></i> Fraude o estafa</span></label>
        <label class="reporte-motivo-opcion"><input type="radio" name="reporte-motivo" value="Spam o publicidad" style="display:none;"><span><i class="mdi mdi-email-remove-outline"></i> Spam / publicidad</span></label>
        <label class="reporte-motivo-opcion" style="grid-column: span 2;"><input type="radio" name="reporte-motivo" value="Otro" style="display:none;"><span><i class="mdi mdi-dots-horizontal"></i> Otro motivo</span></label>
      </div>
      <div class="field">
        <label class="label" style="font-size:13px;">Describe el problema (opcional)</label>
        <div class="control"><textarea id="reporteDescripcion" class="textarea" rows="3" placeholder="Cuéntanos qué pasó..." maxlength="1000" style="border-radius:10px;"></textarea></div>
      </div>
    </section>
    <footer class="modal-card-foot" style="border-top:1px solid #eee;">
      <button class="button" onclick="enviarReporte()" style="background:linear-gradient(45deg,#d93025,#f36a5a); color:#fff; border:none; border-radius:10px; font-weight:600;"><i class="mdi mdi-flag"></i>&nbsp;Enviar reporte</button>
      <button class="button" onclick="document.getElementById('modal-reportar').classList.remove('is-active')" style="border-radius:10px;">Cancelar</button>
    </footer>
  </div>
</div>

<style>
  .reporte-motivo-opcion { display: flex; align-items: center; gap: 8px; padding: 10px 12px; border: 1.5px solid #e5e7eb; border-radius: 10px; cursor: pointer; font-size: 13px; color: #444; }
  .reporte-motivo-opcion.selected { border-color: #ff7a18; background: #fff1e0; color: #ff7a18; font-weight: 700; }
</style>

<script src="{{ asset('assets/usuario/js/main.js') }}"></script>
<script src="{{ asset('assets/usuario/js/ads-widget.js') }}"></script>
<script>
  const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
  const vacanteIdActual = {{ $vacante->id }};
  let postulacionBorradorId = null;
  let miTieneCvEnBorrador = false;
  let miTieneSolicitudEnBorrador = false;
  const modalRequiereCv = {{ $vacante->requiere_cv ? 'true' : 'false' }};
  const modalRequiereSolicitud = {{ $vacante->requiere_solicitud_empleo ? 'true' : 'false' }};

  const btnPostularme = document.getElementById('btnPostularme');
  if (btnPostularme) {
    btnPostularme.addEventListener('click', function () {
      abrirModalPostular(vacanteIdActual, '{{ addslashes($vacante->titulo) }}', modalRequiereCv, modalRequiereSolicitud);
    });
  }

  function abrirModalPostular(vacanteId, titulo, requiereCv, requiereSolicitud) {
    document.getElementById('modalPostularTitulo').textContent = titulo;
    document.getElementById('modalPostularMensaje').value = '';
    document.getElementById('modal-postular').classList.add('is-active');

    if (!requiereCv && !requiereSolicitud) {
      document.getElementById('modalPostularDocs').innerHTML = '';
      return;
    }

    document.getElementById('modalPostularDocs').innerHTML = '<p style="font-size:12.5px; color:#8a6d1f;"><i class="mdi mdi-loading mdi-spin"></i> Preparando...</p>';

    fetch(`/usuario/empleador/postulacion-borrador/${vacanteId}`, { method: 'POST', headers: { 'X-CSRF-TOKEN': csrfToken } })
      .then(res => res.json().then(data => ({ ok: res.ok, data })))
      .then(({ ok, data }) => {
        if (!ok) { document.getElementById('modal-postular').classList.remove('is-active'); alert('❌ ' + (data.error || 'Ocurrió un error.')); return; }
        postulacionBorradorId = data.postulacionId;
        fetch(`/usuario/postulantes/${postulacionBorradorId}/cv/estado`)
          .then(res => res.json())
          .then(estado => {
            if (estado.ok) { miTieneCvEnBorrador = estado.tieneCv; miTieneSolicitudEnBorrador = estado.tieneSolicitud; }
            pintarDocsModal();
          })
          .catch(() => pintarDocsModal());
      })
      .catch(() => alert('❌ Ocurrió un error de conexión.'));
  }

  function pintarDocsModal() {
    const cont = document.getElementById('modalPostularDocs');
    let html = '<div style="background:#fff3e6; border:1px solid #ffe0bd; border-radius:14px; padding:16px; margin-bottom:18px;"><p style="font-size:12.5px; color:#c96410; font-weight:700; margin-bottom:10px;"><i class="mdi mdi-file-check-outline"></i> Este empleador pide lo siguiente:</p>';
    if (modalRequiereCv) {
      html += miTieneCvEnBorrador
        ? '<div style="font-size:13px; color:#1e7e34; font-weight:600; margin-bottom:10px;"><i class="mdi mdi-check-circle"></i> CV listo</div>'
        : `<div style="margin-bottom:10px;"><div style="font-size:13px; color:#d93025; margin-bottom:8px; font-weight:600;"><i class="mdi mdi-alert-circle-outline"></i> Falta tu CV</div>
            <a href="/usuario/postulantes/${postulacionBorradorId}/cv/crear" style="background:linear-gradient(45deg,#ff7a18,#ffb347); color:#fff; border:none; padding:7px 16px; border-radius:20px; font-size:12.5px; font-weight:700; text-decoration:none; margin-right:8px; display:inline-block;">Crear CV</a>
            <label style="background:#fff; border:1.5px solid #ff7a18; color:#ff7a18; padding:6px 16px; border-radius:20px; font-size:12.5px; font-weight:700; cursor:pointer; display:inline-block;">Subir CV<input type="file" accept=".pdf,.doc,.docx" style="display:none;" onchange="subirCvDesdeModal(this)"></label></div>`;
    }
    if (modalRequiereSolicitud) {
      html += miTieneSolicitudEnBorrador
        ? '<div style="font-size:13px; color:#1e7e34; font-weight:600;"><i class="mdi mdi-check-circle"></i> Solicitud de Empleo lista</div>'
        : `<div><div style="font-size:13px; color:#d93025; margin-bottom:8px; font-weight:600;"><i class="mdi mdi-alert-circle-outline"></i> Falta tu Solicitud</div>
            <label style="background:#fff; border:1.5px solid #ff7a18; color:#ff7a18; padding:6px 16px; border-radius:20px; font-size:12.5px; font-weight:700; cursor:pointer; display:inline-block;">Subir Solicitud (PDF)<input type="file" accept=".pdf" style="display:none;" onchange="subirSolicitudDesdeModal(this)"></label></div>`;
    }
    html += '</div>';
    cont.innerHTML = html;
  }

  function subirCvDesdeModal(input) {
    const file = input.files[0]; if (!file || !postulacionBorradorId) return;
    const fd = new FormData(); fd.append('archivo', file);
    fetch(`/usuario/postulantes/${postulacionBorradorId}/cv/subir-archivo`, { method: 'POST', headers: { 'X-CSRF-TOKEN': csrfToken }, body: fd })
      .then(res => res.json().then(data => ({ ok: res.ok, data })))
      .then(({ ok }) => { if (!ok) { alert('❌ Ocurrió un error al subir tu CV.'); return; } miTieneCvEnBorrador = true; pintarDocsModal(); })
      .catch(() => alert('❌ Ocurrió un error de conexión.'));
  }

  function subirSolicitudDesdeModal(input) {
    const file = input.files[0]; if (!file || !postulacionBorradorId) return;
    const fd = new FormData(); fd.append('archivo', file);
    fetch(`/usuario/postulantes/${postulacionBorradorId}/solicitud-empleo`, { method: 'POST', headers: { 'X-CSRF-TOKEN': csrfToken }, body: fd })
      .then(res => res.json().then(data => ({ ok: res.ok, data })))
      .then(({ ok }) => { if (!ok) { alert('❌ Ocurrió un error al subir tu Solicitud.'); return; } miTieneSolicitudEnBorrador = true; pintarDocsModal(); })
      .catch(() => alert('❌ Ocurrió un error de conexión.'));
  }

  function enviarPostulacion() {
    const mensaje = document.getElementById('modalPostularMensaje').value.trim();
    fetch(`/usuario/empleador/postularse/${vacanteIdActual}`, {
      method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken }, body: JSON.stringify({ mensaje }),
    })
      .then(res => res.json().then(data => ({ ok: res.ok, data })))
      .then(({ ok, data }) => {
        if (!ok) { alert('❌ ' + (data.error || 'Ocurrió un error al postularte.')); return; }
        document.getElementById('modal-postular').classList.remove('is-active');
        alert('✅ ¡Postulación enviada!');
        location.reload();
      })
      .catch(() => alert('❌ Ocurrió un error de conexión.'));
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
      const csrfToken = csrfMeta ? csrfMeta.content : '';
      fetch('/logout', { method: 'POST', headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' } }).finally(() => { window.location.href = '/'; });
    });
  })();
</script>

<script>
  const csrfTokenComentarios = document.querySelector('meta[name="csrf-token"]').content;
  const vacanteIdComentarios = {{ $vacante->id }};

  document.addEventListener('DOMContentLoaded', cargarComentarios);

  function cargarComentarios() {
    fetch(`/usuario/comentarios/vacante/${vacanteIdComentarios}`)
      .then(res => res.json())
      .then(data => pintarComentarios(data.comentarios))
      .catch(() => {
        document.getElementById('comentariosLista').innerHTML = '<p class="has-text-danger has-text-centered">No se pudieron cargar los comentarios.</p>';
      });
  }

  function pintarComentarios(comentarios) {
    const cont = document.getElementById('comentariosLista');
    if (!comentarios.length) {
      cont.innerHTML = '<p class="has-text-grey has-text-centered" style="margin-top:10px;">Todavía no hay comentarios.<br>¡Sé el primero en escribir uno!</p>';
      return;
    }
    const ordenados = [...comentarios].reverse();
    cont.innerHTML = ordenados.map(c => burbujaComentario(c)).join('');
    cont.scrollTop = cont.scrollHeight;
  }

  function inicialesDe(nombre) {
    return (nombre || '?').split(' ').filter(Boolean).slice(0, 2).map(p => p[0].toUpperCase()).join('');
  }

  function escaparHtml(texto) {
    const div = document.createElement('div');
    div.textContent = texto;
    return div.innerHTML;
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

  function burbujaComentario(c) {
    if (c.esMio) {
      return `
        <div id="comentario-${c.id}" style="align-self:flex-end; max-width:78%; display:flex; flex-direction:column; align-items:flex-end;">
          <div class="burbuja-contenido" style="background:linear-gradient(45deg,#ff7a18,#ffb347); color:#fff; padding:8px 14px; border-radius:16px 16px 4px 16px; font-size:14px; line-height:1.4; word-break:break-word;">
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

  function enviarComentario() {
    const input = document.getElementById('comentarioNuevo');
    const contenido = input.value.trim();
    if (!contenido) return;

    fetch(`/usuario/comentarios/vacante/${vacanteIdComentarios}`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfTokenComentarios },
      body: JSON.stringify({ contenido: contenido }),
    })
      .then(res => res.json())
      .then(data => {
        if (!data.ok) { alert('❌ Ocurrió un error al comentar.'); return; }
        input.value = '';
        const cont = document.getElementById('comentariosLista');
        if (cont.querySelector('p')) cont.innerHTML = '';
        cont.insertAdjacentHTML('beforeend', burbujaComentario(data.comentario));
        cont.scrollTop = cont.scrollHeight;
      })
      .catch(() => alert('❌ Ocurrió un error de conexión.'));
  }

  function editarComentario(id) {
    const wrapper = document.getElementById(`comentario-${id}`);
    const span = wrapper.querySelector('.burbuja-texto');
    const textoActual = span.textContent;

    wrapper.querySelector('.burbuja-contenido').innerHTML = `
      <input type="text" value="${textoActual.replace(/"/g, '&quot;')}" id="edit-input-${id}"
        style="border:none; outline:none; background:rgba(255,255,255,.25); color:#fff; padding:2px 6px; border-radius:6px; width:100%; font-size:14px;"
        onkeydown="if(event.key==='Enter'){event.preventDefault(); guardarEdicion(${id});} if(event.key==='Escape'){cargarComentarios();}">
    `;
    document.getElementById(`edit-input-${id}`).focus();

    const acciones = wrapper.querySelector('div:last-child');
    acciones.innerHTML = `
      <button onclick="guardarEdicion(${id})" style="background:none; border:none; cursor:pointer; padding:0; color:#16a34a; font-size:11px; font-weight:700;">Guardar</button>
      <button onclick="cargarComentarios()" style="background:none; border:none; cursor:pointer; padding:0; color:#8a8d91; font-size:11px;">Cancelar</button>
    `;
  }

  function guardarEdicion(id) {
    const input = document.getElementById(`edit-input-${id}`);
    const nuevoContenido = input.value.trim();
    if (!nuevoContenido) return;

    fetch(`/usuario/comentarios/${id}`, {
      method: 'PATCH',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfTokenComentarios },
      body: JSON.stringify({ contenido: nuevoContenido }),
    })
      .then(res => res.json())
      .then(data => {
        if (!data.ok) { alert('❌ Ocurrió un error al editar.'); return; }
        cargarComentarios();
      })
      .catch(() => alert('❌ Ocurrió un error de conexión.'));
  }

  function eliminarComentario(id) {
    if (!confirm('¿Eliminar este comentario?')) return;

    fetch(`/usuario/comentarios/${id}`, {
      method: 'DELETE',
      headers: { 'X-CSRF-TOKEN': csrfTokenComentarios },
    })
      .then(res => res.json())
      .then(data => {
        if (!data.ok) { alert('❌ Ocurrió un error al eliminar.'); return; }
        const el = document.getElementById(`comentario-${id}`);
        if (el) el.remove();
      })
      .catch(() => alert('❌ Ocurrió un error de conexión.'));
  }

  // ===== Reportar =====
  function abrirModalReportar(tipo, id) {
    document.querySelectorAll('input[name="reporte-motivo"]').forEach(r => r.checked = false);
    document.querySelectorAll('.reporte-motivo-opcion').forEach(op => op.classList.remove('selected'));
    document.getElementById('reporteDescripcion').value = '';
    document.getElementById('modal-reportar').classList.add('is-active');
  }

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

    if (!motivoInput) { alert('Elige un motivo para el reporte.'); return; }

    fetch(`/usuario/reportar/vacante/${vacanteIdComentarios}`, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfTokenComentarios },
      body: JSON.stringify({ motivo: motivoInput.value, descripcion: descripcion }),
    })
      .then(res => res.json().then(data => ({ ok: res.ok, data })))
      .then(({ ok, data }) => {
        if (!ok) { alert('❌ ' + (data.error || 'Ocurrió un error al reportar.')); return; }
        document.getElementById('modal-reportar').classList.remove('is-active');
        alert('✅ Gracias, tu reporte fue enviado. Un administrador lo revisará.');
      })
      .catch(() => alert('❌ Ocurrió un error de conexión.'));
  }
</script>
</body>
</html>
