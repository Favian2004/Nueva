<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Inicio</title>
  <!-- Font Awesome para iconos (alternativa a los iconos personalizados) -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <!-- Estilos de bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <!-- Estilos propios -->
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <!-- Para Android/Chrome -->
  <link rel="icon" href="{{ asset('img/template/home.png') }}" type="image/png" sizes="192x192">
  <!-- jQuery (OBLIGATORIO antes de tu JS) -->


  <style>
    /* ===== CONTENIDO ===== */
    .contenido {
      margin-left: 230px;
      margin-right: 230px;
      padding: 0;
      background: #fff;
      min-height: 100vh;
    }

    /* ===== COLUMNAS DE ANUNCIOS ===== */
    .anuncio-izq,
    .anuncio-der {
      position: fixed;
      top: 10px;
      width: 210px;
      display: flex;
      flex-direction: column;
      gap: 15px;
      z-index: 999;
    }

    .anuncio-izq {
      left: 10px;
    }

    .anuncio-der {
      right: 10px;
    }

    /* ===== TARJETAS ===== */
    .ad-box {
      height: 210px;
      border-radius: 15px;
      overflow: hidden;
      background: #fff;
      box-shadow: 0 4px 15px rgba(0, 0, 0, .15);
    }

    .ad-box img {
      width: 100%;
      height: 210px;
      object-fit: cover;
      cursor: zoom-in;
    }

    /* ===== BANNER INFERIOR ===== */
    .banner-publicidad {
      margin-top: 40px;
      margin-bottom: 20px;
    }

    .banner-publicidad img {
      width: 100%;
      height: 140px;
      object-fit: cover;
      border-radius: 15px;
    }

    /* ===== FRANJA DE ANUNCIOS PARA MÓVIL (oculta en escritorio) ===== */
    .anuncios-mobile {
      display: none;
    }

    .anuncios-mobile .ad-titulo {
      margin: 10px 15px 0;
    }

    .anuncios-mobile-cols {
      display: flex;
      gap: 12px;
      padding: 10px 15px 15px;
    }

    .ad-box-mobile {
      flex: 1;
      min-width: 0;
      height: 190px;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 4px 15px rgba(0, 0, 0, .15);
    }

    .ad-box-mobile img {
      width: 100%;
      height: 190px;
      object-fit: cover;
      cursor: zoom-in;
    }

    /* ===== RESPONSIVE ===== */
    @media(max-width:1200px) {

      .anuncio-izq,
      .anuncio-der {
        display: none;
      }

      .contenido {
        margin-left: 0;
        margin-right: 0;
      }

      .anuncios-mobile {
        display: block;
      }
    }





    /* Banner inferior */
    .banner-negocio {
      width: 100%;
      background: linear-gradient(90deg, #6b1021, #8f1d2f, #b12d25);
      border-radius: 12px;

      padding: 8px 25px;
      /* menos alto */

      display: flex;
      align-items: center;
      justify-content: space-between;

      color: white;
      overflow: hidden;
      box-shadow: 0 4px 10px rgba(0, 0, 0, .15);
    }

    /* Texto izquierdo */
    .banner-texto h2 {
      font-size: 24px;
      margin: 0;
    }

    .banner-texto p {
      margin: 0;
      font-size: 14px;
    }

    /* Centro */
    .banner-destaca h2 {
      margin: 0;
      font-size: 22px;
    }

    .banner-destaca h1 {
      margin: 0;
      font-size: 32px;
      color: #ffcf33;
      font-weight: bold;
    }

    /* Icono tienda */
    .banner-icono img {
      width: 70px;
    }

    /* Botón */
    .btn-anunciar {
      background: #ffc107;
      color: #000;
      text-decoration: none;
      padding: 10px 18px;
      border-radius: 8px;
      font-weight: bold;
      display: inline-block;
    }

    .banner-boton small {
      display: block;
      text-align: center;
      margin-top: 3px;
    }

    /* Persona */
    .banner-persona img {
      height: 85px;
      /* aquí reduces mucho la altura */
      display: block;
    }


    .banner-persona {
      height: 90px;
    }

    .banner-persona img {
      height: 90px;
      width: auto;
      object-fit: cover;
      border-radius: 999px;

    }

    /* ===== BANNER "¿TIENES UN NEGOCIO?" - RESPONSIVO CELULAR ===== */
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


    /* ===== TITULO DE PUBLICIDAD ===== */

    .ad-titulo {
      background: #fff;
      border-radius: 12px;
      padding: 15px 10px;
      text-align: center;
      box-shadow: 0 4px 15px rgba(0, 0, 0, .10);
    }

    .ad-titulo h5 {
      margin: 0;
      font-size: 20px;
      font-weight: 800;
      color: #333;
    }

    .linea-titulo {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      margin-top: 5px;
    }

    .linea-titulo span {
      width: 40px;
      height: 2px;
      background: #f39c12;
    }

    .linea-titulo small {
      font-size: 13px;
      color: #555;
      font-weight: 600;
    }

    /* ===== CONTADOR DE VISITAS ===== */
    .contador-visitas {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      background: linear-gradient(90deg, #6b1021, #8f1d2f, #b12d25);
      color: #fff;
      border-radius: 30px;
      padding: 10px 24px;
      max-width: fit-content;
      margin: 18px auto;
      box-shadow: 0 4px 12px rgba(0,0,0,.12);
      font-weight: 600;
      font-size: 14px;
    }

    .contador-visitas i {
      font-size: 18px;
      color: #ffcf33;
    }

    .contador-visitas strong {
      color: #ffcf33;
      font-size: 16px;
    }

    /* ===== CÓMO FUNCIONA ===== */
    .como-funciona-section {
      padding: 40px 0 10px;
    }

    .como-funciona-titulo {
      text-align: center;
      font-weight: 800;
      font-size: 28px;
      color: #1a1a2e;
      margin-bottom: 4px;
    }

    .como-funciona-subtitulo {
      text-align: center;
      color: #888;
      font-size: 14px;
      margin-bottom: 0;
    }

    .como-funciona-card {
      background: #fff;
      border-radius: 16px;
      padding: 24px 16px;
      text-align: center;
      height: 100%;
      box-shadow: 0 4px 15px rgba(0,0,0,.06);
      border: 1px solid #f0f0f0;
      position: relative;
      transition: 0.25s;
    }

    .como-funciona-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 8px 24px rgba(0,0,0,.10);
      border-color: #ffd8ae;
    }

    .como-funciona-numero {
      position: absolute;
      top: 10px;
      left: 14px;
      width: 26px;
      height: 26px;
      border-radius: 50%;
      background: linear-gradient(135deg, #6b1021, #b12d25);
      color: #fff;
      font-weight: 800;
      font-size: 13px;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .como-funciona-icono {
      font-size: 34px;
      color: #ff7a18;
      margin: 10px 0 12px;
      display: block;
    }

    .como-funciona-card h5 {
      font-weight: 700;
      font-size: 15px;
      color: #1a1a2e;
      margin-bottom: 6px;
    }

    .como-funciona-card p {
      font-size: 12.5px;
      color: #777;
      margin: 0;
      line-height: 1.5;
    }

    /* ===== MODAL DE ANUNCIO AMPLIADO ===== */
    .modal-anuncio-overlay {
      display: none;
      position: fixed;
      top: 0; left: 0; right: 0; bottom: 0;
      background: rgba(0, 0, 0, 0.85);
      z-index: 9999;
      align-items: center;
      justify-content: center;
      padding: 20px;
      cursor: zoom-out;
    }

    .modal-anuncio-overlay.activo {
      display: flex;
    }

    .modal-anuncio-overlay img {
      max-width: 90%;
      max-height: 85vh;
      border-radius: 14px;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
      cursor: default;
      animation: modalAnuncioAparece 0.2s ease-out;
    }

    @keyframes modalAnuncioAparece {
      from { transform: scale(0.92); opacity: 0; }
      to { transform: scale(1); opacity: 1; }
    }

    .modal-anuncio-cerrar {
      position: fixed;
      top: 18px;
      right: 22px;
      width: 42px;
      height: 42px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.15);
      border: 1.5px solid rgba(255, 255, 255, 0.3);
      color: #fff;
      font-size: 26px;
      line-height: 1;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      transition: 0.2s;
    }

    .modal-anuncio-cerrar:hover {
      background: rgba(255, 255, 255, 0.3);
      transform: rotate(90deg);
    }
  </style>

</head>

<body>

<!-- MODAL: Ver anuncio en grande -->
  <div id="modalAnuncio" class="modal-anuncio-overlay">
    <button type="button" class="modal-anuncio-cerrar" onclick="cerrarModalAnuncio()">&times;</button>
    <img id="modalAnuncioImg" src="" alt="Anuncio">
  </div>

  <!-- ========================= -->
  <!-- ANUNCIOS IZQUIERDOS -->
  <!-- ========================= -->
  <div class="anuncio-izq">

    <div class="ad-titulo">
      <h5>NEGOCIOS DESTACADOS</h5>
      <div class="linea-titulo">
        <span></span>
        <small>de tu municipio</small>
        <span></span>
      </div>
    </div>

    @forelse ($anunciosIzquierda as $anuncio)
      @if ($anuncio->imagenes->count())
        <div class="ad-box">
          <div id="adLeft{{ $anuncio->orden }}" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
              @foreach ($anuncio->imagenes as $index => $img)
                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}" data-bs-interval="2500">
                  <img src="{{ $img->imagen }}">
                </div>
              @endforeach
            </div>
          </div>
        </div>
      @endif
    @empty
    @endforelse

  </div>
  <!-- ========================= -->
  <div class="anuncio-der">

    <div class="ad-titulo">
      <h5>NEGOCIOS DESTACADOS</h5>
      <div class="linea-titulo">
        <span></span>
        <small>de tu municipio</small>
        <span></span>
      </div>
    </div>

    @forelse ($anunciosDerecha as $anuncio)
      @if ($anuncio->imagenes->count())
        <div class="ad-box">
          <div id="adRight{{ $anuncio->orden }}" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
              @foreach ($anuncio->imagenes as $index => $img)
                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}" data-bs-interval="2500">
                  <img src="{{ $img->imagen }}">
                </div>
              @endforeach
            </div>
          </div>
        </div>
      @endif
    @empty
    @endforelse

  </div>

  <!-- CONTENIDO -->
  <div class="contenido">


    <!--=====================================
    PRELOADER
    ======================================-->


    <div class="lds-dual-ring loader" id="loader"></div>


    <!--=====================================
    Header TOP (solo para escritorio)
