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
    <link rel="stylesheet" href="{{ asset('css/anuncio-negocio.css') }}">

    <style>
        /* =====================================================
           VER EMPLEOS: encabezado, filtro y tarjetas
           (estilos solo de esta página; todo empieza con "ve-")
           ===================================================== */

        /* ---------- Encabezado con logo ---------- */
        .ve-hero {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            background: #fff;
            border: 1px solid #f1f1f1;
            border-radius: 30px;
            padding: 22px 34px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, .06);
        }

        .ve-hero-text {
            min-width: 0;
        }

        .ve-hero h1 {
            margin: 0;
            font-size: 46px;
            font-weight: 800;
            line-height: 1.08;
            background: linear-gradient(45deg, #ff7a18, #ffb347);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            color: transparent;
        }

        .ve-hero p {
            margin: 8px 0 0;
            font-size: 18px;
            font-weight: 500;
            line-height: 1.3;
            color: #666;
        }

        .ve-hero-logo {
            flex: 0 0 auto;
            display: block;
            width: 120px;
            height: 120px;
            object-fit: contain;
        }

        /* ---------- Tarjeta del filtro ---------- */
        .ve-card {
            margin: 20px 0 14px;
            padding: 24px 28px 26px;
            background: #fff;
            border: 1px solid #f1f1f1;
            border-radius: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, .08);
        }

        .ve-head h3 {
            margin: 0 0 4px;
            font-size: 22px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 8px;
            color: #ff7a18;
        }

        .ve-head p {
            margin: 0 0 18px;
            font-size: 14px;
            color: #777;
        }

        .ve-label {
            display: block;
            margin: 0 0 6px;
            font-size: 13px;
            font-weight: 700;
            color: #444;
        }

        .ve-input,
        .ve-select {
            display: block;
            width: 100%;
            height: 46px;
            padding: 0 14px;
            font-size: 15px;
            color: #333;
            background-color: #fff;
            border: 2px solid #f0f0f0;
            border-radius: 12px;
            box-shadow: none;
            transition: border-color .2s, box-shadow .2s;
        }

        .ve-input:focus,
        .ve-select:focus {
            outline: none;
            border-color: #ff7a18;
            box-shadow: 0 0 0 4px rgba(255, 122, 24, .15);
        }

        .ve-input::placeholder {
            color: #b5b5b5;
        }

        .ve-select {
            -webkit-appearance: none;
            appearance: none;
            padding-right: 38px;
            text-overflow: ellipsis;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1.5l5 5 5-5' fill='none' stroke='%23ff7a18' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 14px center;
            cursor: pointer;
        }

        .ve-icono-campo {
            position: relative;
        }

        .ve-icono-campo>i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 20px;
            color: #ff7a18;
            pointer-events: none;
        }

        .ve-icono-campo .ve-input {
            padding-left: 44px;
        }

        /* Fila de arriba: buscador + botones */
        .ve-top {
            display: grid;
            grid-template-columns: 1fr auto;
            grid-template-areas: "q buscar";
            gap: 12px;
            align-items: end;
            margin-bottom: 16px;
        }

        .ve-q {
            grid-area: q;
            min-width: 0;
        }

        .ve-btn-buscar {
            grid-area: buscar;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            height: 46px;
            min-width: 140px;
            padding: 0 22px;
            font-size: 15px;
            font-weight: 700;
            color: #fff;
            background: #ff7a00;
            border: none;
            border-radius: 14px;
            cursor: pointer;
            transition: background .2s, transform .2s, box-shadow .2s;
        }

        .ve-btn-buscar:hover {
            background: #e56700;
            transform: translateY(-1px);
            box-shadow: 0 6px 14px rgba(255, 122, 0, .28);
        }

        .ve-btn-buscar i {
            font-size: 19px;
        }

        .ve-toggle {
            display: none;
            grid-area: toggle;
            align-items: center;
            justify-content: center;
            gap: 8px;
            height: 46px;
            padding: 0 16px;
            font-size: 15px;
            font-weight: 700;
            color: #ff7a00;
            background: #fff6ec;
            border: 2px solid #ffd9b3;
            border-radius: 14px;
            cursor: pointer;
        }

        .ve-toggle i {
            font-size: 19px;
        }

        .ve-toggle .ve-flecha {
            transition: transform .2s;
        }

        .ve-toggle[aria-expanded="true"] .ve-flecha {
            transform: rotate(180deg);
        }

        .ve-badge {
            min-width: 20px;
            height: 20px;
            padding: 0 6px;
            font-size: 12px;
            line-height: 20px;
            text-align: center;
            color: #fff;
            background: #ff7a00;
            border-radius: 10px;
        }

        /* Los demás filtros */
        .ve-panel {
            display: grid;
            grid-template-columns: repeat(12, minmax(0, 1fr));
            gap: 14px;
        }

        .ve-campo {
            grid-column: span 3;
            min-width: 0;
        }

        .ve-precios {
            display: grid;
            grid-template-columns: 1fr auto 1fr;
            align-items: center;
            gap: 8px;
        }

        .ve-precios>span.ve-guion {
            color: #aaa;
            font-weight: 700;
        }

        .ve-dinero {
            position: relative;
        }

        .ve-dinero>b {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            font-weight: 700;
            color: #ff7a18;
            pointer-events: none;
        }

        .ve-dinero .ve-input {
            padding-left: 26px;
            padding-right: 8px;
            -moz-appearance: textfield;
        }

        .ve-dinero .ve-input::-webkit-outer-spin-button,
        .ve-dinero .ve-input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* ---------- Resultados, atajo y filtros activos ---------- */
        .ve-resultados {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: 10px 14px;
            margin: 0 0 10px;
            scroll-margin-top: 80px;
        }

        .ve-count {
            margin: 0;
            font-size: 15px;
            color: #666;
        }

        .ve-count strong {
            font-size: 18px;
            color: #ff7a00;
        }

        .ve-cerca {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 16px;
            font-size: 14px;
            font-weight: 700;
            color: #ff7a00;
            background: #fff;
            border: 2px solid #ffd9b3;
            border-radius: 999px;
            transition: all .2s;
        }

        .ve-cerca:hover {
            color: #e56700;
            border-color: #ff7a00;
        }

        .ve-cerca.is-on {
            color: #fff;
            background: #ff7a00;
            border-color: #ff7a00;
        }

        .ve-chips {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 8px;
            margin: 0 0 12px;
        }

        .ve-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            max-width: 100%;
            padding: 5px 6px 5px 12px;
            font-size: 13px;
            font-weight: 600;
            color: #9a4a00;
            background: #fff1e2;
            border-radius: 999px;
        }

        .ve-chip span {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .ve-chip a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 auto;
            width: 20px;
            height: 20px;
            font-size: 13px;
            color: #9a4a00;
            background: rgba(255, 122, 0, .18);
            border-radius: 50%;
        }

        .ve-chip a:hover {
            color: #fff;
            background: #ff7a00;
        }

        .ve-limpiar {
            font-size: 13px;
            font-weight: 700;
            color: #888;
            text-decoration: underline;
        }

        .ve-aviso {
            margin: 0 0 12px;
            padding: 10px 14px;
            font-size: 13px;
            color: #8a5a00;
            background: #fff8e6;
            border: 1px solid #ffe2a8;
            border-radius: 12px;
        }

        .ve-aviso a {
            font-weight: 700;
            color: #e56700;
            text-decoration: underline;
        }

        /* ---------- Tarjetas de servicios ---------- */
        #listProducts .product {
            border-radius: 18px;
        }

        .ve-foto {
            position: relative;
        }

        .ve-tag-cerca {
            position: absolute;
            left: 8px;
            top: 8px;
            padding: 3px 10px;
            font-size: 11px;
            font-weight: 700;
            color: #fff;
            background: rgba(255, 122, 0, .94);
            border-radius: 999px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, .2);
        }

        .ve-lugar {
            display: flex;
            align-items: center;
            gap: 4px;
            margin: 0 0 8px;
            font-size: 12px;
            color: #888;
        }

        .ve-lugar i {
            color: #ff7a18;
        }

        .ve-lugar span {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .ve-precio {
            display: flex;
            align-items: baseline;
            justify-content: space-between;
            gap: 6px;
            margin: 6px 0 10px;
        }

        .ve-precio small {
            font-size: 12px;
            color: #999;
        }

        .ve-precio strong {
            font-size: 18px;
            font-weight: 800;
            color: #e8590c;
        }

        .ve-precio strong em {
            font-size: 11px;
            font-style: normal;
            font-weight: 600;
            color: #999;
        }

        .ve-vacio {
            grid-column: 1 / -1;
            padding: 36px 18px;
            text-align: center;
            color: #777;
            background: #fff;
            border: 2px dashed #ffd9b3;
            border-radius: 20px;
        }

        .ve-vacio i {
            display: block;
            font-size: 46px;
            line-height: 1;
            color: #ffb347;
        }

        .ve-vacio strong {
            display: block;
            margin: 8px 0 4px;
            font-size: 17px;
            color: #444;
        }

        .ve-vacio .ve-btn-vacio {
            display: inline-block;
            margin-top: 12px;
            padding: 9px 20px;
            font-weight: 700;
            color: #fff;
            background: #ff7a00;
            border-radius: 999px;
        }

        @media (min-width: 769px) {
            #listProducts .btn-add {
                display: block;
                padding: 11px 8px;
                font-size: 14px;
                font-weight: 700;
                line-height: 1.2;
                text-align: center;
                white-space: nowrap;
            }
        }

        /* ---------- Pantallas medianas ---------- */
        @media (max-width: 1100px) {
            .ve-campo {
                grid-column: span 6;
            }
        }

        /* ---------- Celular ---------- */
        @media (max-width: 768px) {
            .ve-hero {
                gap: 12px;
                padding: 16px 18px;
                border-radius: 24px;
            }

            .ve-hero h1 {
                font-size: 24px;
            }

            .ve-hero p {
                margin-top: 4px;
                font-size: 13px;
            }

            .ve-hero-logo {
                width: 72px;
                height: 72px;
            }

            .ve-card {
                margin-top: 14px;
                padding: 18px 16px 20px;
                border-radius: 24px;
            }

            .ve-head h3 {
                font-size: 19px;
                justify-content: center;
            }

            .ve-head p {
                margin-bottom: 14px;
                text-align: center;
            }

            .ve-top {
                grid-template-columns: 1fr 1fr;
                grid-template-areas: "q q" "toggle buscar";
                margin-bottom: 0;
            }

            .ve-toggle {
                display: inline-flex;
            }

            .ve-btn-buscar {
                min-width: 0;
                width: 100%;
            }

            .ve-panel {
                display: none;
                grid-template-columns: 1fr;
                margin-top: 16px;
            }

            .ve-panel.is-open {
                display: grid;
            }

            .ve-campo {
                grid-column: auto;
            }

            .ve-resultados {
                justify-content: center;
                text-align: center;
            }

            .ve-chips {
                justify-content: center;
            }
        }

        @media (max-width: 380px) {
            .ve-hero h1 {
                font-size: 21px;
            }

            .ve-hero-logo {
                width: 60px;
                height: 60px;
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
                    <span><b>Modo Trabajador</b></span>
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

                <!-- BARRA DE TÍTULO + LOGO -->
                <section class="ve-hero">
                    <div class="ve-hero-text">
                        <h1>Empleos Zacapoaxtla</h1>
                        <p>Tu próxima oportunidad laboral</p>
                    </div>
                    <img src="{{ asset('assets/usuario/img/icono.png') }}" alt="Logo Empleos Zacapoaxtla" class="ve-hero-logo" width="120" height="120">
                </section>

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


                <!-- FILTROS -->
                @php
                    // Cuántos filtros están puestos (sin contar el orden "más recientes")
                    $numFiltros = count($chips);
                @endphp
                <form method="GET" action="/usuario/verEmpleos" id="veForm" novalidate>
                    <div class="ve-card">

                        <div class="ve-head">
                            <h3><i class="mdi mdi-magnify"></i> Buscar servicios</h3>
                            <p>Encuentra al profesional ideal para tu necesidad</p>
                        </div>

                        <div class="ve-top">
                            <!-- BUSCADOR -->
                            <div class="ve-q">
                                <label class="ve-label" for="veQ">¿Qué buscas?</label>
                                <div class="ve-icono-campo">
                                    <i class="mdi mdi-magnify"></i>
                                    <input class="ve-input" id="veQ" type="search" name="q" value="{{ $q }}" maxlength="100"
                                        enterkeyhint="search" autocomplete="off"
                                        placeholder="Ej. Plomero, Electricista...">
                                </div>
                            </div>

                            <!-- Solo en celular: abre y cierra los demás filtros -->
                            <button type="button" class="ve-toggle" id="veToggle" aria-expanded="false" aria-controls="vePanel">
                                <i class="mdi mdi-tune"></i> Filtros
                                @if ($numFiltros)
                                <span class="ve-badge">{{ $numFiltros }}</span>
                                @endif
                                <i class="mdi mdi-chevron-down ve-flecha"></i>
                            </button>

                            <button type="submit" class="ve-btn-buscar">
                                <i class="mdi mdi-magnify"></i> Buscar
                            </button>
                        </div>

                        <div class="ve-panel" id="vePanel">

                            <!-- CATEGORÍA -->
                            <div class="ve-campo">
                                <label class="ve-label" for="veCategoria">Categoría</label>
                                <select class="ve-select" id="veCategoria" name="categoria_id">
                                    <option value="">Todas las categorías</option>
                                    @foreach ($categorias as $c)
                                    <option value="{{ $c->id }}" {{ (string) $categoriaId === (string) $c->id ? 'selected' : '' }}>
                                        {{ $c->nombre }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- LOCALIDAD / CERCANÍA -->
                            <div class="ve-campo">
                                <label class="ve-label" for="veLocalidad">Localidad (cerca de ti)</label>
                                <select class="ve-select" id="veLocalidad" name="localidad_id">
                                    <option value="">Todas las localidades</option>
                                    @foreach ($opcionesLocalidad as $l)
                                    @if ($l['id'] === $miLocalidadId)
                                    <option value="{{ $l['id'] }}" {{ $localidadId === $l['id'] ? 'selected' : '' }}>
                                        📍 Mi localidad: {{ $l['nombre'] }} ({{ $l['total'] }})
                                    </option>
                                    @endif
                                    @endforeach
                                    @foreach ($opcionesLocalidad as $l)
                                    @if ($l['id'] !== $miLocalidadId)
                                    <option value="{{ $l['id'] }}" {{ $localidadId === $l['id'] ? 'selected' : '' }}>
                                        {{ $l['nombre'] }} ({{ $l['total'] }})
                                    </option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>

                            <!-- PRECIO -->
                            <div class="ve-campo">
                                <label class="ve-label" for="vePrecioMin">Precio (MXN)</label>
                                <div class="ve-precios">
                                    <div class="ve-dinero">
                                        <b>$</b>
                                        <input class="ve-input" id="vePrecioMin" type="number" name="precio_min" value="{{ $precioMin }}"
                                            min="0" max="9999999" step="1" inputmode="numeric" placeholder="Mín." aria-label="Precio mínimo">
                                    </div>
                                    <span class="ve-guion">–</span>
                                    <div class="ve-dinero">
                                        <b>$</b>
                                        <input class="ve-input" id="vePrecioMax" type="number" name="precio_max" value="{{ $precioMax }}"
                                            min="0" max="9999999" step="1" inputmode="numeric" placeholder="Máx." aria-label="Precio máximo">
                                    </div>
                                </div>
                            </div>

                            <!-- ORDENAR -->
                            <div class="ve-campo">
                                <label class="ve-label" for="veOrden">Ordenar por</label>
                                <select class="ve-select" id="veOrden" name="orden">
                                    <option value="recientes" {{ $orden === 'recientes' ? 'selected' : '' }}>Más recientes</option>
                                    <option value="cercania" {{ $orden === 'cercania' ? 'selected' : '' }} {{ $miLocalidadId ? '' : 'disabled' }}>
                                        {{ $miLocalidadId ? 'Más cercanos a mí' : 'Más cercanos (elige tu localidad en el perfil)' }}
                                    </option>
                                    <option value="precio_asc" {{ $orden === 'precio_asc' ? 'selected' : '' }}>Precio: menor a mayor</option>
                                    <option value="precio_desc" {{ $orden === 'precio_desc' ? 'selected' : '' }}>Precio: mayor a menor</option>
                                </select>
                            </div>

                        </div>
                    </div>
                </form>



                <!-- SERVICIOS DISPONIBLES -->
                <section class="products" id="listProducts">
                    <h2>Servicios Disponibles</h2>

                    <div class="ve-resultados" id="veResultados">
                        <p class="ve-count">
                            <strong>{{ $servicios->count() }}</strong>
                            {{ $servicios->count() === 1 ? 'servicio' : 'servicios' }}
                            {{ $numFiltros ? 'con tus filtros' : 'disponibles' }}
                        </p>

                        @if ($miLocalidadId)
                        <a href="{{ $enlaceCerca }}" class="ve-cerca {{ $orden === 'cercania' ? 'is-on' : '' }}"
                            title="Muestra primero lo que está en tu localidad">
                            <i class="mdi mdi-map-marker"></i> Cerca de mí
                        </a>
                        @else
                        <a href="/usuario/profile" class="ve-cerca" title="Elige tu localidad en tu perfil para ver lo más cercano">
                            <i class="mdi mdi-map-marker"></i> Cerca de mí
                        </a>
                        @endif
                    </div>

                    @if ($cercaSinLocalidad)
                    <p class="ve-aviso">
                        <i class="mdi mdi-information-outline"></i>
                        Para ver primero lo que está más cerca de ti, <a href="/usuario/profile">elige tu localidad en tu perfil</a>.
                    </p>
                    @endif

                    @if ($numFiltros)
                    <div class="ve-chips">
                        @foreach ($chips as $chip)
                        <span class="ve-chip">
                            <i class="mdi {{ $chip['icono'] }}"></i>
                            <span>{{ $chip['texto'] }}</span>
                            <a href="{{ $chip['quitar'] }}" title="Quitar este filtro" aria-label="Quitar filtro {{ $chip['texto'] }}"><i class="mdi mdi-close"></i></a>
                        </span>
                        @endforeach
                        <a href="{{ $enlaceLimpiar }}" class="ve-limpiar">Limpiar todo</a>
                    </div>
                    @endif

                    <div class="products-grid container">
                        @forelse ($servicios as $s)
                        @php
                            $locId = $localidadDeServicio[$s->id] ?? null;
                            $esMia = $miLocalidadId && $locId === $miLocalidadId;
                            $lugar = trim((string) $s->ubicacion) !== ''
                                ? trim($s->ubicacion)
                                : ($locId ? ($localidadesPorId[$locId]->nombre ?? '') : '');
                        @endphp
                        <div class="product">
                            <div class="ve-foto">
                                <img src="{{ $s->imagen ?? asset('assets/usuario/img/services/plomero.jpg') }}" alt="{{ $s->titulo }}" loading="lazy">
                                @if ($esMia)
                                <span class="ve-tag-cerca"><i class="mdi mdi-map-marker"></i> En tu localidad</span>
                                @endif
                            </div>
                            <div class="product-info">
                                <h4>{{ $s->titulo }}
                                    @if ($s->usuario && $s->usuario->verificacion_estado === 'aprobado')
                                    <span title="Identidad verificada" style="background:#dcfce7; color:#16a34a; padding:1px 8px; border-radius:20px; font-size:.65rem; font-weight:700; vertical-align:middle;">
                                        <i class="mdi mdi-check-decagram"></i> Verificado
                                    </span>
                                    @endif
                                </h4>
                                @if ($lugar !== '')
                                <p class="ve-lugar" title="{{ $lugar }}"><i class="mdi mdi-map-marker"></i> <span>{{ $lugar }}</span></p>
                                @endif
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
                                <div class="ve-precio">
                                    <small>Desde</small>
                                    <strong>${{ number_format($s->precio, 0) }} <em>MXN</em></strong>
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
                        <div class="ve-vacio">
                            <i class="mdi mdi-magnify-close"></i>
                            <strong>No encontramos servicios con esos filtros</strong>
                            <span>Prueba con otro rango de precio, otra localidad o una búsqueda más corta.</span><br>
                            <a href="{{ $enlaceLimpiar }}" class="ve-btn-vacio">Quitar filtros</a>
                        </div>
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

    <script>
        // Filtros de "Ver empleos": abrir/cerrar en celular, URL limpia y bajar a los resultados
        (function() {
            var form = document.getElementById('veForm');
            var panel = document.getElementById('vePanel');
            var toggle = document.getElementById('veToggle');
            if (!form) return;

            if (toggle && panel) {
                toggle.addEventListener('click', function() {
                    var abierto = panel.classList.toggle('is-open');
                    toggle.setAttribute('aria-expanded', abierto ? 'true' : 'false');
                });
            }

            // Los campos vacíos (y el orden "más recientes", que es el normal)
            // no se mandan, así el enlace queda corto y limpio.
            form.addEventListener('submit', function() {
                Array.prototype.forEach.call(form.elements, function(el) {
                    if (el.name && (el.value === '' || (el.name === 'orden' && el.value === 'recientes'))) el.disabled = true;
                });
            });

            // Si la persona regresa con el botón "atrás", los campos vuelven a estar activos.
            window.addEventListener('pageshow', function() {
                Array.prototype.forEach.call(form.elements, function(el) { el.disabled = false; });
            });

            // Si venimos de buscar o filtrar, mostrar directamente los resultados.
            if (window.location.search) {
                var res = document.getElementById('veResultados');
                if (res) setTimeout(function() { res.scrollIntoView({ block: 'start' }); }, 60);
            }
        })();
    </script>
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
        (function() {
            const logoutBtn = document.getElementById('logoutBtn');
            if (!logoutBtn) return;
            logoutBtn.addEventListener('click', function(e) {
                e.preventDefault();
                if (!confirm('¿Seguro que quieres cerrar sesión?')) return;
                const csrfMeta = document.querySelector('meta[name="csrf-token"]');
                const csrfToken = csrfMeta ? csrfMeta.content : '';
                fetch('/logout', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                }).finally(() => {
                    window.location.href = '/';
                });
            });
        })();
    </script>
</body>

</html>
