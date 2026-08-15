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
                        <a href="/admin" class="is-active router-link-active has-icon">
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
                            <li>Dashboard</li>
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
                            <h1 class="title">Dashboard</h1>
                        </div>
                    </div>
                    <div class="level-right">
                        <div class="level-item">
                            <span class="tag is-primary">Vista general</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="section is-main-section">

            <div class="columns is-multiline is-mobile is-gapless-mobile dashboard-cards">

                <!-- Usuarios registrados -->
                <div class="column is-6-mobile is-4-tablet">
                    <div class="card card-dashboard">
                        <div class="card-content">
                            <div class="level is-mobile">
                                <div class="level-item">
                                    <div class="is-widget-label">
                                        <p class="subtitle is-7 has-text-grey-light">Usuarios</p>
                                        <h2 class="title is-5 has-text-weight-bold">{{ $stats['usuarios'] }}</h2>
                                    </div>
                                </div>
                                <div class="level-item has-widget-icon">
                                    <div class="icon-wrapper has-background-primary-light">
                                        <span class="icon has-text-primary">
                                            <i class="mdi mdi-account-multiple mdi-24px"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="progress-indicator is-primary">
                                <div class="progress-bar" style="width: 70%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Verificaciones pendientes -->
                <div class="column is-6-mobile is-4-tablet">
                    <div class="card card-dashboard">
                        <div class="card-content">
                            <div class="level is-mobile">
                                <div class="level-item">
                                    <div class="is-widget-label">
                                        <p class="subtitle is-7 has-text-grey-light">Verificaciones</p>
                                        <h2 class="title is-5 has-text-weight-bold">{{ $stats['verificaciones'] }}</h2>
                                    </div>
                                </div>
                                <div class="level-item has-widget-icon">
                                    <div class="icon-wrapper has-background-warning-light">
                                        <span class="icon has-text-warning">
                                            <i class="mdi mdi mdi-check-all mdi-24px"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="progress-indicator is-warning">
                                <div class="progress-bar" style="width: 45%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Vacantes activas -->
                <div class="column is-6-mobile is-4-tablet">
                    <div class="card card-dashboard">
                        <div class="card-content">
                            <div class="level is-mobile">
                                <div class="level-item">
                                    <div class="is-widget-label">
                                        <p class="subtitle is-7 has-text-grey-light">Vacantes</p>
                                        <h2 class="title is-5 has-text-weight-bold">{{ $stats['vacantes'] }}</h2>
                                    </div>
                                </div>
                                <div class="level-item has-widget-icon">
                                    <div class="icon-wrapper has-background-info-light">
                                        <span class="icon has-text-info">
                                            <i class="mdi mdi-briefcase-search mdi-24px"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="progress-indicator is-info">
                                <div class="progress-bar" style="width: 80%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Servicios activos -->
                <div class="column is-6-mobile is-4-tablet">
                    <div class="card card-dashboard">
                        <div class="card-content">
                            <div class="level is-mobile">
                                <div class="level-item">
                                    <div class="is-widget-label">
                                        <p class="subtitle is-7 has-text-grey-light">Servicios</p>
                                        <h2 class="title is-5 has-text-weight-bold">{{ $stats['servicios'] }}</h2>
                                    </div>
                                </div>
                                <div class="level-item has-widget-icon">
                                    <div class="icon-wrapper has-background-success-light">
                                        <span class="icon has-text-success">
                                            <i class="mdi mdi-hammer-wrench mdi-24px"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="progress-indicator is-success">
                                <div class="progress-bar" style="width: 60%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Postulaciones pendientes -->
                <div class="column is-6-mobile is-4-tablet">
                    <div class="card card-dashboard">
                        <div class="card-content">
                            <div class="level is-mobile">
                                <div class="level-item">
                                    <div class="is-widget-label">
                                        <p class="subtitle is-7 has-text-grey-light">Postulaciones</p>
                                        <h2 class="title is-5 has-text-weight-bold">{{ $stats['postulaciones'] }}</h2>
                                    </div>
                                </div>
                                <div class="level-item has-widget-icon">
                                    <div class="icon-wrapper has-background-danger-light">
                                        <span class="icon has-text-danger">
                                            <i class="mdi mdi-account-arrow-right mdi-24px"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="progress-indicator is-danger">
                                <div class="progress-bar" style="width: 30%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Anuncios activos -->
                <div class="column is-6-mobile is-4-tablet">
                    <div class="card card-dashboard">
                        <div class="card-content">
                            <div class="level is-mobile">
                                <div class="level-item">
                                    <div class="is-widget-label">
                                        <p class="subtitle is-7 has-text-grey-light">Anuncios</p>
                                        <h2 class="title is-5 has-text-weight-bold">{{ $stats['anuncios'] }}</h2>
                                    </div>
                                </div>
                                <div class="level-item has-widget-icon">
                                    <div class="icon-wrapper has-background-primary-light">
                                        <span class="icon has-text-primary">
                                            <i class="mdi mdi-bullhorn mdi-24px"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="progress-indicator is-primary">
                                <div class="progress-bar" style="width: 50%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Municipios cubiertos -->
                <div class="column is-6-mobile is-4-tablet">
                    <div class="card card-dashboard">
                        <div class="card-content">
                            <div class="level is-mobile">
                                <div class="level-item">
                                    <div class="is-widget-label">
                                        <p class="subtitle is-7 has-text-grey-light">Municipios</p>
                                        <h2 class="title is-5 has-text-weight-bold">{{ $stats['municipios'] }}</h2>
                                    </div>
                                </div>
                                <div class="level-item has-widget-icon">
                                    <div class="icon-wrapper has-background-grey-light">
                                        <span class="icon has-text-grey-dark">
                                            <i class="mdi mdi-map-marker mdi-24px"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="progress-indicator is-grey">
                                <div class="progress-bar" style="width: 90%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Categorías -->
                <div class="column is-6-mobile is-4-tablet">
                    <div class="card card-dashboard">
                        <div class="card-content">
                            <div class="level is-mobile">
                                <div class="level-item">
                                    <div class="is-widget-label">
                                        <p class="subtitle is-7 has-text-grey-light">Categorías</p>
                                        <h2 class="title is-5 has-text-weight-bold">{{ $stats['categorias'] }}</h2>
                                    </div>
                                </div>
                                <div class="level-item has-widget-icon">
                                    <div class="icon-wrapper has-background-link-light">
                                        <span class="icon has-text-link">
                                            <i class="mdi mdi-shape mdi-24px"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="progress-indicator is-link">
                                <div class="progress-bar" style="width: 40%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reportes pendientes -->
                <div class="column is-6-mobile is-4-tablet">
                    <div class="card card-dashboard">
                        <div class="card-content">
                            <div class="level is-mobile">
                                <div class="level-item">
                                    <div class="is-widget-label">
                                        <p class="subtitle is-7 has-text-grey-light">Reportes</p>
                                        <h2 class="title is-5 has-text-weight-bold">{{ $stats['reportes'] }}</h2>
                                    </div>
                                </div>
                                <div class="level-item has-widget-icon">
                                    <div class="icon-wrapper has-background-danger-light">
                                        <span class="icon has-text-danger">
                                            <i class="mdi mdi-alert-octagon mdi-24px"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="progress-indicator is-danger">
                                <div class="progress-bar" style="width: 20%"></div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="tile is-ancestor">
                <div class="tile is-parent is-6">
                    <div class="card tile is-child">
                        <header class="card-header">
                            <p class="card-header-title"><span class="icon"><i class="mdi mdi-account-clock default"></i></span>Verificaciones pendientes</p>
                            <a href="/admin/usuarios" class="card-header-icon"><span class="icon"><i class="mdi mdi-arrow-right"></i></span></a>
                        </header>
                        <div class="card-content">
                            <table class="table is-fullwidth is-hoverable">
                                <thead>
                                    <tr>
                                        <th>Usuario</th>
                                        <th>Municipio</th>
                                        <th>Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($verificacionesPendientes as $u)
                                        <tr>
                                            <td>{{ $u->nombre }}</td>
                                            <td>{{ $u->localidad->nombre ?? '—' }}</td>
                                            <td>{{ $u->created_at->format('d/m/Y') }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="3" class="has-text-grey">No hay verificaciones pendientes</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="tile is-parent is-6">
                    <div class="card tile is-child">
                        <header class="card-header">
                            <p class="card-header-title"><span class="icon"><i class="mdi mdi-briefcase-outline default"></i></span>Últimas vacantes publicadas</p>
                            <a href="/admin/vacantes" class="card-header-icon"><span class="icon"><i class="mdi mdi-arrow-right"></i></span></a>
                        </header>
                        <div class="card-content">
                            <table class="table is-fullwidth is-hoverable">
                                <thead>
                                    <tr>
                                        <th>Título</th>
                                        <th>Publicante</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($ultimasVacantes as $v)
                                        <tr>
                                            <td>{{ $v->titulo }}</td>
                                            <td>{{ $v->publicante }}</td>
                                            <td><span class="tag {{ $v->estado === 'activa' ? 'is-success' : ($v->estado === 'cerrada' ? 'is-grey' : 'is-danger') }}">{{ $v->estado }}</span></td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="3" class="has-text-grey">No hay vacantes publicadas todavía</td></tr>
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
