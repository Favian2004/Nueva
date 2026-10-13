<!DOCTYPE html>
<!-- misEmpleos.html -->
<html lang="es" class="has-aside-left has-aside-mobile-transition has-navbar-fixed-top has-aside-expanded">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Mis Empleos · Dashboard</title>
  <link rel="stylesheet" href="{{ asset('assets/usuario/css/main.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/usuario/css/styles.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/usuario/css/switch.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.9.95/css/materialdesignicons.min.css">
  <link rel="icon" type="img/" href="{{ asset('assets/usuario/img/icono.png') }}">
  <link rel="stylesheet" href="{{ asset('assets/usuario/css/ads-widget.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/usuario/css/theme-conectaya.css') }}">
  <link rel="stylesheet" href="{{ asset('css/anuncio-negocio.css') }}">

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
            <span class="crumb-page">Mis Empleos</span>
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

    <!-- SIDEBAR -->
    <aside class="aside is-placed-left is-expanded" id="mainSidebar">
      <div class="aside-tools">
        <div class="aside-tools-label">
          <span><b> Modo Trabajador</b></span>
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
          <li><a href="/usuario" class="has-icon"><span class="icon"><i class="mdi mdi-desktop-mac"></i></span><span
                class="menu-item-label">Inicio</span></a></li>
        </ul>
        <p class="menu-label">Otros</p>
        <ul class="menu-list">
          <li><a href="/usuario/verEmpleos" class="has-icon"><span class="icon"><i
                  class="mdi mdi-briefcase"></i></span><span class="menu-item-label">Ver empleos</span></a></li>
          <li><a href="/usuario/publicarEmpleo" class="has-icon"><span class="icon"><i
                  class="mdi mdi-square-edit-outline"></i></span><span class="menu-item-label">Publicar
                empleo</span></a></li>
          <li><a href="/usuario/misEmpleos" class="is-active router-link-active has-icon"><span
                class="icon has-update-mark"><i class="mdi mdi-format-list-bulleted"></i></span><span
                class="menu-item-label">Mis empleos</span></a></li>
          <li><a href="/usuario/solicitudes" class="has-icon"><span class="icon"><i
                  class="mdi mdi-account-clock"></i></span><span class="menu-item-label">Solicitudes</span></a></li>
          <li><a href="/usuario/profile" class="has-icon"><span class="icon"><i
                  class="mdi mdi-account-circle"></i></span><span class="menu-item-label">Perfil</span></a></li>
        </ul>
      </div>
    </aside>

    <!-- CONTENIDO -->
    <section class="section is-main-section mis-empleos-section">

      <!-- ESTADÍSTICAS -->
      <div class="empleos-stats">
        <div class="stat-card">
          <div class="stat-num">{{ $total }}</div>
          <div class="stat-label">Total publicados</div>
        </div>
        <div class="stat-card">
          <div class="stat-num" style="color:#2563eb">{{ $activos }}</div>
          <div class="stat-label">Activos</div>
        </div>
        <div class="stat-card">
          <div class="stat-num" style="color:#ca8a04">{{ $inactivos }}</div>
          <div class="stat-label">Inactivos</div>
        </div>
      </div>

      <!-- TABLA -->
      <div class="empleos-table-wrap">
        <div class="empleos-table-header">
          <h3>📋 Mis publicaciones</h3>
          <a href="/usuario/publicarEmpleo" class="btn-nuevo">+ Nuevo empleo</a>
        </div>

        <div style="overflow-x:auto">
          <table class="table is-fullwidth is-hoverable empleos-table">
            <thead>
              <tr>
                <th>Empleo / Servicio</th>
                <th>Categoría</th>
                <th>Subcategoría</th>
                <th>Precio</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($servicios as $s)
                <tr>
                  <td><strong>{{ $s->titulo }}</strong><br><small style="color:#888">Zacapoaxtla</small></td>
                  <td>{{ $s->categoria->nombre ?? '—' }}</td>
                  <td>{{ $s->subcategoria->nombre ?? '—' }}</td>
                  <td>${{ number_format($s->precio, 2) }}</td>
                  <td>{{ $s->created_at->format('d/m/Y') }}</td>
                  <td><span class="status-badge {{ $s->estado === 'activo' ? 'status-activo' : 'status-pendiente' }}">{{ $s->estado === 'activo' ? 'Activo' : 'Inactivo' }}</span></td>
                  <td>
                    <a href="/usuario/misEmpleos/{{ $s->id }}/editar" class="btn-accion btn-editar"><i class="fa-solid fa-pen-to-square"></i> Editar</a>
                    <button class="btn-accion" onclick="toggleServicio({{ $s->id }})">{{ $s->estado === 'activo' ? '👁️‍🗨️ Desactivar' : '👁️ Activar' }}</button>
                    <button class="btn-accion btn-eliminar" onclick="confirmarEliminar({{ $s->id }}, this)"><i class="fa-solid fa-trash-can"></i> Eliminar</button>
                  </td>
                </tr>
              @empty
                <tr>
                  <td colspan="7" style="text-align:center; color:#888;">Todavía no has publicado ningún servicio. <a href="/usuario/publicarEmpleo">Publica el primero</a>.</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

    </section>

    <!-- ========================= -->
    <!-- ANUNCIOS INFERIOR -->
    <!-- ========================= -->

    <div class="container mb-4 d-none d-md-block" style="margin-top: 90px;">
      @include('partials.anuncio-negocio')
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
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    function toggleServicio(id) {
      fetch(`/usuario/misEmpleos/${id}/toggle`, {
        method: 'PATCH',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
      })
        .then(res => {
          if (!res.ok) throw new Error();
          location.reload();
        })
        .catch(() => alert('Ocurrió un error al actualizar el servicio. Intenta de nuevo.'));
    }

    function confirmarEliminar(id, btn) {
      if (!confirm('¿Seguro que deseas eliminar esta publicación?')) return;
      fetch(`/usuario/misEmpleos/${id}`, {
        method: 'DELETE',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
      })
        .then(res => {
          if (!res.ok) throw new Error();
          btn.closest('tr').remove();
        })
        .catch(() => alert('Ocurrió un error al eliminar la publicación. Intenta de nuevo.'));
    }
  </script>
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
