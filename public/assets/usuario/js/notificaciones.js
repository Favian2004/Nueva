"use strict";

/*
 * Campana de notificaciones del menú de arriba (todas las pantallas de usuario).
 *
 * - Se pone sola en el menú: en escritorio junto a "Cerrar sesión" y en celular
 *   junto al botón del menú (main.js carga este archivo).
 * - Es una sola lista por cuenta; cada aviso dice si es "Como trabajador" o
 *   "Como empleador".
 * - Se actualiza sola cada minuto (y al volver a la pestaña).
 * - Cuando llega un aviso nuevo suena un "ding" corto (se puede silenciar con el
 *   botón de la bocina; la elección se recuerda en este navegador).
 * - Al tocar un aviso, cambia sola al modo (Trabajador / Empleador) que necesita
 *   la pantalla de destino; si no, esa pantalla te mandaría de regreso al inicio.
 * - Datos: GET /usuario/notificaciones, POST /usuario/notificaciones/{id}/leer
 *   y POST /usuario/notificaciones/leer-todas.
 */
(function () {
  if (window.__campanaNotificaciones) return;
  window.__campanaNotificaciones = true;

  var URL_LISTA = '/usuario/notificaciones';
  var INTERVALO_MS = 60000;

  var ICONOS = {
    nueva_postulacion: 'mdi-account-plus',
    contratado: 'mdi-briefcase-check',
    rechazado: 'mdi-close-circle-outline',
    nueva_solicitud: 'mdi-clipboard-text',
    solicitud_aceptada: 'mdi-check-circle',
    solicitud_cancelada: 'mdi-close-circle-outline'
  };

  var ETIQUETA_MODO = { trabajador: 'Como trabajador', empleador: 'Como empleador' };

  // A qué modo pertenece cada pantalla. Es lo mismo que ya deciden el interruptor
  // Trabajador/Empleador de cada página y su "userRole" guardado en el navegador:
  // si entras a una pantalla de otro modo, te manda al inicio de tu modo.
  var PANTALLAS_EMPLEADOR = ['empleador', 'buscar-talento', 'mis-vacantes', 'postulantes', 'pronostico', 'publicar-vacante'];
  var PANTALLAS_TRABAJADOR = ['', 'verEmpleos', 'publicarEmpleo', 'misEmpleos', 'solicitudes'];

  var estado = { items: [], noLeidas: 0 };
  var idsVistos = {};        // avisos que ya se habían visto en una consulta anterior
  var primeraCarga = true;   // en la primera consulta de la página no suena nada
  var campanas = [];

  // ---------- utilidades ----------
  function csrf() {
    var m = document.querySelector('meta[name="csrf-token"]');
    return m ? m.content : '';
  }

  function el(tag, clase, texto) {
    var e = document.createElement(tag);
    if (clase) e.className = clase;
    if (texto !== undefined) e.textContent = texto;
    return e;
  }

  function hace(iso) {
    var t = Date.parse(iso);
    if (isNaN(t)) return '';
    var seg = Math.max(0, Math.round((Date.now() - t) / 1000));
    if (seg < 60) return 'hace un momento';
    var min = Math.round(seg / 60);
    if (min < 60) return 'hace ' + min + ' min';
    var h = Math.round(min / 60);
    if (h < 24) return 'hace ' + h + (h === 1 ? ' hora' : ' horas');
    var d = Math.round(h / 24);
    if (d === 1) return 'ayer';
    if (d < 7) return 'hace ' + d + ' días';
    var f = new Date(t);
    var meses = ['ene', 'feb', 'mar', 'abr', 'may', 'jun', 'jul', 'ago', 'sep', 'oct', 'nov', 'dic'];
    return f.getDate() + ' ' + meses[f.getMonth()];
  }

  function peticion(url, metodo) {
    return fetch(url, {
      method: metodo || 'GET',
      headers: { 'X-CSRF-TOKEN': csrf(), 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
      credentials: 'same-origin',
      keepalive: metodo === 'POST'
    });
  }

  // 'employer' | 'worker' | null (null = pantalla que no depende del modo, ej. ver una vacante).
  function modoDeLaPantalla(url) {
    var a = document.createElement('a');
    a.href = url;
    var ruta = a.pathname;
    if (ruta.indexOf('/usuario') !== 0) return null;
    var pantalla = ruta.replace(/^\/usuario\/?/, '').split('/')[0];
    if (PANTALLAS_EMPLEADOR.indexOf(pantalla) !== -1) return 'employer';
    if (PANTALLAS_TRABAJADOR.indexOf(pantalla) !== -1) return 'worker';
    return null;
  }

  // Deja guardado el modo que necesita la pantalla a la que se va a ir.
  function cambiarModoPara(url) {
    var modo = modoDeLaPantalla(url);
    if (!modo) return;
    try { localStorage.setItem('userRole', modo); } catch (e) { /* sin almacenamiento: no se puede */ }
  }

  // ---------- sonido ----------
  var audio = null;

  function sonidoActivo() {
    try { return localStorage.getItem('ntfSonido') !== '0'; } catch (e) { return true; }
  }

  // Los navegadores no dejan sonar nada hasta que la persona toca la página
  // (clic, tecla o toque); por eso el audio se prepara en el primer gesto.
  function prepararAudio() {
    if (audio) return;
    var AC = window.AudioContext || window.webkitAudioContext;
    if (!AC) return;
    try { audio = new AC(); } catch (e) { audio = null; }
    if (audio && audio.state === 'suspended') audio.resume().catch(function () {});
  }

  function sonar() {
    if (!sonidoActivo() || !audio) return;

    function tocar() {
      if (audio.state !== 'running') return; // el navegador aún no lo permite
      var t = audio.currentTime;
      // Dos notas suaves, la segunda más aguda: "ding-ding".
      [[880, 0], [1174.66, 0.14]].forEach(function (n) {
        var osc = audio.createOscillator();
        var ganancia = audio.createGain();
        var ini = t + n[1];
        osc.type = 'sine';
        osc.frequency.value = n[0];
        ganancia.gain.setValueAtTime(0.0001, ini);
        ganancia.gain.exponentialRampToValueAtTime(0.22, ini + 0.02);
        ganancia.gain.exponentialRampToValueAtTime(0.0001, ini + 0.5);
        osc.connect(ganancia);
        ganancia.connect(audio.destination);
        osc.start(ini);
        osc.stop(ini + 0.55);
      });
    }

    if (audio.state === 'running') tocar();
    else audio.resume().then(tocar).catch(function () {});
  }

  function alternarSonido() {
    var activar = !sonidoActivo();
    try { localStorage.setItem('ntfSonido', activar ? '1' : '0'); } catch (e) { /* sin almacenamiento */ }
    prepararAudio(); // este clic ya es un gesto de la persona
    pintar();
    if (activar) sonar(); // para que escuche cómo suena
  }

  // ---------- estilos ----------
  function inyectarEstilos() {
    var css = [
      '.ntf{position:relative;display:flex;align-items:center}',
      '.ntf-m{display:none}',
      '@media (max-width:1023px){.ntf-d.ntf-con-m{display:none!important}.ntf-m{display:flex}}',
      '.ntf-btn{position:relative;display:flex;align-items:center;justify-content:center;width:40px;height:40px;border:0;border-radius:50%;background:transparent;color:#4a4a4a;font-size:22px;cursor:pointer;padding:0}',
      '.ntf-btn:hover,.ntf-btn[aria-expanded="true"]{background:rgba(0,0,0,.07)}',
      '.ntf-btn:focus-visible{outline:2px solid #ff7a18;outline-offset:1px}',
      '.ntf-badge{position:absolute;top:1px;right:-1px;min-width:18px;height:18px;padding:0 5px;border-radius:9px;background:#e03131;color:#fff;font:700 11px/18px Arial,Helvetica,sans-serif;text-align:center;box-shadow:0 0 0 2px #fff}',
      '.ntf-badge[hidden]{display:none}',
      '.ntf-panel{position:fixed;z-index:1001;display:flex;flex-direction:column;background:#fff;border:1px solid #e5e7eb;border-radius:12px;box-shadow:0 12px 32px rgba(0,0,0,.18);overflow:hidden;font-family:Arial,Helvetica,sans-serif;text-align:left}',
      '.ntf-panel[hidden]{display:none}',
      '.ntf-head{display:flex;align-items:center;justify-content:space-between;gap:8px;padding:12px 16px;border-bottom:1px solid #eef0f3}',
      '.ntf-head strong{font-size:16px;color:#1f2937}',
      '.ntf-todas{border:0;background:none;padding:6px 0 6px 8px;color:#c2410c;font-size:12.5px;font-weight:700;cursor:pointer}',
      '.ntf-todas:hover{text-decoration:underline}',
      '.ntf-todas[hidden]{display:none}',
      '.ntf-todas{white-space:nowrap}',
      '.ntf-corto{display:none}',
      '@media (max-width:420px){.ntf-largo{display:none}.ntf-corto{display:inline}}',
      '.ntf-acciones{display:flex;align-items:center;gap:2px}',
      '.ntf-sonido{display:flex;align-items:center;justify-content:center;width:32px;height:32px;padding:0;border:0;border-radius:50%;background:none;color:#6b7280;font-size:18px;cursor:pointer}',
      '.ntf-sonido:hover{background:#f3f4f6;color:#1f2937}',
      '.ntf-sonido:focus-visible{outline:2px solid #ff7a18;outline-offset:1px}',
      '.ntf-lista{list-style:none;margin:0;padding:0;overflow-y:auto}',
      '.ntf-item{display:flex;align-items:flex-start;gap:12px;padding:12px 16px;border-bottom:1px solid #f3f4f6;color:#1f2937;text-decoration:none}',
      '.ntf-item:hover{background:#faf7f4;color:#1f2937}',
      '.ntf-item:focus-visible{outline:2px solid #ff7a18;outline-offset:-2px}',
      '.ntf-item.is-nueva{background:#fff4e8}',
      '.ntf-item.is-nueva:hover{background:#ffecd9}',
      '.ntf-ico{flex:0 0 36px;display:flex;align-items:center;justify-content:center;width:36px;height:36px;border-radius:50%;background:#f3f4f6;color:#4b5563;font-size:19px}',
      '.ntf-item.is-nueva .ntf-ico{background:#ffe3c9;color:#c2410c}',
      '.ntf-cuerpo{flex:1;min-width:0}',
      '.ntf-tit{font-size:14px;font-weight:700;line-height:1.3}',
      '.ntf-txt{margin-top:2px;font-size:13px;line-height:1.4;color:#4b5563;overflow-wrap:anywhere}',
      '.ntf-meta{display:flex;flex-wrap:wrap;align-items:center;gap:8px;margin-top:5px;font-size:12px;color:#6b7280}',
      '.ntf-chip{padding:0 8px;border-radius:999px;background:#eef0f3;color:#4b5563;font-size:11.5px;font-weight:600;border:1px solid transparent}',
      // Mismos colores del interruptor Trabajador / Empleador del menú lateral
      // (verde #34d399 y azul #60a5fa), con texto más oscuro para que se lea sobre blanco.
      '.ntf-chip-trabajador{background:rgba(52,211,153,.18);border-color:rgba(52,211,153,.6);color:#065f46}',
      '.ntf-chip-empleador{background:rgba(96,165,250,.18);border-color:rgba(96,165,250,.65);color:#1d4ed8}',
      '.ntf-punto{flex:0 0 10px;width:10px;height:10px;margin-top:6px;border-radius:50%;background:#ff7a18}',
      '.ntf-sr{position:absolute;width:1px;height:1px;overflow:hidden;clip:rect(0 0 0 0);white-space:nowrap}',
      '.ntf-vacio{margin:0;padding:30px 20px;text-align:center;font-size:13.5px;line-height:1.5;color:#6b7280}',
      '.ntf-vacio i{display:block;margin-bottom:6px;font-size:30px;color:#c3c8cf}'
    ].join('\n');
    var st = document.createElement('style');
    st.textContent = css;
    document.head.appendChild(st);
  }

  // ---------- construir una campana ----------
  function crearCampana(clases) {
    var raiz = el('div', 'navbar-item ntf ' + clases);

    var btn = el('button', 'ntf-btn');
    btn.type = 'button';
    btn.setAttribute('aria-label', 'Notificaciones');
    btn.setAttribute('aria-haspopup', 'true');
    btn.setAttribute('aria-expanded', 'false');
    var icono = el('i', 'mdi mdi-bell-outline');
    icono.setAttribute('aria-hidden', 'true');
    var badge = el('span', 'ntf-badge');
    badge.hidden = true;
    btn.appendChild(icono);
    btn.appendChild(badge);

    var panel = el('div', 'ntf-panel');
    panel.hidden = true;
    panel.setAttribute('role', 'dialog');
    panel.setAttribute('aria-label', 'Notificaciones');

    var cabeza = el('div', 'ntf-head');
    cabeza.appendChild(el('strong', '', 'Notificaciones'));
    var acciones = el('div', 'ntf-acciones');
    var sonido = el('button', 'ntf-sonido');
    sonido.type = 'button';
    var sonidoIcono = el('i', 'mdi mdi-volume-high');
    sonidoIcono.setAttribute('aria-hidden', 'true');
    sonido.appendChild(sonidoIcono);
    var todas = el('button', 'ntf-todas');
    todas.type = 'button';
    todas.appendChild(el('span', 'ntf-largo', 'Marcar todas como leídas'));
    todas.appendChild(el('span', 'ntf-corto', 'Marcar leídas')); // en pantallas muy angostas
    todas.hidden = true;
    acciones.appendChild(sonido);
    acciones.appendChild(todas);
    cabeza.appendChild(acciones);

    var lista = el('ul', 'ntf-lista');
    var vacio = el('p', 'ntf-vacio');
    var vi = el('i', 'mdi mdi-bell-outline');
    vi.setAttribute('aria-hidden', 'true');
    vacio.appendChild(vi);
    vacio.appendChild(document.createTextNode('Todavía no tienes notificaciones. Aquí te avisaremos cuando alguien se postule a tus vacantes o cuando te contraten.'));

    panel.appendChild(cabeza);
    panel.appendChild(lista);
    panel.appendChild(vacio);
    raiz.appendChild(btn);
    raiz.appendChild(panel);

    var c = { raiz: raiz, btn: btn, badge: badge, panel: panel, lista: lista, vacio: vacio, todas: todas, sonido: sonido, sonidoIcono: sonidoIcono };

    btn.addEventListener('click', function (e) {
      e.stopPropagation();
      if (panel.hidden) abrir(c); else cerrar(c);
    });
    todas.addEventListener('click', marcarTodas);
    sonido.addEventListener('click', alternarSonido);
    return c;
  }

  function posicionar(c) {
    var r = c.btn.getBoundingClientRect();
    var vw = window.innerWidth;
    var ancho = Math.min(380, vw - 16);
    var izq = Math.max(8, Math.min(r.right - ancho, vw - ancho - 8));
    var arriba = r.bottom + 8;
    c.panel.style.width = ancho + 'px';
    c.panel.style.left = izq + 'px';
    c.panel.style.top = arriba + 'px';
    c.panel.style.maxHeight = Math.max(200, window.innerHeight - arriba - 16) + 'px';
  }

  function abrir(c) {
    campanas.forEach(cerrar);
    c.panel.hidden = false;
    c.btn.setAttribute('aria-expanded', 'true');
    posicionar(c);
    cargar(); // lista fresca al abrir
  }

  function cerrar(c) {
    if (c.panel.hidden) return;
    c.panel.hidden = true;
    c.btn.setAttribute('aria-expanded', 'false');
  }

  // ---------- pintar ----------
  function pintar() {
    var n = estado.noLeidas;
    campanas.forEach(function (c) {
      c.badge.hidden = n <= 0;
      c.badge.textContent = n > 9 ? '9+' : String(n);
      c.btn.setAttribute('aria-label', n > 0 ? 'Notificaciones, ' + n + (n === 1 ? ' sin leer' : ' sin leer') : 'Notificaciones');
      c.todas.hidden = n <= 0;
      var suena = sonidoActivo();
      c.sonidoIcono.className = 'mdi ' + (suena ? 'mdi-volume-high' : 'mdi-volume-off');
      c.sonido.setAttribute('aria-pressed', suena ? 'true' : 'false');
      c.sonido.setAttribute('aria-label', suena ? 'Sonido de avisos activado. Tocar para silenciar' : 'Sonido de avisos silenciado. Tocar para activar');
      c.sonido.title = suena ? 'Sonido activado' : 'Sonido silenciado';
      c.vacio.hidden = estado.items.length > 0;
      c.lista.hidden = estado.items.length === 0;

      c.lista.textContent = '';
      estado.items.forEach(function (it) {
        var li = el('li');
        var a = el('a', 'ntf-item' + (it.leida ? '' : ' is-nueva'));
        a.href = it.url || '/usuario';

        var ico = el('span', 'ntf-ico');
        var i = el('i', 'mdi ' + (ICONOS[it.tipo] || 'mdi-bell-outline'));
        i.setAttribute('aria-hidden', 'true');
        ico.appendChild(i);

        var cuerpo = el('div', 'ntf-cuerpo');
        if (!it.leida) cuerpo.appendChild(el('span', 'ntf-sr', 'Sin leer. '));
        cuerpo.appendChild(el('div', 'ntf-tit', it.titulo));
        cuerpo.appendChild(el('div', 'ntf-txt', it.texto));
        var meta = el('div', 'ntf-meta');
        meta.appendChild(el('span', '', hace(it.fecha)));
        if (ETIQUETA_MODO[it.modo]) meta.appendChild(el('span', 'ntf-chip ntf-chip-' + it.modo, ETIQUETA_MODO[it.modo]));
        cuerpo.appendChild(meta);

        a.appendChild(ico);
        a.appendChild(cuerpo);
        if (!it.leida) a.appendChild(el('span', 'ntf-punto'));

        a.addEventListener('click', function () {
          cambiarModoPara(it.url); // antes de que la página cambie
          marcarUna(it);
        });
        li.appendChild(a);
        c.lista.appendChild(li);
      });
    });
  }

  // ---------- datos ----------
  function cargar() {
    if (document.hidden) return Promise.resolve();
    return peticion(URL_LISTA)
      .then(function (r) {
        if (!r.ok) throw new Error('http ' + r.status);
        return r.json();
      })
      .then(function (d) {
        estado.items = d.items || [];
        estado.noLeidas = d.no_leidas || 0;

        // ¿Hay algún aviso sin leer que no habíamos visto? Entonces suena (menos en la primera consulta).
        var hayNuevo = false;
        estado.items.forEach(function (it) {
          if (!it.leida && !idsVistos[it.id]) hayNuevo = true;
          idsVistos[it.id] = true;
        });
        pintar();
        if (hayNuevo && !primeraCarga) sonar();
        primeraCarga = false;
      })
      .catch(function () { /* sin sesión o sin red: la campana se queda como está */ });
  }

  function marcarUna(it) {
    if (it.leida) return;
    it.leida = true;
    estado.noLeidas = Math.max(0, estado.noLeidas - 1);
    pintar();
    // Corre aunque la página cambie enseguida (keepalive).
    peticion('/usuario/notificaciones/' + encodeURIComponent(it.id) + '/leer', 'POST').catch(function () {});
  }

  function marcarTodas() {
    estado.items.forEach(function (it) { it.leida = true; });
    estado.noLeidas = 0;
    pintar();
    peticion('/usuario/notificaciones/leer-todas', 'POST').catch(function () {});
  }

  // Un aviso puede llevar a una pestaña de la página (?tab=enviadas).
  function abrirPestana() {
    var tab = new URLSearchParams(window.location.search).get('tab');
    if (tab !== 'enviadas' && tab !== 'recibidas') return; // cualquier otro valor se ignora
    if (typeof window.mostrarTabPost === 'function') window.mostrarTabPost(tab);   // /usuario/postulantes
    if (typeof window.mostrarTab === 'function') window.mostrarTab(tab);           // /usuario/solicitudes
  }

  // ---------- arranque ----------
  function iniciar() {
    var fin = document.querySelector('#navbar-main .navbar-end') || document.querySelector('.navbar-end');
    if (!fin) return;
    var marca = document.querySelector('#navbar-main .navbar-brand.is-right') || document.querySelector('.navbar-brand.is-right');

    inyectarEstilos();

    // Escritorio: dentro del menú, antes de "Cerrar sesión".
    var escritorio = crearCampana('ntf-d' + (marca ? ' ntf-con-m' : ''));
    fin.insertBefore(escritorio.raiz, fin.firstChild);
    campanas.push(escritorio);

    // Celular: el menú está plegado, así que la campana va junto a su botón.
    if (marca) {
      var movil = crearCampana('ntf-m');
      marca.insertBefore(movil.raiz, marca.firstChild);
      campanas.push(movil);
    }

    document.addEventListener('click', function (e) {
      campanas.forEach(function (c) { if (!c.raiz.contains(e.target)) cerrar(c); });
    });
    document.addEventListener('keydown', function (e) {
      if (e.key !== 'Escape') return;
      campanas.forEach(function (c) {
        if (!c.panel.hidden) { cerrar(c); c.btn.focus(); }
      });
    });
    window.addEventListener('resize', function () {
      campanas.forEach(function (c) { if (!c.panel.hidden) posicionar(c); });
    });
    document.addEventListener('visibilitychange', function () { if (!document.hidden) cargar(); });
    ['pointerdown', 'keydown', 'touchstart'].forEach(function (ev) {
      document.addEventListener(ev, prepararAudio, { once: true, passive: true });
    });

    pintar();
    cargar();
    setInterval(cargar, INTERVALO_MS);
    abrirPestana();
  }

  // Se espera a que la página termine de cargar (así ya existen sus funciones de pestañas).
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', iniciar);
  } else {
    iniciar();
  }
})();
