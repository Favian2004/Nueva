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
          <a href="/admin/categorias" class="is-active router-link-active has-icon">
            <span class="icon"><i class="mdi mdi-shape"></i></span>
            <span class="menu-item-label">Categorías</span>
          </a>
        </li>
        <li>
          <a href="/admin/municipios" class="has-icon">
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
            <li>Categorías</li>
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
            <h1 class="title">Categorías y subcategorías</h1>
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
      <div class="column is-5">
        <div class="card has-table">
          <header class="card-header">
            <p class="card-header-title"><span class="icon"><i class="mdi mdi-shape default"></i></span>Categorías</p>
          </header>
          <div class="card-content">
            <div class="field has-addons mb-4">
              <div class="control is-expanded"><input class="input" id="nueva-categoria" placeholder="Nueva categoría..."></div>
              <div class="control"><button class="button is-primary" onclick="agregarCategoria()"><span class="icon"><i class="mdi mdi-plus"></i></span></button></div>
            </div>
            <table class="table is-fullwidth is-striped is-hoverable">
              <thead><tr><th>Nombre</th><th class="is-actions-cell">Acciones</th></tr></thead>
              <tbody id="tbl-categorias">
                @forelse ($categorias as $c)
                  <tr>
                    <td>{{ $c->nombre }}</td>
                    <td class="is-actions-cell">
                      <div class="buttons is-right">
                        <button class="button is-small is-danger" title="Eliminar" onclick="eliminarCategoria({{ $c->id }})">
                          <span class="icon"><i class="mdi mdi-trash-can"></i></span>
                        </button>
                      </div>
                    </td>
                  </tr>
                @empty
                  <tr><td colspan="2" class="has-text-centered has-text-grey">No hay categorías todavía.</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="column is-7">
        <div class="card has-table">
          <header class="card-header">
            <p class="card-header-title"><span class="icon"><i class="mdi mdi-shape-outline default"></i></span>Subcategorías</p>
          </header>
          <div class="card-content">
            <div class="field has-addons mb-4">
              <div class="control">
                <div class="select">
                  <select id="select-categoria-padre">
                    @foreach ($categorias as $c)
                      <option value="{{ $c->id }}">{{ $c->nombre }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="control is-expanded"><input class="input" id="nueva-subcategoria" placeholder="Nueva subcategoría..."></div>
              <div class="control"><button class="button is-primary" onclick="agregarSubcategoria()"><span class="icon"><i class="mdi mdi-plus"></i></span></button></div>
            </div>
            <table class="table is-fullwidth is-striped is-hoverable">
              <thead><tr><th>Subcategoría</th><th>Categoría</th><th class="is-actions-cell">Acciones</th></tr></thead>
              <tbody id="tbl-subcategorias">
                @forelse ($subcategorias as $s)
                  <tr>
                    <td>{{ $s->nombre }}</td>
                    <td>{{ $s->categoria->nombre ?? '—' }}</td>
                    <td class="is-actions-cell">
                      <div class="buttons is-right">
                        <button class="button is-small is-danger" title="Eliminar" onclick="eliminarSubcategoria({{ $s->id }})">
                          <span class="icon"><i class="mdi mdi-trash-can"></i></span>
                        </button>
                      </div>
                    </td>
                  </tr>
                @empty
                  <tr><td colspan="3" class="has-text-centered has-text-grey">No hay subcategorías todavía.</td></tr>
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
  // ===== Categorías y subcategorías (guardan de verdad en la base de datos) =====
  const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

  function agregarCategoria() {
    const input = document.getElementById('nueva-categoria');
    const nombre = input.value.trim();
    if (!nombre) return;

    fetch('/admin/categorias', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
      body: JSON.stringify({ nombre: nombre }),
    })
      .then(res => {
        if (!res.ok) throw new Error();
        location.reload();
      })
      .catch(() => alert('Ocurrió un error al agregar la categoría. Intenta de nuevo.'));
  }

  function eliminarCategoria(id) {
    if (!confirm('¿Eliminar categoría? También se eliminarán sus subcategorías.')) return;

    fetch(`/admin/categorias/${id}`, {
      method: 'DELETE',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
    })
      .then(res => {
        if (!res.ok) throw new Error();
        location.reload();
      })
      .catch(() => alert('Ocurrió un error al eliminar la categoría. Intenta de nuevo.'));
  }

  function agregarSubcategoria() {
    const input = document.getElementById('nueva-subcategoria');
    const catId = document.getElementById('select-categoria-padre').value;
    const nombre = input.value.trim();
    if (!nombre || !catId) return;

    fetch('/admin/subcategorias', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
      body: JSON.stringify({ nombre: nombre, categoria_id: catId }),
    })
      .then(res => {
        if (!res.ok) throw new Error();
        location.reload();
      })
      .catch(() => alert('Ocurrió un error al agregar la subcategoría. Intenta de nuevo.'));
  }

  function eliminarSubcategoria(id) {
    if (!confirm('¿Eliminar subcategoría?')) return;

    fetch(`/admin/subcategorias/${id}`, {
      method: 'DELETE',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
    })
      .then(res => {
        if (!res.ok) throw new Error();
        location.reload();
      })
      .catch(() => alert('Ocurrió un error al eliminar la subcategoría. Intenta de nuevo.'));
  }
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
