<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Servicio al cliente</title>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

  <!-- Tus estilos -->
  <link rel="stylesheet" href="{{ asset('css/style.css') }}">

  <link rel="icon" href="{{ asset('img/template/call.png') }}" type="image/png" sizes="192x192">

  <style>
    .nav-link.active { font-weight: bold; }
    .btn.bg-custom:hover { filter: brightness(90%); }
    .service-img { max-width: 100%; border-radius: 8px; margin-top: 20px; }
  </style>

<style>

      /* ===== CONTENIDO ===== */
.contenido{
    margin-left:230px;
    margin-right:230px;
    padding:0;
    background:#fff;
    min-height:100vh;
}

/* ===== COLUMNAS DE ANUNCIOS ===== */
.anuncio-izq,
.anuncio-der{
    position:fixed;
    top:10px;
    width:210px;
    display:flex;
    flex-direction:column;
    gap:15px;
    z-index:999;
}

.anuncio-izq{
    left:10px;
}

.anuncio-der{
    right:10px;
}

/* ===== TARJETAS ===== */
.ad-box{
    height:210px;
    border-radius:15px;
    overflow:hidden;
    background:#fff;
    box-shadow:0 4px 15px rgba(0,0,0,.15);
}

.ad-box img{
    width:100%;
     height:210px;
    object-fit:cover;
    cursor:zoom-in;
}

/* ===== BANNER INFERIOR ===== */
.banner-publicidad{
    margin-top:40px;
    margin-bottom:20px;
}

.banner-publicidad img{
    width:100%;
    height:140px;
    object-fit:cover;
    border-radius:15px;
}

/* ===== RESPONSIVE ===== */
@media(max-width:1200px){

    .anuncio-izq,
    .anuncio-der{
        display:none;
    }

    .contenido{
        margin-left:0;
        margin-right:0;
    }
}





