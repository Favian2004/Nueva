<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Acceso</title>
    <!-- Font Awesome para iconos (alternativa a los iconos personalizados) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Estilos propios -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <!-- Para Android/Chrome -->
    <link rel="icon" href="{{ asset('img/template/user.png') }}" type="image/png" sizes="192x192">

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
        /* (por si más adelante agregas el banner también en esta página) */
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
                        <li class="me-3"><a href="https://www.facebook.com/" target="_blank"><i
                                    class="fab fa-facebook-f"></i></a></li>
                        <li class="me-3"><a href="https://www.instagram.com/" target="_blank"><i
                                    class="fab fa-instagram"></i></a></li>
                        <li class="me-3"><a href="https://twitter.com/" target="_blank"><i
                                    class="fab fa-twitter"></i></a></li>
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


        <!-- Barra de navegación Menu1 (IGUAL A TU PLANTILLA) -->
        <nav class="navbar navbar-expand-sm navbar-dark bg-custom">
            <div class="container-fluid">
                <a class="navbar-brand" href="/">
                    <img src="{{ asset('img/template/logo.png') }}" alt="Logo" width="75" height="75"
                        class="d-inline-block align-text-center">
                    <span class="brand-text">¡SINTECZATE!</span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>



                <!-- Menu navar top (IGUAL A TU PLANTILLA) -->
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="/">
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
                            <a class="nav-link active" href="/acceso">
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

        <!-- Página de Autenticación (Login/Register) - SOLO ESTO SE AGREGA -->
        <div class="login-container">
            <div class="auth-card">

                @if (session('error'))
                  <div style="background:#fdeaea; color:#c0392b; padding:10px 14px; border-radius:8px; margin-bottom:12px; font-size:14px;">
                    {{ session('error') }}
                  </div>
                @endif

                @if (session('status'))
                  <div style="background:#dcfce7; color:#16a34a; padding:10px 14px; border-radius:8px; margin-bottom:12px; font-size:14px;">
                    {{ session('status') }}
                  </div>
                @endif

                <!-- Pestañas -->
                <div class="auth-tabs">
                    <button class="auth-tab active" id="loginTab" onclick="showSection('login')">Acceso</button>
                    <button class="auth-tab" id="registerTab" onclick="showSection('register')">Registro</button>
                </div>

                <div class="auth-card">
                    <!-- SECCIÓN LOGIN -->
                    <div id="loginSection" class="auth-section active">
                        <h3 class="text-center mb-4" style="color: #ffffff;">¿Ya tienes una cuenta?</h3>

                        <form action="/login" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label class="form-label">Usuario o correo electronico</label>
                                <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="ejemplo@correo.com" required>
                            </div>

                            <div class="mb-3">

                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label for="password" class="form-label">Contraseña</label>
                                </div>

                                <div class="position-relative">
                                    <input type="password" id="password" name="password" class="form-control pe-5"
                                        placeholder="••••••••" required>

                                    <span class="toggle-password" onclick="verPassword('password', this)">
                                        <i class="fa-solid fa-eye"></i>
                                    </span>
                                </div>

                                <div class="d-flex justify-content-end mt-2">
                                    <a href="#" class="forgot-link" data-bs-toggle="modal" data-bs-target="#modalForgotPassword">
                                        ¿Olvidé mi contraseña?
                                    </a>
                                </div>

                            </div>


                            <button type="submit" class="btn-auth">
                                <i class="bi bi-box-arrow-in-right me-3"></i>Iniciar sesión
                            </button>
                        </form>
                    </div>





                    <!-- SECCIÓN REGISTER -->
                    <div id="registerSection" class="auth-section">
                        <h3 class="text-center mb-4" style="color: #ffffff;">¡¡¡Crear cuenta!!!</h3>

                        @if ($errors->any() && old('nombre') !== null)
                          <div style="background:#fdeaea; color:#c0392b; padding:10px 14px; border-radius:8px; margin-bottom:12px; font-size:14px;">
                            @foreach ($errors->all() as $error)
                              <div>{{ $error }}</div>
                            @endforeach
                          </div>
                        @endif

                        <form action="/register" method="POST">
                            @csrf
                            <div class="name-row mb-3">
                                <div>
                                    <label class="form-label">Nombre</label>
                                    <input type="text" id="regNombre" value="{{ old('nombre') }}" class="form-control" placeholder="Juan">
                                </div>
                                <div>
                                    <label class="form-label">Apellidos</label>
                                    <input type="text" id="regApellidos" class="form-control" placeholder="Pérez">
                                </div>
                            </div>
                            <input type="hidden" name="nombre" id="regNombreCompleto">

                            <div class="mb-3">
                                <label class="form-label">Usuario</label>
                                <input type="text" class="form-control" placeholder="juanito2004">
                                <small style="color:#ddd;">(Opcional por ahora, no se usa todavía)</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Dirección de correo electrónico</label>
                                <input type="email" name="email" value="{{ old('email') }}" class="form-control" placeholder="ejemplo@gmail.com" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Contraseña</label>

                                <div class="password-container">
                                    <input type="password" name="password" id="regPassword" class="form-control" placeholder="Mínimo 8 caracteres" required minlength="8">
                                    <span class="toggle-password" onclick="verPassword('regPassword', this)">
                                        <i class="fa-solid fa-eye"></i>
                                    </span>
                                </div>

                            </div>

                            <div class="mb-4">
                                <label class="form-label">Confirmar contraseña</label>
                                <input type="password" name="password_confirmation" class="form-control" placeholder="Repite la contraseña" required minlength="8">
                            </div>

                            <button type="submit" id="btnRegister" class="btn-auth">
                                <i class="bi bi-person-plus me-2"></i>Register
                            </button>
                        </form>
                    </div>

                </div>

                <!-- Social Login (compartido) -->
                <div class="social-login">
                    <p>Registrate con:</p>
                    <div class="social-buttons">
                        <button class="social-btn facebook" title="Próximamente" disabled style="opacity:.5; cursor:not-allowed;">
                            <i class="bi bi-facebook"></i>
                        </button>
                        <a href="/auth/google/redirect" class="social-btn google">
                            <i class="bi bi-google"></i>
                        </a>
                    </div>
                </div>

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
                <div class="text-center text-light small">
                    © 2026 ¡SINTECZATE! | Todos los derechos reservados
                </div>

            </div>
        </footer>

    </div>

    <!-- JavaScript para cambiar entre pestañas -->
    <script>
        function verPassword(inputId, iconSpan) {
            const input = document.getElementById(inputId);
            const icon = iconSpan.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        function showSection(section) {
            // Ocultar todas las secciones
            document.getElementById('loginSection').classList.remove('active');
            document.getElementById('registerSection').classList.remove('active');

            // Desactivar todas las pestañas
            document.getElementById('loginTab').classList.remove('active');
            document.getElementById('registerTab').classList.remove('active');

            // Mostrar la sección seleccionada
            if (section === 'login') {
                document.getElementById('loginSection').classList.add('active');
                document.getElementById('loginTab').classList.add('active');
            } else {
                document.getElementById('registerSection').classList.add('active');
                document.getElementById('registerTab').classList.add('active');
            }
        }

        // Junta "Nombre" + "Apellidos" en un solo campo antes de enviar el registro
        const formRegister = document.querySelector('#registerSection form');
        if (formRegister) {
            formRegister.addEventListener('submit', function (e) {
                const nombre = document.getElementById('regNombre').value.trim();
                const apellidos = document.getElementById('regApellidos').value.trim();
                document.getElementById('regNombreCompleto').value = (nombre + ' ' + apellidos).trim();

                const pass = document.getElementById('regPassword').value;
                if (pass.length < 8) {
                    e.preventDefault();
                    alert('La contraseña debe tener al menos 8 caracteres.');
                }
            });
        }

        // Si venimos de un intento de registro fallido (hay datos viejos de "nombre"),
        // abre automáticamente la pestaña de Registro en vez de la de Acceso.
        @if (old('nombre') !== null)
          showSection('register');
        @endif
    </script>

    <!--=====================================
	JS PERSONALIZADO
	======================================-->
    <script src="{{ asset('js/main.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>

    <!-- Modal: ¿Olvidé mi contraseña? -->
    <div class="modal fade" id="modalForgotPassword" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius:16px;">
          <form action="/forgot-password" method="POST">
            @csrf
            <div class="modal-header">
              <h5 class="modal-title">Recuperar contraseña</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <p>Escribe el correo con el que te registraste. Te mandaremos un link para crear una nueva contraseña.</p>
              <div class="mb-3">
                <label class="form-label">Correo electrónico</label>
                <input type="email" name="email" class="form-control" placeholder="ejemplo@correo.com" required>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
              <button type="submit" class="btn" style="background: linear-gradient(45deg, #ff7a18, #ffb347); color:#fff;">Enviar link</button>
            </div>
          </form>
        </div>
      </div>
    </div>

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
