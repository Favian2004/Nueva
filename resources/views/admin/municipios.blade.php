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
            <p class="card-header-title"><span class="icon"><i class="mdi mdi-map-marker default"></i></span>Municipios</p>
          </header>
          <div class="card-content">
            <div class="field has-addons mb-4">
              <div class="control is-expanded"><input class="input" id="nuevo-municipio" placeholder="Nuevo municipio..."></div>
              <div class="control"><button class="button is-primary" onclick="agregarMunicipio()"><span class="icon"><i class="mdi mdi-plus"></i></span></button></div>
            </div>
            <table class="table is-fullwidth is-striped is-hoverable">
              <thead><tr><th>Nombre</th><th>Localidades</th><th class="is-actions-cell">Acciones</th></tr></thead>
              <tbody id="tbl-municipios">
                @forelse ($municipios as $m)
                  <tr>
                    <td>{{ $m->nombre }}</td>
                    <td>{{ $m->localidades_count }}</td>
                    <td class="is-actions-cell">
                      <div class="buttons is-right">
                        <button class="button is-small is-danger" title="Eliminar" onclick="eliminarMunicipio({{ $m->id }})">
                          <span class="icon"><i class="mdi mdi-trash-can"></i></span>
                        </button>
                      </div>
                    </td>
                  </tr>
                @empty
                  <tr><td colspan="3" class="has-text-centered has-text-grey">No hay municipios todavía.</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="column is-8">
        <div class="card has-table">
          <header class="card-header">
            <p class="card-header-title"><span class="icon"><i class="mdi mdi-map-marker-outline default"></i></span>Localidades</p>
          </header>
          <div class="card-content">
            <div class="field has-addons mb-4">
              <div class="control">
                <div class="select">
                  <select id="select-municipio-padre">
                    @foreach ($municipios as $m)
                      <option value="{{ $m->id }}">{{ $m->nombre }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="control is-expanded"><input class="input" id="nueva-localidad" placeholder="Nueva localidad..."></div>
              <div class="control"><button class="button is-primary" onclick="agregarLocalidad()"><span class="icon"><i class="mdi mdi-plus"></i></span></button></div>
            </div>
            <div class="field">
              <div class="control">
                <input class="input" id="buscar-localidad" placeholder="Buscar localidad...">
              </div>
            </div>
            <table class="table is-fullwidth is-striped is-hoverable">
              <thead><tr><th>Localidad</th><th>Municipio</th><th>Usuarios</th><th class="is-actions-cell">Acciones</th></tr></thead>
              <tbody id="tbl-localidades">
                @forelse ($localidades as $l)
                  <tr>
                    <td data-label="Localidad">{{ $l->nombre }}</td>
                    <td data-label="Municipio">{{ $l->municipio->nombre ?? '—' }}</td>
                    <td data-label="Usuarios">{{ $l->usuarios_count }}</td>
                    <td class="is-actions-cell">
                      <div class="buttons is-right">
                        <button class="button is-small is-danger" title="Eliminar" onclick="eliminarLocalidad({{ $l->id }})">
                          <span class="icon"><i class="mdi mdi-trash-can"></i></span>
                        </button>
                      </div>
                    </td>
                  </tr>
                @empty
                  <tr><td colspan="4" class="has-text-centered has-text-grey">No hay localidades todavía.</td></tr>
                @endforelse
              </tbody>
            </table>
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
  const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

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
    const munId = document.getElementById('select-municipio-padre').value;
    const nombre = input.value.trim();
    if (!nombre || !munId) return;

    fetch('/admin/localidades', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
      body: JSON.stringify({ nombre: nombre, municipio_id: munId }),
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

  // ===== Buscador de localidades (filtra las filas ya renderizadas) =====
  document.getElementById('buscar-localidad').addEventListener('input', function (e) {
    const f = e.target.value.toLowerCase();
    document.querySelectorAll('#tbl-localidades tr').forEach(function (tr) {
      const nombreCell = tr.querySelector('[data-label="Localidad"]');
      if (!nombreCell) return; // fila de "no hay localidades"
      tr.style.display = nombreCell.textContent.toLowerCase().includes(f) ? '' : 'none';
    });
  });
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
