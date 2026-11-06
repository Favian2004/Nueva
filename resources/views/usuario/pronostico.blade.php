<!DOCTYPE html>
<html lang="es" class="has-aside-left has-aside-mobile-transition has-navbar-fixed-top has-aside-expanded">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pronóstico de demanda · Empleador</title>

    <link rel="stylesheet" href="{{ asset('assets/usuario/css/main.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/usuario/css/styles.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/usuario/css/switch.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/usuario/css/employer-dashboard.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.9.95/css/materialdesignicons.min.css">
    <link rel="icon" type="img/" href="{{ asset('assets/usuario/img/icono.png') }}">

    <style>
        /* ============================================================
           PRONÓSTICO DE DEMANDA
           Gráficas hechas con HTML + CSS (sin librerías externas).
           ============================================================ */
        .pron-page {
            --pron-accent: #e8590c;
            --pron-accent-soft: #fbe3d3;
            --pron-bar-muted: #d8d0c8;
            --pron-ink: #1f2937;
            --pron-ink-2: #4b5563;
            --pron-ink-3: #6b7280;
            --pron-line: #e8eaed;
            --pron-grid: #eceef1;
            --pron-surface: #ffffff;
            font-family: 'Nunito', sans-serif;
            color: var(--pron-ink);
        }

        .pron-page *,
        .pron-page *::before,
        .pron-page *::after {
            box-sizing: border-box;
        }

        .pron-titulo {
            margin: 0 0 4px;
            font-size: 22px;
            font-weight: 800;
        }

        .pron-subtitulo {
            margin: 0 0 18px;
            color: var(--pron-ink-2);
            font-size: 14px;
        }

        /* ---------- Filtros (una sola fila, arriba de todo) ---------- */
        .pron-filtros {
            display: flex;
            flex-wrap: wrap;
            gap: 12px 16px;
            align-items: flex-end;
            background: var(--pron-surface);
            border: 1px solid var(--pron-line);
            border-radius: 12px;
            padding: 14px 18px;
            margin-bottom: 18px;
        }

        .pron-f {
            display: flex;
            flex-direction: column;
            gap: 4px;
            font-size: 12px;
            font-weight: 700;
            color: var(--pron-ink-3);
        }

        .pron-f select {
            padding: 8px 14px;
            border-radius: 10px;
            border: 1.5px solid #e5e7eb;
            font-size: 13px;
            background: #fff;
            min-width: 190px;
            color: var(--pron-ink);
            outline: none;
        }

        .pron-f select:focus {
            border-color: #ff7a18;
            box-shadow: 0 0 0 3px rgba(255, 122, 24, 0.12);
        }

        .pron-f select:disabled {
            background: #f5f6f8;
            color: #9ca3af;
        }

        .pron-limpiar {
            font-size: 13px;
            font-weight: 700;
            color: var(--pron-accent);
            padding: 9px 4px;
        }

        /* ---------- Aviso de pocos datos ---------- */
        .pron-aviso {
            display: flex;
            gap: 10px;
            align-items: flex-start;
            background: #fef7e0;
            border: 1px solid #f5d78e;
            color: #7a5c12;
            border-radius: 12px;
            padding: 12px 16px;
            margin-bottom: 18px;
            font-size: 13.5px;
            line-height: 1.5;
        }

        .pron-aviso .mdi {
            font-size: 20px;
            line-height: 1.2;
        }

        /* ---------- Tarjetas de cifras ---------- */
        .pron-kpis {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
            gap: 14px;
            margin-bottom: 18px;
        }

        .pron-kpi {
            background: var(--pron-surface);
            border: 1px solid var(--pron-line);
            border-radius: 12px;
            padding: 16px 18px;
        }

        .pron-kpi-label {
            font-size: 13px;
            color: var(--pron-ink-3);
            font-weight: 600;
        }

        .pron-kpi-valor {
            font-size: 28px;
            font-weight: 800;
            line-height: 1.2;
            margin: 4px 0 2px;
            color: var(--pron-ink);
        }

        .pron-kpi-sub {
            font-size: 12.5px;
            color: var(--pron-ink-2);
        }

        .pron-delta {
            display: inline-flex;
            align-items: center;
            gap: 2px;
            font-weight: 700;
            color: var(--pron-ink);
        }

        /* ---------- Resumen en palabras ---------- */
        .pron-resumen {
            background: var(--pron-surface);
            border: 1px solid var(--pron-line);
            border-left: 4px solid var(--pron-accent);
            border-radius: 12px;
            padding: 14px 18px;
            margin-bottom: 18px;
            font-size: 14.5px;
            line-height: 1.6;
        }

        .pron-resumen p {
            margin: 0 0 6px;
        }

        .pron-resumen p:last-child {
            margin-bottom: 0;
        }

        /* ---------- Tarjetas de gráficas ---------- */
        .pron-card {
            background: var(--pron-surface);
            border: 1px solid var(--pron-line);
            border-radius: 12px;
            padding: 18px 20px 14px;
            margin-bottom: 18px;
        }

        .pron-card h2 {
            margin: 0;
            font-size: 17px;
            font-weight: 800;
        }

        .pron-card .pron-card-sub {
            margin: 2px 0 16px;
            font-size: 13px;
            color: var(--pron-ink-3);
        }

        .pron-leyenda {
            display: flex;
            gap: 18px;
            flex-wrap: wrap;
            margin: -6px 0 10px;
            font-size: 12.5px;
            color: var(--pron-ink-2);
        }

        .pron-leyenda span {
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .pron-key {
            width: 14px;
            height: 10px;
            border-radius: 3px;
            display: inline-block;
        }

        .pron-key.real {
            background: var(--pron-accent);
        }

        .pron-key.proy,
        .pron-bar.is-proy {
            background: repeating-linear-gradient(135deg, var(--pron-accent) 0 3px, var(--pron-accent-soft) 3px 7px);
        }

        .pron-key.muted {
            background: var(--pron-bar-muted);
        }

        /* ---------- La gráfica ---------- */
        .pron-area {
            position: relative;
            height: 230px;
            margin: 20px 0 0 38px;
        }

        .pron-gridlayer,
        .pron-ylayer {
            position: absolute;
            top: 0;
            bottom: 30px;
            pointer-events: none;
        }

        .pron-gridlayer {
            left: 0;
            right: 0;
        }

        .pron-gridlayer i {
            position: absolute;
            left: 0;
            right: 0;
            height: 0;
            border-top: 1px solid var(--pron-grid);
        }

        .pron-ylayer {
            left: -38px;
            width: 32px;
        }

        .pron-ylayer span {
            position: absolute;
            right: 0;
            transform: translateY(50%);
            font-size: 11px;
            line-height: 1;
            color: var(--pron-ink-3);
            font-variant-numeric: tabular-nums;
        }

        .pron-cols {
            position: absolute;
            inset: 0;
            display: flex;
        }

        .pron-col {
            flex: 1 1 0;
            min-width: 0;
            display: flex;
            flex-direction: column;
            cursor: default;
            border-radius: 6px;
            outline: none;
        }

        .pron-col:hover,
        .pron-col:focus-visible {
            background: rgba(232, 89, 12, 0.07);
        }

        .pron-col:focus-visible {
            box-shadow: inset 0 0 0 2px var(--pron-accent);
        }

        .pron-col.is-proy {
            background: rgba(232, 89, 12, 0.035);
        }

        .pron-col.is-proy:hover,
        .pron-col.is-proy:focus-visible {
            background: rgba(232, 89, 12, 0.09);
        }

        .pron-col.is-proy-first {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
            box-shadow: -1px 0 0 var(--pron-line);
        }

        .pron-barzone {
            flex: 1 1 auto;
            position: relative;
            display: flex;
            align-items: flex-end;
            justify-content: center;
        }

        .pron-bar {
            width: calc(100% - 4px);
            max-width: 24px;
            border-radius: 4px 4px 0 0;
            background: var(--pron-bar-muted);
            transition: filter .15s ease;
        }

        .pron-bar.is-real,
        .pron-bar.is-top {
            background: var(--pron-accent);
        }

        .pron-col:hover .pron-bar,
        .pron-col:focus-visible .pron-bar {
            filter: brightness(.9);
        }

        .pron-nodata {
            width: 10px;
            height: 2px;
            background: var(--pron-grid);
            border-radius: 2px;
        }

        .pron-val {
            position: absolute;
            left: 50%;
            transform: translateX(-50%);
            font-size: 12px;
            font-weight: 800;
            color: var(--pron-ink);
            white-space: nowrap;
            pointer-events: none;
        }

        .pron-xl {
            flex: 0 0 30px;
            padding-top: 7px;
            display: flex;
            flex-direction: column;
            align-items: center;
            font-size: 12px;
            line-height: 1.1;
            color: var(--pron-ink-2);
            white-space: nowrap;
        }

        .pron-xl small {
            font-size: 10px;
            color: var(--pron-ink-3);
            margin-top: 2px;
        }

        .pron-nota {
            margin: 10px 0 0;
            font-size: 12px;
            color: var(--pron-ink-3);
        }

        /* ---------- Tooltip ---------- */
        .pron-tip {
            position: fixed;
            z-index: 9999;
            pointer-events: none;
            background: #111827;
            color: #fff;
            border-radius: 8px;
            padding: 7px 11px;
            font-size: 12px;
            line-height: 1.35;
            box-shadow: 0 6px 18px rgba(0, 0, 0, .25);
            max-width: 240px;
        }

        .pron-tip[hidden] {
            display: none;
        }

        .pron-tip strong {
            display: block;
            font-size: 14px;
            font-weight: 800;
            color: #ffffff;
        }

        .pron-tip span {
            display: block;
            color: #d1d5db;
        }

        /* ---------- Vista de tabla ---------- */
        .pron-tabla {
            margin-top: 12px;
            border-top: 1px solid var(--pron-line);
            padding-top: 8px;
        }

        .pron-tabla summary {
            cursor: pointer;
            font-size: 13px;
            font-weight: 700;
            color: var(--pron-ink-2);
            padding: 4px 0;
        }

        .pron-tabla table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            font-size: 13px;
        }

        .pron-tabla th,
        .pron-tabla td {
            text-align: left;
            padding: 6px 8px;
            border-bottom: 1px solid var(--pron-grid);
        }

        .pron-tabla th {
            color: var(--pron-ink-3);
            font-weight: 700;
        }

        .pron-tabla td.num,
        .pron-tabla th.num {
            text-align: right;
            font-variant-numeric: tabular-nums;
        }

        /* ---------- Vacío ---------- */
        .pron-vacio {
            text-align: center;
            padding: 36px 20px;
            background: var(--pron-surface);
            border: 1px dashed #d1d5db;
            border-radius: 12px;
            margin-bottom: 18px;
        }

        .pron-vacio .mdi {
            font-size: 40px;
            color: #9ca3af;
        }

        .pron-vacio p {
            margin: 6px 0 14px;
            color: var(--pron-ink-2);
        }

        .pron-btn {
            display: inline-block;
            padding: 9px 20px;
            border-radius: 10px;
            background: linear-gradient(45deg, #ff7a18, #ffb347);
            color: #fff;
            font-weight: 700;
            font-size: 13px;
        }

        .pron-btn:hover {
            color: #fff;
            opacity: .9;
        }

        .pron-como summary {
            cursor: pointer;
            font-weight: 800;
            font-size: 15px;
        }

        .pron-como ul {
            margin: 10px 0 0 18px;
            font-size: 13.5px;
            line-height: 1.6;
            color: var(--pron-ink-2);
            list-style: disc;
        }

        /* ---------- Ranking de categorías ---------- */
        .pron-rank-cols {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 8px 28px;
        }

        .pron-rank-col h3 {
            margin: 0 0 6px;
            font-size: 14px;
            font-weight: 800;
            color: var(--pron-ink);
        }

        .pron-rank-col h3 small {
            display: block;
            margin-top: 1px;
            font-size: 12px;
            font-weight: 400;
            color: var(--pron-ink-3);
        }

        .pron-rank-list {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        .pron-rank-row {
            display: grid;
            grid-template-columns: 22px minmax(0, 1fr) auto;
            align-items: center;
            gap: 10px;
            padding: 9px 8px;
            margin: 0 -8px;
            border-radius: 8px;
            color: var(--pron-ink);
            text-decoration: none;
        }

        .pron-rank-row:hover {
            background: #faf7f4;
            color: var(--pron-ink);
        }

        .pron-rank-row:focus-visible {
            outline: 2px solid var(--pron-accent);
            outline-offset: -2px;
        }

        .pron-rank-row.is-sel {
            background: var(--pron-accent-soft);
        }

        .pron-rank-pos {
            font-size: 13px;
            font-weight: 800;
            color: var(--pron-ink-3);
            text-align: center;
        }

        .pron-rank-nombre {
            display: block;
            margin-bottom: 5px;
            font-size: 14px;
            font-weight: 600;
            line-height: 1.25;
            overflow-wrap: anywhere;
        }

        .pron-rank-track {
            display: block;
            height: 10px;
            background: var(--pron-grid);
            border-radius: 5px;
            overflow: hidden;
        }

        .pron-rank-fill {
            display: block;
            height: 100%;
            min-width: 4px;
            border-radius: 0 4px 4px 0;
            background: var(--pron-bar-muted);
        }

        .pron-rank-row.is-first .pron-rank-fill {
            background: var(--pron-accent);
        }

        .pron-rank-col.is-proy .pron-rank-fill {
            background: repeating-linear-gradient(135deg, var(--pron-bar-muted) 0 3px, #efeae5 3px 7px);
        }

        .pron-rank-col.is-proy .pron-rank-row.is-first .pron-rank-fill {
            background: repeating-linear-gradient(135deg, var(--pron-accent) 0 3px, var(--pron-accent-soft) 3px 7px);
        }

        .pron-rank-val {
            min-width: 54px;
            text-align: right;
            font-size: 15px;
            font-weight: 800;
            font-variant-numeric: tabular-nums;
            white-space: nowrap;
        }

        .pron-rank-val .pron-delta {
            display: flex;
            justify-content: flex-end;
            margin-top: 1px;
            font-size: 11.5px;
            font-weight: 600;
            color: var(--pron-ink-2);
        }

        .pron-rank-vacio {
            margin: 6px 0 0;
            font-size: 13px;
            color: var(--pron-ink-3);
        }

        @media (max-width: 760px) {
            .pron-rank-cols {
                grid-template-columns: minmax(0, 1fr);
                gap: 18px;
            }
        }

        /* ---------- Celular ---------- */
        @media (max-width: 640px) {
            .pron-area {
                height: 200px;
                margin-left: 30px;
            }

            .pron-ylayer {
                left: -30px;
                width: 26px;
            }

            .pron-card {
                padding: 14px 12px 12px;
            }

            .pron-xl {
                font-size: 10px;
                letter-spacing: -0.2px;
            }

            .pron-kpis {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 10px;
            }

            .pron-kpi {
                padding: 12px 14px;
            }

            .pron-kpi-label {
                font-size: 12px;
            }

            .pron-f select {
                min-width: 0;
                width: 100%;
            }

            .pron-f {
                flex: 1 1 100%;
            }

            .pron-kpi-valor {
                font-size: 21px;
            }
        }
    </style>
    <link rel="stylesheet" href="{{ asset('assets/usuario/css/ads-widget.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/usuario/css/theme-conectaya.css') }}">
    <link rel="stylesheet" href="{{ asset('css/anuncio-negocio.css') }}">
</head>

<body>
    @php
        $unidad = $p['unidad'];
        $etiquetaFiltro = $subcategoria
            ? $categoria->nombre . ' › ' . $subcategoria->nombre
            : ($categoria ? $categoria->nombre : 'Todas las categorías');
        $plural = fn($n, $uno, $varios) => number_format($n) . ' ' . ($n == 1 ? $uno : $varios);
        $hayDatos = $p['registros'] > 0;
        $hayFiltro = $categoria || $medida === 'trabajadores';
    @endphp

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
                        <span class="crumb-page">Pronóstico</span>
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
                        <a href="/usuario/mis-vacantes" class="has-icon">
                            <span class="icon"><i class="mdi mdi-briefcase"></i></span>
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
                        <a href="/usuario/pronostico" class="is-active router-link-active has-icon">
                            <span class="icon has-update-mark"><i class="mdi mdi-chart-line"></i></span>
                            <span class="menu-item-label">Pronóstico</span>
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
            <div class="container pron-page">

                <h1 class="pron-titulo">Pronóstico de demanda</h1>
                <p class="pron-subtitulo">Mira en qué días y meses se publica más trabajo en Zacapoaxtla, y qué esperar en los próximos meses.</p>

                <!-- ===== FILTROS ===== -->
                <form method="GET" action="/usuario/pronostico" class="pron-filtros" id="pronFiltros">
                    <label class="pron-f">
                        <span>Categoría</span>
                        <select name="categoria_id" id="fCategoria">
                            <option value="">Todas las categorías</option>
                            @foreach ($categorias as $c)
                                <option value="{{ $c->id }}" {{ $categoria && $categoria->id === $c->id ? 'selected' : '' }}>{{ $c->nombre }}</option>
                            @endforeach
                        </select>
                    </label>
                    <label class="pron-f">
                        <span>Subcategoría</span>
                        <select name="subcategoria_id" id="fSubcategoria" {{ $categoria ? '' : 'disabled' }}>
                            <option value="">{{ $categoria ? 'Todas las subcategorías' : 'Primero elige una categoría' }}</option>
                            @if ($categoria)
                                @foreach ($categoria->subcategorias as $s)
                                    <option value="{{ $s->id }}" {{ $subcategoria && $subcategoria->id === $s->id ? 'selected' : '' }}>{{ $s->nombre }}</option>
                                @endforeach
                            @endif
                        </select>
                    </label>
                    <label class="pron-f">
                        <span>Medir por</span>
                        <select name="medida" id="fMedida">
                            <option value="vacantes" {{ $medida === 'vacantes' ? 'selected' : '' }}>Vacantes publicadas</option>
                            <option value="trabajadores" {{ $medida === 'trabajadores' ? 'selected' : '' }}>Trabajadores solicitados</option>
                        </select>
                    </label>
                    @if ($hayFiltro)
                        <a href="/usuario/pronostico" class="pron-limpiar">Quitar filtros</a>
                    @endif
                    <noscript><button type="submit" class="pron-btn" style="border:0">Aplicar</button></noscript>
                </form>

                <!-- ===== RANKING DE CATEGORÍAS ===== -->
                @if ($ranking['este_mes'] || $ranking['proximo_mes'])
                    @php
                        $urlCat = fn($id) => '/usuario/pronostico?' . http_build_query(array_filter([
                            'categoria_id' => $id,
                            'medida' => $medida === 'trabajadores' ? 'trabajadores' : null,
                        ]));
                        $unidadRank = $medida === 'trabajadores' ? 'trabajadores solicitados' : 'vacantes publicadas';
                    @endphp
                    <section class="pron-card" aria-labelledby="pronRankTitulo">
                        <h2 id="pronRankTitulo">¿Qué tipo de trabajo se viene fuerte?</h2>
                        <p class="pron-card-sub">
                            Las categorías con más {{ $unidadRank }}, comparadas entre sí. Toca una para ver sus días y meses.
                        </p>

                        <div class="pron-rank-cols">
                            <div class="pron-rank-col">
                                <h3>
                                    Este mes
                                    <small>{{ $ranking['mes_actual_corto'] }}, hasta el {{ $ranking['dia_actual'] }}</small>
                                </h3>
                                @if ($ranking['este_mes'])
                                    <ol class="pron-rank-list">
                                        @foreach ($ranking['este_mes'] as $r)
                                            <li>
                                                <a href="{{ $urlCat($r['id']) }}"
                                                   class="pron-rank-row {{ $loop->first ? 'is-first' : '' }} {{ $categoria && $categoria->id === $r['id'] ? 'is-sel' : '' }}"
                                                   @if ($categoria && $categoria->id === $r['id']) aria-current="true" @endif>
                                                    <span class="pron-rank-pos">{{ $r['puesto'] }}</span>
                                                    <span>
                                                        <span class="pron-rank-nombre">{{ $r['nombre'] }}</span>
                                                        <span class="pron-rank-track"><span class="pron-rank-fill" style="width: {{ $r['barra'] }}%"></span></span>
                                                    </span>
                                                    <span class="pron-rank-val">{{ number_format($r['valor']) }}</span>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ol>
                                @else
                                    <p class="pron-rank-vacio">Todavía no hay vacantes de este mes.</p>
                                @endif
                            </div>

                            <div class="pron-rank-col is-proy">
                                <h3>
                                    El próximo mes
                                    <small>{{ $ranking['mes_siguiente_corto'] }}, proyección (rayado)</small>
                                </h3>
                                @if ($ranking['proximo_mes'])
                                    <ol class="pron-rank-list">
                                        @foreach ($ranking['proximo_mes'] as $r)
                                            <li>
                                                <a href="{{ $urlCat($r['id']) }}"
                                                   class="pron-rank-row {{ $loop->first ? 'is-first' : '' }} {{ $categoria && $categoria->id === $r['id'] ? 'is-sel' : '' }}"
                                                   @if ($categoria && $categoria->id === $r['id']) aria-current="true" @endif>
                                                    <span class="pron-rank-pos">{{ $r['puesto'] }}</span>
                                                    <span>
                                                        <span class="pron-rank-nombre">{{ $r['nombre'] }}</span>
                                                        <span class="pron-rank-track"><span class="pron-rank-fill" style="width: {{ $r['barra'] }}%"></span></span>
                                                    </span>
                                                    <span class="pron-rank-val">
                                                        ~{{ number_format($r['valor']) }}
                                                        @if ($r['variacion'] !== null)
                                                            <span class="pron-delta">
                                                                @if ($r['variacion'] > 0)
                                                                    <i class="mdi mdi-arrow-up"></i> {{ $r['variacion'] }}% más
                                                                @elseif ($r['variacion'] < 0)
                                                                    <i class="mdi mdi-arrow-down"></i> {{ abs($r['variacion']) }}% menos
                                                                @else
                                                                    <i class="mdi mdi-minus"></i> igual
                                                                @endif
                                                            </span>
                                                        @endif
                                                    </span>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ol>
                                @else
                                    <p class="pron-rank-vacio">Aún no hay un mes completo de datos para proyectar.</p>
                                @endif
                            </div>
                        </div>

                        <p class="pron-nota">
                            El % de la proyección compara con el promedio reciente de esa categoría.
                            @if ($ranking['sin_categoria'] > 0)
                                No incluye {{ $plural($ranking['sin_categoria'], 'vacante', 'vacantes') }} sin categoría.
                            @endif
                            @if ($ranking['confianza'] === 'baja')
                                Hay pocos datos todavía: tómalo como referencia.
                            @endif
                        </p>
                    </section>
                @endif

                @if (!$hayDatos)
                    <!-- ===== SIN DATOS ===== -->
                    <div class="pron-vacio">
                        <i class="mdi mdi-chart-bar"></i>
                        <p>
                            @if ($hayFiltro)
                                Todavía no hay vacantes de <strong>{{ $etiquetaFiltro }}</strong> para analizar.
                            @else
                                Todavía no hay vacantes publicadas para analizar.
                            @endif
                        </p>
                        @if ($hayFiltro)
                            <a href="/usuario/pronostico" class="pron-btn">Ver todas las categorías</a>
                        @else
                            <a href="/usuario/publicar-vacante" class="pron-btn">Publicar una vacante</a>
                        @endif
                    </div>
                @else

                    @if ($p['confianza'] === 'baja')
                        <div class="pron-aviso" role="note">
                            <i class="mdi mdi-information-outline"></i>
                            <div>
                                <strong>Todavía hay pocos datos</strong>
                                ({{ $plural($p['registros'], 'vacante', 'vacantes') }} en {{ $plural($p['meses_completos'], 'mes completo', 'meses completos') }}).
                                Tómalo como una referencia: el pronóstico se afina conforme se publiquen más vacantes.
                            </div>
                        </div>
                    @endif

                    <!-- ===== CIFRAS ===== -->
                    <div class="pron-kpis">
                        <div class="pron-kpi">
                            <div class="pron-kpi-label">{{ $medida === 'trabajadores' ? 'Trabajadores solicitados' : 'Vacantes analizadas' }}</div>
                            <div class="pron-kpi-valor">{{ number_format($p['total']) }}</div>
                            <div class="pron-kpi-sub">
                                @if ($medida === 'trabajadores')
                                    en {{ $plural($p['registros'], 'vacante', 'vacantes') }}
                                @else
                                    desde {{ $p['primer_registro'] }}
                                @endif
                            </div>
                        </div>

                        <div class="pron-kpi">
                            <div class="pron-kpi-label">Día con más demanda</div>
                            <div class="pron-kpi-valor">{{ $p['dia_top']['nombre'] }}</div>
                            <div class="pron-kpi-sub">{{ $p['dia_top']['pct'] }}% del total</div>
                        </div>

                        <div class="pron-kpi">
                            <div class="pron-kpi-label">Mes con más demanda</div>
                            @if ($p['mes_top'])
                                <div class="pron-kpi-valor">{{ $p['mes_top']['mes'] }}</div>
                                <div class="pron-kpi-sub">{{ number_format($p['mes_top']['valor']) }} {{ $unidad }} · {{ $p['mes_top']['anio'] }}</div>
                            @else
                                <div class="pron-kpi-valor">—</div>
                                <div class="pron-kpi-sub">En los últimos 12 meses</div>
                            @endif
                        </div>

                        <div class="pron-kpi">
                            <div class="pron-kpi-label">Próximo mes (proyección)</div>
                            @if ($p['proyeccion'])
                                <div class="pron-kpi-valor">{{ $p['proyeccion'][0]['mes'] }}</div>
                                <div class="pron-kpi-sub">~{{ number_format($p['proyeccion'][0]['valor']) }} {{ $unidad }}</div>
                                @if ($p['variacion_siguiente'] !== null)
                                    <div class="pron-kpi-sub">
                                        <span class="pron-delta">
                                            @if ($p['variacion_siguiente'] > 0)
                                                <i class="mdi mdi-arrow-up"></i> {{ $p['variacion_siguiente'] }}% más
                                            @elseif ($p['variacion_siguiente'] < 0)
                                                <i class="mdi mdi-arrow-down"></i> {{ abs($p['variacion_siguiente']) }}% menos
                                            @else
                                                <i class="mdi mdi-minus"></i> igual
                                            @endif
                                        </span>
                                        que el promedio reciente
                                    </div>
                                @endif
                            @else
                                <div class="pron-kpi-valor">—</div>
                                <div class="pron-kpi-sub">Aún no hay un mes completo de datos</div>
                            @endif
                        </div>
                    </div>

                    <!-- ===== RESUMEN EN PALABRAS ===== -->
                    <div class="pron-resumen">
                        <p>
                            En <strong>{{ $etiquetaFiltro }}</strong>, el día en que más se publica trabajo es el
                            <strong>{{ strtolower($p['dia_top']['nombre']) }}</strong>
                            ({{ $p['dia_top']['pct'] }}% del total).
                        </p>
                        @if ($p['mes_top'])
                            <p>
                                El mes con más movimiento en el último año fue <strong>{{ strtolower($p['mes_top']['mes']) }}</strong>
                                ({{ number_format($p['mes_top']['valor']) }} {{ $unidad }}).
                            </p>
                        @endif
                        @if ($p['proyeccion_top'])
                            <p>
                                Para los próximos 3 meses se espera más demanda en
                                <strong>{{ strtolower($p['proyeccion_top']['mes']) }}</strong>
                                (~{{ number_format($p['proyeccion_top']['valor']) }} {{ $unidad }}).
                            </p>
                        @endif
                    </div>

                    <!-- ===== GRÁFICA: DÍAS DE LA SEMANA ===== -->
                    @php
                        $escD = $p['escala_dias'];
                        $maxD = $escD['max'];
                    @endphp
                    <div class="pron-card">
                        <h2>¿Qué días se publica más trabajo?</h2>
                        <p class="pron-card-sub">{{ $etiquetaFiltro }} · {{ $unidad }} por día de la semana</p>

                        <div class="pron-leyenda">
                            <span><i class="pron-key real"></i> Día con más demanda</span>
                            <span><i class="pron-key muted"></i> Demás días</span>
                        </div>

                        <div role="group" aria-label="Gráfica de {{ $unidad }} por día de la semana">
                            <div class="pron-area">
                                <div class="pron-gridlayer">
                                    @foreach ($escD['ticks'] as $t)
                                        <i style="bottom: {{ $t / $maxD * 100 }}%"></i>
                                    @endforeach
                                </div>
                                <div class="pron-ylayer">
                                    @foreach ($escD['ticks'] as $t)
                                        <span style="bottom: {{ $t / $maxD * 100 }}%">{{ number_format($t) }}</span>
                                    @endforeach
                                </div>
                                <div class="pron-cols">
                                    @foreach ($p['dias'] as $d)
                                        @php $h = $d['valor'] / $maxD * 100; @endphp
                                        <div class="pron-col" tabindex="0" role="img"
                                            aria-label="{{ $d['nombre'] }}: {{ number_format($d['valor']) }} {{ $unidad }}, {{ $d['pct'] }}% del total"
                                            data-tip-valor="{{ number_format($d['valor']) }} {{ $unidad }}"
                                            data-tip-sub="{{ $d['nombre'] }} · {{ $d['pct'] }}% del total">
                                            <div class="pron-barzone">
                                                @if ($d['valor'] > 0)
                                                    <div class="pron-bar {{ $d['es_top'] ? 'is-top' : '' }}" style="height: {{ $h }}%"></div>
                                                @else
                                                    <span class="pron-nodata"></span>
                                                @endif
                                                @if ($d['es_top'])
                                                    <span class="pron-val" style="bottom: calc({{ $h }}% + 4px)">{{ number_format($d['valor']) }}</span>
                                                @endif
                                            </div>
                                            <div class="pron-xl">{{ $d['corto'] }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        @if ($p['dias_pocos_datos'])
                            <p class="pron-nota">Con pocas vacantes el patrón por día puede cambiar rápido; se vuelve más confiable con más datos.</p>
                        @endif

                        <details class="pron-tabla">
                            <summary><i class="mdi mdi-table"></i> Ver como tabla</summary>
                            <table>
                                <thead>
                                    <tr>
                                        <th>Día</th>
                                        <th class="num">{{ ucfirst($unidad) }}</th>
                                        <th class="num">% del total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($p['dias'] as $d)
                                        <tr>
                                            <td>{{ $d['nombre'] }}</td>
                                            <td class="num">{{ number_format($d['valor']) }}</td>
                                            <td class="num">{{ $d['pct'] }}%</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </details>
                    </div>

                    <!-- ===== GRÁFICA: MESES + PROYECCIÓN ===== -->
                    @php
                        $escM = $p['escala_meses'];
                        $maxM = $escM['max'];
                        $columnasMes = array_merge($p['meses'], $p['proyeccion']);
                        $primeraProy = count($p['meses']);
                        $topProyClave = $p['proyeccion_top']['clave'] ?? null;
                        $topRealClave = $p['mes_top']['clave'] ?? null;
                    @endphp
                    <div class="pron-card">
                        <h2>¿Qué meses hay más trabajo?</h2>
                        <p class="pron-card-sub">{{ $etiquetaFiltro }} · {{ $unidad }} por mes: últimos 12 meses y proyección de los próximos {{ count($p['proyeccion']) ?: 3 }}</p>

                        <div class="pron-leyenda">
                            <span><i class="pron-key real"></i> Real</span>
                            @if ($p['proyeccion'])
                                <span><i class="pron-key proy"></i> Proyección</span>
                            @endif
                        </div>

                        <div role="group" aria-label="Gráfica de {{ $unidad }} por mes, con proyección">
                            <div class="pron-area">
                                <div class="pron-gridlayer">
                                    @foreach ($escM['ticks'] as $t)
                                        <i style="bottom: {{ $t / $maxM * 100 }}%"></i>
                                    @endforeach
                                </div>
                                <div class="pron-ylayer">
                                    @foreach ($escM['ticks'] as $t)
                                        <span style="bottom: {{ $t / $maxM * 100 }}%">{{ number_format($t) }}</span>
                                    @endforeach
                                </div>
                                <div class="pron-cols">
                                    @foreach ($columnasMes as $i => $m)
                                        @php
                                            $esProy = $m['tipo'] === 'proyeccion';
                                            $h = $m['valor'] / $maxM * 100;
                                            if ($m['sin_datos']) {
                                                $tipoTxt = 'Todavía no había datos de este mes';
                                                $tipTitulo = 'Sin datos';
                                            } elseif ($esProy) {
                                                $tipoTxt = 'Proyección';
                                                $tipTitulo = '~' . number_format($m['valor']) . ' ' . $unidad;
                                            } else {
                                                $tipoTxt = $m['en_curso'] ? 'Real · mes en curso (aún no termina)' : 'Real';
                                                $tipTitulo = number_format($m['valor']) . ' ' . $unidad;
                                            }
                                            $muestraEtiqueta = !$m['sin_datos'] && $m['valor'] > 0
                                                && ($m['clave'] === $topRealClave || $m['clave'] === $topProyClave);
                                        @endphp
                                        <div class="pron-col {{ $esProy ? 'is-proy' : '' }} {{ $i === $primeraProy ? 'is-proy-first' : '' }}"
                                            tabindex="0" role="img"
                                            aria-label="{{ $m['nombre'] }}: {{ $m['sin_datos'] ? 'sin datos' : ($esProy ? 'proyección de ' : '') . number_format($m['valor']) . ' ' . $unidad }}"
                                            data-tip-valor="{{ $tipTitulo }}"
                                            data-tip-sub="{{ $m['nombre'] }} · {{ $tipoTxt }}">
                                            <div class="pron-barzone">
                                                @if ($m['sin_datos'] || $m['valor'] <= 0)
                                                    <span class="pron-nodata"></span>
                                                @else
                                                    <div class="pron-bar {{ $esProy ? 'is-proy' : 'is-real' }}" style="height: {{ $h }}%"></div>
                                                @endif
                                                @if ($muestraEtiqueta)
                                                    <span class="pron-val" style="bottom: calc({{ $h }}% + 4px)">{{ $esProy ? '~' : '' }}{{ number_format($m['valor']) }}</span>
                                                @endif
                                            </div>
                                            <div class="pron-xl">
                                                {{ $m['corto'] }}
                                                @if ($m['enero'] || $i === 0)
                                                    <small>{{ $m['anio'] }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <p class="pron-nota">
                            El último mes real está en curso y todavía no termina.
                            @if ($p['proyeccion'])
                                @if ($p['proyeccion_metodo'] === 'tendencia_estacional')
                                    La proyección combina la tendencia reciente con lo que pasó en ese mismo mes el año pasado.
                                @else
                                    La proyección sigue la tendencia de los últimos meses (aún no hay un año completo para comparar épocas).
                                @endif
                            @endif
                        </p>

                        <details class="pron-tabla">
                            <summary><i class="mdi mdi-table"></i> Ver como tabla</summary>
                            <table>
                                <thead>
                                    <tr>
                                        <th>Mes</th>
                                        <th class="num">{{ ucfirst($unidad) }}</th>
                                        <th>Tipo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($columnasMes as $m)
                                        <tr>
                                            <td>{{ $m['nombre'] }}</td>
                                            <td class="num">
                                                @if ($m['sin_datos'])
                                                    —
                                                @else
                                                    {{ $m['tipo'] === 'proyeccion' ? '~' : '' }}{{ number_format($m['valor']) }}
                                                @endif
                                            </td>
                                            <td>
                                                @if ($m['sin_datos'])
                                                    Sin datos todavía
                                                @elseif ($m['tipo'] === 'proyeccion')
                                                    Proyección
                                                @elseif ($m['en_curso'])
                                                    Real (mes en curso)
                                                @else
                                                    Real
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </details>
                    </div>

                    <!-- ===== CÓMO SE CALCULA ===== -->
                    <div class="pron-card pron-como">
                        <details>
                            <summary>¿Cómo se calcula esto?</summary>
                            <ul>
                                <li>Contamos las vacantes publicadas en la plataforma (o los trabajadores que piden, si eliges "Trabajadores solicitados"), de la categoría que elijas.</li>
                                <li>Los <strong>días</strong> salen de ver en qué día de la semana se publicó cada vacante.</li>
                                <li>La <strong>proyección de meses</strong> promedia los últimos 3 meses completos (el más reciente pesa más) y suma la tendencia. Cuando ya hay más de un año de datos, también se compara con ese mismo mes del año pasado.</li>
                                <li>No es una predicción exacta ni usa inteligencia artificial: es una guía que mejora conforme haya más vacantes registradas.</li>
                                <li>Las vacantes que se publicaron antes de que existiera el campo Categoría no aparecen al filtrar por categoría; sí cuentan en "Todas las categorías".</li>
                            </ul>
                        </details>
                    </div>

                @endif

            </div>
        </section>

        <div class="container my-4 d-none d-md-block">
            @include('partials.anuncio-negocio')
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

    <div class="pron-tip" id="pronTip" hidden><strong></strong><span></span></div>

    <script src="{{ asset('assets/usuario/js/main.js') }}"></script>
    <script>
        // ===== Interruptor Trabajador / Empleador (igual que en las demás pantallas de empleador) =====
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

        // ===== Filtros: al cambiar cualquier opción se actualiza la página =====
        (function() {
            const form = document.getElementById('pronFiltros');
            const cat = document.getElementById('fCategoria');
            const sub = document.getElementById('fSubcategoria');
            const medida = document.getElementById('fMedida');

            cat.addEventListener('change', function() {
                // Al cambiar de categoría, la subcategoría elegida ya no aplica.
                sub.disabled = true;
                form.submit();
            });
            sub.addEventListener('change', function() { form.submit(); });
            medida.addEventListener('change', function() { form.submit(); });
        })();

        // ===== Tooltip de las gráficas (al pasar el mouse, tocar, o enfocar con teclado) =====
        (function() {
            const tip = document.getElementById('pronTip');
            const tipValor = tip.querySelector('strong');
            const tipSub = tip.querySelector('span');

            function mostrar(col) {
                tipValor.textContent = col.dataset.tipValor || '';
                tipSub.textContent = col.dataset.tipSub || '';
                tip.hidden = false;

                const barra = col.querySelector('.pron-bar') || col.querySelector('.pron-barzone');
                const r = barra.getBoundingClientRect();
                const c = col.getBoundingClientRect();
                const ancho = tip.offsetWidth;
                const alto = tip.offsetHeight;

                let left = c.left + c.width / 2 - ancho / 2;
                left = Math.max(8, Math.min(left, window.innerWidth - ancho - 8));
                let top = r.top - alto - 8;
                if (top < 8) top = r.bottom + 8;

                tip.style.left = left + 'px';
                tip.style.top = top + 'px';
            }

            function ocultar() { tip.hidden = true; }

            document.querySelectorAll('.pron-col').forEach(function(col) {
                col.addEventListener('pointerenter', function() { mostrar(col); });
                col.addEventListener('pointerleave', ocultar);
                col.addEventListener('focus', function() { mostrar(col); });
                col.addEventListener('blur', ocultar);
            });

            document.addEventListener('pointerdown', function(e) {
                if (!e.target.closest('.pron-col')) ocultar();
            });
            window.addEventListener('scroll', ocultar, { passive: true });
        })();
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
