<!DOCTYPE html>
<html lang="es" class="has-aside-left has-aside-mobile-transition has-navbar-fixed-top has-aside-expanded">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Admin · Testimonios · Empleabilidad Zacapoaxtla</title>

  <link rel="stylesheet" href="{{ asset('assets/admin/css/main.min.css') }}">

  <link rel="dns-prefetch" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.9.95/css/materialdesignicons.min.css">
  <style>
    .aside-tools-label b { font-weight: 700; }
    .testimonio-texto { max-width: 360px; white-space: normal; }
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
            <a href="/admin/testimonios" class="is-active router-link-active has-icon">
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
              <li>Testimonios</li>
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
              <h1 class="title">Testimonios de la plataforma</h1>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="section is-main-section">

      <div class="card has-table">
        <header class="card-header">
          <p class="card-header-title">
            <span class="icon"><i class="mdi mdi-comment-quote default"></i></span>
            Reseñas enviadas por usuarios
          </p>
        </header>

        <div class="card-content">

          <div class="field is-grouped mb-4">
            <div class="control">
              <div class="select">
                <select id="filtro-estado-testimonio">
                  <option value="">Todos los estados</option>
                  <option value="pendiente" selected>Pendientes</option>
                  <option value="aprobado">Aprobados</option>
                  <option value="rechazado">Rechazados</option>
                </select>
              </div>
            </div>
          </div>

          <table class="table is-fullwidth is-striped is-hoverable">
            <thead>
              <tr>
                <th>Usuario</th>
                <th>Reseña</th>
                <th>Estrellas</th>
                <th>Estado</th>
                <th>Fecha</th>
                <th class="is-actions-cell">Acciones</th>
              </tr>
            </thead>

            <tbody id="tbl-testimonios">
              @forelse ($testimonios as $t)
                <tr data-estado="{{ $t->estado }}" data-testimonio-id="{{ $t->id }}">
                  <td data-label="Usuario">{{ $t->usuario->nombre ?? '—' }}</td>
                  <td data-label="Reseña" class="testimonio-texto">{{ $t->texto }}</td>
                  <td data-label="Estrellas">
                    @if ($t->estrellas)
                      @for ($i = 1; $i <= 5; $i++)
                        <i class="mdi mdi-star{{ $i <= $t->estrellas ? '' : '-outline' }}" style="color:#ffb347;"></i>
                      @endfor
                    @else
                      <small class="has-text-grey">—</small>
                    @endif
                  </td>
                  <td data-label="Estado">
                    <span class="tag is-{{ $t->estado === 'pendiente' ? 'warning' : ($t->estado === 'aprobado' ? 'success' : 'danger') }}">{{ $t->estado }}</span>
                  </td>
                  <td data-label="Fecha"><small class="has-text-grey">{{ $t->created_at->format('d/m/Y') }}</small></td>
                  <td class="is-actions-cell">
                    <div class="buttons is-right">
                      @if ($t->estado !== 'aprobado')
                        <button class="button is-small is-success" title="Aprobar" onclick="cambiarEstadoTestimonio({{ $t->id }}, 'aprobado', this)">
                          <span class="icon"><i class="mdi mdi-check"></i></span>
                        </button>
                      @endif
                      @if ($t->estado !== 'rechazado')
                        <button class="button is-small is-warning" title="Rechazar" onclick="cambiarEstadoTestimonio({{ $t->id }}, 'rechazado', this)">
                          <span class="icon"><i class="mdi mdi-close"></i></span>
                        </button>
                      @endif
                      <button class="button is-small is-danger" title="Eliminar" onclick="eliminarTestimonio({{ $t->id }}, this)">
                        <span class="icon"><i class="mdi mdi-trash-can"></i></span>
                      </button>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="6" class="has-text-centered has-text-grey">No hay testimonios registrados todavía.</td>
                </tr>
              @endforelse
            </tbody>
          </table>

        </div>
      </div>

    </section>
  </div>

  <script src="{{ asset('assets/admin/js/main.min.js') }}"></script>
  <script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    // ===== Filtro por estado =====
    const filtroEstado = document.getElementById('filtro-estado-testimonio');
    function aplicarFiltroTestimonio() {
      const valor = filtroEstado.value;
      document.querySelectorAll('#tbl-testimonios tr[data-estado]').forEach(row => {
        row.style.display = (!valor || row.dataset.estado === valor) ? '' : 'none';
      });
    }
    filtroEstado.addEventListener('change', aplicarFiltroTestimonio);
    aplicarFiltroTestimonio();

    // ===== Aprobar / Rechazar =====
    function cambiarEstadoTestimonio(id, estado, btn) {
      fetch(`/admin/testimonios/${id}`, {
        method: 'PATCH',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
        body: JSON.stringify({ estado: estado }),
      })
        .then(res => res.json())
        .then(data => {
          if (!data.ok) { alert('❌ Ocurrió un error.'); return; }
          location.reload();
        })
        .catch(() => alert('❌ Ocurrió un error de conexión.'));
    }

    // ===== Eliminar =====
    function eliminarTestimonio(id, btn) {
      if (!confirm('¿Eliminar este testimonio? No se puede deshacer.')) return;
      fetch(`/admin/testimonios/${id}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': csrfToken },
      })
        .then(res => res.json())
        .then(data => {
          if (!data.ok) { alert('❌ Ocurrió un error.'); return; }
          btn.closest('tr').remove();
        })
        .catch(() => alert('❌ Ocurrió un error de conexión.'));
    }
  </script>

  <script>
    (function () {
      document.querySelectorAll('.js-logout').forEach(el => {
        el.addEventListener('click', function (e) {
          e.preventDefault();
          if (!confirm('¿Seguro que quieres cerrar sesión?')) return;
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
