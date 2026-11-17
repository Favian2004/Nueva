<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Inicio</title>
  <meta name="description" content="¡SINTECZATE! Plataforma de servicios y empleos en Zacapoaxtla, Puebla.
  Ofrece tus servicios, encuentra profesionales o publica oportunidades de trabajo y conecta con personas de tu comunidad.">
  <!-- Font Awesome para iconos (alternativa a los iconos personalizados) -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <!-- Estilos de bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <!-- Estilos propios -->
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('css/anuncio-negocio.css') }}">
  <!-- Para Android/Chrome -->
  <link rel="icon" href="{{ asset('img/template/logo.png') }}" type="image/png" sizes="192x192">
  <!-- jQuery (OBLIGATORIO antes de tu JS) -->


  <style>
    /* ===== TARJETAS DE "SERVICIOS DISPONIBLES" (mismo diseño que el dashboard del usuario) =====
       Van aquí, junto a la página, para no depender de que style.css esté al día.
       Todo está limitado a #listProducts (servicios); las vacantes no se tocan. */
    :root { --tc-vino:#8a1232; --tc-vino-osc:#6a0d26; --tc-precio:#a10f33; }

    #listProducts .products-grid .product { position:relative; display:flex; flex-direction:column; overflow:hidden; border-radius:18px; background:#fff;
      box-shadow:0 6px 18px rgba(0,0,0,.08); transition:transform .2s, box-shadow .2s; cursor:pointer; }
    #listProducts .products-grid .product:hover { transform:translateY(-4px); box-shadow:0 14px 28px rgba(110,12,38,.18); }
    #listProducts .tc-foto { position:relative; flex:0 0 auto; height:170px; overflow:hidden; background:#fbe9dc; }
    #listProducts .tc-foto img { display:block; width:100%; height:100%; margin:0; object-fit:cover; border-radius:0; }
    #listProducts .tc-estado { position:absolute; left:10px; top:10px; z-index:1; display:inline-flex; align-items:center; gap:5px; padding:4px 11px;
      border-radius:999px; font-size:12px; font-weight:700; color:#fff; background:#16a34a; box-shadow:0 2px 6px rgba(0,0,0,.25); }
    #listProducts .products-grid .product-info { flex:1 1 auto; display:flex; flex-direction:column; padding:14px 16px 16px; }
    #listProducts .tc-titulo { margin:0 0 6px; font-size:17px; font-weight:800; line-height:1.25; color:#222; }
    #listProducts .tc-titulo a.tc-link { color:inherit; text-decoration:none; }
    #listProducts .tc-titulo a.tc-link::after { content:""; position:absolute; inset:0; z-index:1; }
    #listProducts .tc-titulo a.tc-link:focus-visible { outline:none; }
    #listProducts .tc-titulo a.tc-link:focus-visible::after { outline:3px solid #ffb347; outline-offset:-3px; border-radius:18px; }
    #listProducts .tc-lugar { display:flex; align-items:center; gap:4px; margin:0 0 8px; font-size:12.5px; color:#777; }
    #listProducts .tc-lugar i { color:#ff7a18; }
    #listProducts .tc-lugar span { overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
    #listProducts .products-grid .tc-desc { margin:0 0 8px; font-size:13.5px; line-height:1.45; color:#555; flex:0 0 auto;
      display:-webkit-box; -webkit-line-clamp:3; -webkit-box-orient:vertical; overflow:hidden; }
    #listProducts .products-grid .rating { display:flex; flex-wrap:wrap; align-items:center; gap:3px; margin-bottom:0; }
    #listProducts .tc-pie { margin-top:auto; padding-top:6px; }
    #listProducts .tc-precio { display:flex; align-items:baseline; justify-content:space-between; gap:6px; margin:6px 0 10px; }
    #listProducts .tc-precio small { font-size:13px; color:#888; }
    #listProducts .tc-precio strong { font-size:22px; font-weight:800; color:var(--tc-precio); }
    #listProducts .tc-precio strong em { font-size:12px; font-style:normal; font-weight:700; color:#888; }
    #listProducts .products-grid .product-acciones { display:flex; align-items:center; gap:10px; width:100%; }
    #listProducts a.tc-contratame { position:relative; z-index:2; flex:1 1 auto; display:inline-flex; align-items:center; justify-content:center; gap:8px;
      padding:11px 12px; border-radius:999px; background:var(--tc-vino); color:#fff; font-size:14px; font-weight:700; white-space:nowrap;
      text-decoration:none; box-shadow:0 4px 10px rgba(138,18,50,.3); transition:background .2s, transform .15s; }
    #listProducts a.tc-contratame:hover { background:var(--tc-vino-osc); color:#fff; transform:translateY(-1px); }
    #listProducts a.tc-contratame i { font-size:17px; line-height:1; }
    #listProducts .products-grid .btn-fav { position:relative; z-index:2; flex:0 0 auto; width:auto; height:auto; border:none; background:none; box-shadow:none;
      border-radius:0; padding:4px 8px; font-size:1.4rem; line-height:1; color:var(--tc-vino); cursor:pointer; transition:color .2s; }
    #listProducts .products-grid .btn-fav:hover,
    #listProducts .products-grid .btn-fav.activo { color:#e63946; background:none; transform:none; }

    /* Celular (2 columnas): tarjeta compacta, botón a todo el ancho y corazón sobre la foto */
    @media (max-width: 576px) {
      #listProducts .tc-foto { height:112px; }
      #listProducts .tc-estado { left:6px; top:6px; padding:3px 8px; font-size:11px; gap:3px; }
      #listProducts .products-grid .product-info { padding:10px 10px 12px; }
      #listProducts .tc-titulo { margin-bottom:4px; font-size:14px; line-height:1.2; }
      #listProducts .tc-lugar { margin-bottom:5px; font-size:11px; }
      #listProducts .products-grid .tc-desc { margin-bottom:6px; font-size:12px; line-height:1.35; -webkit-line-clamp:2; }
      #listProducts .products-grid .rating { gap:1px; }
      #listProducts .products-grid .rating .icon-star { font-size:11px; }
      #listProducts .products-grid .rating small { display:none; }
      #listProducts .tc-precio { flex-direction:column; align-items:flex-start; gap:0; margin:6px 0 8px; }
      #listProducts .tc-precio small { font-size:11px; }
      #listProducts .tc-precio strong { font-size:18px; white-space:nowrap; }
      #listProducts .tc-precio strong em { font-size:10px; }
      #listProducts .products-grid .product-acciones { display:block; }
      #listProducts a.tc-contratame { box-sizing:border-box; display:flex; width:100%; min-width:0; gap:5px; padding:9px 6px; font-size:12px; line-height:1.15;
        text-align:center; white-space:normal; box-shadow:0 3px 8px rgba(138,18,50,.28); }
      #listProducts a.tc-contratame i { flex:0 0 auto; font-size:14px; }
      #listProducts .products-grid .product-acciones .btn-fav { position:absolute; top:6px; right:6px; z-index:3; width:30px; height:30px; padding:0;
        border-radius:50%; background:rgba(255,255,255,.94); box-shadow:0 2px 6px rgba(0,0,0,.25); font-size:1.05rem;
        display:inline-flex; align-items:center; justify-content:center; }
    }

    /* ===== CONTENIDO ===== */
    .contenido {
      margin-left: 10px;
      margin-right: 230px;
      padding: 0;
      background: #fff;
      min-height: 100vh;
    }

    /* ===== COLUMNA DE ANUNCIOS (solo derecha) ===== */
    .anuncio-der {
      position: fixed;
      top: 10px;
      width: 210px;
      display: flex;
      flex-direction: column;
      gap: 15px;
      z-index: 999;
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
      justify-content: center;
      padding: 10px 15px 15px;
    }

    .ad-box-mobile {
      width: 100%;
      max-width: 320px;
      aspect-ratio: 10 / 9;
      border-radius: 14px;
      overflow: hidden;
      box-shadow: 0 4px 15px rgba(0, 0, 0, .15);
    }

    .ad-box-mobile .carousel,
    .ad-box-mobile .carousel-inner,
    .ad-box-mobile .carousel-item {
      height: 100%;

    }

    .ad-box-mobile img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      cursor: zoom-in;
    }

    /* ===== RESPONSIVE ===== */

    /* Pantallas medianas (laptops 14"-15", ~1201px a 1600px):
       la columna se achica en vez de verse igual de grande que en
       un monitor de escritorio de 1920px. */
    @media (min-width: 1201px) and (max-width: 1600px) {
      .anuncio-der {
        width: 180px;
      }

      .ad-box {
        height: 180px;
      }

      .ad-box img {
        height: 180px;
      }

      .contenido {
        margin-right: 200px;
      }
    }

    @media(max-width:1200px) {

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
    }

    .modal-anuncio-overlay.activo {
      display: flex;
    }

    .modal-anuncio-card {
      background: #fff;
      border-radius: 18px;
      max-width: 550px;
      width: 100%;
      overflow: hidden;
      text-align: center;
      animation: modalAnuncioAparece 0.2s ease-out;
      box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
    }

    @keyframes modalAnuncioAparece {
      from { transform: scale(0.92); opacity: 0; }
      to { transform: scale(1); opacity: 1; }
    }

    .modal-anuncio-img-box {
      width: 100%;
      aspect-ratio: 1 / 1;
      background: #e5e0d8;
      overflow: hidden;
    }

    .modal-anuncio-img-box img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
    }

    .modal-anuncio-body {
      padding: 18px 20px 22px;
    }

    .modal-anuncio-body h6 {
      font-weight: 800;
      color: #1a1a2e;
      margin: 0 0 4px;
      font-size: 15px;
    }

    .modal-anuncio-body p {
      font-size: 12.5px;
      color: #777;
      margin: 0 0 14px;
    }

    .modal-anuncio-btn {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: #6b1021;
      color: #fff;
      border: none;
      padding: 9px 18px;
      border-radius: 10px;
      font-size: 12.5px;
      font-weight: 700;
      text-decoration: none;
      margin: 3px;
    }

    .modal-anuncio-btn.btn-ubicacion {
      background: #fff;
      color: #6b1021;
      border: 1.5px solid #6b1021;
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

    /* ===== SELECT DE COMUNIDAD EN EL BUSCADOR ===== */
    .select-comunidad {
      border: 1.5px solid #e5e7eb;
      border-radius: 0;
      padding: 8px 14px;
      font-size: 14px;
      background: #fff;
      max-width: 190px;
    }
    .select-comunidad:focus {
      outline: none;
      border-color: #ff7a18;
      box-shadow: none;
    }

    /* ===== SECCIÓN DE VACANTES (mismo estilo que servicios) ===== */
    .vacantes-section {
      padding-top: 10px;
    }
    .vacante-card {
      display: flex;
      background: #fff;
      border-radius: 14px;
      overflow: hidden;
      box-shadow: 0 2px 10px rgba(0,0,0,.07);
      border: 1px solid #eee;
      transition: 0.2s;
    }
    .vacante-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 20px rgba(0,0,0,.10);
    }
    .vacante-card img {
      width: 110px;
      height: 100%;
      min-height: 150px;
      object-fit: cover;
      flex-shrink: 0;
    }
    .vacante-card .vacante-info {
      padding: 14px 16px;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      flex: 1;
    }
    .vacante-card h5 {
      font-size: 15px;
      font-weight: 700;
      color: #1a1a2e;
      margin: 0 0 4px;
    }
    .vacante-card .vacante-meta {
      font-size: 12px;
      color: #777;
      margin-bottom: 6px;
    }
    .vacante-card .vacante-desc {
      font-size: 12.5px;
      color: #555;
      margin-bottom: 10px;
    }
    .vacante-badges span {
      display: inline-block;
      font-size: 10.5px;
      font-weight: 700;
      padding: 3px 10px;
      border-radius: 20px;
      margin-right: 6px;
      margin-bottom: 6px;
    }
    .badge-salario { background: #e6f4ea; color: #1e7e34; }
    .badge-contrato { background: #fef7e0; color: #e37400; }

    /* ===== BUSCADOR "PRO" (mismo estilo para Servicios y Vacantes) ===== */
    .buscador-pro {
      background: #fff;
      border-radius: 18px;
      box-shadow: 0 6px 24px rgba(0,0,0,.08);
      border: 1px solid #f2f2f2;
      padding: 16px 20px;
      display: flex;
      align-items: center;
      gap: 12px;
      max-width: 920px;
      margin: 0 auto 10px;
      flex-wrap: wrap;
    }
    .buscador-pro .campo-input {
      flex: 1 1 240px;
      display: flex;
      align-items: center;
      gap: 8px;
      border: 1.5px solid #ececec;
      border-radius: 12px;
      padding: 11px 16px;
      transition: 0.2s;
    }
    .buscador-pro .campo-input:focus-within {
      border-color: #ff7a18;
      box-shadow: 0 0 0 3px rgba(255,122,24,.10);
    }
    .buscador-pro .campo-input i {
      color: #ff7a18;
      font-size: 16px;
      flex-shrink: 0;
    }
    .buscador-pro .campo-input input {
      border: none;
      outline: none;
      flex: 1;
      font-size: 14px;
      min-width: 0;
    }
    .buscador-pro select {
      border: 1.5px solid #ececec;
      border-radius: 12px;
      padding: 11px 14px;
      font-size: 14px;
      background: #fff;
      flex: 1 1 170px;
      max-width: 230px;
      color: #444;
    }
    .buscador-pro select:focus {
      outline: none;
      border-color: #ff7a18;
    }
    .buscador-pro button {
      background: linear-gradient(45deg, #ff7a18, #ffb347);
      color: #fff;
      border: none;
      border-radius: 12px;
      padding: 12px 26px;
      font-weight: 700;
      font-size: 14px;
      display: flex;
      align-items: center;
      gap: 6px;
      cursor: pointer;
      transition: 0.2s;
      flex-shrink: 0;
      box-shadow: 0 4px 12px rgba(255,122,24,.30);
    }
    .buscador-pro button:hover {
      opacity: .92;
      transform: translateY(-1px);
    }
    .buscador-pro-nota {
      text-align: center;
      font-size: 12px;
      color: #999;
      max-width: 920px;
      margin: 0 auto 22px;
    }
    .buscador-pro-resultados {
      text-align: center;
      color: #777;
      font-size: 13.5px;
      max-width: 920px;
      margin: 0 auto 18px;
    }

    @media (max-width: 576px) {
      .buscador-pro {
        padding: 14px;
        border-radius: 16px;
        gap: 10px;
      }
      .buscador-pro .campo-input,
      .buscador-pro select,
      .buscador-pro button {
        flex: 1 1 100%;
        max-width: 100%;
        justify-content: center;
      }
    }


/*!-- ===== BANNER: Crea tu cuenta / Acceder (arriba del slider) ===== --**/

    .cta-cuenta {
        background: linear-gradient(90deg, #6b1021, #8f1d2f, #b12d25);
        border-radius: 12px;
        padding: 10px 22px;
        margin: 12px auto 16px;
        max-width: 1300px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        flex-wrap: wrap;
        box-shadow: 0 4px 14px rgba(0,0,0,.10);
      }
      .cta-cuenta-texto h3 {
        color: #fff;
        font-weight: 800;
        font-size: 14.5px;
        margin: 0 0 1px;
      }
      .cta-cuenta-texto p {
        color: #f0d5d5;
        font-size: 11.5px;
        margin: 0;
      }
      .cta-cuenta-boton {
        background: linear-gradient(45deg, #ff7a18, #ffb347);
        color: #fff;
        text-decoration: none;
        font-weight: 700;
        font-size: 15px;
        padding: 7px 18px;
        border-radius: 999px;
        white-space: nowrap;
        box-shadow: 0 3px 10px rgba(255,122,24,.35);
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: 0.2s;
      }
      .cta-cuenta-boton:hover {
        color: #fff;
        opacity: .92;
        transform: translateY(-1px);
      }
      @media (max-width: 576px) {
        .cta-cuenta {
          flex-direction: column;
          text-align: center;
          padding: 14px 16px;
        }
        .cta-cuenta-boton {
          width: 100%;
          justify-content: center;
        }
      }




    /* ===== PRESENTACIÓN: ¿QUÉ ES SINTECZATE? (lo primero que ve el visitante) ===== */
    .intro-hero {
      position: relative;
      overflow: hidden;
      max-width: 1300px;
      margin: 10px auto 12px;
      padding: 18px 20px;
      border-radius: 16px;
      color: #fff;
      background: linear-gradient(120deg, #6b1021 0%, #8f1d2f 55%, #b12d25 100%);
      box-shadow: 0 6px 20px rgba(0, 0, 0, .14);
    }

    .intro-hero::before {
      content: "";
      position: absolute;
      right: -120px;
      top: -140px;
      width: 420px;
      height: 420px;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(255, 255, 255, .14), rgba(255, 255, 255, 0) 70%);
      pointer-events: none;
    }

    .intro-hero-grid {
      position: relative;
      display: grid;
      grid-template-columns: 1.15fr 1fr;
      gap: 28px;
      align-items: center;
    }

    .intro-eyebrow {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      font-size: 12.5px;
      font-weight: 700;
      letter-spacing: .6px;
      text-transform: uppercase;
      color: #ffd9a8;
      margin-bottom: 10px;
    }

    .intro-hero h1 {
      font-size: clamp(1.45rem, 2.6vw, 2.1rem);
      font-weight: 800;
      line-height: 1.15;
      margin: 0 0 12px;
    }

    .intro-hero .intro-sub {
      font-size: clamp(.95rem, 1.2vw, 1.05rem);
      line-height: 1.55;
      color: #f6e3e3;
      margin: 0 0 16px;
      max-width: 560px;
    }

    .intro-acciones {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      gap: 12px 18px;
    }

    .intro-btn {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: #fff;
      color: #6b1021;
      font-weight: 800;
      font-size: 15px;
      text-decoration: none;
      padding: 11px 24px;
      border-radius: 999px;
      box-shadow: 0 4px 14px rgba(0, 0, 0, .20);
      transition: .2s;
    }

    .intro-btn:hover {
      color: #6b1021;
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(0, 0, 0, .28);
    }

    .intro-link {
      color: #fff;
      font-size: 14px;
      font-weight: 600;
      text-decoration: none;
      opacity: .92;
    }

    .intro-link:hover {
      color: #fff;
      opacity: 1;
      text-decoration: underline;
    }

    .intro-cards {
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    .intro-card {
      display: flex;
      align-items: center;
      gap: 14px;
      padding: 12px 16px;
      border-radius: 14px;
      color: #fff;
      text-decoration: none;
      background: rgba(255, 255, 255, .10);
      border: 1px solid rgba(255, 255, 255, .20);
      -webkit-backdrop-filter: blur(6px);
      backdrop-filter: blur(6px);
      transition: .2s;
    }

    .intro-card:hover {
      color: #fff;
      background: rgba(255, 255, 255, .18);
      border-color: rgba(255, 255, 255, .40);
      transform: translateY(-2px);
    }

    .intro-card-icono {
      flex-shrink: 0;
      width: 44px;
      height: 44px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 21px;
      color: #ffd36b;
      background: rgba(255, 255, 255, .14);
    }

    .intro-card h3 {
      font-size: 15px;
      font-weight: 700;
      margin: 0 0 2px;
    }

    .intro-card p {
      font-size: 12.5px;
      line-height: 1.4;
      color: #f0d5d5;
      margin: 0;
    }

    .intro-card .intro-card-flecha {
      margin-left: auto;
      font-size: 16px;
      opacity: .7;
    }

    @media (max-width: 991px) {
      .intro-hero-grid {
        grid-template-columns: 1fr;
        gap: 20px;
      }
    }

    @media (max-width: 576px) {
      .intro-hero {
        padding: 22px 16px;
        border-radius: 14px;
      }
      .intro-hero .intro-sub { margin-bottom: 16px; }
      .intro-btn { width: 100%; justify-content: center; }
      .intro-acciones { flex-direction: column; align-items: stretch; text-align: center; }
      .intro-card { padding: 10px 12px; gap: 12px; }
      .intro-card-icono { width: 38px; height: 38px; font-size: 18px; }
    }


    /* ===== MITAD IZQUIERDA DEL ENCABEZADO: el slider va de fondo ===== */
    .intro-izq {
      position: relative;
      overflow: hidden;
      border-radius: 14px;
      padding: 22px 24px;
      background: #6b1021;
      box-shadow: 0 4px 16px rgba(0, 0, 0, .25);
    }
    .intro-bg {
      position: absolute;
      inset: 0;
      z-index: 0;
    }
    .intro-bg .carousel,
    .intro-bg .carousel-inner,
    .intro-bg .carousel-item {
      height: 100%;
    }
    .intro-bg img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
    }
    /* capa vino encima de las fotos para que el texto blanco se lea bien */
    .intro-izq::after {
      content: "";
      position: absolute;
      inset: 0;
      z-index: 1;
      background: linear-gradient(100deg, rgba(60, 6, 18, .72) 0%, rgba(107, 16, 33, .40) 50%, rgba(107, 16, 33, .04) 100%);
      pointer-events: none;
    }
    .intro-contenido {
      position: relative;
      z-index: 2;
    }
    .intro-contenido h1,
    .intro-contenido .intro-sub,
    .intro-contenido .intro-eyebrow,
    .intro-contenido .intro-link {
      text-shadow: 0 1px 3px rgba(0, 0, 0, .55), 0 2px 12px rgba(0, 0, 0, .45);
    }
    .intro-visitas {
      position: absolute;
      top: 12px;
      right: 12px;
      z-index: 3;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 4px 11px;
      border-radius: 999px;
      background: rgba(0, 0, 0, .38);
      border: 1px solid rgba(255, 255, 255, .25);
      -webkit-backdrop-filter: blur(4px);
      backdrop-filter: blur(4px);
      color: #fff;
      font-size: 12px;
      font-weight: 600;
      box-sizing: border-box;
    }
    .intro-visitas i { color: #ffcf33; }
    .intro-visitas strong { color: #ffcf33; font-size: 13px; }

    /* ===== CÓMO FUNCIONA: oculto hasta tocar el enlace ===== */
    .como-funciona-section { display: none; }
    .como-funciona-section.abierto {
      display: block;
      animation: cfAparece .3s ease-out;
    }
    @keyframes cfAparece {
      from { opacity: 0; transform: translateY(-8px); }
      to   { opacity: 1; transform: none; }
    }
    .intro-link[aria-expanded="true"] .bi-arrow-down-circle { transform: rotate(180deg); }
    .intro-link .bi-arrow-down-circle { display: inline-block; transition: .25s; }

    @media (max-width: 576px) {
      .intro-hero { margin: 8px 8px 10px; padding: 10px; }
      .intro-hero-grid { gap: 10px; }
      .intro-izq { padding: 16px 14px; }
      /* en celular la foto va más cubierta porque el texto ocupa todo el ancho */
      .intro-izq::after {
        background: linear-gradient(180deg, rgba(60, 6, 18, .68) 0%, rgba(107, 16, 33, .48) 100%);
      }
      .intro-sub-extra { display: none; }
      .intro-hero .intro-sub { font-size: .92rem; margin-bottom: 12px; }
      .intro-hero h1 { font-size: 1.35rem; margin-bottom: 8px; }
      .intro-acciones { gap: 8px 14px; }
      .intro-btn { padding: 10px 18px; font-size: 14px; }

      /* las 3 tarjetas pasan a 3 botones chicos en una sola fila */
      .intro-cards { flex-direction: row; gap: 8px; }
      .intro-cards .intro-card {
        flex: 1 1 0;
        flex-direction: column;
        justify-content: center;
        text-align: center;
        gap: 6px;
        padding: 10px 6px;
      }
      .intro-cards .intro-card p,
      .intro-cards .intro-card-flecha { display: none; }
      .intro-cards .intro-card h3 { font-size: 12px; line-height: 1.2; margin: 0; }
      .intro-cards .intro-card-icono { width: 34px; height: 34px; font-size: 17px; }
    }

    /* ===== BURBUJA FLOTANTE DE NEGOCIOS (celular/tablet; igual que en el dashboard) ===== */
    .burbuja-anuncio {
      position: fixed;
      left: 12px;
      bottom: calc(78px + env(safe-area-inset-bottom, 0px));
      max-width: 240px;
      background: linear-gradient(135deg, #6b1021, #b12d25);
      border-radius: 16px;
      box-shadow: 0 8px 30px rgba(107, 16, 33, .45);
      display: flex;
      align-items: center;
      gap: 10px;
      padding: 10px 14px 10px 10px;
      z-index: 9998;
      cursor: pointer;
      transform: translateY(20px) scale(.92);
      opacity: 0;
      transition: transform .3s ease, opacity .3s ease;
    }
    .burbuja-anuncio::after {
      content: '';
      position: absolute;
      bottom: -8px;
      left: 28px;
      width: 16px;
      height: 16px;
      background: #b12d25;
      clip-path: polygon(0 0, 100% 0, 50% 100%);
    }
    .burbuja-anuncio.mostrar { transform: none; opacity: 1; }
    .burbuja-anuncio img {
      width: 50px;
      height: 50px;
      border-radius: 10px;
      object-fit: cover;
      flex-shrink: 0;
      border: 2px solid rgba(255, 255, 255, .5);
      cursor: pointer;
    }
    .burbuja-anuncio-texto { display: flex; flex-direction: column; min-width: 0; }
    .burbuja-anuncio-texto small {
      font-size: 10px;
      color: #ffcf33;
      text-transform: uppercase;
      font-weight: 700;
      letter-spacing: .03em;
    }
    .burbuja-anuncio-texto strong {
      font-size: 13px;
      color: #fff;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      max-width: 150px;
    }
    .burbuja-anuncio-cerrar {
      position: absolute;
      top: -8px;
      right: -8px;
      width: 22px;
      height: 22px;
      border-radius: 50%;
      background: #fff;
      color: #6b1021;
      border: 2px solid #6b1021;
      font-size: 14px;
      line-height: 1;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      padding: 0;
    }
    @media (min-width: 1201px) { .burbuja-anuncio { display: none; } }

  </style>

</head>

<body>


<!-- MODAL: Ver anuncio en grande -->
  <div id="modalAnuncio" class="modal-anuncio-overlay">
    <button type="button" class="modal-anuncio-cerrar" onclick="cerrarModalAnuncio()">&times;</button>
    <div class="modal-anuncio-card">
      <div class="modal-anuncio-img-box">
        <img id="modalAnuncioImg" src="" alt="Anuncio">
      </div>
      <div class="modal-anuncio-body">
        <h6 id="modalAnuncioNombre"></h6>
        <p id="modalAnuncioEslogan"></p>
        <a href="#" id="modalAnuncioBotonLink" class="modal-anuncio-btn" style="display:none;" target="_blank">Visitar página</a>
        <a href="#" id="modalAnuncioBotonUbicacion" class="modal-anuncio-btn btn-ubicacion" style="display:none;" target="_blank">Cómo llegar</a>
      </div>
    </div>
  </div>

  <!-- ========================= -->
  <!-- ANUNCIOS (solo columna derecha) -->
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
                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}" data-bs-interval="6000">
                  <img src="{{ $img->imagen }}"
                    data-nombre="{{ $img->solicitud->nombre_negocio ?? '' }}"
                    data-eslogan="{{ $img->eslogan }}"
                    data-link="{{ $img->link_externo }}"
                    data-ubicacion="{{ $img->link_ubicacion }}">
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
            <i class="icon-telephone"></i> Línea directa: <strong>2331014306</strong>
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

    <!--=====================================
    PRESENTACIÓN: qué es SINTECZATE y para quién es
======================================-->
    <section class="intro-hero" aria-labelledby="introTitulo">
      <div class="intro-hero-grid">

        <div class="intro-izq">

          <div class="intro-visitas" title="Visitas a la página">
            <i class="bi bi-eye-fill"></i>
            <span><strong>{{ number_format($totalVisitas) }}</strong> visitas</span>
          </div>

          <!-- Slider de fondo -->
          <div class="intro-bg" aria-hidden="true">
            <div id="introBgCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
              <div class="carousel-inner">
                <div class="carousel-item active" data-bs-interval="4000">
                  <img src="{{ asset('img/slider/horizontal/img1.png') }}" alt="">
                </div>
                <div class="carousel-item" data-bs-interval="4000">
                  <img src="{{ asset('img/slider/horizontal/img2.png') }}" alt="">
                </div>
                <div class="carousel-item" data-bs-interval="4000">
                  <img src="{{ asset('img/slider/horizontal/img3.png') }}" alt="">
                </div>
                <div class="carousel-item" data-bs-interval="4000">
                  <img src="{{ asset('img/slider/horizontal/img4.png') }}" alt="">
                </div>
              </div>
            </div>
          </div>

          <div class="intro-contenido">
          <span class="intro-eyebrow"><i class="bi bi-geo-alt-fill"></i> Zacapoaxtla, Puebla</span>
          <h1 id="introTitulo">Servicios y empleos de tu comunidad, en un solo lugar</h1>
          <p class="intro-sub">
            ¡SINTECZATE! es una plataforma digital gratuita que conecta a las personas
            de Zacapoaxtla y sus alrededores.
            <span class="intro-sub-extra">Aquí puedes ofrecer y encontrar servicios
            y oficios, consultar oportunidades de empleo, publicar vacantes y postularte
            a ellas. Explora opciones, conoce las calificaciones de otros usuarios y
            contacta directamente para generar nuevas oportunidades.</span>
         </p>
          <div class="intro-acciones">
            <a href="/acceso?modo=registro" class="intro-btn"><i class="bi bi-person-plus-fill"></i> Crear mi cuenta gratis</a>
            <a href="/acceso?modo=login" class="intro-link">¿Ya tienes cuenta? Inicia sesión</a>
            <a href="#comoFunciona" class="intro-link" id="btnComoFunciona" role="button" aria-expanded="false" aria-controls="comoFunciona"><i class="bi bi-arrow-down-circle"></i> ¿Cómo funciona?</a>
          </div>
          </div>
        </div>

        <div class="intro-cards">
          <a href="#listProducts" class="intro-card">
            <span class="intro-card-icono"><i class="bi bi-search"></i></span>
            <div>
              <h3>Busco un servicio</h3>
              <p>Encuentra a la persona que necesitas, cerca de tu comunidad.</p>
            </div>
            <i class="bi bi-chevron-right intro-card-flecha"></i>
          </a>
          <a href="#listVacantes" class="intro-card">
            <span class="intro-card-icono"><i class="bi bi-briefcase-fill"></i></span>
            <div>
              <h3>Busco trabajo</h3>
              <p>Revisa las vacantes publicadas y postúlate.</p>
            </div>
            <i class="bi bi-chevron-right intro-card-flecha"></i>
          </a>
          <a href="/acceso?modo=registro" class="intro-card">
            <span class="intro-card-icono"><i class="bi bi-megaphone-fill"></i></span>
            <div>
              <h3>Ofrezco servicios o contrato</h3>
              <p>Crea tu cuenta y publica tu servicio o una vacante.</p>
            </div>
            <i class="bi bi-chevron-right intro-card-flecha"></i>
          </a>
        </div>

      </div>
    </section>

    <!--=====================================
    CÓMO FUNCIONA
======================================-->
    <div class="como-funciona-section" id="comoFunciona">
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



    <!-- ANUNCIOS EN CELULAR: ya no hay franja fija; salen como burbuja flotante (ver script al final) -->
    @php
      $imgsDerecha = $anunciosDerecha->flatMap(fn($a) => $a->imagenes);
      $anunciosBurbuja = [];
      foreach ($imgsDerecha as $im) {
        $anunciosBurbuja[] = [
          'imagen'    => $im->imagen,
          'nombre'    => optional($im->solicitud)->nombre_negocio ?? '',
          'eslogan'   => $im->eslogan,
          'link'      => $im->link_externo,
          'ubicacion' => $im->link_ubicacion,
        ];
      }
    @endphp

    <!-- Ancla para que el buscador te traiga directo aquí -->
    <div id="resultados"></div>

    <!--=====================================
    SERVICIOS DESTACADOS (reales, de toda la plataforma)
======================================-->
    <main>
      <section class="products" id="listProducts">
        <h2>Servicios Disponibles</h2>

        <form action="/#resultados" method="GET" class="buscador-pro">
          <div class="campo-input">
            <i class="bi bi-search"></i>
            <input type="text" name="q" value="{{ $q }}" placeholder="¿Qué servicio buscas?">
          </div>

          <select name="categoria_id">
            <option value="">Todas las categorías</option>
            @foreach ($categorias as $cat)
              <option value="{{ $cat->id }}" {{ (string) $categoriaId === (string) $cat->id ? 'selected' : '' }}>
                {{ $cat->nombre }} ({{ $cat->servicios_count }})
              </option>
            @endforeach
          </select>

          <select name="localidad_id">
            <option value="">Toda Zacapoaxtla</option>
            @foreach ($localidades as $loc)
              <option value="{{ $loc->id }}" {{ (string) $localidadId === (string) $loc->id ? 'selected' : '' }}>
                {{ $loc->nombre }}
              </option>
            @endforeach
          </select>

          <button type="submit"><i class="bi bi-search"></i> Buscar</button>
        </form>
        <p class="buscador-pro-nota">Busca por nombre, y combina con categoría y comunidad</p>

        @if ($q || $categoriaId || $localidadId)
          <p class="buscador-pro-resultados">
            Resultados
            @if ($q) para "<strong>{{ $q }}</strong>" @endif
            @if ($categoriaId) en <strong>{{ $categorias->firstWhere('id', $categoriaId)->nombre ?? '' }}</strong> @endif
            @if ($localidadId) en <strong>{{ $localidades->firstWhere('id', $localidadId)->nombre ?? '' }}</strong> @endif
            &nbsp;·&nbsp; <a href="/">Quitar filtros</a>
          </p>
        @endif

        <div class="products-grid container">

          @forelse ($servicios as $s)
            <div class="product" data-id="{{ $s->id }}" data-titulo="{{ $s->titulo }}">
              <div class="tc-foto">
                <img src="{{ $s->imagen ?? asset('img/services/plomero.jpg') }}" alt="{{ $s->titulo }}" loading="lazy">
                <span class="tc-estado"><i class="bi bi-check-circle-fill"></i> Disponible</span>
              </div>

              <div class="product-info">
                <h4 class="tc-titulo"><a href="/servicio/{{ $s->id }}" class="tc-link">{{ $s->titulo }}</a>
                  @if ($s->usuario && $s->usuario->verificacion_estado === 'aprobado')
                    <span title="Identidad verificada" style="background:#dcfce7; color:#16a34a; padding:1px 8px; border-radius:20px; font-size:.65rem; font-weight:700; display:table; margin:6px 0 0 0; white-space:nowrap;">
                      <i class="bi bi-patch-check-fill"></i> Verificado
                    </span>
                  @endif
                </h4>

                @if (trim((string) $s->ubicacion) !== '')
                  <p class="tc-lugar" title="{{ $s->ubicacion }}"><i class="bi bi-geo-alt-fill"></i> <span>{{ $s->ubicacion }}</span></p>
                @endif

                <p class="product-text tc-desc">
                  {{ \Illuminate\Support\Str::limit($s->descripcion, 140) }}
                </p>

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

                <div class="tc-pie">
                  <div class="tc-precio">
                    <small>Desde</small>
                    <strong>${{ number_format($s->precio, 0) }} <em>MXN</em></strong>
                  </div>

                  <div class="product-acciones">
                    <a href="/servicio/{{ $s->id }}" class="tc-contratame"><i class="bi bi-briefcase-fill"></i> ¡Contrátame ahora!</a>
                    <button type="button" class="btn-fav" title="Agregar a favoritos" aria-label="Agregar a favoritos" aria-pressed="false">
                      <i class="bi bi-heart"></i>
                    </button>
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

    <!--=====================================
    VACANTES DISPONIBLES (empleos publicados por empleadores)
======================================-->
    <section class="products vacantes-section" id="listVacantes">
      <h2>Vacantes Disponibles</h2>

      <form action="/#listVacantes" method="GET" class="buscador-pro">
        <div class="campo-input">
          <i class="bi bi-search"></i>
          <input type="text" name="q_vacante" value="{{ $qVacante }}" placeholder="¿Qué trabajo buscas?">
        </div>

        <select name="localidad_vacante_id">
          <option value="">Toda Zacapoaxtla</option>
          @foreach ($localidades as $loc)
            <option value="{{ $loc->id }}" {{ (string) $localidadVacanteId === (string) $loc->id ? 'selected' : '' }}>
              {{ $loc->nombre }}
            </option>
          @endforeach
        </select>

        <button type="submit"><i class="bi bi-search"></i> Buscar</button>
      </form>
      <p class="buscador-pro-nota">Busca por tipo de trabajo, y combina con comunidad</p>

      @if ($qVacante || $localidadVacanteId)
        <p class="buscador-pro-resultados">
          Resultados
          @if ($qVacante) para "<strong>{{ $qVacante }}</strong>" @endif
          @if ($localidadVacanteId) en <strong>{{ $localidades->firstWhere('id', $localidadVacanteId)->nombre ?? '' }}</strong> @endif
          &nbsp;·&nbsp; <a href="/#listVacantes">Quitar filtros</a>
        </p>
      @endif

      <div class="container">
        <div class="row g-3">
          @forelse ($vacantes as $v)
            <div class="col-12 col-md-6">
              <div class="vacante-card">
                <img src="{{ $v->imagen ?? asset('img/services/plomero.jpg') }}" alt="{{ $v->titulo }}">
                <div class="vacante-info">
                  <div>
                    <h5>{{ $v->titulo }}</h5>
                    <div class="vacante-meta">
                      <i class="bi bi-person"></i> {{ $v->publicante ?? 'Empleador' }}
                      &nbsp;·&nbsp; <i class="bi bi-geo-alt"></i> {{ $v->ubicacion }}
                    </div>
                    @if ($v->empleador && $v->empleador->verificacion_estado === 'aprobado')
                      <span title="Identidad verificada" style="background:#dcfce7; color:#16a34a; padding:1px 8px; border-radius:20px; font-size:.7rem; font-weight:700; display:table; margin:-2px 0 8px 0; white-space:nowrap;"><i class="bi bi-patch-check-fill"></i> Usuario verificado</span>
                    @endif
                    <p class="vacante-desc">{{ \Illuminate\Support\Str::limit($v->descripcion, 100) }}</p>
                    <div class="vacante-badges">
                      <span class="badge-salario"><i class="bi bi-currency-dollar"></i> {{ $v->salario }}</span>
                      <span class="badge-contrato"><i class="bi bi-file-earmark-text"></i> {{ $v->contrato }}</span>
                    </div>
                  </div>
                  <a href="/vacante/{{ $v->id }}" class="btn-add" style="text-decoration:none; text-align:center; align-self:flex-start;">Ver más / Postularme</a>
                </div>
              </div>
            </div>
          @empty
            <p class="has-text-grey text-center" style="grid-column: 1 / -1;">Todavía no hay vacantes publicadas. ¡Sé el primero en <a href="/acceso">publicar una</a>!</p>
          @endforelse
        </div>
      </div>
    </section>

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


    <!-- MENÚ INFERIOR SOLO PARA CELULAR -->
    <div class="menu-movil d-lg-none">

      <a class="item" data-bs-toggle="offcanvas" href="#panelCategorias">
        <i class="bi bi-grid"></i>
        <span>Categorías</span>
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
              <span class="badge rounded-pill bg-danger">{{ $cat->servicios_count }}</span>
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
                Conectamos personas que ofrecen y buscan servicios de forma rápida y segura aqui en Zacapoaxtla.
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
              <p><i class="bi bi-telephone"></i> 2331442834</p>
              <p><i class="bi bi-geo-alt"></i> Zacapoaxtla, Puebla</p>
              <p><i class="bi bi-envelope"></i> sinteczate.oficial@gmail.com</p>

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
          @include('partials.anuncio-negocio')
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

            const nombreEl = document.getElementById('modalAnuncioNombre');
            const nombre = this.dataset.nombre || '';
            nombreEl.textContent = nombre;
            nombreEl.style.display = nombre ? 'block' : 'none';

            const esloganEl = document.getElementById('modalAnuncioEslogan');
            const eslogan = this.dataset.eslogan || '';
            esloganEl.textContent = eslogan;
            esloganEl.style.display = eslogan ? 'block' : 'none';

            const botonLink = document.getElementById('modalAnuncioBotonLink');
            if (this.dataset.link) {
              botonLink.href = this.dataset.link;
              botonLink.style.display = 'inline-flex';
            } else {
              botonLink.style.display = 'none';
            }

            const botonUbicacion = document.getElementById('modalAnuncioBotonUbicacion');
            if (this.dataset.ubicacion) {
              botonUbicacion.href = this.dataset.ubicacion;
              botonUbicacion.style.display = 'inline-flex';
            } else {
              botonUbicacion.style.display = 'none';
            }

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


    <script>
      // ===== "¿Cómo funciona?": oculto hasta que se toca el enlace =====
      document.addEventListener('DOMContentLoaded', function () {
        const seccion = document.getElementById('comoFunciona');
        if (!seccion) return;
        const enlaces = document.querySelectorAll('a[href="#comoFunciona"]');

        function alternar(abrir) {
          seccion.classList.toggle('abierto', abrir);
          enlaces.forEach(function (a) { a.setAttribute('aria-expanded', abrir ? 'true' : 'false'); });
          if (abrir) seccion.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        enlaces.forEach(function (a) {
          a.addEventListener('click', function (e) {
            e.preventDefault();
            alternar(!seccion.classList.contains('abierto'));
          });
        });

        // siempre empieza oculto; si la dirección trae #comoFunciona, se limpia
        if (location.hash === '#comoFunciona' && window.history && history.replaceState) {
          history.replaceState(null, '', location.pathname + location.search);
        }
      });
    </script>


    <script>
      // ===== Burbuja flotante de negocios (celular/tablet): aparece de rato en rato =====
      document.addEventListener('DOMContentLoaded', function () {
        const anuncios = @json($anunciosBurbuja);

        if (!anuncios.length) return;
        if (!window.matchMedia('(max-width: 1200px)').matches) return;

        let indice = 0;
        try { indice = parseInt(localStorage.getItem('burbujaAnuncioIndice') || '0', 10) || 0; } catch (e) {}

        function abrirModal(a) {
          const modal = document.getElementById('modalAnuncio');
          if (!modal) return;
          document.getElementById('modalAnuncioImg').src = a.imagen;
          const n = document.getElementById('modalAnuncioNombre');
          n.textContent = a.nombre || ''; n.style.display = a.nombre ? 'block' : 'none';
          const e = document.getElementById('modalAnuncioEslogan');
          e.textContent = a.eslogan || ''; e.style.display = a.eslogan ? 'block' : 'none';
          const l = document.getElementById('modalAnuncioBotonLink');
          if (a.link) { l.href = a.link; l.style.display = 'inline-flex'; } else { l.style.display = 'none'; }
          const u = document.getElementById('modalAnuncioBotonUbicacion');
          if (a.ubicacion) { u.href = a.ubicacion; u.style.display = 'inline-flex'; } else { u.style.display = 'none'; }
          modal.classList.add('activo');
        }

        function mostrarBurbuja() {
          if (document.getElementById('burbujaAnuncio')) return;
          // si el visitante ya tiene el anuncio ampliado abierto, no se encima otra burbuja
          const modal = document.getElementById('modalAnuncio');
          if (modal && modal.classList.contains('activo')) return;

          const a = anuncios[indice % anuncios.length];
          indice++;
          try { localStorage.setItem('burbujaAnuncioIndice', indice); } catch (e) {}

          const b = document.createElement('div');
          b.id = 'burbujaAnuncio';
          b.className = 'burbuja-anuncio';

          const cerrar = document.createElement('button');
          cerrar.type = 'button'; cerrar.className = 'burbuja-anuncio-cerrar';
          cerrar.setAttribute('aria-label', 'Cerrar'); cerrar.innerHTML = '&times;';

          const img = document.createElement('img');
          img.src = a.imagen; img.alt = 'Negocio destacado';

          const txt = document.createElement('div');
          txt.className = 'burbuja-anuncio-texto';
          const small = document.createElement('small'); small.textContent = 'Negocio destacado';
          const strong = document.createElement('strong'); strong.textContent = a.nombre || 'Conoce este negocio';
          txt.appendChild(small); txt.appendChild(strong);

          b.appendChild(cerrar); b.appendChild(img); b.appendChild(txt);
          document.body.appendChild(b);
          requestAnimationFrame(function () { b.classList.add('mostrar'); });

          function quitar() { b.classList.remove('mostrar'); setTimeout(function () { b.remove(); }, 300); }
          const auto = setTimeout(quitar, 15000);

          cerrar.addEventListener('click', function (ev) { ev.stopPropagation(); clearTimeout(auto); quitar(); });
          b.addEventListener('click', function () { clearTimeout(auto); quitar(); abrirModal(a); });
        }

        // la primera a los 6 s de entrar; luego cada 60 s
        setTimeout(mostrarBurbuja, 6000);
        setInterval(mostrarBurbuja, 60000);
      });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
      crossorigin="anonymous"></script>

    <script src="{{ asset('assets/usuario/js/asistente-voz.js') }}" defer></script>
</body>

</html>