======================================-->
    <div class="header__top">
      <div class="container d-flex justify-content-between align-items-center">
        <!-- Social -->
        <div class="header__left justify-content-start">
          <ul class="d-flex list-unstyled mb-0">
            <li class="me-3"><a href="https://www.facebook.com/" target="_blank"><i class="fab fa-facebook-f"></i></a>
            </li>
            <li class="me-3"><a href="https://www.instagram.com/" target="_blank"><i class="fab fa-instagram"></i></a>
            </li>
            <li class="me-3"><a href="https://twitter.com/" target="_blank"><i class="fab fa-twitter"></i></a></li>
            <li><a href="https://www.youtube.com/" target="_blank"><i class="fab fa-youtube"></i></a></li>
          </ul>
        </div>

        <!-- Contact & Language -->
        <div class="header__right d-flex align-items-center">
          <div class="me-3">
            <i class="icon-telephone"></i> Linea directa: <strong>2331014306</strong>
          </div>
          <div>
            <select class="form-select form-select-sm language-select">
              <option value="es">Español</option>
              <option value="en">Inglés</option>
            </select>
          </div>
        </div>
      </div>
    </div>



    <!-- Barra de navegación Menu1-->
    <nav class="navbar navbar-expand-sm navbar-dark bg-custom">
      <div class="container-fluid">

        <a class="navbar-brand" href="#">
          <img src="{{ asset('img/template/logo.png') }}" alt="Logo" width="75" height="75" class="d-inline-block align-text-center">
          <span class="brand-text">¡SINTECZATE!</span>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
          aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Menu navbar top -->
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav ms-auto">

            <li class="nav-item">
              <a class="nav-link active" href="/">
                <i class="bi bi-house-door me-1"></i> Inicio
              </a>
            </li>

            <li class="nav-item">
              <a class="nav-link" href="/terminos">
                <i class="bi bi-file-text me-1"></i> Términos y Condiciones
              </a>
            </li>

            <li class="nav-item">
              <a class="nav-link" href="/acerca-de">
                <i class="bi bi-info-circle me-1"></i> Acerca de
              </a>
            </li>


            <li class="nav-item">
              <a class="nav-link" href="/acceso">
                <i class="bi bi-person-circle me-1"></i> Acceso
              </a>
            </li>

            <li class="nav-item">
              <a class="nav-link" href="/servicio-cliente">
                <i class="fas fa-headset"></i> Servicio al cliente
              </a>
            </li>

            <!-- SOLO COMPUTADORA ------------------------------------- -->
            <!-- FAVORITOS -->
            <li class="nav-item d-none d-lg-block position-relative"> <a class="nav-link position-relative" href="#"
                id="btnFavorites"> <i class="bi bi-heart" style="font-size: 1.3rem;"></i> <span id="favCount"
                  class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"> 0 </span>
              </a>
              <!-- DROPDOWN FAVORITOS -->
              <div class="cart-box" id="favBox" style="display: none; min-width: 350px;">
                <table class="table">
                  <thead>
                    <tr>
                      <th>Img</th>
                      <th>Servicio</th>
                      <th>Descripción</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>
                  <tbody id="favItems"></tbody>
                  <tfoot>
                    <tr>
                      <td colspan="4" class="text-end"> <button class="btn btn-danger w-100" id="clearFavorites">Vaciar
                          favoritos</button> </td>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </li>

          </ul>
        </div>

      </div>
    </nav>

    <!-- Barra de navegación Menu2 -->
    <nav class="navbar navbar-expand-lg navbar-search">
      <div class="container-fluid">

        <!-- Contenedor del buscador (siempre visible) -->
        <div class="collapse navbar-collapse show" id="navbarSearch">
          <!-- Búsqueda - Versión Premium (con ms-auto para alinear a la derecha) -->
          <form action="/" method="GET" class="input-group ms-auto buscador-container">

            <!-- Dropdown elegante -->
            <button class="btn dropdown-toggle dropdown-categorias" type="button" data-bs-toggle="dropdown">
              <i class="bi bi-funnel"></i> Categorias
            </button>

            @php
              $iconosCategoria = [
                'Construcción' => ['icon' => 'bi-hammer', 'color' => 'text-warning'],
                'Hogar y limpieza' => ['icon' => 'bi-house-door', 'color' => 'text-success'],
                'Talleres y mecánica' => ['icon' => 'bi-tools', 'color' => 'text-secondary'],
                'Educación y clases' => ['icon' => 'bi-mortarboard', 'color' => 'text-info'],
                'Campo y jardinería' => ['icon' => 'bi-flower1', 'color' => 'text-success'],
                'Eventos y celebraciones' => ['icon' => 'bi-balloon-heart', 'color' => 'text-danger'],
                'Servicios Profecionales' => ['icon' => 'bi-briefcase-fill', 'color' => 'text-primary'],
                'Oficios y Tradiciones' => ['icon' => 'bi-palette2', 'color' => 'text-warning'],
                'Compra y Venta Local' => ['icon' => 'bi-shop', 'color' => 'text-info'],
                'Salud y belleza' => ['icon' => 'bi-heart-pulse', 'color' => 'text-danger'],
                'Tecnología y electrónica' => ['icon' => 'bi-laptop', 'color' => 'text-primary'],
                'Transporte y mensajería' => ['icon' => 'bi-truck', 'color' => 'text-secondary'],
                'Mascotas' => ['icon' => 'bi-heart', 'color' => 'text-warning'],
              ];
            @endphp

            <ul class="dropdown-menu dropdown-menu-end p-3 dropdown-categorias-menu">
              <li>
                <h6 class="dropdown-header text-danger fw-bold fs-6">Categorías</h6>
              </li>
              @foreach ($categorias as $cat)
                @php $icono = $iconosCategoria[$cat->nombre] ?? ['icon' => 'bi-briefcase-fill', 'color' => 'text-primary']; @endphp
                <li>
                  <a class="dropdown-item py-2 rounded-3 {{ (string) $categoriaId === (string) $cat->id ? 'active' : '' }}" href="/?categoria_id={{ $cat->id }}">
                    <i class="bi {{ $icono['icon'] }} me-2 {{ $icono['color'] }}"></i>
                    {{ $cat->nombre }}
                  </a>
                </li>
              @endforeach
              @if ($categoriaId)
                <li><hr class="dropdown-divider"></li>
                <li>
                  <a class="dropdown-item text-center text-danger fw-bold" href="/">
                    <i class="bi bi-x-circle"></i> Quitar filtro
                  </a>
                </li>
              @endif
            </ul>

            <!-- Input con borde degradado -->
            <input type="text" name="q" value="{{ $q }}" class="form-control input-buscar" placeholder="¿Qué servicio necesitas?">
            @if ($categoriaId)
              <input type="hidden" name="categoria_id" value="{{ $categoriaId }}">
            @endif

            <!-- Botón con efecto hover -->
            <button class="btn boton-buscar" type="submit">
              <i class="bi bi-search"></i>
            </button>

          </form>
        </div>
      </div>
    </nav>

      <!-- Agrega data-bs-ride="carousel" para que sea automático -->
    <div id="carouselExampleDark" class="carousel carousel-dark slide" data-bs-ride="carousel">

      <!-- INDICADORES - Agrega uno por cada slide (ya tienes 4 slides, necesitas 4 indicadores) -->
      <div class="carousel-indicators">
        <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="0" class="active"
          aria-current="true" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="1" aria-label="Slide 2"></button>
        <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="2" aria-label="Slide 3"></button>
        <!-- NUEVO INDICADOR para la cuarta imagen -->
        <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="3" aria-label="Slide 4"></button>
      </div>

      <!-- SLIDES (tus 4 imágenes) -->
      <div class="carousel-inner">
        <div class="carousel-item active" data-bs-interval="4000">
          <!-- Cambié a 3 segundos para que sea más dinámico -->
          <img src="{{ asset('img/slider/horizontal/img1.png') }}" class="d-block w-100" alt="...">
        </div>

        <div class="carousel-item" data-bs-interval="4000">
          <img src="{{ asset('img/slider/horizontal/img2.png') }}" class="d-block w-100" alt="...">
        </div>

        <div class="carousel-item" data-bs-interval="4000">
          <img src="{{ asset('img/slider/horizontal/img3.png') }}" class="d-block w-100" alt="...">
        </div>

        <div class="carousel-item" data-bs-interval="4000">
          <img src="{{ asset('img/slider/horizontal/img4.png') }}" class="d-block w-100" alt="...">
        </div>
      </div>

      <!-- CONTROLES (opcional pero recomendado) -->
      <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
      </button>

    </div>

    <!-- ========================= -->
    <!-- CONTADOR DE VISITAS -->
    <!-- ========================= -->
    <div class="contador-visitas">
      <i class="bi bi-eye-fill"></i>
      <span>Personas que han visitado esta página: <strong>{{ number_format($totalVisitas) }}</strong></span>
    </div>

    <!-- ========================= -->
    <!-- ANUNCIOS EN MÓVIL (franja horizontal con scroll) -->
    <!-- ========================= -->
    <div class="anuncios-mobile">
      <div class="ad-titulo">
        <h5>NEGOCIOS DESTACADOS</h5>
        <div class="linea-titulo">
          <span></span>
          <small>de tu municipio</small>
          <span></span>
        </div>
      </div>
      @php
        $imgsIzquierda = $anunciosIzquierda->flatMap(fn($a) => $a->imagenes);
        $imgsDerecha = $anunciosDerecha->flatMap(fn($a) => $a->imagenes);
      @endphp
      <div class="anuncios-mobile-cols">
        @if ($imgsIzquierda->count())
          <div class="ad-box-mobile">
            <div id="adMobileIzq" class="carousel slide" data-bs-ride="carousel">
              <div class="carousel-inner">
                @foreach ($imgsIzquierda as $index => $img)
                  <div class="carousel-item {{ $index === 0 ? 'active' : '' }}" data-bs-interval="2500">
                    <img src="{{ $img->imagen }}">
                  </div>
                @endforeach
              </div>
            </div>
          </div>
        @endif
        @if ($imgsDerecha->count())
          <div class="ad-box-mobile">
            <div id="adMobileDer" class="carousel slide" data-bs-ride="carousel">
              <div class="carousel-inner">
                @foreach ($imgsDerecha as $index => $img)
                  <div class="carousel-item {{ $index === 0 ? 'active' : '' }}" data-bs-interval="2500">
                    <img src="{{ $img->imagen }}">
                  </div>
                @endforeach
              </div>
            </div>
          </div>
        @endif
      </div>
    </div>




    <!--=====================================
    Home Features
