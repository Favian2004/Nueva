<!-- verEmpleos.html-->
<!DOCTYPE html>
<html lang="es" class="has-aside-left has-aside-mobile-transition has-navbar-fixed-top has-aside-expanded">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Ver Empleos · Dashboard</title>

  <!-- Bulma is included -->
  <link rel="stylesheet" href="{{ asset('assets/usuario/css/main.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/usuario/css/styles.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/usuario/css/switch.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  <link rel="icon" type="img/" href="{{ asset('assets/usuario/img/icono.png') }}">

  <!-- Fonts -->
  <link rel="dns-prefetch" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">


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
            <span class="crumb-page">Ver empleos</span>
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
  <aside class="aside is-placed-left is-expanded">
    <div class="aside-tools">
      <div class="aside-tools-label">
        <span><b>Modo Empleado</b></span>
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
          <a href="/usuario" class="has-icon">
            <span class="icon"><i class="mdi mdi-desktop-mac"></i></span>
            <span class="menu-item-label">Inicio</span>
          </a>
        </li>
      </ul>
      <p class="menu-label">Otros</p>
      <ul class="menu-list">
        <li>
          <a href="/usuario/verEmpleos" class="is-active router-link-active has-icon">
            <span class="icon has-update-mark"><i class="mdi mdi-briefcase"></i></span>
            <span class="menu-item-label">Ver empleos</span>
          </a>
        </li>
        <li>
          <a href="/usuario/publicarEmpleo" class="has-icon">
            <span class="icon "><i class="mdi mdi-square-edit-outline"></i></span>
            <span class="menu-item-label">Publicar empleo</span>
          </a>
        </li>
        <li>
          <a href="/usuario/misEmpleos" class="has-icon">
            <span class="icon"><i class="mdi mdi-format-list-bulleted"></i></span>
            <span class="menu-item-label">Mis empleos</span>
          </a>
        </li>
        <li>
          <a href="/usuario/solicitudes" class="has-icon">
            <span class="icon"><i class="mdi mdi-account-clock"></i></span>
            <span class="menu-item-label">Solicitudes</span>
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

  <!-- CONTENIDO PRINCIPAL -->
<section class="section is-main-section">
  <div class="container">

    <!-- AVISO DE ROL -->
       <div class="role-notice" id="roleNotice">
         <span class="notice-icon">👷</span>
         <span>Estás en modo <strong class="notice-role worker" id="roleNameDisplay">Trabajador</strong> · Puedes gestionar tus servicios y buscar empleos.</span>
      </div>

    <!-- BARRA DE TÍTULO + LOGO -->
    <section class="section is-title-bar custom-bar">
      <div class="level">
        <!-- Lado izquierdo: texto -->
        <div class="level-left">
          <div class="level-item titulo-header">
            <h1 class="title-bar-text">Empleos Zacapoaxtla</h1>
            <p class="title-bar-subtext">Tu próxima oportunidad laboral</p>
          </div>
        </div>

        <!-- Lado derecho: logo -->
        <div class="level-right">
          <div class="level-item">
            <img src="{{ asset('assets/usuario/img/icono.png') }}" alt="Logo Empleos Zacapoaxtla" class="logo-header">
          </div>
        </div>
      </div>
    </section>

    <!-- FILTROS -->
    <form method="GET" action="/usuario/verEmpleos">
      <div class="filter-card">
        <div class="filter-header">
          <h3><i class="mdi mdi-magnify"></i> Buscar Servicios</h3>
          <p>Encuentra al profesional ideal para tu necesidad</p>
        </div>

        <div class="columns is-variable is-3">
          <div class="column is-6">
            <label class="label">¿Qué buscas?</label>
            <div class="control has-icons-left">
              <input class="input custom-input" type="text" name="q" value="{{ $q }}" placeholder="Ej. Plomero, Electricista, Carpintero...">
              <span class="icon is-left"><i class="mdi mdi-magnify"></i></span>
            </div>
          </div>

          <div class="column">
            <label class="label">Categoría</label>
            <div class="select is-fullwidth custom-select">
              <select name="categoria_id">
                <option value="">Todas las categorías</option>
                @foreach ($categorias as $c)
                  <option value="{{ $c->id }}" {{ (string) $categoriaId === (string) $c->id ? 'selected' : '' }}>{{ $c->nombre }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="column">
            <label class="label">Ordenar por</label>
            <div class="select is-fullwidth custom-select">
              <select name="orden">
                <option value="recientes" {{ $orden === 'recientes' ? 'selected' : '' }}>Más recientes</option>
                <option value="precio_asc" {{ $orden === 'precio_asc' ? 'selected' : '' }}>Precio: menor a mayor</option>
                <option value="precio_desc" {{ $orden === 'precio_desc' ? 'selected' : '' }}>Precio: mayor a menor</option>
              </select>
            </div>
          </div>

          <div class="column is-narrow" style="display:flex; align-items:flex-end;">
            <button type="submit" class="btn-add" style="width:100%;"><i class="mdi mdi-filter"></i> Buscar</button>
          </div>
        </div>
      </div>
    </form>

    <!-- ESTADÍSTICAS -->
    <div class="columns mt-5 estadisticas-row">
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

    <!-- SERVICIOS DISPONIBLES -->
    <section class="products" id="listProducts">
      <h2>Servicios Disponibles</h2>
      <div class="products-grid container">
        @forelse ($servicios as $s)
          <div class="product">
            <img src="{{ $s->imagen ?? asset('assets/usuario/img/services/plomero.jpg') }}" alt="{{ $s->titulo }}">
            <div class="product-info">
              <h4>{{ $s->titulo }}
                @if ($s->usuario && $s->usuario->verificacion_estado === 'aprobado')
                  <span title="Identidad verificada" style="background:#dcfce7; color:#16a34a; padding:1px 8px; border-radius:20px; font-size:.65rem; font-weight:700; vertical-align:middle;">
                    <i class="mdi mdi-check-decagram"></i> Verificado
                  </span>
                @endif
              </h4>
              <p class="product-text">{{ \Illuminate\Support\Str::limit($s->descripcion, 90) }}</p>
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
              <div class="price">
                <span>Desde</span>
                <p class="currentPrice">${{ number_format($s->precio, 0) }} MXN</p>
              </div>
              <div class="d-flex gap-2">
                <a href="/usuario/ver_servicio/{{ $s->id }}" class="btn-add">Ver más / Contactar</a>
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
        @empty
          <p class="has-text-grey has-text-centered" style="grid-column: 1 / -1;">No se encontraron servicios con esos filtros. <a href="/usuario/verEmpleos">Ver todos</a>.</p>
        @endforelse
      </div>
    </section>

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

<!-- ========================= -->
<!-- ANUNCIOS INFERIOR -->
<!-- ========================= -->

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
<!-- Icons below are for demo only. Feel free to use any icon pack. Docs: https://bulma.io/documentation/elements/icon/ -->
<link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.9.95/css/materialdesignicons.min.css">
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
