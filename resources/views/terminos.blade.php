<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Terminos y condiciones</title>
    <!-- Font Awesome para iconos (alternativa a los iconos personalizados) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Estilos propios -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <!-- Para Android/Chrome -->
    <link rel="icon" href="{{ asset('img/template/terms.png') }}" type="image/png" sizes="192x192">

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

    <!--=====================================
    Header TOP (solo para escritorio)
======================================-->
<div class="header__top">
    <div class="container d-flex justify-content-between align-items-center">
        <!-- Social -->
        <div class="header__left justify-content-start">
            <ul class="d-flex list-unstyled mb-0">
                <li class="me-3"><a href="https://www.facebook.com/" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
                <li class="me-3"><a href="https://www.instagram.com/" target="_blank"><i class="fab fa-instagram"></i></a></li>
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
      <img src="{{ asset('img/template/logo.png') }}" alt="Logo" width="80" height="80" class="d-inline-block align-text-center">
      <span class="brand-text">¡SINTECZATE!</span>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>



   <!-- Menu navar top -->
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
  <a class="nav-link" href="/">
    <i class="bi bi-house-door me-1"></i> Inicio
  </a>
</li>

<li class="nav-item">
  <a class="nav-link active" href="/terminos">
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

      </ul>
    </div>
  </div>
</nav>

<!--=====================================
SECCIÓN TÉRMINOS Y CONDICIONES
======================================-->
<section class="terminos-section">

<div class="container">

    <div class="terminos-card">

        <!-- Título -->
        <div class="terminos-header">
            <i class="bi bi-shield-check"></i>
            <h1>Términos y Condiciones</h1>
            <p>Última actualización: 2026</p>
        </div>

        <!-- Introducción -->
        <div class="terminos-intro">
            Bienvenido a <strong>¡SINTECZATE!</strong>.

           Al utilizar esta plataforma aceptas cumplir con los siguientes términos y condiciones
           que regulan el uso del sistema. Estos términos tienen como objetivo establecer las reglas
          y responsabilidades tanto de los usuarios como de la plataforma, con el fin de garantizar
           un uso seguro, responsable y adecuado del servicio.

          El acceso y uso de este sitio web implica la aceptación plena de estos términos por parte
           del usuario. Si no estás de acuerdo con alguna de las condiciones establecidas,
           te recomendamos no utilizar la plataforma.

        </div>

        <!-- Sección -->
        <div class="terminos-item">
            <h4><i class="bi bi-person-circle"></i> Uso de la cuenta</h4>
            <p>
                Los usuarios deben proporcionar información verídica al momento
                de registrarse. Cada cuenta es personal y no debe compartirse
                con terceros.

                Se prohíbe el uso de la plataforma para realizar actividades ilegales,
                fraudulentas, ofensivas o que puedan afectar el funcionamiento del sistema
                o la experiencia de otros usuarios.
            </p>
        </div>

        <!-- Sección -->
        <div class="terminos-item">
            <h4><i class="bi bi-lock"></i> Protección de datos</h4>
            <p>
                Los datos personales proporcionados serán utilizados únicamente
                para el funcionamiento de la plataforma y no serán compartidos
                sin autorización del usuario.
            </p>
        </div>

        <!-- Sección -->
        <div class="terminos-item">
            <h4><i class="bi bi-exclamation-triangle"></i> Responsabilidades</h4>
            <p>
                El usuario se compromete a utilizar la plataforma de manera
                responsable y conforme a las leyes aplicables, de lo contrario se le
                cancelara la cuenta definitivamente y este no tendra acceso.

            </p>

        </div>

        <!-- Sección -->
        <div class="terminos-item">
            <h4><i class="bi bi-arrow-repeat"></i> Cambios en los términos</h4>
            <p>
                ¡SINTECZATE! se reserva el derecho de modificar estos términos en
                cualquier momento para mejorar el servicio.

                Si tienes dudas, comentarios o necesitas más información sobre estos
                 términos y condiciones, puedes comunicarte con nosotros a través de
                  los medios de contacto disponibles en la plataforma.
            </p>
        </div>

        <!-- Botón -->
        <div class="terminos-footer">
            <a href="/acceso" class="btn-terminos">
                Aceptar y continuar
            </a>
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
          <li><a href="/servicio-cliente">Servicio al cliente</a></li>        </ul>
      </div>

      <!-- Contacto -->
      <div class="col-md-4 text-center text-md-end">
        <h5 class="text-white mb-3">Contacto</h5>
        <p><i class="bi bi-telephone"></i> 2331014306</p>
        <p><i class="bi bi-geo-alt"></i> Teziutlán, Puebla</p>
        <p><i class="bi bi-envelope"></i> sinteczate@gmail.com</p>
      </div>

    </div>

    <!-- Línea -->
    <hr class="border-light mt-4">

    <!-- Copy -->
    <div class="text-center text-light small">
      © 2026 SINTECZATE | Todos los derechos reservados
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
            <h1>SINTECZATE</h1>
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



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

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