=====================================-->

    <div class="ps-site-features">
      <div class="container">

        <div class="row">

          <div class="col-6 col-md-3">
            <div class="ps-block__item">
              <div class="ps-block__left">
                <i class="fas fa-briefcase"></i>
              </div>
              <div class="ps-block__right">
                <h4>¿Listo para empezar?</h4>
                <p>Únete a nuestra comunidad y encuentra el servicio que necesitas</p>
              </div>
            </div>
          </div>

          <div class="col-6 col-md-3">
            <div class="ps-block__item">
              <div class="ps-block__left">
                <i class="fas fa-handshake"></i>
              </div>
              <div class="ps-block__right">
                <h4>Confianza garantizada</h4>
                <p>Profesionales verificados</p>
                <p>¡Regístrate ahora mismo!</p>
              </div>
            </div>
          </div>

          <div class="col-6 col-md-3">
            <div class="ps-block__item">
              <div class="ps-block__left">
                <i class="fas fa-search"></i>
              </div>
              <div class="ps-block__right">
                <h4>Encuentra cualquier tipo de empleado</h4>
                <p>Contrata servicios cerca de tu comunidad</p>
              </div>
            </div>
          </div>

          <div class="col-6 col-md-3">
            <div class="ps-block__item">
              <div class="ps-block__left">
                <i class="fas fa-sync-alt"></i>
              </div>
              <div class="ps-block__right">
                <h4>"Hoy ofreces, mañana contratas"</h4>
                <p>Todos tenemos algo que dar y recibir</p>
              </div>
            </div>
          </div>

        </div>

      </div>
    </div>

    <!--=====================================
    CÓMO FUNCIONA
