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

  <style>
    /* ===== Responsivo para móvil ===== */
    @media (max-width: 768px) {
      #tbl-postulaciones .is-actions-cell .buttons {
        justify-content: flex-start;
        flex-wrap: wrap;
      }
      .field.is-grouped.is-grouped-multiline {
        flex-wrap: wrap;
        row-gap: 0.5rem;
      }
      .field.is-grouped.is-grouped-multiline > .control {
        width: 100%;
      }
      .field.is-grouped.is-grouped-multiline .select,
      .field.is-grouped.is-grouped-multiline select {
        width: 100%;
      }
    }
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
          <a href="/admin/postulaciones" class="is-active router-link-active has-icon">
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
            <li>Postulaciones</li>
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
            <h1 class="title">Postulaciones</h1>
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

    <div class="card has-table">
      <header class="card-header">
        <p class="card-header-title"><span class="icon"><i class="mdi mdi-account-arrow-right default"></i></span>Postulaciones</p>
      </header>
      <div class="card-content">
        <div class="field is-grouped is-grouped-multiline mb-4">
          <div class="control">
            <div class="select">
              <select id="filtro-vacante">
                <option value="">Todas las vacantes</option>
                @foreach ($vacantes as $v)
                  <option value="{{ $v->id }}">{{ $v->titulo }}</option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="control">
            <div class="select">
              <select id="filtro-estado-post">
                <option value="">Todos los estados</option>
                <option value="pendiente">Pendiente</option>
                <option value="contratado">Contratado</option>
                <option value="rechazado">Rechazado</option>
              </select>
            </div>
          </div>
        </div>
        <table class="table is-fullwidth is-striped is-hoverable">
          <thead><tr><th>Postulante</th><th>Vacante</th><th>Fecha</th><th>Estado</th><th class="is-actions-cell">Acciones</th></tr></thead>
          <tbody id="tbl-postulaciones">
            @forelse ($postulaciones as $p)
              <tr data-vacante-id="{{ $p->vacante_id }}" data-estado="{{ $p->estado }}">
                <td data-label="Postulante">{{ $p->postulante->nombre ?? '—' }}</td>
                <td data-label="Vacante">{{ $p->vacante->titulo ?? '—' }}</td>
                <td data-label="Fecha"><small class="has-text-grey">{{ $p->created_at->format('d/m/Y') }}</small></td>
                <td data-label="Estado"><span class="tag is-{{ $p->estado === 'pendiente' ? 'warning' : ($p->estado === 'contratado' ? 'success' : 'danger') }}">{{ $p->estado }}</span></td>
                <td class="is-actions-cell">
                  <div class="buttons is-right">
                    <button class="button is-small is-success" {{ $p->estado === 'contratado' ? 'disabled' : '' }} onclick="cambiarEstadoPost({{ $p->id }}, 'contratado', this)">
                      <span class="icon"><i class="mdi mdi-check"></i></span>
                    </button>
                    <button class="button is-small is-danger" {{ $p->estado === 'rechazado' ? 'disabled' : '' }} onclick="cambiarEstadoPost({{ $p->id }}, 'rechazado', this)">
                      <span class="icon"><i class="mdi mdi-close"></i></span>
                    </button>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="5" class="has-text-centered has-text-grey">No hay postulaciones todavía.</td>
              </tr>
            @endforelse
          </tbody>
        </table>
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

  function cambiarEstadoPost(id, estado, btn) {
    fetch(`/admin/postulaciones/${id}`, {
      method: 'PATCH',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
      body: JSON.stringify({ estado: estado }),
    })
      .then(res => {
        if (!res.ok) throw new Error();
        location.reload();
      })
      .catch(() => alert('Ocurrió un error al actualizar la postulación. Intenta de nuevo.'));
  }

  function filtrarPostulaciones() {
    const vacF = document.getElementById('filtro-vacante').value;
    const estF = document.getElementById('filtro-estado-post').value;
    document.querySelectorAll('#tbl-postulaciones tr').forEach(function (tr) {
      if (!tr.dataset.vacanteId) return; // fila de "no hay postulaciones"
      const coincideVac = !vacF || tr.dataset.vacanteId === vacF;
      const coincideEst = !estF || tr.dataset.estado === estF;
      tr.style.display = (coincideVac && coincideEst) ? '' : 'none';
    });
  }
  document.getElementById('filtro-vacante').addEventListener('change', filtrarPostulaciones);
  document.getElementById('filtro-estado-post').addEventListener('change', filtrarPostulaciones);
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
