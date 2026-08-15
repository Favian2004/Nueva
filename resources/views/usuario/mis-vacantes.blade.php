<!DOCTYPE html>
<html lang="es" class="has-aside-left has-aside-mobile-transition has-navbar-fixed-top has-aside-expanded">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Mis Vacantes · Empleador</title>

    <link rel="stylesheet" href="{{ asset('assets/usuario/css/main.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/usuario/css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/usuario/css/switch.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/usuario/css/employer-dashboard.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.9.95/css/materialdesignicons.min.css">
    <link rel="icon" type="img/" href="{{ asset('assets/usuario/img/icono.png') }}">

    <style>
        /* ============================================================
       MIS VACANTES - ESTILO TARJETAS EN FILAS
       ============================================================ */

        /* Filtros */
        .filtro-vacantes {
            display: flex;
            gap: 12px;
            margin-bottom: 20px;
            flex-wrap: wrap;
            align-items: center;
            background: #fff;
            padding: 16px 20px;
            border-radius: 12px;
            border: 1px solid #e8eaed;
        }

        .filtro-vacantes select,
        .filtro-vacantes input {
            padding: 8px 14px;
            border-radius: 10px;
            border: 1.5px solid #e5e7eb;
            font-size: 13px;
            outline: none;
            background: #fff;
            min-width: 160px;
        }

        .filtro-vacantes select:focus,
        .filtro-vacantes input:focus {
            border-color: #ff7a18;
            box-shadow: 0 0 0 3px rgba(255, 122, 24, 0.12);
        }

        .filtro-vacantes .btn-filtrar {
            padding: 8px 20px;
            border: none;
            border-radius: 10px;
            background: linear-gradient(45deg, #ff7a18, #ffb347);
            color: #fff;
            font-weight: 600;
            font-size: 13px;
            cursor: pointer;
            transition: 0.3s;
        }

        .filtro-vacantes .btn-filtrar:hover {
            opacity: 0.85;
            transform: scale(1.02);
        }

        /* Contador de vacantes */
        .vacantes-count {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .vacantes-count span {
            font-size: 14px;
            color: #5f6368;
        }

        .vacantes-count .count-number {
            font-weight: 700;
            color: #1a1a2e;
        }

        /* ============================================================
       TARJETAS DE VACANTES (MISMO ESTILO QUE EMPLEOS)
       ============================================================ */
        .products-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
            width: 100%;
        }

        .product-row {
            display: flex;
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.06);
            border: 1px solid #e8eaed;
            transition: all 0.2s ease;
            width: 100%;
        }

        .product-row:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.10);
        }

        .product-row .product-img {
            width: 120px;
            height: 120px;
            flex-shrink: 0;
            object-fit: cover;
            background: #f0f0f0;
        }

        .product-row .product-info {
            padding: 10px 14px;
            display: flex;
            flex-direction: column;
            flex: 1;
            min-width: 0;
            justify-content: space-between;
        }

        .product-row .product-info .job-title {
            font-size: 14px;
            font-weight: 700;
            color: #1a1a2e;
            margin: 0 0 2px 0;
            line-height: 1.3;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .product-row .product-info .job-publisher {
            font-size: 12px;
            color: #5f6368;
            margin: 0 0 2px 0;
            display: flex;
            align-items: center;
            gap: 4px;
            flex-wrap: wrap;
        }

        .product-row .product-info .job-details {
            display: flex;
            gap: 14px;
            font-size: 12px;
            color: #3c4043;
            margin: 1px 0;
            flex-wrap: wrap;
            align-items: center;
        }

        .product-row .product-info .job-details span {
            display: flex;
            align-items: center;
            gap: 3px;
        }

        .product-row .product-info .job-extras {
            display: flex;
            gap: 12px;
            font-size: 12px;
            color: #3c4043;
            margin: 2px 0;
            flex-wrap: wrap;
            align-items: center;
        }

        .product-row .product-info .job-extras span {
            display: flex;
            align-items: center;
            gap: 3px;
        }

        .exp-badge {
            background: #e8f0fe;
            color: #1a73e8;
            padding: 1px 10px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 600;
        }

        .salary-badge {
            background: #e6f4ea;
            color: #1e7e34;
            padding: 1px 10px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 600;
        }

        .contract-badge {
            background: #fef7e0;
            color: #e37400;
            padding: 1px 10px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 600;
        }

        .job-status {
            font-size: 10px;
            font-weight: 600;
            padding: 2px 12px;
            border-radius: 20px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .job-status.active {
            background: #e6f4ea;
            color: #1e7e34;
        }

        .job-status.pending {
            background: #fef7e0;
            color: #e37400;
        }

        .job-status.closed {
            background: #f1f3f4;
            color: #5f6368;
        }

        /* ========================================================== */
        /* FECHAS - MISMO ESTILO QUE EMPLEOS                          */
        /* ========================================================== */
        .job-dates {
            display: flex;
            gap: 12px;
            margin: 6px 0 8px 0;
            flex-wrap: wrap;
        }

        .job-date-work {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 12px;
            background: #e8f0fe;
            padding: 4px 14px;
            border-radius: 8px;
            border-left: 3px solid #1a73e8;
        }

        .job-date-work .mdi {
            color: #1a73e8;
            font-size: 16px;
        }

        .job-date-work span {
            color: #1a73e8;
            font-weight: 500;
        }

        .job-date-work span strong {
            font-weight: 700;
        }

        .job-date-duration {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 12px;
            background: #f3e8ff;
            padding: 4px 14px;
            border-radius: 8px;
            border-left: 3px solid #7c3aed;
        }

        .job-date-duration .mdi {
            color: #7c3aed;
            font-size: 16px;
        }

        .job-date-duration span {
            color: #7c3aed;
            font-weight: 500;
        }

        .job-date-duration span strong {
            font-weight: 700;
        }

        .job-date-limit {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 12px;
            background: #fce8e6;
            padding: 4px 14px;
            border-radius: 8px;
            border-left: 3px solid #d93025;
        }

        .job-date-limit .mdi {
            color: #d93025;
            font-size: 16px;
        }

        .job-date-limit span {
            color: #d93025;
            font-weight: 500;
        }

        .job-date-limit span strong {
            font-weight: 700;
        }

        /* ========================================================== */
        /* ACCIONES DE LA TARJETA                                     */
        /* ========================================================== */
        .product-row .product-info .job-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 4px;
            padding-top: 6px;
            border-top: 1px solid #e8eaed;
            flex-wrap: wrap;
            gap: 6px;
        }

        .product-row .product-info .job-actions .left {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .product-row .product-info .job-actions .postulados {
            font-size: 12px;
            color: #5f6368;
            display: flex;
            align-items: center;
            gap: 3px;
        }

        .btn-accion {
            border: none;
            padding: 4px 12px;
            border-radius: 8px;
            font-size: 11px;
            cursor: pointer;
            font-weight: 600;
            transition: 0.2s;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .btn-ver {
            background: #e8f0fe;
            color: #1a73e8;
        }

        .btn-ver:hover {
            background: #d2e3fc;
        }

        .btn-editar {
            background: #fef7e0;
            color: #e37400;
        }

        .btn-editar:hover {
            background: #feefc0;
        }

        .btn-eliminar {
            background: #fce8e6;
            color: #d93025;
        }

        .btn-eliminar:hover {
            background: #fad2cf;
        }

        .btn-cerrar {
            background: #f1f3f4;
            color: #5f6368;
        }

        .btn-cerrar:hover {
            background: #e8eaed;
        }

        /* ========================================================== */
        /* BOTÓN VER POSTULANTES                                      */
        /* ========================================================== */
        .btn-ver-postulantes {
            font-size: 11px;
            background: #e8f0fe;
            color: #1a73e8;
            padding: 3px 14px;
            border-radius: 20px;
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            transition: 0.2s;
            border: 1px solid transparent;
        }

        .btn-ver-postulantes:hover {
            background: #d2e3fc;
            border-color: #1a73e8;
            transform: translateY(-1px);
        }

        /* ============================================================
       RESPONSIVE
       ============================================================ */
        @media (max-width: 768px) {
            .product-row {
                flex-direction: column;
                border-radius: 12px;
            }

            .product-row .product-img {
                width: 100%;
                height: 120px;
            }

            .product-row .product-info {
                padding: 10px 12px;
            }

            .product-row .product-info .job-title {
                font-size: 13px;
                white-space: normal;
            }

            .product-row .product-info .job-details {
                font-size: 11px;
                gap: 10px;
            }

            .product-row .product-info .job-extras {
                font-size: 11px;
                gap: 8px;
            }

            .product-row .product-info .job-actions {
                flex-direction: column;
                align-items: stretch;
                gap: 6px;
            }

            .product-row .product-info .job-actions .left {
                justify-content: space-between;
                flex-wrap: wrap;
            }

            .filtro-vacantes {
                flex-direction: column;
                padding: 12px 16px;
            }

            .filtro-vacantes select,
            .filtro-vacantes input {
                width: 100%;
                min-width: auto;
            }

            .filtro-vacantes .btn-filtrar {
                width: 100%;
            }

            .vacantes-count {
                flex-direction: column;
                align-items: flex-start;
            }

            .job-dates {
                flex-direction: column;
                gap: 6px;
            }

            .job-date-work,
            .job-date-duration,
            .job-date-limit {
                font-size: 11px;
                padding: 3px 12px;
            }

            .btn-ver-postulantes {
                font-size: 10px;
                padding: 2px 10px;
            }
        }

        @media (max-width: 480px) {
            .product-row .product-img {
                height: 100px;
            }

            .product-row .product-info .job-title {
                font-size: 12px;
            }

            .product-row .product-info .job-details {
                font-size: 10px;
                gap: 8px;
            }

            .product-row .product-info .job-extras {
                font-size: 10px;
                gap: 6px;
            }

            .btn-accion {
                font-size: 10px;
                padding: 3px 10px;
            }

            .job-date-work,
            .job-date-duration,
            .job-date-limit {
                font-size: 10px;
                padding: 2px 10px;
            }

            .btn-ver-postulantes {
                font-size: 9px;
                padding: 2px 8px;
            }
        }
    </style>
    <link rel="stylesheet" href="{{ asset('assets/usuario/css/ads-widget.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/usuario/css/theme-conectaya.css') }}">

    <style>
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
        .banner-texto h2 { font-size: 24px; margin: 0; }
        .banner-texto p { margin: 0; font-size: 14px; }
        .banner-destaca h2 { margin: 0; font-size: 22px; }
        .banner-destaca h1 { margin: 0; font-size: 32px; color: #ffcf33; font-weight: bold; }
        .banner-icono img { width: 70px; }
        .btn-anunciar { background: #ffc107; color: #000; text-decoration: none; padding: 10px 18px; border-radius: 8px; font-weight: bold; display: inline-block; }
        .banner-boton small { display: block; text-align: center; margin-top: 3px; color: #fff; }
        .banner-persona { height: 90px; }
        .banner-persona img { height: 90px; width: auto; object-fit: cover; border-radius: 999px; }

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
                        <span class="crumb-section">Empleador</span>
                        <i class="mdi mdi-chevron-right crumb-sep"></i>
                        <span class="crumb-page">Mis Vacantes</span>
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
                    <span><b> Modo Empleador</b></span>
                </div>
            </div>

            <div class="sidebar-role-switch">
                <div class="switch-row" id="switchRow">
                    <div class="active-bg employer-bg" id="activeBg"></div>
                    <span class="role-text worker-text" id="labelWorker">TRABAJADOR</span>
                    <span class="role-text employer-text active" id="labelEmployer">EMPLEADOR</span>
                </div>
                <div class="role-tooltip-sidebar" id="roleTooltip">
                    <div class="tooltip-title">
                        <span id="tooltipRoleName">🏢 <span class="highlight-employer">Modo Empleador</span></span>
                    </div>
                    <div class="tooltip-desc" id="tooltipDesc">Panel exclusivo para contratar talento</div>
                </div>
            </div>

            <div class="menu is-menu-main">
                <p class="menu-label">Panel</p>
                <ul class="menu-list">
                    <li>
                        <a href="/usuario/empleador" class="has-icon">
                            <span class="icon"><i class="mdi mdi-desktop-mac"></i></span>
                            <span class="menu-item-label">Inicio</span>
                        </a>
                    </li>
                    <p class="menu-label">Acciones</p>
                    <li>
                        <a href="/usuario/buscar-talento" class="has-icon">
                            <span class="icon"><i class="mdi mdi-magnify"></i></span>
                            <span class="menu-item-label">Buscar trabajo</span>
                        </a>
                    </li>
                    <li>
                        <a href="/usuario/mis-vacantes" class="is-active router-link-active has-icon">
                            <span class="icon has-update-mark"><i class="mdi mdi-briefcase"></i></span>
                            <span class="menu-item-label">Mis vacantes</span>
                        </a>
                    </li>
                    <li>
                        <a href="/usuario/postulantes" class="has-icon">
                            <span class="icon"><i class="mdi mdi-account-group"></i></span>
                            <span class="menu-item-label">Postulantes</span>
                        </a>
                    </li>
                </ul>
                <ul class="menu-list">
                    <li>
                        <a href="/usuario/publicar-vacante" class="has-icon">
                            <span class="icon"><i class="mdi mdi-plus-circle"></i></span>
                            <span class="menu-item-label">Publicar vacante</span>
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



        <!-- CONTENIDO -->
        <section class="section is-main-section">
            <div class="container">

                <div class="role-notice">
                    <span class="notice-icon">🏢</span>
                    <span>Estás en modo <strong class="notice-role employer">Empleador</strong> · Gestiona tus vacantes publicadas.</span>
                </div>

                <!-- ===== ESTADÍSTICAS ===== -->
                <div class="estadisticas-row">
                    <div class="column">
                        <div class="box has-text-centered">
                            <span class="icon is-large">
                                <i class="mdi mdi-briefcase mdi-36px" style="color: #ff7a18;"></i>
                            </span>
                            <h2 class="title" style="color: #ff7a18;">{{ $vacantesActivas }}</h2>
                            <p class="has-text-grey">Vacantes Activas</p>
                        </div>
                    </div>
                    <div class="column">
                        <div class="box has-text-centered">
                            <span class="icon is-large">
                                <i class="mdi mdi-account-group mdi-36px" style="color: #1a73e8;"></i>
                            </span>
                            <h2 class="title" style="color: #1a73e8;">{{ $totalPostulantes }}</h2>
                            <p class="has-text-grey">Total Postulantes</p>
                        </div>
                    </div>
                    <div class="column">
                        <div class="box has-text-centered">
                            <span class="icon is-large">
                                <i class="mdi mdi-account-check mdi-36px" style="color: #2e7d32;"></i>
                            </span>
                            <h2 class="title" style="color: #2e7d32;">{{ $contratados }}</h2>
                            <p class="has-text-grey">Contratados</p>
                        </div>
                    </div>
                </div>

                <!-- ===== FILTROS ===== -->
                <form method="GET" action="/usuario/mis-vacantes" class="filtro-vacantes">

                    <select name="estado">
                        <option value="">Todos los estados</option>
                        <option value="activa" {{ $estado === 'activa' ? 'selected' : '' }}>Activas</option>
                        <option value="cerrada" {{ $estado === 'cerrada' ? 'selected' : '' }}>Cerradas</option>
                        <option value="vencida" {{ $estado === 'vencida' ? 'selected' : '' }}>Vencidas</option>
                    </select>

                    <select name="contrato">
                        <option value="">Todos los contratos</option>
                        <option value="Temporal" {{ $contrato === 'Temporal' ? 'selected' : '' }}>Temporal</option>
                        <option value="Temporada" {{ $contrato === 'Temporada' ? 'selected' : '' }}>Temporada</option>
                        <option value="Por obra" {{ $contrato === 'Por obra' ? 'selected' : '' }}>Por obra</option>
                        <option value="Fijo" {{ $contrato === 'Fijo' ? 'selected' : '' }}>Fijo</option>
                        <option value="Eventual" {{ $contrato === 'Eventual' ? 'selected' : '' }}>Eventual</option>
                    </select>

                    <input type="text" name="q" value="{{ $q }}" placeholder="🔍 Buscar vacante...">

                    <button type="submit" class="btn-filtrar">
                        <i class="mdi mdi-filter"></i>
                        Filtrar
                    </button>

                </form>


                <!-- ===== CONTADOR ===== -->
                <div class="vacantes-count">
                    <span>Mostrando <span class="count-number">{{ $vacantes->count() }}</span> vacante(s)</span>
                </div>

                <!-- ===== LISTA DE VACANTES ===== -->
                <div class="products-list">

                    @forelse ($vacantes as $v)
                        @php
                          $estadoClass = $v->estado === 'activa' ? 'active' : ($v->estado === 'cerrada' ? 'closed' : 'pending');
                          $estadoTexto = $v->estado === 'activa' ? 'Activa' : ($v->estado === 'cerrada' ? 'Cerrada' : 'Vencida');
                        @endphp
                        <div class="product-row">
                            <img class="product-img" src="{{ $v->imagen ?? asset('assets/usuario/img/services/frijol.jpg') }}" alt="{{ $v->titulo }}"
                                onerror="this.src='https://via.placeholder.com/120x120/ff7a18/fff?text=💼'">
                            <div class="product-info">
                                <div>
                                    <div class="job-title">{{ $v->titulo }}</div>
                                    <div class="job-publisher">
                                        <i class="mdi mdi-account"></i> {{ $v->publicante }} · <i class="mdi mdi-map-marker"></i> {{ $v->ubicacion }}
                                    </div>
                                    <div class="job-details">
                                        <span><i class="mdi mdi-account-multiple"></i> {{ $v->trabajadores_requeridos }} trabajador(es)</span>
                                        <span><i class="mdi mdi-currency-usd"></i> {{ $v->tipo_pago }}</span>
                                    </div>
                                    <div class="job-extras">
                                        <span><i class="mdi mdi-school"></i> <span class="exp-badge">{{ $v->experiencia }}</span></span>
                                        <span><i class="mdi mdi-currency-usd"></i> <span class="salary-badge">{{ $v->salario }}</span></span>
                                        <span><i class="mdi mdi-file-document"></i> <span class="contract-badge">{{ $v->contrato }}</span></span>
                                    </div>
                                    <div class="job-description">
                                        <i class="mdi mdi-information"></i> {{ $v->descripcion }}
                                    </div>
                                    @if ($v->beneficios && count($v->beneficios))
                                        <div class="job-benefits">
                                            @foreach ($v->beneficios as $beneficio)
                                                <span><i class="mdi mdi-check-circle" style="color:#2e7d32;"></i> {{ $beneficio }}</span>
                                            @endforeach
                                        </div>
                                    @endif
                                    <div class="job-dates">
                                        <div class="job-date-work">
                                            <i class="mdi mdi-calendar-clock"></i>
                                            <span><strong>Día del trabajo:</strong> {{ $v->fecha_trabajo }}</span>
                                        </div>
                                        @if ($v->duracion)
                                            <div class="job-date-duration">
                                                <i class="mdi mdi-timer"></i>
                                                <span><strong>Duración de trabajo:</strong> {{ $v->duracion }}</span>
                                            </div>
                                        @endif
                                        @if ($v->fecha_limite)
                                            <div class="job-date-limit">
                                                <i class="mdi mdi-clock-alert"></i>
                                                <span><strong>Límite para postular:</strong> {{ $v->fecha_limite->format('d/m/Y') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="job-contact">
                                        <i class="mdi mdi-phone"></i> <span class="contact-text">{{ $v->telefono }}</span>
                                        @if ($v->whatsapp)
                                            <i class="mdi mdi-whatsapp" style="color:#25D366; margin-left: 12px;"></i> <span class="contact-text">{{ $v->whatsapp }}</span>
                                        @endif
                                    </div>
                                    <div style="display: flex; gap: 12px; margin-top: 3px; flex-wrap: wrap; align-items: center;">
                                        <span class="job-status {{ $estadoClass }}">{{ $estadoTexto }}</span>
                                        <span style="font-size: 12px; color: #5f6368;">
                                            <i class="mdi mdi-account"></i> {{ $v->postulaciones_count }} postulados
                                        </span>
                                        <!-- BOTÓN VER POSTULANTES -->
                                        <a href="/usuario/postulantes?vacante={{ $v->id }}" class="btn-ver-postulantes">
                                            <i class="mdi mdi-account-group"></i> Ver postulantes
                                        </a>
                                    </div>
                                </div>
                                <div class="job-actions">
                                    <div class="left">
                                        <a href="/usuario/mis-vacantes/{{ $v->id }}/editar" class="btn-accion btn-editar"><i class="mdi mdi-pencil"></i> Editar</a>
                                        @if ($v->estado === 'activa')
                                            <button class="btn-accion btn-cerrar" onclick="cerrarVacante({{ $v->id }})"><i class="mdi mdi-close"></i> Cerrar</button>
                                        @else
                                            <button class="btn-accion btn-ver" onclick="reactivarVacante({{ $v->id }})"><i class="mdi mdi-lock-open"></i> Reactivar</button>
                                        @endif
                                    </div>
                                    <button class="btn-accion btn-eliminar" onclick="eliminarVacante({{ $v->id }})"><i class="mdi mdi-delete"></i> Eliminar</button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="has-text-grey has-text-centered">No tienes vacantes publicadas todavía. <a href="/usuario/publicar-vacante">Publica la primera</a>.</p>
                    @endforelse

                </div>

                <!-- ===== ACCIÓN PRINCIPAL ===== -->
                <div class="acciones-vacantes">
                    <a href="/usuario/publicar-vacante" class="btn-publicar-vacante">
                        <span class="icono">
                            <i class="mdi mdi-briefcase-plus"></i>
                        </span>
                        <span>
                            <strong>Publicar oferta de trabajo</strong>
                            <small>Crea una nueva vacante en pocos minutos</small>
                        </span>
                    </a>
                </div>

            </div>
        </section>

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
                            <a href="/anunciar" class="btn-anunciar">¡ANÚNCIATE AQUÍ!</a>
                            <small>Más información →</small>
                        </div>
                        <div class="banner-persona">
                            <img src="{{ asset('img/anuncios/persona.png') }}" alt="Anunciante">
                        </div>
                    </div>
                </div>

        <!-- FOOTER -->
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
    <script>
        (function() {
            const labelWorker = document.getElementById('labelWorker');
            const labelEmployer = document.getElementById('labelEmployer');
            const activeBg = document.getElementById('activeBg');
            const tooltip = document.getElementById('roleTooltip');
            let isWorker = false;
            let tooltipTimeout;

            function showTooltip() {
                clearTimeout(tooltipTimeout);
                tooltip.classList.add('show');
                tooltipTimeout = setTimeout(() => {
                    tooltip.classList.remove('show');
                }, 2500);
            }

            function updateUI() {
                labelWorker.classList.toggle('active', isWorker);
                labelEmployer.classList.toggle('active', !isWorker);
                activeBg.className = 'active-bg ' + (isWorker ? 'worker-bg' : 'employer-bg');
                if (isWorker) {
                    document.getElementById('tooltipRoleName').innerHTML = '👷 <span class="highlight-worker">Modo Trabajador</span>';
                    document.getElementById('tooltipDesc').textContent = 'Publica y gestiona tus servicios';
                } else {
                    document.getElementById('tooltipRoleName').innerHTML = '🏢 <span class="highlight-employer">Modo Empleador</span>';
                    document.getElementById('tooltipDesc').textContent = 'Panel exclusivo para contratar talento';
                }
            }

            function toggleRole(e) {
                e.stopPropagation();
                isWorker = !isWorker;
                updateUI();
                showTooltip();
                localStorage.setItem('userRole', isWorker ? 'worker' : 'employer');
                if (isWorker) window.location.href = '/usuario';
            }

            labelWorker.addEventListener('click', function(e) {
                if (!isWorker) toggleRole(e);
            });
            labelEmployer.addEventListener('click', function(e) {
                if (isWorker) toggleRole(e);
            });

            const switchRow = document.querySelector('.switch-row');
            switchRow.addEventListener('mouseenter', function() {
                tooltip.classList.add('show');
            });
            switchRow.addEventListener('mouseleave', function() {
                clearTimeout(tooltipTimeout);
                setTimeout(() => tooltip.classList.remove('show'), 200);
            });

            const savedRole = localStorage.getItem('userRole');
            if (savedRole === 'worker') {
                isWorker = true;
                window.location.href = '/usuario';
            }
            updateUI();
        })();

        // ===== Acciones sobre las vacantes (guardan de verdad en la base de datos) =====
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        function cerrarVacante(id) {
            if (!confirm('¿Cerrar esta vacante? Ya no aparecerá disponible para postularse.')) return;
            fetch(`/usuario/mis-vacantes/${id}/cerrar`, {
                method: 'PATCH',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            })
                .then(res => { if (!res.ok) throw new Error(); location.reload(); })
                .catch(() => alert('Ocurrió un error al cerrar la vacante.'));
        }

        function reactivarVacante(id) {
            fetch(`/usuario/mis-vacantes/${id}/reactivar`, {
                method: 'PATCH',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            })
                .then(res => { if (!res.ok) throw new Error(); location.reload(); })
                .catch(() => alert('Ocurrió un error al reactivar la vacante.'));
        }

        function eliminarVacante(id) {
            if (!confirm('¿Eliminar esta vacante? Esta acción no se puede deshacer.')) return;
            fetch(`/usuario/mis-vacantes/${id}`, {
                method: 'DELETE',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
            })
                .then(res => { if (!res.ok) throw new Error(); location.reload(); })
                .catch(() => alert('Ocurrió un error al eliminar la vacante.'));
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
