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
    .aside-tools-label b {
      font-weight: 700;
    }

    .is-image-cell .image img {
      object-fit: cover;
    }

    .anuncio-thumb {
      width: 160px;
      height: 90px;
      object-fit: cover;
      border-radius: 6px;
    }

    .badge-dot {
      display: inline-block;
      width: 8px;
      height: 8px;
      border-radius: 50%;
      margin-right: 6px;
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
            <a href="/admin/reportes" class="is-active router-link-active has-icon">
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
              <li>Reportes</li>
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
              <h1 class="title">Reportes y quejas</h1>
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

      <!-- TABLA DE REPORTES -->
      <div class="card has-table">
        <header class="card-header">
          <p class="card-header-title">
            <span class="icon">
              <i class="mdi mdi-alert-octagon default"></i>
            </span>
            Reportes y quejas
          </p>
        </header>

        <div class="card-content">

          <!-- FILTRO -->
          <div class="field is-grouped mb-4">

            <div class="control">
              <div class="select">
                <select id="filtro-estado-reporte">
                  <option value="">Todos los estados</option>
                  <option value="pendiente">Pendiente</option>
                  <option value="revisado">Revisado</option>
                  <option value="resuelto">Resuelto</option>
                  <option value="descartado">Descartado</option>
                </select>
              </div>
            </div>

          </div>

          <!-- TABLA -->
          <table class="table is-fullwidth is-striped is-hoverable">

            <thead>
              <tr>
                <th>Reportó</th>
                <th>Reportado</th>
                <th>Tipo</th>
                <th>Motivo</th>
                <th>Estado</th>
                <th>Fecha</th>
                <th class="is-actions-cell">Acciones</th>
              </tr>
            </thead>

            <tbody id="tbl-reportes">
              @forelse ($reportes as $r)
                <tr data-estado="{{ $r->estado }}">
                  <td data-label="Reportó">{{ $r->usuarioReporta->nombre ?? '—' }}</td>
                  <td data-label="Reportado">{{ $r->usuarioReportado->nombre ?? '—' }}</td>
                  <td data-label="Tipo"><small class="has-text-grey">{{ $r->tipo_objeto }}</small></td>
                  <td data-label="Motivo">{{ $r->motivo }}</td>
                  <td data-label="Estado">
                    <span class="tag is-{{ $r->estado === 'pendiente' ? 'warning' : ($r->estado === 'revisado' ? 'info' : ($r->estado === 'resuelto' ? 'success' : 'grey-dark')) }}">{{ $r->estado }}</span>
                  </td>
                  <td data-label="Fecha"><small class="has-text-grey">{{ $r->created_at->format('d/m/Y') }}</small></td>
                  <td class="is-actions-cell">
                    <div class="buttons is-right">
                      <button
                        class="button is-small is-primary"
                        title="Ver detalle"
                        onclick="verReporte(this)"
                        data-reporte-id="{{ $r->id }}"
                        data-reporta="{{ $r->usuarioReporta->nombre ?? '—' }}"
                        data-reportado="{{ $r->usuarioReportado->nombre ?? '—' }}"
                        data-tipo="{{ $r->tipo_objeto }}"
                        data-motivo="{{ $r->motivo }}"
                        data-descripcion="{{ $r->descripcion ?? 'Sin descripción adicional' }}"
                        data-estado="{{ $r->estado }}"
                        data-usuario-reportado-id="{{ $r->usuario_reportado_id }}"
                      >
                        <span class="icon"><i class="mdi mdi-eye"></i></span>
                      </button>
                    </div>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" class="has-text-centered has-text-grey">No hay reportes registrados todavía.</td>
                </tr>
              @endforelse
            </tbody>

          </table>

        </div>
      </div>



      <!-- MODAL -->
      <div id="modal-reporte" class="modal">

        <div class="modal-background" onclick="document.getElementById('modal-reporte').classList.remove('is-active')">
        </div>

        <div class="modal-card" style="max-width:520px; border-radius:16px; overflow:hidden;">

          <header class="modal-card-head" style="background:linear-gradient(135deg,#8f1d2f,#b12d25); border:none;">

            <p class="modal-card-title" style="color:#fff;">
              <i class="mdi mdi-alert-circle"></i>
              Detalle del reporte
            </p>

            <button class="delete" aria-label="close"
              onclick="document.getElementById('modal-reporte').classList.remove('is-active')">
            </button>

          </header>


          <section class="modal-card-body" id="modal-reporte-body" style="padding:22px;">

            <!-- Se llena desde JavaScript -->

          </section>


          <footer class="modal-card-foot" style="display:grid; grid-template-columns:1fr 1fr; gap:8px; padding:16px 22px; border-top:1px solid #eee;">

            <input type="hidden" id="modal-reporte-id">
            <input type="hidden" id="modal-reporte-usuario-id">

            <button class="button is-dark" onclick="suspenderDesdeReporte()" style="border-radius:10px; font-size:.85rem;">
              <span class="icon"><i class="mdi mdi-account-lock"></i></span>
              <span>Suspender usuario</span>
            </button>

            <button class="button is-success" onclick="cambiarEstadoReporte('resuelto')" style="border-radius:10px; font-size:.85rem;">
              <span class="icon"><i class="mdi mdi-check-circle"></i></span>
              <span>Marcar resuelto</span>
            </button>

            <button class="button is-warning" onclick="cambiarEstadoReporte('revisado')" style="border-radius:10px; font-size:.85rem;">
              <span class="icon"><i class="mdi mdi-eye-check"></i></span>
              <span>Marcar revisado</span>
            </button>

            <button class="button is-danger is-light" onclick="cambiarEstadoReporte('descartado')" style="border-radius:10px; font-size:.85rem;">
              <span class="icon"><i class="mdi mdi-close-circle"></i></span>
              <span>Descartar</span>
            </button>

          </footer>

        </div>

        <style>
          @media (max-width: 480px) {
            #modal-reporte .modal-card-foot {
              grid-template-columns: 1fr !important;
            }
          }
        </style>
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
                <a href="https://justboil.me"><img src="{{ asset('assets/admin/img/justboil-logo.svg') }}" alt="JustBoil.me"
                    style="height:20px;"></a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </footer>
  </div>

  <script type="text/javascript" src="{{ asset('assets/admin/js/main.min.js') }}"></script>
  <script>
    const badgeReporte = { pendiente: 'is-warning', revisado: 'is-info', resuelto: 'is-success', descartado: 'is-grey-dark' };

    // ===== Ver detalle (usa los datos ya renderizados en el botón, sin mock) =====
    function verReporte(btn) {
      const d = btn.dataset;
      document.getElementById('modal-reporte-id').value = d.reporteId;
      document.getElementById('modal-reporte-usuario-id').value = d.usuarioReportadoId || '';
      document.getElementById('modal-reporte-body').innerHTML = `
      <div style="display:flex; gap:10px; margin-bottom:16px;">
        <span class="tag is-info is-light">${d.tipo}</span>
        <span class="tag ${badgeReporte[d.estado]}">${d.estado}</span>
      </div>

      <div style="background:#f8fafc; border-radius:12px; padding:14px 16px; margin-bottom:16px; display:grid; grid-template-columns:1fr 1fr; gap:14px;">
        <div>
          <div style="font-size:.75rem; color:#888; text-transform:uppercase; letter-spacing:.03em;">
            <i class="mdi mdi-account-alert"></i> Reportado por
          </div>
          <strong>${d.reporta}</strong>
        </div>
        <div>
          <div style="font-size:.75rem; color:#888; text-transform:uppercase; letter-spacing:.03em;">
            <i class="mdi mdi-account-off"></i> Usuario reportado
          </div>
          <strong>${d.reportado}</strong>
        </div>
      </div>

      <h3 style="font-size:.85rem; color:#888; text-transform:uppercase; letter-spacing:.03em; margin-bottom:4px;">
        <i class="mdi mdi-alert-octagon-outline"></i> Motivo
      </h3>
      <p style="margin-bottom:16px; font-weight:600;">${d.motivo}</p>

      <h3 style="font-size:.85rem; color:#888; text-transform:uppercase; letter-spacing:.03em; margin-bottom:4px;">
        <i class="mdi mdi-text-box-outline"></i> Descripción
      </h3>
      <p style="line-height:1.6; color:#333;">${d.descripcion || '<span style="color:#aaa;">Sin descripción adicional.</span>'}</p>
      `;
      document.getElementById('modal-reporte').classList.add('is-active');
    }

    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    // ===== Acciones (guardan de verdad en la base de datos) =====
    function cambiarEstadoReporte(estado) {
      const id = document.getElementById('modal-reporte-id').value;
      fetch(`/admin/reportes/${id}`, {
        method: 'PATCH',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
        body: JSON.stringify({ estado: estado }),
      })
        .then(res => {
          if (!res.ok) throw new Error();
          document.getElementById('modal-reporte').classList.remove('is-active');
          location.reload();
        })
        .catch(() => alert('Ocurrió un error al actualizar el reporte. Intenta de nuevo.'));
    }

    function suspenderDesdeReporte() {
      const id = document.getElementById('modal-reporte-id').value;
      const usuarioId = document.getElementById('modal-reporte-usuario-id').value;
      if (!usuarioId) { alert('Este reporte no está asociado a un usuario.'); return; }

      fetch(`/admin/reportes/${id}/suspender-usuario`, {
        method: 'PATCH',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
      })
        .then(res => {
          if (!res.ok) throw new Error();
          document.getElementById('modal-reporte').classList.remove('is-active');
          location.reload();
        })
        .catch(() => alert('Ocurrió un error al suspender la cuenta. Intenta de nuevo.'));
    }

    // ===== Filtro (filtra las filas ya renderizadas) =====
    document.getElementById('filtro-estado-reporte').addEventListener('change', function (e) {
      const f = e.target.value;
      document.querySelectorAll('#tbl-reportes tr').forEach(function (tr) {
        if (!tr.dataset.estado) return; // fila de "no hay reportes"
        tr.style.display = (!f || tr.dataset.estado === f) ? '' : 'none';
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
