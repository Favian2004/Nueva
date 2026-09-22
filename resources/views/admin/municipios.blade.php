<!DOCTYPE html>
<html lang="es" class="has-aside-left has-aside-mobile-transition has-navbar-fixed-top has-aside-expanded">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Admin · Empleabilidad Zacapoaxtla</title>

  <link rel="stylesheet" href="{{ asset('assets/admin/css/main.min.css') }}">

  <link rel="dns-prefetch" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.9.95/css/materialdesignicons.min.css">
  <style>
    .aside-tools-label b { font-weight: 700; }
    .is-image-cell .image img { object-fit: cover; }
    .anuncio-thumb { width: 160px; height: 90px; object-fit: cover; border-radius: 6px; }
    .badge-dot { display:inline-block; width:8px; height:8px; border-radius:50%; margin-right:6px; }

    .cat-item {
      display: flex; align-items: center; justify-content: space-between;
      padding: 10px 12px; border-radius: 8px; cursor: pointer;
      margin-bottom: 4px; transition: 0.15s; border: 1px solid transparent;
    }
    .cat-item:hover { background: #f5f5f5; }
    .cat-item.activa { background: #eef6ff; border-color: #7957d5; font-weight: 700; }
    .cat-item .cat-nombre { flex: 1; }
    .cat-item .cat-acciones { display: flex; align-items: center; gap: 6px; }
    .cat-item .tag { flex-shrink: 0; }
    .cat-item .btn-borrar-cat { background: none; border: none; color: #b5b5b5; cursor: pointer; padding: 2px; }
    .cat-item .btn-borrar-cat:hover { color: #f14668; }

    .sub-chip {
      display: inline-flex; align-items: center; gap: 8px;
      background: #f5f5f5; border-radius: 20px; padding: 6px 8px 6px 14px;
      font-size: 13px;
    }
    .sub-chip .usuarios-badge { color: #7a7a7a; font-size: 11px; }
    .sub-chip .btn-borrar-sub { background: #fff; border: none; color: #b5b5b5; cursor: pointer; width: 20px; height: 20px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 13px; line-height: 1; }
    .sub-chip .btn-borrar-sub:hover { color: #f14668; background: #ffe3e8; }
  </style>

  <link rel="stylesheet" href="{{ asset('assets/admin/css/theme-conectaya-admin.css') }}">
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
        <div class="navbar-item has-dropdown has-dropdown-with-icons has-divider has-user-avatar is-hoverable">
          <a class="navbar-link is-arrowless">
            <div class="is-user-avatar">
              <img src="https://avatars.dicebear.com/v2/initials/admin.svg" alt="Admin">
            </div>
            <div class="is-user-name"><span>Admin</span></div>
            <span class="icon"><i class="mdi mdi-chevron-down"></i></span>
          </a>
          <div class="navbar-dropdown">
            <a href="/admin/profile" class="navbar-item">
              <span class="icon"><i class="mdi mdi-account"></i></span>
              <span>Mi perfil</span>
            </a>
            <hr class="navbar-divider">
            <a href="#" class="navbar-item js-logout">
              <span class="icon"><i class="mdi mdi-logout"></i></span>
              <span>Cerrar sesión</span>
            </a>
          </div>
        </div>
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
        <li>
          <a href="/admin" class="has-icon">
            <span class="icon"><i class="mdi mdi-desktop-mac"></i></span>
            <span class="menu-item-label">Dashboard</span>
          </a>
        </li>
        <li>
          <a href="/admin/usuarios" class="has-icon">
            <span class="icon"><i class="mdi mdi-account-multiple"></i></span>
            <span class="menu-item-label">Usuarios</span>
          </a>
        </li>
        <li>
          <a href="/admin/vacantes" class="has-icon">
            <span class="icon"><i class="mdi mdi-briefcase-search"></i></span>
            <span class="menu-item-label">Vacantes</span>
          </a>
        </li>
        <li>
          <a href="/admin/postulaciones" class="has-icon">
            <span class="icon"><i class="mdi mdi-account-arrow-right"></i></span>
            <span class="menu-item-label">Postulaciones</span>
          </a>
        </li>
        <li>
          <a href="/admin/servicios" class="has-icon">
            <span class="icon"><i class="mdi mdi-hammer-wrench"></i></span>
            <span class="menu-item-label">Servicios</span>
          </a>
        </li>
        <li>
          <a href="/admin/contrataciones" class="has-icon">
            <span class="icon"><i class="mdi mdi-handshake"></i></span>
            <span class="menu-item-label">Contrataciones</span>
          </a>
        </li>
        <li>
          <a href="/admin/reportes" class="has-icon">
            <span class="icon"><i class="mdi mdi-alert-octagon"></i></span>
            <span class="menu-item-label">Reportes</span>
          </a>
        </li>
          <li>
          <a href="/admin/testimonios" class="has-icon">
            <span class="icon"><i class="mdi mdi-comment-quote"></i></span>
            <span class="menu-item-label">Testimonios</span>
          </a>
        </li>
        <li>
          <a href="/admin/categorias" class="has-icon">
            <span class="icon"><i class="mdi mdi-shape"></i></span>
            <span class="menu-item-label">Categorías</span>
          </a>
        </li>
        <li>
          <a href="/admin/municipios" class="is-active router-link-active has-icon">
            <span class="icon"><i class="mdi mdi-map-marker"></i></span>
            <span class="menu-item-label">Municipios</span>
          </a>
        </li>
        <li>
          <a href="/admin/anuncios" class="has-icon">
            <span class="icon"><i class="mdi mdi-bullhorn"></i></span>
            <span class="menu-item-label">Anuncios</span>
          </a>
        </li>
      </ul>
    </div>
  </aside>
  <section class="section is-title-bar">
    <div class="level">
      <div class="level-left">
        <div class="level-item">
          <ul>
            <li>Admin</li>
            <li>Municipios</li>
          </ul>
        </div>
      </div>
    </div>
  </section>
  <section class="hero is-hero-bar">
    <div class="hero-body">
      <div class="level">
        <div class="level-left">
          <div class="level-item">
            <h1 class="title">Municipios y localidades</h1>
          </div>
        </div>
        <div class="level-right">
          <div class="level-item">

          </div>
        </div>
      </div>
    </div>
  </section>
  <section class="section is-main-section">

    <div class="columns">
      <div class="column is-4">
        <div class="card has-table">
          <header class="card-header">
            <p class="card-header-title"><span class="icon"><i class="mdi mdi-map-marker default"></i></span>Municipios <span class="tag is-light ml-2">{{ $municipios->count() }}</span></p>
          </header>
          <div class="card-content">
            <div class="field has-addons mb-3">
              <div class="control is-expanded"><input class="input" id="nuevo-municipio" placeholder="Nuevo municipio..." onkeydown="if(event.key==='Enter'){event.preventDefault();agregarMunicipio();}"></div>
              <div class="control"><button class="button is-primary" onclick="agregarMunicipio()"><span class="icon"><i class="mdi mdi-plus"></i></span></button></div>
            </div>
            <div class="control mb-3 has-icons-left">
              <input class="input is-small" id="buscar-municipio" placeholder="Buscar municipio..." oninput="filtrarMunicipios()">
              <span class="icon is-small is-left"><i class="mdi mdi-magnify"></i></span>
            </div>
            <div id="lista-municipios" style="max-height:600px; overflow-y:auto;"></div>
          </div>
        </div>
      </div>
      <div class="column is-8">
        <div class="card has-table">
          <header class="card-header">
            <p class="card-header-title"><span class="icon"><i class="mdi mdi-map-marker-outline default"></i></span><span id="titulo-localidades">Selecciona un municipio</span></p>
          </header>
          <div class="card-content">
            <div class="field has-addons mb-3">
              <div class="control is-expanded"><input class="input" id="nueva-localidad" placeholder="Nueva localidad..." disabled onkeydown="if(event.key==='Enter'){event.preventDefault();agregarLocalidad();}"></div>
              <div class="control"><button class="button is-primary" id="btn-agregar-loc" onclick="agregarLocalidad()" disabled><span class="icon"><i class="mdi mdi-plus"></i></span></button></div>
            </div>
            <div class="control mb-3 has-icons-left">
              <input class="input is-small" id="buscar-localidad" placeholder="Buscar dentro de este municipio..." oninput="filtrarLocalidades()">
              <span class="icon is-small is-left"><i class="mdi mdi-magnify"></i></span>
            </div>
            <div id="grid-localidades" style="display:flex; flex-wrap:wrap; gap:8px; max-height:520px; overflow-y:auto; align-content:flex-start;"></div>
            <p id="vacio-localidades" class="has-text-centered has-text-grey mt-4" style="display:none;">Este municipio todavía no tiene localidades. Agrega la primera arriba.</p>
          </div>
        </div>
      </div>
    </div>

  </section>
  <footer class="footer">
    <div class="container-fluid">
      <div class="level">
        <div class="level-left">
          <div class="level-item">
            © 2026, Empleabilidad Zacapoaxtla
          </div>
        </div>
        <div class="level-right">
          <div class="level-item">
            <div class="logo">
              <a href="https://justboil.me"><img src="{{ asset('assets/admin/img/justboil-logo.svg') }}" alt="JustBoil.me" style="height:20px;"></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </footer>
</div>

<script type="text/javascript" src="{{ asset('assets/admin/js/main.min.js') }}"></script>
<script>
  // ===== Municipios y localidades (guardan de verdad en la base de datos) =====
  const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

  // Datos ya cargados del servidor, para que cambiar de municipio sea instantáneo
  const municipios = @json($municipiosJson);
  const localidades = @json($localidadesJson);

  let municipioSeleccionadoId = null;

  function locsDe(munId) {
    return localidades.filter(l => l.municipio_id === munId).sort((a, b) => a.nombre.localeCompare(b.nombre));
  }

  function pintarMunicipios(filtro = '') {
    const cont = document.getElementById('lista-municipios');
    const filtroLower = filtro.trim().toLowerCase();
    const lista = municipios
      .filter(m => m.nombre.toLowerCase().includes(filtroLower))
      .sort((a, b) => a.nombre.localeCompare(b.nombre));

    if (!lista.length) {
      cont.innerHTML = '<p class="has-text-grey has-text-centered">No hay municipios que coincidan.</p>';
      return;
    }

    cont.innerHTML = lista.map(m => {
      const count = locsDe(m.id).length;
      const activa = m.id === municipioSeleccionadoId ? 'activa' : '';
      return `
        <div class="cat-item ${activa}" onclick="seleccionarMunicipio(${m.id})">
          <span class="cat-nombre">${escaparHtml(m.nombre)}</span>
          <span class="cat-acciones">
            <span class="tag ${count === 0 ? 'is-warning' : 'is-light'}">${count}</span>
            <button class="btn-borrar-cat" title="Eliminar municipio" onclick="event.stopPropagation(); eliminarMunicipio(${m.id})"><i class="mdi mdi-trash-can-outline"></i></button>
          </span>
        </div>`;
    }).join('');
  }

  function seleccionarMunicipio(id) {
    municipioSeleccionadoId = id;
    document.getElementById('buscar-localidad').value = '';
    document.getElementById('nueva-localidad').disabled = false;
    document.getElementById('btn-agregar-loc').disabled = false;
    pintarMunicipios(document.getElementById('buscar-municipio').value);
    pintarLocalidades();
  }

  function pintarLocalidades(filtro = '') {
    const mun = municipios.find(m => m.id === municipioSeleccionadoId);
    document.getElementById('titulo-localidades').textContent = mun ? `Localidades de "${mun.nombre}"` : 'Selecciona un municipio';

    const grid = document.getElementById('grid-localidades');
    const vacio = document.getElementById('vacio-localidades');

    if (!mun) { grid.innerHTML = ''; vacio.style.display = 'none'; return; }

    const filtroLower = filtro.trim().toLowerCase();
    const lista = locsDe(mun.id).filter(l => l.nombre.toLowerCase().includes(filtroLower));

    if (!lista.length) {
      grid.innerHTML = '';
      vacio.style.display = 'block';
      vacio.textContent = filtroLower ? 'No hay ninguna que coincida con tu búsqueda.' : 'Este municipio todavía no tiene localidades. Agrega la primera arriba.';
      return;
    }

    vacio.style.display = 'none';
    grid.innerHTML = lista.map(l => `
      <span class="sub-chip">
        ${escaparHtml(l.nombre)}
        ${l.usuarios_count > 0 ? `<span class="usuarios-badge" title="Usuarios registrados aquí"><i class="mdi mdi-account"></i> ${l.usuarios_count}</span>` : ''}
        <button class="btn-borrar-sub" title="Eliminar" onclick="eliminarLocalidad(${l.id})"><i class="mdi mdi-close"></i></button>
      </span>
    `).join('');
  }

  function filtrarMunicipios() { pintarMunicipios(document.getElementById('buscar-municipio').value); }
  function filtrarLocalidades() { pintarLocalidades(document.getElementById('buscar-localidad').value); }

  function escaparHtml(texto) {
    const div = document.createElement('div');
    div.textContent = texto;
    return div.innerHTML;
  }

  function agregarMunicipio() {
    const input = document.getElementById('nuevo-municipio');
    const nombre = input.value.trim();
    if (!nombre) return;

    fetch('/admin/municipios', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
      body: JSON.stringify({ nombre: nombre }),
    })
      .then(res => {
        if (!res.ok) throw new Error();
        location.reload();
      })
      .catch(() => alert('Ocurrió un error al agregar el municipio. Intenta de nuevo.'));
  }

  function eliminarMunicipio(id) {
    if (!confirm('¿Eliminar municipio? También se eliminan sus localidades.')) return;

    fetch(`/admin/municipios/${id}`, {
      method: 'DELETE',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
    })
      .then(res => {
        if (!res.ok) throw new Error();
        location.reload();
      })
      .catch(() => alert('Ocurrió un error al eliminar el municipio. Intenta de nuevo.'));
  }

  function agregarLocalidad() {
    const input = document.getElementById('nueva-localidad');
    const nombre = input.value.trim();
    if (!nombre || !municipioSeleccionadoId) return;

    fetch('/admin/localidades', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
      body: JSON.stringify({ nombre: nombre, municipio_id: municipioSeleccionadoId }),
    })
      .then(res => {
        if (!res.ok) throw new Error();
        location.reload();
      })
      .catch(() => alert('Ocurrió un error al agregar la localidad. Intenta de nuevo.'));
  }

  function eliminarLocalidad(id) {
    if (!confirm('¿Eliminar localidad?')) return;

    fetch(`/admin/localidades/${id}`, {
      method: 'DELETE',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
    })
      .then(res => {
        if (!res.ok) throw new Error();
        location.reload();
      })
      .catch(() => alert('Ocurrió un error al eliminar la localidad. Intenta de nuevo.'));
  }

  // Pinta la lista de municipios al cargar, y selecciona el primero automáticamente
  pintarMunicipios();
  if (municipios.length) seleccionarMunicipio([...municipios].sort((a, b) => a.nombre.localeCompare(b.nombre))[0].id);
</script>


  <script>
    (function () {
      const logoutBtns = document.querySelectorAll('.js-logout');
      if (!logoutBtns.length) return;
      logoutBtns.forEach(function (btn) {
        btn.addEventListener('click', function (e) {
          e.preventDefault();
          if (!confirm('¿Seguro que quieres cerrar sesión?')) return;
          const csrfMeta = document.querySelector('meta[name="csrf-token"]');
          const csrfToken = csrfMeta ? csrfMeta.content : '';
          fetch('/logout', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
          }).finally(() => { window.location.href = '/'; });
        });
      });
    })();
  </script>
</body>
</html>
