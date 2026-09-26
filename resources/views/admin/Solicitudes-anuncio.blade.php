<!DOCTYPE html>
<html lang="es" class="has-aside-left has-aside-mobile-transition has-navbar-fixed-top has-aside-expanded">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Solicitudes de Anuncio · Admin</title>

  <link rel="stylesheet" href="{{ asset('assets/admin/css/main.min.css') }}">
  <link rel="dns-prefetch" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.9.95/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="{{ asset('assets/admin/css/theme-conectaya-admin.css') }}">
  <style>
    .badge-estado { display:inline-block; padding:3px 12px; border-radius:20px; font-size:.72rem; font-weight:700; }
    .badge-pagado { background:#dcfce7; color:#16a34a; }
    .badge-pendiente_pago { background:#fef3c7; color:#b45309; }
    .badge-pago_rechazado { background:#fee2e2; color:#dc2626; }
    .badge-aprobado { background:#dbeafe; color:#1d4ed8; }
    .badge-rechazado { background:#f3f4f6; color:#6b7280; }
    .badge-pendiente { background:#f3f4f6; color:#6b7280; }
    .solicitud-thumb { width:70px; height:70px; object-fit:cover; border-radius:8px; }
    .fila-pagado { background:#f0fdf4; }

    #modal-crear-directo.modal.is-active {
      display: flex !important;
      align-items: center !important;
      justify-content: center !important;
    }
    #modal-crear-directo .modal-card {
      max-height: 85vh !important;
      height: auto !important;
      display: flex !important;
      flex-direction: column !important;
      overflow: hidden !important;
    }
    #modal-crear-directo .modal-card-head,
    #modal-crear-directo .modal-card-foot {
      flex: 0 0 auto !important;
    }
    #modal-crear-directo form {
      display: flex !important;
      flex-direction: column !important;
      min-height: 0 !important;
      flex: 1 1 auto !important;
      overflow: hidden !important;
    }
    #modal-crear-directo .modal-card-body {
      overflow-y: auto !important;
      flex: 1 1 auto !important;
      min-height: 0 !important;
      max-height: none !important;
    }

    /* --- Preview en vivo del anuncio (igual que en /anunciar) --- */
    .cd-preview-wrap { display: flex; gap: 24px; align-items: flex-start; }
    .cd-preview-form { flex: 1 1 auto; min-width: 0; }
    .cd-preview-panel { flex: 0 0 220px; position: sticky; top: 0; text-align: center; }
    .cd-preview-label { font-size: .72rem; text-transform: uppercase; letter-spacing: .05em; color: #9ca3af; font-weight: 700; margin-bottom: 10px; }
    #cd-preview-box {
      width: 190px;
      height: 171px;
      margin: 0 auto;
      border-radius: 12px;
      overflow: hidden;
      background: #e5e0d8;
      box-shadow: 0 4px 15px rgba(0,0,0,.15);
      position: relative;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: transform .15s;
    }
    #cd-preview-box:hover { transform: scale(1.03); }
    #cd-preview-box img { display: none; width: 100%; height: 100%; object-fit: cover; }
    #cd-preview-box .cd-click-hint {
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
      text-align: center;
    }
    #cd-preview-box:hover .cd-click-hint { background: rgba(0,0,0,.35); opacity: 1; }
    #cd-preview-img-placeholder { color: #fff; font-size: 2rem; opacity: .6; }
    #cd-preview-eslogan {
      display: none;
      background: #fff;
      border: 1px solid #e5e7eb;
      border-radius: 8px;
      padding: 6px 10px;
      margin: 8px auto 0;
      font-size: 11.5px;
      font-weight: 700;
      color: #333;
      max-width: 190px;
    }
    #cd-preview-nombre { font-weight: 700; font-size: .82rem; color: #111827; margin: 8px 0 0; }

    /* Lightbox del admin (igual estilo que el público) */
    .cd-lightbox-overlay {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(15,10,10,.82);
      z-index: 9999;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }
    .cd-lightbox-overlay.activo { display: flex; }
    .cd-lightbox-card {
      background: #fff;
      border-radius: 18px;
      max-width: 380px;
      width: 100%;
      overflow: hidden;
      text-align: center;
      position: relative;
    }
    .cd-lightbox-img-box {
      width: 100%;
      aspect-ratio: 1 / 1;
      background: #e5e0d8;
      display: flex;
      align-items: center;
      justify-content: center;
      overflow: hidden;
    }
    .cd-lightbox-img-box img { width: 100%; height: 100%; object-fit: cover; }
    .cd-lightbox-body { padding: 18px 20px 22px; }
    .cd-lightbox-body h6 { font-weight: 800; color: #1a1a2e; margin-bottom: 4px; font-size: 15px; }
    .cd-lightbox-body p { font-size: 12.5px; color: #777; margin-bottom: 14px; }
    .cd-lightbox-btn {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: #6b1021;
      color: #fff;
      border: none;
      padding: 9px 18px;
      border-radius: 10px;
      font-size: 12.5px;
      font-weight: 700;
      text-decoration: none;
      margin: 3px;
    }
    .cd-lightbox-btn.cd-btn-ubicacion { background: #fff; color: #6b1021; border: 1.5px solid #6b1021; }
    .cd-lightbox-cerrar {
      position: absolute;
      top: 14px;
      right: 18px;
      color: #fff;
      font-size: 26px;
      cursor: pointer;
      line-height: 1;
    }

    @media (max-width: 720px) {
      .cd-preview-wrap { flex-direction: column; }
      .cd-preview-panel { flex: 1 1 auto; width: 100%; position: static; }
    }
  </style>
</head>
<body>

<div id="app">
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
      <div class="navbar-end">
        <a title="Cerrar sesión" href="#" class="navbar-item is-desktop-icon-only js-logout">
          <span class="icon"><i class="mdi mdi-logout"></i></span>
          <span>Cerrar sesión</span>
        </a>
      </div>
    </div>
  </nav>
  <aside class="aside is-placed-left is-expanded">
    <div class="aside-tools">
      <div class="aside-tools-label">
        <span><b>Empleabilidad</b> Zacapoaxtla</span>
      </div>
    </div>
    <div class="menu is-menu-main">
      <p class="menu-label">Panel</p>
      <ul class="menu-list">
        <li><a href="/admin" class="has-icon"><span class="icon"><i class="mdi mdi-desktop-mac"></i></span><span class="menu-item-label">Dashboard</span></a></li>
        <li><a href="/admin/usuarios" class="has-icon"><span class="icon"><i class="mdi mdi-account-multiple"></i></span><span class="menu-item-label">Usuarios</span></a></li>
        <li><a href="/admin/vacantes" class="has-icon"><span class="icon"><i class="mdi mdi-briefcase"></i></span><span class="menu-item-label">Vacantes</span></a></li>
        <li><a href="/admin/postulaciones" class="has-icon"><span class="icon"><i class="mdi mdi-account-check"></i></span><span class="menu-item-label">Postulaciones</span></a></li>
        <li><a href="/admin/servicios" class="has-icon"><span class="icon"><i class="mdi mdi-hammer-screwdriver"></i></span><span class="menu-item-label">Servicios</span></a></li>
        <li><a href="/admin/contrataciones" class="has-icon"><span class="icon"><i class="mdi mdi-handshake"></i></span><span class="menu-item-label">Contrataciones</span></a></li>
        <li><a href="/admin/reportes" class="has-icon"><span class="icon"><i class="mdi mdi-flag"></i></span><span class="menu-item-label">Reportes</span></a></li>
        <li><a href="/admin/testimonios" class="has-icon"><span class="icon"><i class="mdi mdi-star"></i></span><span class="menu-item-label">Testimonios</span></a></li>
        <li><a href="/admin/categorias" class="has-icon"><span class="icon"><i class="mdi mdi-shape"></i></span><span class="menu-item-label">Categorías</span></a></li>
        <li><a href="/admin/municipios" class="has-icon"><span class="icon"><i class="mdi mdi-map-marker"></i></span><span class="menu-item-label">Municipios</span></a></li>
        <li><a href="/admin/anuncios" class="has-icon"><span class="icon"><i class="mdi mdi-bullhorn"></i></span><span class="menu-item-label">Anuncios</span></a></li>
        <li>
          <a href="/admin/solicitudes-anuncio" class="is-active router-link-active has-icon">
            <span class="icon has-update-mark"><i class="mdi mdi-cash-register"></i></span>
            <span class="menu-item-label">Solicitudes de Anuncio</span>
            @if ($contadorPagadas > 0)
              <span class="tag is-success is-rounded" style="margin-left:6px;">{{ $contadorPagadas }}</span>
            @endif
          </a>
        </li>
      </ul>
    </div>
  </aside>

  <section class="section is-title-bar">
    <div class="level">
      <div class="level-left">
        <div class="level-item">
          <ul><li>Admin</li><li>Solicitudes de Anuncio</li></ul>
        </div>
      </div>
    </div>
  </section>

  <section class="hero is-hero-bar">
    <div class="hero-body">
      <div class="level">
        <div class="level-left">
          <div class="level-item"><h1 class="title">Solicitudes de Anuncio</h1></div>
        </div>
      </div>
    </div>
  </section>

  <section class="section is-main-section">

    @if (session('exito'))
      <div class="notification is-success is-light">
        <span class="icon"><i class="mdi mdi-check-circle"></i></span>
        {{ session('exito') }}
      </div>
    @endif
    @if ($errors->any())
      <div class="notification is-danger is-light">
        <span class="icon"><i class="mdi mdi-alert-circle"></i></span>
        @foreach ($errors->all() as $error)
          {{ $error }}<br>
        @endforeach
      </div>
    @endif

    <div class="notification is-info is-light">
      <span class="icon"><i class="mdi mdi-information"></i></span>
      Cuando un negocio paga con Mercado Pago, su solicitud aparece aquí marcada como <b>"Pagado"</b>. Revisa los datos, elige municipio y posición, y dale <b>"Activar"</b> para publicar el anuncio en el sitio.
    </div>

    <div class="mb-4">
      <button class="button is-primary" onclick="document.getElementById('modal-crear-directo').classList.add('is-active'); cdActualizarPreview();">
        <span class="icon"><i class="mdi mdi-plus-circle"></i></span>
        <span>Crear anuncio directo (sin Mercado Pago)</span>
      </button>
    </div>

    <div class="field mb-4">
      <div class="control">
        <div class="select">
          <select id="filtro-estado" onchange="location.href = this.value ? '?estado=' + this.value : '/admin/solicitudes-anuncio'">
            <option value="" {{ !$estado ? 'selected' : '' }}>Todas</option>
            <option value="pagado" {{ $estado === 'pagado' ? 'selected' : '' }}>Pagadas (listas para activar)</option>
            <option value="pendiente_pago" {{ $estado === 'pendiente_pago' ? 'selected' : '' }}>Pendientes de pago</option>
            <option value="pago_rechazado" {{ $estado === 'pago_rechazado' ? 'selected' : '' }}>Pago rechazado</option>
            <option value="aprobado" {{ $estado === 'aprobado' ? 'selected' : '' }}>Ya activadas</option>
            <option value="rechazado" {{ $estado === 'rechazado' ? 'selected' : '' }}>Rechazadas</option>
          </select>
        </div>
      </div>
    </div>

    <div class="card">
      <div class="table-container">
        <table class="table is-fullwidth is-hoverable">
          <thead>
            <tr>
              <th></th>
              <th>Negocio</th>
              <th>Encargado</th>
              <th>Plan</th>
              <th>Contacto</th>
              <th>Estado</th>
              <th>Fecha</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            @forelse ($solicitudes as $s)
              @php $pagoAprobado = $s->pagos->firstWhere('estado', 'aprobado'); @endphp
              <tr class="{{ $s->estado === 'pagado' ? 'fila-pagado' : '' }}">
                <td>
                  @if ($s->imagen_negocio)
                    <img src="{{ $s->imagen_negocio }}" class="solicitud-thumb" alt="{{ $s->nombre_negocio }}">
                  @else
                    <div class="solicitud-thumb has-background-light is-flex is-align-items-center is-justify-content-center">
                      <i class="mdi mdi-image-off-outline has-text-grey"></i>
                    </div>
                  @endif
                </td>
                <td>
                  <strong>{{ $s->nombre_negocio }}</strong><br>
                  <small class="has-text-grey">{{ \Illuminate\Support\Str::limit($s->descripcion, 60) }}</small>
                  @if ($s->direccion)<br><small class="has-text-grey"><i class="mdi mdi-map-marker"></i> {{ $s->direccion }}</small>@endif
                  @if ($s->eslogan)<br><small class="has-text-grey"><i class="mdi mdi-format-quote-close"></i> {{ $s->eslogan }}</small>@endif
                  @if ($s->link_ubicacion)<br><a href="{{ $s->link_ubicacion }}" target="_blank" style="font-size:12px;"><i class="mdi mdi-map-marker-radius"></i> Ver ubicación en Maps</a>@endif
                </td>
                <td>{{ $s->nombre_encargado ?? '—' }}</td>
                <td>
                  {{ ucfirst($s->plan) }}
                  @if ($pagoAprobado)<br><small class="has-text-grey">${{ number_format($pagoAprobado->monto, 0) }} · vence {{ $pagoAprobado->fecha_vencimiento_anuncio?->format('d/m/Y') }}</small>@endif
                </td>
                <td>
                  <i class="mdi mdi-phone"></i> {{ $s->telefono }}
                  @if ($s->whatsapp)<br><i class="mdi mdi-whatsapp" style="color:#25D366;"></i> {{ $s->whatsapp }}@endif
                  @if ($s->email)<br><small class="has-text-grey">{{ $s->email }}</small>@endif
                </td>
                <td><span class="badge-estado badge-{{ $s->estado }}">{{ ucfirst(str_replace('_', ' ', $s->estado)) }}</span></td>
                <td><small>{{ $s->created_at->format('d/m/Y H:i') }}</small></td>
                <td>
                  @if ($s->estado === 'pagado')
                    <button class="button is-small is-success" onclick="abrirModalActivar({{ $s->id }}, '{{ addslashes($s->nombre_negocio) }}')">
                      <span class="icon"><i class="mdi mdi-check-circle"></i></span>
                      <span>Activar</span>
                    </button>
                    <button class="button is-small is-danger is-outlined" onclick="rechazarSolicitud({{ $s->id }})">
                      <span class="icon"><i class="mdi mdi-close"></i></span>
                    </button>
                  @endif
                </td>
              </tr>
            @empty
              <tr><td colspan="8" class="has-text-centered has-text-grey py-5">No hay solicitudes con ese filtro.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

  </section>

  <!-- MODAL: Crear anuncio directo (sin Mercado Pago) -->
  <div id="modal-crear-directo" class="modal">
    <div class="modal-background" onclick="document.getElementById('modal-crear-directo').classList.remove('is-active')"></div>
    <div class="modal-card" style="width: 900px; max-width: 95vw;">
      <header class="modal-card-head">
        <p class="modal-card-title">Crear anuncio directo</p>
        <button class="delete" aria-label="close" onclick="document.getElementById('modal-crear-directo').classList.remove('is-active')"></button>
      </header>
      <form action="{{ url('/admin/solicitudes-anuncio/crear-directo') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <section class="modal-card-body">
          <div class="cd-preview-wrap">
            <div class="cd-preview-form">
              <div class="notification is-warning is-light">
                <span class="icon"><i class="mdi mdi-alert"></i></span>
                Esto crea y publica el anuncio de inmediato, sin pasar por Mercado Pago — úsalo solo si el negocio ya pagó por otro medio, o para anuncios internos.
              </div>

              <div class="field">
                <label class="label">Nombre del negocio</label>
                <div class="control"><input type="text" name="nombre_negocio" id="cd-nombre" class="input" value="{{ old('nombre_negocio') }}" required></div>
              </div>

              <div class="field">
                <label class="label">Nombre de quien atiende / encargado</label>
                <div class="control"><input type="text" name="nombre_encargado" class="input" value="{{ old('nombre_encargado') }}" required></div>
              </div>

              <div class="field">
                <label class="label">Descripción del negocio</label>
                <div class="control"><textarea name="descripcion" class="textarea" rows="2" required>{{ old('descripcion') }}</textarea></div>
              </div>

              <div class="field">
                <label class="label">Dirección</label>
                <div class="control"><input type="text" name="direccion" class="input" value="{{ old('direccion') }}" required></div>
              </div>

              <div class="field is-grouped is-grouped-multiline">
                <div class="control is-expanded">
                  <label class="label">Teléfono</label>
                  <input type="text" name="telefono" class="input" value="{{ old('telefono') }}" required>
                </div>
                <div class="control is-expanded">
                  <label class="label">WhatsApp (opcional)</label>
                  <input type="text" name="whatsapp" class="input" value="{{ old('whatsapp') }}">
                </div>
              </div>

              <div class="field">
                <label class="label">Correo (opcional)</label>
                <div class="control"><input type="email" name="email" class="input" value="{{ old('email') }}" placeholder="Para avisarle cuando se publique"></div>
              </div>

              <div class="field">
                <label class="label">Plan</label>
                <div class="control">
                  <div class="select is-fullwidth">
                    <select name="plan" id="cd-plan" onchange="cdActualizarPlan()">
                      <option value="basico">Básico · $29 · 15 días</option>
                      <option value="mensual" selected>Mensual · $49 · 1 mes</option>
                      <option value="anual">Anual · $490 · 1 año</option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="field" id="cd-campo-eslogan">
                <label class="label">Eslogan</label>
                <div class="control"><input type="text" name="eslogan" id="cd-eslogan" class="input" value="{{ old('eslogan') }}" maxlength="150"></div>
              </div>

              <div class="field" id="cd-campo-link">
                <label class="label">Link a página o red social (opcional)</label>
                <div class="control"><input type="url" name="link_externo" id="cd-link-externo" class="input" value="{{ old('link_externo') }}" placeholder="https://facebook.com/tu-negocio"></div>
              </div>

              <div class="field" id="cd-campo-ubicacion">
                <label class="label">Link de ubicación en Google Maps (opcional)</label>
                <div class="control"><input type="url" name="link_ubicacion" id="cd-link-ubicacion" class="input" value="{{ old('link_ubicacion') }}" placeholder="Pega aquí el link que copiaste de Google Maps"></div>
              </div>

              <div class="field">
                <label class="label">Imagen del anuncio</label>
                <div class="control"><input type="file" name="imagen_negocio" id="cd-imagen" class="input" accept="image/*" required></div>
                <p class="help">Recomendado 800x720 px (entre 400x360 y 1200x1200 px), máximo 4 MB.</p>
              </div>
            </div>

            <div class="cd-preview-panel">
              <p class="cd-preview-label">Así se ve en la columna de anuncios</p>
              <div id="cd-preview-box" onclick="cdAbrirLightbox()">
                <img id="cd-preview-img" alt="Vista previa">
                <i class="mdi mdi-image-outline" id="cd-preview-img-placeholder"></i>
                <div class="cd-click-hint"><i class="mdi mdi-arrow-expand-all"></i><br>Ver en grande</div>
              </div>
              <div id="cd-preview-eslogan"></div>
              <p id="cd-preview-nombre">Nombre del negocio</p>
            </div>
          </div>
        </section>
        <footer class="modal-card-foot">
          <button type="submit" class="button is-primary">
            <span class="icon"><i class="mdi mdi-check"></i></span>
            <span>Crear y publicar</span>
          </button>
          <button type="button" class="button" onclick="document.getElementById('modal-crear-directo').classList.remove('is-active')">Cancelar</button>
        </footer>
      </form>
    </div>
  </div>

  <!-- LIGHTBOX del admin: cómo se ve al darle clic, igual que en /anunciar -->
  <div class="cd-lightbox-overlay" id="cd-lightbox-overlay">
    <span class="cd-lightbox-cerrar" onclick="document.getElementById('cd-lightbox-overlay').classList.remove('activo')">&times;</span>
    <div class="cd-lightbox-card">
      <div class="cd-lightbox-img-box">
        <img id="cd-lightbox-img" alt="Anuncio en grande">
      </div>
      <div class="cd-lightbox-body">
        <h6 id="cd-lightbox-nombre">Tu negocio</h6>
        <p id="cd-lightbox-eslogan">Tu eslogan aparecería aquí</p>
        <a href="#" id="cd-lightbox-boton-link" class="cd-lightbox-btn" style="display:none;" target="_blank">
          <i class="mdi mdi-open-in-new"></i> Visitar página
        </a>
        <a href="#" id="cd-lightbox-boton-ubicacion" class="cd-lightbox-btn cd-btn-ubicacion" style="display:none;" target="_blank">
          <i class="mdi mdi-map-marker"></i> Cómo llegar
        </a>
      </div>
    </div>
  </div>

  <!-- MODAL: Activar solicitud -->
  <div id="modal-activar" class="modal">
    <div class="modal-background" onclick="cerrarModalActivar()"></div>
    <div class="modal-card">
      <header class="modal-card-head">
        <p class="modal-card-title">Activar anuncio de <span id="modal-nombre-negocio"></span></p>
        <button class="delete" aria-label="close" onclick="cerrarModalActivar()"></button>
      </header>
      <section class="modal-card-body">
        <input type="hidden" id="modal-solicitud-id">
        <div class="field">
          <label class="label">Municipio</label>
          <div class="control">
            <div class="select is-fullwidth">
              <select id="modal-municipio">
                @foreach ($municipios as $m)
                  <option value="{{ $m->id }}">{{ $m->nombre }}</option>
                @endforeach
              </select>
            </div>
          </div>
        </div>
        <div class="field">
          <label class="label">Posición</label>
          <div class="control">
            <div class="select is-fullwidth">
              <select id="modal-posicion">
                <option value="derecha" selected>Derecha (la que se muestra en el sitio)</option>
                <option value="izquierda">Izquierda (sin uso por ahora)</option>
              </select>
            </div>
          </div>
        </div>
      </section>
      <footer class="modal-card-foot">
        <button class="button is-success" id="btn-confirmar-activar" onclick="confirmarActivar()">
          <span class="icon"><i class="mdi mdi-check"></i></span>
          <span>Confirmar y publicar</span>
        </button>
        <button class="button" onclick="cerrarModalActivar()">Cancelar</button>
      </footer>
    </div>
  </div>

  <script src="{{ asset('assets/admin/js/main.min.js') }}"></script>
  <script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    function cdActualizarPlan() {
      const plan = document.getElementById('cd-plan').value;
      const esBasico = plan === 'basico';
      document.getElementById('cd-campo-eslogan').style.display = esBasico ? 'none' : '';
      document.getElementById('cd-campo-link').style.display = esBasico ? 'none' : '';
      document.getElementById('cd-campo-ubicacion').style.display = esBasico ? 'none' : '';
      cdActualizarPreview();
    }

    @if ($errors->has('crear_directo') || $errors->has('nombre_negocio'))
      document.getElementById('modal-crear-directo').classList.add('is-active');
    @endif

    /* --- Preview en vivo del anuncio (igual que en /anunciar) --- */
    const cdNombreInput = document.getElementById('cd-nombre');
    const cdEsloganInput = document.getElementById('cd-eslogan');
    const cdLinkInput = document.getElementById('cd-link-externo');
    const cdUbicacionInput = document.getElementById('cd-link-ubicacion');
    const cdImgInput = document.getElementById('cd-imagen');
    const cdPreviewImg = document.getElementById('cd-preview-img');
    const cdPreviewPlaceholder = document.getElementById('cd-preview-img-placeholder');
    const cdPreviewEslogan = document.getElementById('cd-preview-eslogan');
    const cdPreviewNombreTxt = document.getElementById('cd-preview-nombre');
    let cdImagenCargada = false;

    function cdActualizarPreview() {
      cdPreviewNombreTxt.textContent = cdNombreInput.value.trim() || 'Nombre del negocio';

      const esBasico = document.getElementById('cd-plan').value === 'basico';
      const eslogan = cdEsloganInput.value.trim();
      if (!esBasico && eslogan) {
        cdPreviewEslogan.textContent = eslogan;
        cdPreviewEslogan.style.display = 'block';
      } else {
        cdPreviewEslogan.style.display = 'none';
      }
    }

    function cdPreviewImagen() {
      const file = cdImgInput.files[0];
      if (!file) {
        cdPreviewImg.style.display = 'none';
        cdPreviewPlaceholder.style.display = 'block';
        cdImagenCargada = false;
        return;
      }
      const reader = new FileReader();
      reader.onload = e => {
        cdPreviewImg.src = e.target.result;
        cdPreviewImg.style.display = 'block';
        cdPreviewPlaceholder.style.display = 'none';
        cdImagenCargada = true;
      };
      reader.readAsDataURL(file);
    }

    cdNombreInput.addEventListener('input', cdActualizarPreview);
    cdEsloganInput.addEventListener('input', cdActualizarPreview);
    cdLinkInput.addEventListener('input', cdActualizarPreview);
    cdImgInput.addEventListener('change', cdPreviewImagen);

    /* --- Lightbox: "así se ve al darle clic" --- */
    function cdAbrirLightbox() {
      if (!cdImagenCargada) {
        alert('Primero sube una imagen para ver cómo se vería en grande.');
        return;
      }
      document.getElementById('cd-lightbox-img').src = cdPreviewImg.src;
      document.getElementById('cd-lightbox-nombre').textContent = cdNombreInput.value.trim() || 'Tu negocio';

      const esBasico = document.getElementById('cd-plan').value === 'basico';
      const esloganEl = document.getElementById('cd-lightbox-eslogan');
      if (!esBasico) {
        esloganEl.textContent = cdEsloganInput.value.trim() || 'Tu eslogan aparecería aquí';
        esloganEl.style.display = 'block';
      } else {
        esloganEl.style.display = 'none';
      }

      const botonLink = document.getElementById('cd-lightbox-boton-link');
      const link = !esBasico ? cdLinkInput.value.trim() : '';
      if (link) {
        botonLink.style.display = 'inline-flex';
        botonLink.href = link;
      } else {
        botonLink.style.display = 'none';
      }

      const botonUbicacion = document.getElementById('cd-lightbox-boton-ubicacion');
      const ubicacion = !esBasico ? cdUbicacionInput.value.trim() : '';
      if (ubicacion) {
        botonUbicacion.style.display = 'inline-flex';
        botonUbicacion.href = ubicacion;
      } else {
        botonUbicacion.style.display = 'none';
      }

      document.getElementById('cd-lightbox-overlay').classList.add('activo');
    }

    document.getElementById('cd-lightbox-overlay').addEventListener('click', function (e) {
      if (e.target === this) this.classList.remove('activo');
    });

    function abrirModalActivar(id, nombre) {
      document.getElementById('modal-solicitud-id').value = id;
      document.getElementById('modal-nombre-negocio').textContent = nombre;
      document.getElementById('modal-activar').classList.add('is-active');
    }

    function cerrarModalActivar() {
      document.getElementById('modal-activar').classList.remove('is-active');
    }

    function confirmarActivar() {
      const id = document.getElementById('modal-solicitud-id').value;
      const municipioId = document.getElementById('modal-municipio').value;
      const posicion = document.getElementById('modal-posicion').value;
      const btn = document.getElementById('btn-confirmar-activar');

      btn.disabled = true;
      btn.classList.add('is-loading');

      fetch(`/admin/solicitudes-anuncio/${id}/activar`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
        body: JSON.stringify({ municipio_id: municipioId, posicion: posicion }),
      })
        .then(res => res.json().then(data => ({ ok: res.ok, data })))
        .then(({ ok, data }) => {
          if (!ok) {
            alert('❌ ' + (data.error || 'Ocurrió un error al activar.'));
            btn.disabled = false;
            btn.classList.remove('is-loading');
            return;
          }
          alert('✅ ¡Anuncio activado y publicado!');
          location.reload();
        })
        .catch(() => {
          alert('❌ Ocurrió un error de conexión.');
          btn.disabled = false;
          btn.classList.remove('is-loading');
        });
    }

    function rechazarSolicitud(id) {
      const motivo = prompt('¿Por qué rechazas esta solicitud? (opcional)');
      if (motivo === null) return; // canceló

      fetch(`/admin/solicitudes-anuncio/${id}/rechazar`, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
        body: JSON.stringify({ motivo: motivo }),
      })
        .then(res => res.json())
        .then(data => {
          if (!data.ok) { alert('❌ Ocurrió un error.'); return; }
          location.reload();
        })
        .catch(() => alert('❌ Ocurrió un error de conexión.'));
    }

    document.querySelectorAll('.js-logout').forEach(btn => {
      btn.addEventListener('click', function (e) {
        e.preventDefault();
        if (!confirm('¿Seguro que quieres cerrar sesión?')) return;
        fetch('/logout', { method: 'POST', headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' } })
          .finally(() => { window.location.href = '/'; });
      });
    });

    // Estado inicial del formulario de "crear directo" — va HASTA el final
    // del script, porque necesita que todo lo demás (constantes, event
    // listeners) ya esté definido primero.
    cdActualizarPlan();
  </script>
</body>
</html>