======================================-->
    <div class="como-funciona-section">
      <div class="container">
        <h2 class="como-funciona-titulo">¿Cómo funciona?</h2>
        <p class="como-funciona-subtitulo">En 4 pasos sencillos, sin complicaciones</p>

        <div class="row g-4 mt-2">
          <div class="col-6 col-md-3">
            <div class="como-funciona-card">
              <div class="como-funciona-numero">1</div>
              <i class="bi bi-search como-funciona-icono"></i>
              <h5>Busca o publica</h5>
              <p>Encuentra el servicio que necesitas, o publica el tuyo si tienes algo que ofrecer.</p>
            </div>
          </div>
          <div class="col-6 col-md-3">
            <div class="como-funciona-card">
              <div class="como-funciona-numero">2</div>
              <i class="bi bi-person-check como-funciona-icono"></i>
              <h5>Regístrate gratis</h5>
              <p>Crea tu cuenta en un minuto para ver los datos de contacto y hablar directo.</p>
            </div>
          </div>
          <div class="col-6 col-md-3">
            <div class="como-funciona-card">
              <div class="como-funciona-numero">3</div>
              <i class="bi bi-whatsapp como-funciona-icono"></i>
              <h5>Contacta y contrata</h5>
              <p>Revisa la insignia de verificado y las calificaciones antes de ponerte de acuerdo.</p>
            </div>
          </div>
          <div class="col-6 col-md-3">
            <div class="como-funciona-card">
              <div class="como-funciona-numero">4</div>
              <i class="bi bi-star como-funciona-icono"></i>
              <h5>Califica</h5>
              <p>Cuando termine el trabajo, deja tu reseña para ayudar a toda la comunidad.</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!--=====================================
    SERVICIOS DESTACADOS (reales, de toda la plataforma)
