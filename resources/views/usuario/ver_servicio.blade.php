<!DOCTYPE html>
<html lang="es" class="has-aside-left has-aside-mobile-transition has-navbar-fixed-top has-aside-expanded">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ $servicio->titulo }} · Empleos Zacapoaxtla</title>

  <!-- CSS Base -->
  <link rel="stylesheet" href="{{ asset('assets/usuario/css/main.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/usuario/css/styles.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.9.95/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="icon" type="img/" href="{{ asset('assets/usuario/img/icono.png') }}">

  <style>
    /* Estilos específicos para la página de detalle */
    .detail-card {
      background: white;
      border-radius: 28px;
      overflow: hidden;
      box-shadow: 0 10px 30px rgba(0,0,0,0.08);
      margin-bottom: 2rem;
    }
    .service-image {
      width: 100%;
      height: auto;
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
    }
    .author-avatar {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      object-fit: cover;
      background: #ff7a18;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-weight: bold;
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

  <!-- ==================== SIDEBAR ==================== -->
  <aside class="aside is-placed-left is-expanded">
    <div class="aside-tools">
      <div class="aside-tools-label"><span><b>Modo Empleado</b></span></div>
    </div>
    <div class="menu is-menu-main">
      <p class="menu-label">General</p>
      <ul class="menu-list">
        <li><a href="/usuario" class="has-icon"><span class="icon"><i class="mdi mdi-desktop-mac"></i></span><span class="menu-item-label">Inicio</span></a></li>
      </ul>
      <p class="menu-label">Otros</p>
      <ul class="menu-list">
        <li><a href="/usuario/verEmpleos" class="has-icon"><span class="icon"><i class="mdi mdi-briefcase"></i></span><span class="menu-item-label">Ver empleos</span></a></li>
        <li><a href="/usuario/publicarEmpleo" class="has-icon"><span class="icon"><i class="mdi mdi-square-edit-outline"></i></span><span class="menu-item-label">Publicar empleo</span></a></li>
        <li><a href="/usuario/misEmpleos" class="has-icon"><span class="icon"><i class="mdi mdi-format-list-bulleted"></i></span><span class="menu-item-label">Mis empleos</span></a></li>
        <li><a href="/usuario/profile" class="has-icon"><span class="icon"><i class="mdi mdi-account-circle"></i></span><span class="menu-item-label">Perfil</span></a></li>
      </ul>
    </div>
  </aside>

  <!-- ==================== CONTENIDO PRINCIPAL ==================== -->
  <section class="section is-main-section">
    <div class="container">

      <!-- Botón volver con mejor espaciado -->
      <div class="mb-4">
        <a href="/usuario/verEmpleos" class="back-button">
          <span class="icon"><i class="mdi mdi-arrow-left"></i></span>
          <span>Volver a servicios</span>
        </a>
      </div>

      @php
        $nombre = $servicio->usuario->nombre ?? 'Usuario';
        $iniciales = collect(explode(' ', $nombre))->map(fn($w) => mb_strtoupper(mb_substr($w, 0, 1)))->take(2)->implode('');
      @endphp

      <!-- Tarjeta de detalle del servicio -->
      <div class="detail-card">
        <img class="service-image" src="{{ $servicio->imagen ?? asset('assets/usuario/img/services/plomero.jpg') }}" alt="{{ $servicio->titulo }}">

        <div class="detail-header">
          <h1>{{ $servicio->titulo }}</h1>
          <div class="company">{{ $servicio->categoria->nombre ?? '' }}{{ $servicio->subcategoria ? ' · ' . $servicio->subcategoria->nombre : '' }}</div>
          <div class="author">
            @if ($servicio->usuario->foto_perfil)
              <img class="author-avatar" src="{{ $servicio->usuario->foto_perfil }}" alt="{{ $nombre }}">
            @else
              <span class="author-avatar">{{ $iniciales }}</span>
            @endif
            <span>Publicado por: {{ $nombre }}</span>
            @if ($servicio->usuario->verificacion_estado === 'aprobado')
              <span title="Identidad verificada" style="background:#dcfce7; color:#16a34a; padding:2px 10px; border-radius:20px; font-size:.75rem; font-weight:700; margin-left:8px;">
                <i class="mdi mdi-check-decagram"></i> Verificado
              </span>
            @endif
          </div>
        </div>

        <div class="card-content" style="padding: 2rem;">
          <!-- Información rápida -->
          <div class="info-grid">
            <div class="info-item">
              <span class="info-icon"><i class="mdi mdi-map-marker"></i></span>
              <div><strong>Ubicación</strong><br>{{ $servicio->ubicacion ?? $servicio->usuario->localidad->nombre ?? '—' }}</div>
            </div>
            <div class="info-item">
              <span class="info-icon"><i class="mdi mdi-shape"></i></span>
              <div><strong>Categoría</strong><br>{{ $servicio->categoria->nombre ?? '—' }}</div>
            </div>
            <div class="info-item">
              <span class="info-icon"><i class="mdi mdi-cash"></i></span>
              <div><strong>Precio</strong><br>${{ number_format($servicio->precio, 2) }} MXN</div>
            </div>
          </div>

          <!-- Descripción del servicio -->
          <h3 class="title is-5"><i class="mdi mdi-text-box"></i> Descripción</h3>
          <p style="line-height: 1.6; margin-bottom: 2rem;">
            {{ $servicio->descripcion }}
          </p>

          @if (!$esPropio)
            <!-- Sección de contacto -->
            <h3 class="title is-5"><i class="mdi mdi-phone"></i> Información de Contacto</h3>
            @php
              $telefono = $servicio->telefono ?? $servicio->usuario->telefono;
              $whatsapp = $servicio->whatsapp ?? $servicio->usuario->whatsapp ?? $telefono;
            @endphp
            <div class="contact-buttons">
              @if ($telefono)
                <a href="tel:{{ $telefono }}" class="btn-contact"><i class="mdi mdi-phone"></i> Llamar</a>
              @endif
              @if ($whatsapp)
                <a href="https://wa.me/52{{ preg_replace('/\D/', '', $whatsapp) }}" target="_blank" class="btn-contact" style="background:#25D366;"><i class="mdi mdi-whatsapp"></i> WhatsApp</a>
              @endif
              @if ($servicio->usuario->email)
                <a href="mailto:{{ $servicio->usuario->email }}" class="btn-contact btn-contact-outline"><i class="mdi mdi-email"></i> Enviar Email</a>
              @endif
            </div>
          @endif

          <!-- Calificaciones -->
          <div style="margin-top: 2rem;">
            <h3 class="title is-5"><i class="mdi mdi-star-circle"></i> Calificaciones</h3>

            <div id="calificacionesResumen" style="display:flex; gap:24px; align-items:center; background:#f8fafc; border-radius:16px; padding:20px; margin-bottom:20px; flex-wrap:wrap;">
              <div style="text-align:center; min-width:110px;">
                <div id="califPromedio" style="font-size:2.6rem; font-weight:800; color:#1a1a2e; line-height:1;">0.0</div>
                <div id="califEstrellasPromedio" style="color:#ffb347; font-size:1.1rem; margin-top:4px;">☆☆☆☆☆</div>
                <div id="califTotal" style="color:#888; font-size:.8rem; margin-top:4px;">0 reseñas</div>
              </div>
              <div id="califDistribucion" style="flex:1; min-width:200px; display:flex; flex-direction:column; gap:4px;"></div>
            </div>

            @if (!$esPropio)
              <div id="calificarForm" style="display:none; background:#fff; border:1px solid #eee; border-radius:14px; padding:18px; margin-bottom:20px;">
                <p style="font-weight:700; margin-bottom:10px;" id="calificarTitulo">Deja tu calificación</p>
                <div id="calificarEstrellas" style="font-size:1.8rem; color:#ddd; cursor:pointer; margin-bottom:12px;">
                  <span data-val="1">★</span><span data-val="2">★</span><span data-val="3">★</span><span data-val="4">★</span><span data-val="5">★</span>
                </div>
                <textarea id="calificarComentario" class="textarea" rows="2" placeholder="Cuéntanos tu experiencia (opcional)" maxlength="500" style="border-radius:10px; margin-bottom:10px;"></textarea>
                <button onclick="enviarCalificacion()" class="button" style="background:linear-gradient(45deg,#ff7a18,#ffb347); color:#fff; border:none; border-radius:10px; font-weight:600;">
                  <i class="mdi mdi-send"></i>&nbsp;<span id="calificarBotonTexto">Enviar</span>
                </button>
              </div>

              <div id="calificarBloqueado" style="display:none; background:#f8fafc; border:1px dashed #ddd; border-radius:14px; padding:18px; margin-bottom:20px; text-align:center; color:#888; font-size:.9rem;">
                <i class="mdi mdi-lock-outline" style="font-size:1.5rem; display:block; margin-bottom:6px; color:#bbb;"></i>
                Solicita este servicio y espera a que el trabajador la acepte para poder calificarlo.
              </div>
            @endif

            <div id="calificacionesLista" style="display:flex; flex-direction:column; gap:14px;">
              <p class="has-text-grey has-text-centered">Cargando reseñas...</p>
            </div>
          </div>

          <!-- Reportar -->
          <div class="has-text-right" style="margin-top:10px; margin-bottom:0;">
            @if (!$esPropio)
              <button type="button" onclick="abrirModalReportar('servicio', {{ $servicio->id }})" style="background:none; border:none; color:#c0392b; font-size:13px; cursor:pointer;">
                <i class="mdi mdi-flag-outline"></i> Reportar este servicio
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
            @if ($esPropio)
              <p class="has-text-grey">Este es tu propio servicio.</p>
              <a href="/usuario/misEmpleos" class="button is-light btn-accion-principal">Ir a Mis Empleos</a>
            @elseif ($yaSolicitado)
              <button class="button is-large btn-accion-principal" disabled style="border-radius: 50px; font-weight: bold;">
                Ya solicitaste este servicio
              </button>
            @else
              <button class="button is-primary is-large btn-accion-principal" id="btnSolicitar" style="background: linear-gradient(45deg, #ff7a18, #ffb347); border: none; border-radius: 50px; font-weight: bold;">
                Solicitar este servicio
              </button>
            @endif
          </div>
        </div>
      </div>
    </div>

    <style>
      .btn-accion-principal {
        padding: 12px 40px;
        white-space: normal;
        height: auto;
      }
      @media (max-width: 480px) {
        .btn-accion-principal {
          width: 100%;
          max-width: 320px;
          padding: 12px 20px;
          font-size: 0.95rem;
        }
      }
    </style>
  </section>

  <!-- ==================== FOOTER ==================== -->
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

<!-- MODAL: Mini perfil (al darle clic al avatar de un comentario) -->
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
      <p class="modal-card-title" style="color:#fff; font-size:1rem;">
        <i class="mdi mdi-flag-outline"></i> Reportar publicación
      </p>
      <button class="delete" aria-label="close" onclick="document.getElementById('modal-reportar').classList.remove('is-active')"></button>
    </header>
    <section class="modal-card-body" style="padding:20px;">
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
    <footer class="modal-card-foot" style="border-top:1px solid #eee;">
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
</style>

<script src="{{ asset('assets/usuario/js/main.js') }}"></script>
<script src="{{ asset('assets/usuario/js/ads-widget.js') }}"></script>
<script>
  const btnSolicitar = document.getElementById('btnSolicitar');
  if (btnSolicitar) {
    btnSolicitar.addEventListener('click', function () {
      const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
      fetch('/usuario/ver_servicio/{{ $servicio->id }}/solicitar', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
      })
        .then(res => res.json().then(data => ({ ok: res.ok, data })))
        .then(({ ok, data }) => {
          if (!ok) {
            alert('❌ ' + (data.error || 'Ocurrió un error al solicitar el servicio.'));
            return;
          }
          alert('✅ ¡Solicitud enviada! El trabajador podrá revisarla y contactarte.');
          location.reload();
        })
        .catch(() => alert('❌ Ocurrió un error de conexión. Intenta de nuevo.'));
    });
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
        fetch('/logout', {
          method: 'POST',
          headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
        }).finally(() => { window.location.href = '/'; });
      });
    })();
  </script>

  <script>
    const csrfTokenComentarios = document.querySelector('meta[name="csrf-token"]').content;
    const servicioIdActual = {{ $servicio->id }};

    // ===== Comentarios (en línea, directo en la página) =====
    document.addEventListener('DOMContentLoaded', cargarComentarios);
    document.addEventListener('DOMContentLoaded', cargarCalificaciones);

    // ===== Calificaciones (estrellas, estilo Play Store) =====
    let estrellaSeleccionada = 0;

    document.querySelectorAll('#calificarEstrellas span').forEach(span => {
      span.addEventListener('click', function () {
        estrellaSeleccionada = parseInt(this.dataset.val);
        pintarEstrellasSeleccion(estrellaSeleccionada);
      });
      span.addEventListener('mouseenter', function () {
        pintarEstrellasSeleccion(parseInt(this.dataset.val));
      });
    });
    const contEstrellas = document.getElementById('calificarEstrellas');
    if (contEstrellas) {
      contEstrellas.addEventListener('mouseleave', function () {
        pintarEstrellasSeleccion(estrellaSeleccionada);
      });
    }

    function pintarEstrellasSeleccion(valor) {
      document.querySelectorAll('#calificarEstrellas span').forEach(span => {
        span.style.color = parseInt(span.dataset.val) <= valor ? '#ffb347' : '#ddd';
      });
    }

    function estrellasTexto(valor) {
      const llenas = Math.round(valor);
      return '★'.repeat(llenas) + '☆'.repeat(5 - llenas);
    }

    function cargarCalificaciones() {
      fetch(`/usuario/calificaciones/${servicioIdActual}`)
        .then(res => res.json())
        .then(data => pintarCalificaciones(data))
        .catch(() => {
          document.getElementById('calificacionesLista').innerHTML = '<p class="has-text-danger has-text-centered">No se pudieron cargar las reseñas.</p>';
        });
    }

    function pintarCalificaciones(data) {
      document.getElementById('califPromedio').textContent = data.promedio.toFixed(1);
      document.getElementById('califEstrellasPromedio').textContent = estrellasTexto(data.promedio);
      document.getElementById('califTotal').textContent = data.total + (data.total === 1 ? ' reseña' : ' reseñas');

      const distCont = document.getElementById('califDistribucion');
      distCont.innerHTML = [5, 4, 3, 2, 1].map(n => `
        <div style="display:flex; align-items:center; gap:8px; font-size:.78rem;">
          <span style="width:34px; color:#888;">${n} ★</span>
          <div style="flex:1; background:#e8eaed; border-radius:8px; height:8px; overflow:hidden;">
            <div style="width:${data.distribucion[n].porcentaje}%; background:#ffb347; height:100%;"></div>
          </div>
          <span style="width:26px; color:#888; text-align:right;">${data.distribucion[n].cantidad}</span>
        </div>
      `).join('');

      const formEl = document.getElementById('calificarForm');
      const bloqueadoEl = document.getElementById('calificarBloqueado');
      if (formEl && bloqueadoEl) {
        if (data.puedeCalificar) {
          formEl.style.display = 'block';
          bloqueadoEl.style.display = 'none';
        } else {
          formEl.style.display = 'none';
          bloqueadoEl.style.display = 'block';
        }
      }

      if (data.miCalificacion) {
        estrellaSeleccionada = data.miCalificacion.estrellas;
        pintarEstrellasSeleccion(estrellaSeleccionada);
        const comentarioEl = document.getElementById('calificarComentario');
        if (comentarioEl) comentarioEl.value = data.miCalificacion.comentario || '';
        const tituloEl = document.getElementById('calificarTitulo');
        if (tituloEl) tituloEl.textContent = 'Tu calificación';
        const botonEl = document.getElementById('calificarBotonTexto');
        if (botonEl) botonEl.textContent = 'Actualizar';
      }

      const lista = document.getElementById('calificacionesLista');
      if (!data.resenas.length) {
        lista.innerHTML = '<p class="has-text-grey has-text-centered">Todavía no hay reseñas para este servicio. ¡Sé el primero en calificarlo!</p>';
        return;
      }
      lista.innerHTML = data.resenas.map(r => `
        <div style="display:flex; gap:10px; border-bottom:1px solid #f0f0f0; padding-bottom:12px;">
          <div onclick="verPerfilComentario(${r.usuario_id}, '${escapeJs(r.nombre)}', '${escapeJs(r.foto || '')}', false, '', '', '', 0)"
            style="width:36px; height:36px; border-radius:50%; background:#6b1021; color:#fff; display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:700; flex-shrink:0; cursor:pointer; overflow:hidden;">
            ${r.foto ? `<img src="${r.foto}" style="width:100%; height:100%; object-fit:cover;">` : inicialesDe(r.nombre)}
          </div>
          <div style="flex:1;">
            <div style="display:flex; justify-content:space-between; align-items:center;">
              <strong style="font-size:.9rem;">${escaparHtml(r.nombre)}</strong>
              <small style="color:#999;">${r.fecha}</small>
            </div>
            <div style="color:#ffb347; font-size:.95rem; margin:2px 0;">${estrellasTexto(r.estrellas)}</div>
            ${r.comentario ? `<p style="font-size:.88rem; color:#444; margin-top:2px;">${escaparHtml(r.comentario)}</p>` : ''}
            ${r.esMia ? `<button onclick="eliminarCalificacion(${r.id})" style="background:none; border:none; color:#c0392b; font-size:11px; cursor:pointer; padding:0; margin-top:4px;"><i class="mdi mdi-trash-can-outline"></i> Eliminar</button>` : ''}
          </div>
        </div>
      `).join('');
    }

    function enviarCalificacion() {
      if (!estrellaSeleccionada) {
        alert('Elige al menos una estrella.');
        return;
      }
      const comentario = document.getElementById('calificarComentario').value.trim();

      fetch(`/usuario/calificaciones/${servicioIdActual}`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfTokenComentarios },
        body: JSON.stringify({ estrellas: estrellaSeleccionada, comentario: comentario }),
      })
        .then(res => res.json().then(data => ({ ok: res.ok, data })))
        .then(({ ok, data }) => {
          if (!ok) { alert('❌ ' + (data.error || 'Ocurrió un error al calificar.')); return; }
          cargarCalificaciones();
        })
        .catch(() => alert('❌ Ocurrió un error de conexión.'));
    }

    function eliminarCalificacion(id) {
      if (!confirm('¿Eliminar tu calificación?')) return;

      fetch(`/usuario/calificaciones/${id}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': csrfTokenComentarios },
      })
        .then(res => res.json())
        .then(data => {
          if (!data.ok) { alert('❌ Ocurrió un error al eliminar.'); return; }
          estrellaSeleccionada = 0;
          pintarEstrellasSeleccion(0);
          const comentarioEl = document.getElementById('calificarComentario');
          if (comentarioEl) comentarioEl.value = '';
          const tituloEl = document.getElementById('calificarTitulo');
          if (tituloEl) tituloEl.textContent = 'Deja tu calificación';
          const botonEl = document.getElementById('calificarBotonTexto');
          if (botonEl) botonEl.textContent = 'Enviar';
          cargarCalificaciones();
        })
        .catch(() => alert('❌ Ocurrió un error de conexión.'));
    }

    function cargarComentarios() {
      fetch(`/usuario/comentarios/servicio/${servicioIdActual}`)
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

      fetch(`/usuario/comentarios/servicio/${servicioIdActual}`, {
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

      fetch(`/usuario/reportar/servicio/${servicioIdActual}`, {
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
