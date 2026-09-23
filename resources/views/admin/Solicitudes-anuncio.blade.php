<!DOCTYPE html>
<html lang="es" class="has-aside-left has-aside-mobile-transition has-navbar-fixed-top has-aside-expanded">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Solicitudes de Anuncio · Admin</title>

    <link rel="stylesheet" href="{{ asset('assets/admin/css/main.min.css') }}">
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.9.95/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="{{ asset('assets/admin/css/theme-conectaya-admin.css') }}">
    <style>
        .badge-estado {
            display: inline-block;
            padding: 3px 12px;
            border-radius: 20px;
            font-size: .72rem;
            font-weight: 700;
        }

        .badge-pagado {
            background: #dcfce7;
            color: #16a34a;
        }

        .badge-pendiente_pago {
            background: #fef3c7;
            color: #b45309;
        }

        .badge-pago_rechazado {
            background: #fee2e2;
            color: #dc2626;
        }

        .badge-aprobado {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .badge-rechazado {
            background: #f3f4f6;
            color: #6b7280;
        }

        .badge-pendiente {
            background: #f3f4f6;
            color: #6b7280;
        }

        .solicitud-thumb {
            width: 70px;
            height: 70px;
            object-fit: cover;
            border-radius: 8px;
        }

        .fila-pagado {
            background: #f0fdf4;
        }
    </style>
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
                    <li><a href="/admin" class="has-icon"><span class="icon"><i class="mdi mdi-desktop-mac"></i></span><span class="menu-item-label">Dashboard</span></a></li>
                    <li><a href="/admin/usuarios" class="has-icon"><span class="icon"><i class="mdi mdi-account-multiple"></i></span><span class="menu-item-label">Usuarios</span></a></li>
                    <li><a href="/admin/vacantes" class="has-icon"><span class="icon"><i class="mdi mdi-briefcase"></i></span><span class="menu-item-label">Vacantes</span></a></li>
                    <li><a href="/admin/postulaciones" class="has-icon"><span class="icon"><i class="mdi mdi-account-check"></i></span><span class="menu-item-label">Postulaciones</span></a></li>
                    <li><a href="/admin/servicios" class="has-icon"><span class="icon"><i class="mdi mdi-hammer-screwdriver"></i></span><span class="menu-item-label">Servicios</span></a></li>
                    <li><a href="/admin/contrataciones" class="has-icon"><span class="icon"><i class="mdi mdi-handshake"></i></span><span class="menu-item-label">Contrataciones</span></a></li>
                    <li><a href="/admin/reportes" class="has-icon"><span class="icon"><i class="mdi mdi-flag"></i></span><span class="menu-item-label">Reportes</span></a></li>
                    <li><a href="/admin/testimonios" class="has-icon"><span class="icon"><i class="mdi mdi-star"></i></span><span class="menu-item-label">Testimonios</span></a></li>
                    <li><a href="/admin/categorias" class="has-icon"><span class="icon"><i class="mdi mdi-shape"></i></span><span class="menu-item-label">Categorías</span></a></li>
                    <li><a href="/admin/municipios" class="has-icon"><span class="icon"><i class="mdi mdi-map-marker"></i></span><span class="menu-item-label">Municipios</span></a></li>
                    <li><a href="/admin/anuncios" class="has-icon"><span class="icon"><i class="mdi mdi-bullhorn"></i></span><span class="menu-item-label">Anuncios</span></a></li>
                    <li>
                        <a href="/admin/solicitudes-anuncio" class="is-active router-link-active has-icon">
                            <span class="icon has-update-mark"><i class="mdi mdi-cash-register"></i></span>
                            <span class="menu-item-label">Pagos de anuncios</span>
                            @if ($contadorPagadas > 0)
                            <span class="tag is-success is-rounded" style="margin-left:6px;">{{ $contadorPagadas }}</span>
                            @endif
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
                            <li>Pagos de anuncios</li>
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
                            <h1 class="title">Solicitudes de Anuncio</h1>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="section is-main-section">

            <div class="notification is-info is-light">
                <span class="icon"><i class="mdi mdi-information"></i></span>
                Cuando un negocio paga con Mercado Pago, su solicitud aparece aquí marcada como <b>"Pagado"</b>. Revisa los datos, elige municipio y posición, y dale <b>"Activar"</b> para publicar el anuncio en el sitio.
            </div>

            <div class="field mb-4">
                <div class="control">
                    <div class="select">
                        <select id="filtro-estado" onchange="location.href = this.value ? '?estado=' + this.value : '/admin/solicitudes-anuncio'">
                            <option value="" {{ !$estado ? 'selected' : '' }}>Todas</option>
                            <option value="pagado" {{ $estado === 'pagado' ? 'selected' : '' }}>Pagadas (listas para activar)</option>
                            <option value="pendiente_pago" {{ $estado === 'pendiente_pago' ? 'selected' : '' }}>Pendientes de pago</option>
                            <option value="pago_rechazado" {{ $estado === 'pago_rechazado' ? 'selected' : '' }}>Pago rechazado</option>
                            <option value="aprobado" {{ $estado === 'aprobado' ? 'selected' : '' }}>Ya activadas</option>
                            <option value="rechazado" {{ $estado === 'rechazado' ? 'selected' : '' }}>Rechazadas</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="table-container">
                    <table class="table is-fullwidth is-hoverable">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Negocio</th>
                                <th>Encargado</th>
                                <th>Plan</th>
                                <th>Contacto</th>
                                <th>Estado</th>
                                <th>Fecha</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($solicitudes as $s)
                            @php $pagoAprobado = $s->pagos->firstWhere('estado', 'aprobado'); @endphp
                            <tr class="{{ $s->estado === 'pagado' ? 'fila-pagado' : '' }}">
                                <td>
                                    @if ($s->imagen_negocio)
                                    <img src="{{ $s->imagen_negocio }}" class="solicitud-thumb" alt="{{ $s->nombre_negocio }}">
                                    @else
                                    <div class="solicitud-thumb has-background-light is-flex is-align-items-center is-justify-content-center">
                                        <i class="mdi mdi-image-off-outline has-text-grey"></i>
                                    </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $s->nombre_negocio }}</strong><br>
                                    <small class="has-text-grey">{{ \Illuminate\Support\Str::limit($s->descripcion, 60) }}</small>
                                    @if ($s->direccion)<br><small class="has-text-grey"><i class="mdi mdi-map-marker"></i> {{ $s->direccion }}</small>@endif
                                    @if ($s->eslogan)<br><small class="has-text-grey"><i class="mdi mdi-format-quote-close"></i> {{ $s->eslogan }}</small>@endif
                                </td>
                                <td>{{ $s->nombre_encargado ?? '—' }}</td>
                                <td>
                                    {{ ucfirst($s->plan) }}
                                    @if ($pagoAprobado)<br><small class="has-text-grey">${{ number_format($pagoAprobado->monto, 0) }} · vence {{ $pagoAprobado->fecha_vencimiento_anuncio?->format('d/m/Y') }}</small>@endif
                                </td>
                                <td>
                                    <i class="mdi mdi-phone"></i> {{ $s->telefono }}
                                    @if ($s->whatsapp)<br><i class="mdi mdi-whatsapp" style="color:#25D366;"></i> {{ $s->whatsapp }}@endif
                                    @if ($s->email)<br><small class="has-text-grey">{{ $s->email }}</small>@endif
                                </td>
                                <td><span class="badge-estado badge-{{ $s->estado }}">{{ ucfirst(str_replace('_', ' ', $s->estado)) }}</span></td>
                                <td><small>{{ $s->created_at->format('d/m/Y H:i') }}</small></td>
                                <td>
                                    @if ($s->estado === 'pagado')
                                    <button class="button is-small is-success" onclick="abrirModalActivar({{ $s->id }}, '{{ addslashes($s->nombre_negocio) }}')">
                                        <span class="icon"><i class="mdi mdi-check-circle"></i></span>
                                        <span>Activar</span>
                                    </button>
                                    <button class="button is-small is-danger is-outlined" onclick="rechazarSolicitud({{ $s->id }})">
                                        <span class="icon"><i class="mdi mdi-close"></i></span>
                                    </button>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="has-text-centered has-text-grey py-5">No hay solicitudes con ese filtro.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </section>

        <!-- MODAL: Activar solicitud -->
        <div id="modal-activar" class="modal">
            <div class="modal-background" onclick="cerrarModalActivar()"></div>
            <div class="modal-card">
                <header class="modal-card-head">
                    <p class="modal-card-title">Activar anuncio de <span id="modal-nombre-negocio"></span></p>
                    <button class="delete" aria-label="close" onclick="cerrarModalActivar()"></button>
                </header>
                <section class="modal-card-body">
                    <input type="hidden" id="modal-solicitud-id">
                    <div class="field">
                        <label class="label">Municipio</label>
                        <div class="control">
                            <div class="select is-fullwidth">
                                <select id="modal-municipio">
                                    @foreach ($municipios as $m)
                                    <option value="{{ $m->id }}">{{ $m->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="field">
                        <label class="label">Posición</label>
                        <div class="control">
                            <div class="select is-fullwidth">
                                <select id="modal-posicion">
                                    <option value="derecha" selected>Derecha (la que se muestra en el sitio)</option>
                                    <option value="izquierda">Izquierda (sin uso por ahora)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </section>
                <footer class="modal-card-foot">
                    <button class="button is-success" id="btn-confirmar-activar" onclick="confirmarActivar()">
                        <span class="icon"><i class="mdi mdi-check"></i></span>
                        <span>Confirmar y publicar</span>
                    </button>
                    <button class="button" onclick="cerrarModalActivar()">Cancelar</button>
                </footer>
            </div>
        </div>

        <script src="{{ asset('assets/admin/js/main.min.js') }}"></script>
        <script>
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            function abrirModalActivar(id, nombre) {
                document.getElementById('modal-solicitud-id').value = id;
                document.getElementById('modal-nombre-negocio').textContent = nombre;
                document.getElementById('modal-activar').classList.add('is-active');
            }

            function cerrarModalActivar() {
                document.getElementById('modal-activar').classList.remove('is-active');
            }

            function confirmarActivar() {
                const id = document.getElementById('modal-solicitud-id').value;
                const municipioId = document.getElementById('modal-municipio').value;
                const posicion = document.getElementById('modal-posicion').value;
                const btn = document.getElementById('btn-confirmar-activar');

                btn.disabled = true;
                btn.classList.add('is-loading');

                fetch(`/admin/solicitudes-anuncio/${id}/activar`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            municipio_id: municipioId,
                            posicion: posicion
                        }),
                    })
                    .then(res => res.json().then(data => ({
                        ok: res.ok,
                        data
                    })))
                    .then(({
                        ok,
                        data
                    }) => {
                        if (!ok) {
                            alert('❌ ' + (data.error || 'Ocurrió un error al activar.'));
                            btn.disabled = false;
                            btn.classList.remove('is-loading');
                            return;
                        }
                        alert('✅ ¡Anuncio activado y publicado!');
                        location.reload();
                    })
                    .catch(() => {
                        alert('❌ Ocurrió un error de conexión.');
                        btn.disabled = false;
                        btn.classList.remove('is-loading');
                    });
            }

            function rechazarSolicitud(id) {
                const motivo = prompt('¿Por qué rechazas esta solicitud? (opcional)');
                if (motivo === null) return; // canceló

                fetch(`/admin/solicitudes-anuncio/${id}/rechazar`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            motivo: motivo
                        }),
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (!data.ok) {
                            alert('❌ Ocurrió un error.');
                            return;
                        }
                        location.reload();
                    })
                    .catch(() => alert('❌ Ocurrió un error de conexión.'));
            }

            document.querySelectorAll('.js-logout').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (!confirm('¿Seguro que quieres cerrar sesión?')) return;
                    fetch('/logout', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            }
                        })
                        .finally(() => {
                            window.location.href = '/';
                        });
                });
            });
        </script>
</body>

</html>