/* Banner inferior */
.banner-negocio{
    width:100%;
    background:linear-gradient(90deg,#6b1021,#8f1d2f,#b12d25);
    border-radius:12px;

    padding:8px 25px; /* menos alto */

    display:flex;
    align-items:center;
    justify-content:space-between;

    color:white;
    overflow:hidden;
    box-shadow:0 4px 10px rgba(0,0,0,.15);
}

/* Texto izquierdo */
.banner-texto h2{
    font-size:24px;
    margin:0;
}

.banner-texto p{
    margin:0;
    font-size:14px;
}

/* Centro */
.banner-destaca h2{
    margin:0;
    font-size:22px;
}

.banner-destaca h1{
    margin:0;
    font-size:32px;
    color:#ffcf33;
    font-weight:bold;
}

/* Icono tienda */
.banner-icono img{
    width:70px;
}

/* Botón */
.btn-anunciar{
    background:#ffc107;
    color:#000;
    text-decoration:none;
    padding:10px 18px;
    border-radius:8px;
    font-weight:bold;
    display:inline-block;
}

.banner-boton small{
    display:block;
    text-align:center;
    margin-top:3px;
}

/* Persona */
.banner-persona img{
    height:85px; /* aquí reduces mucho la altura */
    display:block;
}


.banner-persona{
    height:90px;
}

.banner-persona img{
    height:90px;
    width:auto;
    object-fit:cover;
    border-radius:999px;

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

.ad-titulo{
    background:#fff;
    border-radius:12px;
    padding:15px 10px;
    text-align:center;
    box-shadow:0 4px 15px rgba(0,0,0,.10);
}

.ad-titulo h5{
    margin:0;
    font-size:20px;
    font-weight:800;
    color:#333;
}

.linea-titulo{
    display:flex;
    align-items:center;
    justify-content:center;
    gap:8px;
    margin-top:5px;
}

.linea-titulo span{
    width:40px;
    height:2px;
    background:#f39c12;
}

.linea-titulo small{
    font-size:13px;
    color:#555;
    font-weight:600;
}

/* ===== POR QUÉ CONFIAR ===== */
.confianza-section {
  background: #fdf6ee;
  padding: 40px 0;
}

.confianza-titulo {
  text-align: center;
  font-weight: 800;
  font-size: 26px;
  color: #1a1a2e;
  margin-bottom: 4px;
}

.confianza-subtitulo {
  text-align: center;
  color: #888;
  font-size: 14px;
  margin-bottom: 30px;
}

.confianza-card {
  background: #fff;
  border-radius: 14px;
  padding: 22px 16px;
  text-align: center;
  height: 100%;
  box-shadow: 0 4px 15px rgba(0,0,0,.06);
  border: 1px solid #f0f0f0;
  transition: 0.25s;
}

.confianza-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 24px rgba(0,0,0,.10);
  border-color: #ffd8ae;
}

.confianza-icono {
  width: 52px;
  height: 52px;
  border-radius: 50%;
  background: linear-gradient(135deg, #ff7a18, #ffb347);
  color: #fff;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 22px;
  margin: 0 auto 12px;
}

.confianza-card h6 {
  font-weight: 700;
  font-size: 14px;
  color: #1a1a2e;
  margin-bottom: 6px;
}

.confianza-card p {
  font-size: 12px;
  color: #777;
  margin: 0;
  line-height: 1.5;
}

/* ===== MANUAL DE USUARIO ===== */
.manual-section {
  padding: 40px 0;
}

.manual-tabs.nav-pills .nav-link {
  color: #6b1021 !important;
  font-weight: 600;
  font-size: 14px;
  padding: 10px 22px;
  border-radius: 30px;
  margin: 0 6px;
  border: 1.5px solid #ffd8ae;
  background: #fff !important;
}

.manual-tabs.nav-pills .nav-link.active {
  background: linear-gradient(45deg, #ff7a18, #ffb347) !important;
  color: #fff !important;
  border-color: transparent;
}

#manualTrabajador .accordion-button:not(.collapsed),
#manualEmpleador .accordion-button:not(.collapsed) {
  background: #fff7ef;
  color: #ff7a18;
}

.manual-registro {
  display: flex;
  align-items: center;
  gap: 18px;
  background: linear-gradient(135deg, #6b1021, #b12d25);
  color: #fff;
  border-radius: 14px;
  padding: 20px 24px;
  margin-bottom: 28px;
}

.manual-registro-icono {
  width: 56px;
  height: 56px;
  min-width: 56px;
  border-radius: 50%;
  background: rgba(255,255,255,0.15);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 26px;
}

.manual-registro h6 {
  font-weight: 800;
  font-size: 16px;
  margin-bottom: 4px;
}

.manual-registro p {
  font-size: 13px;
  margin: 0;
  opacity: 0.92;
  line-height: 1.6;
}

@media (max-width: 576px) {
  .manual-registro {
    flex-direction: column;
    text-align: center;
  }
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
<!-- ========================= -->    <div class="anuncio-izq">

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
    <!-- ANUNCIOS DERECHOS -->
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

<!-- HEADER -->
<div class="header__top">
  <div class="container d-flex justify-content-between align-items-center">
    <div class="header__left">
      <ul class="d-flex list-unstyled mb-0">
        <li class="me-3"><a href="#"><i class="fab fa-facebook-f"></i></a></li>
        <li class="me-3"><a href="#"><i class="fab fa-instagram"></i></a></li>
        <li class="me-3"><a href="#"><i class="fab fa-twitter"></i></a></li>
        <li><a href="#"><i class="fab fa-youtube"></i></a></li>
      </ul>
    </div>
    <div class="header__right d-flex align-items-center">
      <div class="me-3">
        <i class="bi bi-telephone"></i> Línea directa: <strong>2331014306</strong>
      </div>
    </div>
  </div>
</div>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-sm navbar-dark bg-custom">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">
      <img src="{{ asset('img/template/logo.png') }}" width="75">
      <span class="brand-text">¡SINTECZATE!</span>
    </a>
    <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#menu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="menu">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="/"><i class="bi bi-house-door me-1"></i> Inicio</a></li>
        <li class="nav-item"><a class="nav-link" href="/terminos"><i class="bi bi-file-text me-1"></i> Términos y Condiciones</a></li>
        <li class="nav-item"><a class="nav-link" href="/acerca-de"><i class="bi bi-info-circle me-1"></i> Acerca de</a></li>
        <li class="nav-item"><a class="nav-link" href="/acceso"><i class="bi bi-person-circle me-1"></i> Acceso</a></li>
        <li class="nav-item"><a class="nav-link active" href="/servicio-cliente"><i class="fas fa-headset me-1"></i> Servicio al cliente</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- ¿POR QUÉ CONFIAR EN NOSOTROS? -->
<div class="confianza-section">
  <div class="container">
    <h2 class="confianza-titulo">¿Por qué confiar en ¡SINTECZATE!?</h2>
    <p class="confianza-subtitulo">Nos tomamos en serio la seguridad de nuestra comunidad</p>

    <div class="row g-4">
      <div class="col-6 col-md-4 col-lg">
        <div class="confianza-card">
          <div class="confianza-icono"><i class="bi bi-patch-check-fill"></i></div>
          <h6>Identidad verificada</h6>
          <p>Revisamos INE y selfie antes de darle la insignia de "Verificado" a cada usuario.</p>
        </div>
      </div>
      <div class="col-6 col-md-4 col-lg">
        <div class="confianza-card">
          <div class="confianza-icono"><i class="bi bi-star-fill"></i></div>
          <h6>Calificaciones reales</h6>
          <p>Solo puede calificar quien de verdad contrató el servicio, con reseñas de la comunidad.</p>
        </div>
      </div>
      <div class="col-6 col-md-4 col-lg">
        <div class="confianza-card">
          <div class="confianza-icono"><i class="bi bi-shield-check"></i></div>
          <h6>Moderación activa</h6>
          <p>Comentarios y reportes son revisados por un administrador para mantener todo en orden.</p>
        </div>
      </div>
      <div class="col-6 col-md-4 col-lg">
        <div class="confianza-card">
          <div class="confianza-icono"><i class="bi bi-envelope-check"></i></div>
          <h6>Todo por correo</h6>
          <p>Confirmaciones de registro y recuperación de contraseña, directo a tu correo.</p>
        </div>
      </div>
      <div class="col-6 col-md-4 col-lg">
        <div class="confianza-card">
          <div class="confianza-icono"><i class="bi bi-graph-up"></i></div>
          <h6>Panel transparente</h6>
          <p>Un equipo administrativo supervisa la plataforma con estadísticas en tiempo real.</p>
        </div>
      </div>
    </div>
  </div>
</div>


<!-- MANUAL DE USUARIO -->
<section class="manual-section">
  <div class="container">
    <h2 class="confianza-titulo"><i class="bi bi-journal-text"></i> Manual de usuario</h2>
    <p class="confianza-subtitulo">Todo lo que necesitas saber para usar la plataforma</p>

    <!-- Paso compartido: registro -->
    <div class="manual-registro">
      <div class="manual-registro-icono"><i class="bi bi-person-plus-fill"></i></div>
      <div>
        <h6>Antes que nada: crea tu cuenta</h6>
        <p>
          Da clic en <strong>"Acceso"</strong> y regístrate con tu correo electrónico o con tu cuenta de Google — es gratis y toma menos de un minuto.
          Una sola cuenta te sirve para las dos cosas: <strong>ofrecer tus servicios</strong> (modo Trabajador) y <strong>contratar a alguien</strong> (modo Empleador).
          Puedes cambiar entre los dos modos cuando quieras con el interruptor que aparece en la barra lateral de tu panel.
        </p>
      </div>
    </div>

    <!-- Pestañas: Trabajador / Empleador -->
    <ul class="nav nav-pills justify-content-center mb-4 manual-tabs" id="manualTabs" role="tablist">
      <li class="nav-item" role="presentation">
        <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#manualTrabajador" type="button">
          <i class="bi bi-tools me-1"></i> Modo Trabajador
        </button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" data-bs-toggle="pill" data-bs-target="#manualEmpleador" type="button">
          <i class="bi bi-briefcase me-1"></i> Modo Empleador
        </button>
      </li>
    </ul>

    <div class="tab-content">

      <!-- ===== TAB: TRABAJADOR ===== -->
      <div class="tab-pane fade show active" id="manualTrabajador">
        <div class="accordion" id="manualAccTrabajador">

          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button" data-bs-toggle="collapse" data-bs-target="#mt1">
                <i class="bi bi-toggle-on me-2"></i> 1. Activa el Modo Trabajador
              </button>
            </h2>
            <div id="mt1" class="accordion-collapse collapse show" data-bs-parent="#manualAccTrabajador">
              <div class="accordion-body">
                Al entrar a tu panel verás un interruptor con dos opciones: <strong>TRABAJADOR</strong> y <strong>EMPLEADOR</strong>. Selecciona "Trabajador" para acceder a las herramientas de ofrecer tus servicios: publicar, ver empleos, solicitudes y tu perfil.
              </div>
            </div>
          </div>

          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#mt2">
                <i class="bi bi-patch-check me-2"></i> 2. Verifica tu identidad
              </button>
            </h2>
            <div id="mt2" class="accordion-collapse collapse" data-bs-parent="#manualAccTrabajador">
              <div class="accordion-body">
                En <strong>Perfil → Documentos</strong>, sube una foto de tu INE y una selfie. Un administrador los revisa y, si todo está en orden, te da la insignia de <strong>"Verificado"</strong>, que se muestra junto a tu nombre y genera más confianza con los clientes.
              </div>
            </div>
          </div>

          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#mt3">
                <i class="bi bi-square-half me-2"></i> 3. Publica tu servicio
              </button>
            </h2>
            <div id="mt3" class="accordion-collapse collapse" data-bs-parent="#manualAccTrabajador">
              <div class="accordion-body">
                Ve a <strong>"Publicar empleo"</strong> y llena los datos: título, categoría, descripción, precio y una foto representativa de tu trabajo. En cuanto lo publiques, aparecerá tanto en tu panel como en la página principal para que cualquier persona lo encuentre.
              </div>
            </div>
          </div>

          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#mt4">
                <i class="bi bi-briefcase me-2"></i> 4. Explora "Ver empleos"
              </button>
            </h2>
            <div id="mt4" class="accordion-collapse collapse" data-bs-parent="#manualAccTrabajador">
              <div class="accordion-body">
                Aquí ves todos los servicios publicados por otros usuarios de la comunidad — te sirve para ver qué tipo de trabajos hay, comparar precios, o simplemente inspirarte para tu propia publicación.
              </div>
            </div>
          </div>

          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#mt5">
                <i class="bi bi-inbox me-2"></i> 5. Atiende tus solicitudes
              </button>
            </h2>
            <div id="mt5" class="accordion-collapse collapse" data-bs-parent="#manualAccTrabajador">
              <div class="accordion-body">
                En <strong>"Solicitudes"</strong> verás quién quiere contratar tu servicio. <strong>Antes de aceptar</strong>, comunícate con esa persona para ponerse de acuerdo en fecha, horario y detalles, y confirmar que podrá asistir. Luego, acepta o rechaza la solicitud desde ahí mismo.
              </div>
            </div>
          </div>

          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#mt6">
                <i class="bi bi-star me-2"></i> 6. Recibe calificaciones
              </button>
            </h2>
            <div id="mt6" class="accordion-collapse collapse" data-bs-parent="#manualAccTrabajador">
              <div class="accordion-body">
                Cuando el trabajo se marque como finalizado, el cliente podrá calificarte con estrellas y dejar una reseña. Solo pueden calificar quienes de verdad contrataron el servicio, así que las calificaciones que recibas son siempre reales.
              </div>
            </div>
          </div>

        </div>
      </div>

      <!-- ===== TAB: EMPLEADOR ===== -->
      <div class="tab-pane fade" id="manualEmpleador">
        <div class="accordion" id="manualAccEmpleador">

          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button" data-bs-toggle="collapse" data-bs-target="#me1">
                <i class="bi bi-toggle-on me-2"></i> 1. Activa el Modo Empleador
              </button>
            </h2>
            <div id="me1" class="accordion-collapse collapse show" data-bs-parent="#manualAccEmpleador">
              <div class="accordion-body">
                Con el mismo interruptor de tu panel, cambia a <strong>"Empleador"</strong>. El menú lateral se actualiza para mostrarte las herramientas de contratación: buscar talento, mis vacantes, postulantes y publicar vacante.
              </div>
            </div>
          </div>

          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#me2">
                <i class="bi bi-search me-2"></i> 2. Busca talento
              </button>
            </h2>
            <div id="me2" class="accordion-collapse collapse" data-bs-parent="#manualAccEmpleador">
              <div class="accordion-body">
                En <strong>"Buscar trabajo"</strong> puedes filtrar por experiencia, tipo de contrato y tipo de pago, o buscar por palabra clave, para encontrar rápido a la persona con el perfil que necesitas.
              </div>
            </div>
          </div>

          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#me3">
                <i class="bi bi-plus-circle me-2"></i> 3. Publica una vacante
              </button>
            </h2>
            <div id="me3" class="accordion-collapse collapse" data-bs-parent="#manualAccEmpleador">
              <div class="accordion-body">
                En <strong>"Publicar vacante"</strong>, describe el puesto: título, número de trabajadores que necesitas, tipo de pago, experiencia requerida, fecha y duración del trabajo, y tus datos de contacto. Se publica al instante para que la gente se postule.
              </div>
            </div>
          </div>

          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#me4">
                <i class="bi bi-account-group me-2"></i> 4. Revisa tus postulantes
              </button>
            </h2>
            <div id="me4" class="accordion-collapse collapse" data-bs-parent="#manualAccEmpleador">
              <div class="accordion-body">
                En <strong>"Postulantes"</strong> verás a todas las personas que aplicaron a tus vacantes, con su mensaje de presentación. <strong>Comunícate con ellos antes de aceptar</strong>, para confirmar que están disponibles y ponerse de acuerdo en los detalles.
              </div>
            </div>
          </div>

          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#me5">
                <i class="bi bi-check2-circle me-2"></i> 5. Contrata o rechaza
              </button>
            </h2>
            <div id="me5" class="accordion-collapse collapse" data-bs-parent="#manualAccEmpleador">
              <div class="accordion-body">
                Desde la tarjeta de cada postulante puedes darle clic a "Contratar" o marcarlo como rechazado. Puedes gestionar y editar tus vacantes en cualquier momento desde <strong>"Mis vacantes"</strong>.
              </div>
            </div>
          </div>

          <div class="accordion-item">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#me6">
                <i class="bi bi-flag me-2"></i> 6. Reporta si algo no está bien
              </button>
            </h2>
            <div id="me6" class="accordion-collapse collapse" data-bs-parent="#manualAccEmpleador">
              <div class="accordion-body">
                Si ves información falsa, spam o algo sospechoso en un servicio o vacante, usa el botón "Reportar" en la publicación. Un administrador lo revisará.
              </div>
            </div>
          </div>

        </div>
      </div>

    </div>
  </div>
</section>

<!-- SERVICIO AL CLIENTE -->
<section class="container my-5">
  <div class="row g-4">

    <!-- FORMULARIO -->
    <div class="col-md-6">
      <div class="card shadow-sm">
        <div class="card-header bg-custom text-white">
          <h5 class="mb-0"><i class="fas fa-headset me-2"></i> Servicio al Cliente</h5>
        </div>
        <div class="card-body">
          <form>
            <div class="mb-3">
              <label class="form-label"><i class="fas fa-user me-1"></i> Nombre</label>
              <input type="text" class="form-control" placeholder="Tu nombre completo">
            </div>
            <div class="mb-3">
              <label class="form-label"><i class="fas fa-envelope me-1"></i> Correo</label>
              <input type="email" class="form-control" placeholder="ejemplo@correo.com">
            </div>
            <div class="mb-3">
              <label class="form-label"><i class="fas fa-comment-dots me-1"></i> Mensaje</label>
              <textarea class="form-control" rows="4" placeholder="Escribe tu consulta"></textarea>
            </div>
            <button class="btn bg-custom text-white w-100">
              <i class="fas fa-paper-plane me-1"></i> Enviar
            </button>
          </form>
        </div>
      </div>
    </div>

    <!-- FAQ + IMAGEN ABAJO -->
    <div class="col-md-6">
      <div class="card shadow-sm mb-3">
        <div class="card-header bg-custom text-white">
          <h5 class="mb-0"><i class="fas fa-question-circle me-2"></i> Preguntas Frecuentes</h5>
        </div>
        <div class="card-body">
          <div class="accordion" id="faq">
            <div class="accordion-item">
              <h2 class="accordion-header">
                <button class="accordion-button" data-bs-toggle="collapse" data-bs-target="#f1">
                  ¿Cómo recuperar mi contraseña?
                </button>
              </h2>
              <div id="f1" class="accordion-collapse collapse show">
                <div class="accordion-body">
                  Usa la opción <strong>"Olvidé mi contraseña"</strong> en la página de acceso.
                </div>
              </div>
            </div>
            <div class="accordion-item">
              <h2 class="accordion-header">
                <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#f2">
                  ¿Dónde ver mis documentos?
                </button>
              </h2>
              <div id="f2" class="accordion-collapse collapse">
                <div class="accordion-body">
                  Accede a la sección <strong>"Gestión de archivos"</strong> desde tu cuenta.
                </div>
              </div>
            </div>
            <div class="accordion-item">
              <h2 class="accordion-header">
                <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#f3">
                  ¿Cómo contactar soporte técnico?
                </button>
              </h2>
              <div id="f3" class="accordion-collapse collapse">
                <div class="accordion-body">
                  Puedes escribirnos desde el formulario o llamar a la línea directa <strong>2331014306</strong>.
                </div>
              </div>
            </div>
            <div class="accordion-item">
              <h2 class="accordion-header">
                <button class="accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#f4">
                  ¿Qué horarios de atención tienen?
                </button>
              </h2>
              <div id="f4" class="accordion-collapse collapse">
                <div class="accordion-body">
                  Nuestro horario de atención es de <strong>9:00 AM a 6:00 PM</strong>, de lunes a viernes.
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>



  </div>
</section>

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
        <div class="text-center text-light small">
          © 2026 ¡SINTECZATE! | Todos los derechos reservados
        </div>

      </div>

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


<!-- SCRIPT ACTIVO AUTOMÁTICO -->
<script>
  const current = window.location.pathname.split("/").pop();
  document.querySelectorAll(".nav-link").forEach(link => {
    if (link.getAttribute("href") === current) {
      link.classList.add("active");
    }
  });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

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

</body>
</html>