======================================-->
    <main>
      <section class="products" id="listProducts">
        <h2>Servicios Disponibles</h2>
        @if ($q || $categoriaId)
          <p class="text-center text-muted mb-4">
            Resultados
            @if ($q) para "<strong>{{ $q }}</strong>" @endif
            @if ($categoriaId) en <strong>{{ $categorias->firstWhere('id', $categoriaId)->nombre ?? '' }}</strong> @endif
            &nbsp;·&nbsp; <a href="/">Quitar filtros</a>
          </p>
        @endif

        <div class="products-grid container">

          @forelse ($servicios as $s)
            <div class="product">
              <img src="{{ $s->imagen ?? asset('img/services/plomero.jpg') }}" alt="{{ $s->titulo }}">

              <div class="product-info">
                <h4>{{ $s->titulo }}
                  @if ($s->usuario && $s->usuario->verificacion_estado === 'aprobado')
                    <span title="Identidad verificada" style="background:#dcfce7; color:#16a34a; padding:1px 8px; border-radius:20px; font-size:.65rem; font-weight:700; vertical-align:middle;">
                      <i class="bi bi-patch-check-fill"></i> Verificado
                    </span>
                  @endif
                </h4>
                <p class="product-text">
                  {{ \Illuminate\Support\Str::limit($s->descripcion, 90) }}
                </p>

                @php $prom = round($s->calificaciones_avg_estrellas ?? 0); @endphp
                @for ($i = 1; $i <= 5; $i++)
                  <i class="fa-solid fa-star icon-star" style="color:{{ $i <= $prom ? '#ffb347' : '#ddd' }};"></i>
                @endfor
                <small style="color:#999; margin-left:4px;">
                  {{ $s->calificaciones_avg_estrellas ? number_format($s->calificaciones_avg_estrellas, 1) : 'Sin reseñas' }}
                  @if ($s->calificaciones_count) ({{ $s->calificaciones_count }}) @endif
                </small>

                <div class="price">
                  <span>Desde</span>
                  <p class="currentPrice">${{ number_format($s->precio, 0) }} MXN</p>
                </div>

                <div class="d-flex gap-2">
                  <a href="/acceso" class="btn-add" style="text-decoration:none; text-align:center;">Ver más / Contactar</a>

                  <!-- BOTÓN FAVORITO -->
                  <div class="favorite-wrapper position-relative">
                    <i class="bi bi-heart btn-fav" style="font-size: 1.3rem; cursor: pointer;"></i>

                    <!-- DROPDOWN FAVORITOS DENTRO DEL PRODUCT -->
                    <div class="fav-dropdown" style="display: none;">
                      <p>Agregado a favoritos ❤️</p>
                      <button class="btn btn-sm btn-danger clear-fav">Eliminar</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          @empty
            <p class="has-text-grey text-center" style="grid-column: 1 / -1;">Todavía no hay servicios publicados. ¡Sé el primero en <a href="/acceso">unirte</a>!</p>
          @endforelse

        </div>
      </section>
    </main>


    <!-- MENÚ INFERIOR SOLO PARA CELULAR -->
    <div class="menu-movil d-lg-none">

      <a class="item" data-bs-toggle="offcanvas" href="#panelCategorias">
        <i class="bi bi-grid"></i>
        <span>Categorias</span>
      </a>

      <a class="item" data-bs-toggle="offcanvas" href="#panelFavoritos">
        <i class="bi bi-heart"></i>
        <span>Favoritos</span>
      </a>

    </div>

    <!--=====================================
	Paneles de menu de celular de la parte de abajo---------
	======================================-->

    <!-- CATEGORIAS -->
    <div class="offcanvas offcanvas-end shadow-lg" tabindex="-1" id="panelCategorias">

      <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title fw-bold text-danger">
          <i class="bi bi-grid me-2"></i> Categorías
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
      </div>

      <div class="offcanvas-body p-0">

        <div class="list-group list-group-flush">

          <div class="px-3 py-3 bg-custom">
            <small class="text-white fw-bold">CATEGORÍAS</small>
      </div>

      @foreach ($categorias as $cat)
        @php $icono = $iconosCategoria[$cat->nombre] ?? ['icon' => 'bi-briefcase-fill', 'color' => 'text-primary']; @endphp
        <a href="/?categoria_id={{ $cat->id }}"
              class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3">
              <div>
                <i class="bi {{ $icono['icon'] }} me-2 {{ $icono['color'] }}"></i>
                {{ $cat->nombre }}
              </div>
              </a>
      @endforeach

          </div>

        </div>

      </div>

      <!-- FAVORITOS MOBILE -->
      <div class="offcanvas offcanvas-end" tabindex="-1" id="panelFavoritos">
        <div class="offcanvas-header">
          <h5 class="offcanvas-title">Favoritos</h5>
          <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body" id="favItemsMobile">
          No tienes favoritos aún.
        </div>
        <div class="mt-3">
          <button class="btn btn-danger w-100" id="clearFavoritesMobile">Vaciar favoritos</button>
        </div>
      </div>


      <footer class="footer-pro mt-5">
        <div class="container py-5">

          <div class="row g-4">

            <!-- Marca -->
            <div class="col-md-4 text-center text-md-start">
              <h4 class="fw-bold text-white">¡SINTECZATE!</h4>
              <p class="text-light">
                Conectamos personas que ofrecen y buscan servicios de forma rápida y segura.
              </p>

              <!-- Redes -->
              <div class="mt-3">
                <a href="#" class="social"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="social"><i class="fab fa-instagram"></i></a>
                <a href="#" class="social"><i class="fab fa-twitter"></i></a>
              </div>
            </div>

            <!-- Enlaces -->
            <div class="col-md-4 text-center">
              <h5 class="text-white mb-3">Navegación</h5>
              <ul class="list-unstyled">
                <li><a href="/">Inicio</a></li>
                <li><a href="/terminos">Términos</a></li>
                <li><a href="/acerca-de">Acerca de</a></li>
                <li><a href="/acceso">Acceso</a></li>
                <li><a href="/servicio-cliente">Servicio al cliente</a></li>
              </ul>
            </div>

            <!-- Contacto -->
            <div class="col-md-4 text-center text-md-end">
              <h5 class="text-white mb-3">Contacto</h5>
              <p><i class="bi bi-telephone"></i> 2331014306</p>
              <p><i class="bi bi-geo-alt"></i> Teziutlán, Puebla</p>
              <p><i class="bi bi-envelope"></i> contacto@conectaya.com</p>

            </div>

          </div>

          <!-- Línea -->
          <hr class="border-light mt-4">

          <!-- Copy -->
          <div class="text-center text-light small py-3">
            © 2026 ¡SINTECZATE! | Todos los derechos reservados
          </div>

        </div>

        <!-- ========================= -->
        <!-- ANUNCIOS INFERIOR -->
        <!-- ========================= -->

        <div class="container my-4">

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
              <h1>EN ¡SINTECZATE!</h1>
            </div>

            <div class="banner-boton">
              <a href="/anunciar" class="btn-anunciar">
                ¡ANÚNCIATE AQUÍ!
              </a>
              <small>Más información →</small>
            </div>

            <div class="banner-persona">
              <img src="{{ asset('img/anuncios/persona.png') }}" alt="Anunciante">
            </div>

          </div>

        </div>
      </footer>

    </div>


    <!--=====================================
	JS PERSONALIZADO
	======================================-->
    <script src="{{ asset('js/main.js') }}"></script>

    <script>
      // ===== Modal de anuncio ampliado (al tocar cualquier imagen de anuncio) =====
      document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('modalAnuncio');
        const modalImg = document.getElementById('modalAnuncioImg');
        if (!modal || !modalImg) return;

        document.querySelectorAll('.ad-box img, .ad-box-mobile img').forEach(function (img) {
          img.addEventListener('click', function () {
            modalImg.src = this.src;
            modal.classList.add('activo');
          });
        });

        modal.addEventListener('click', function (e) {
          if (e.target === modal) {
            cerrarModalAnuncio();
          }
        });

        document.addEventListener('keydown', function (e) {
          if (e.key === 'Escape') cerrarModalAnuncio();
        });
      });

      function cerrarModalAnuncio() {
        document.getElementById('modalAnuncio').classList.remove('activo');
      }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
      crossorigin="anonymous"></script>
</body>

</html>
