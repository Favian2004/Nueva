/* =====================================================================
   ANUNCIOS LATERALES - inserta las columnas "Negocios destacados de tu
   municipio" (izquierda y derecha), igual que en index.html, en
   cualquier página del dashboard de usuario.

   Ahora jala los datos reales de tu base de datos vía GET /api/anuncios.
   ===================================================================== */

(function () {
  function buildAdBox(anuncio) {
    const imgsHtml = anuncio.imagenes
      .map((img, idx) => `<img src="${img.imagen}"
        alt="Negocio destacado"
        data-nombre="${(img.nombre_negocio || '').replace(/"/g, '&quot;')}"
        data-eslogan="${(img.eslogan || '').replace(/"/g, '&quot;')}"
        data-link="${img.link_externo || ''}"
        data-ubicacion="${img.link_ubicacion || ''}">`)
      .join('');
    return `<div class="ad-box" data-anuncio-id="${anuncio.id}">${imgsHtml}</div>`;
  }

  function buildColumn(anuncios) {
    const titulo = `
      <div class="ad-titulo">
        <h5>NEGOCIOS DESTACADOS</h5>
        <div class="linea-titulo">
          <span></span>
          <small>de tu municipio</small>
          <span></span>
        </div>
      </div>`;
    return titulo + anuncios.map(buildAdBox).join('');
  }

  // Recuerda, entre una página y otra, en qué foto se quedó cada anuncio
  // (así, aunque la gente navegue rápido entre páginas, con el tiempo
  // se alcanzan a ver TODAS las fotos de cada negocio, no solo la primera).
  function leerIndiceGuardado(anuncioId) {
    const val = parseInt(localStorage.getItem('anuncio-idx-' + anuncioId), 10);
    return isNaN(val) ? 0 : val;
  }
  function guardarIndice(anuncioId, idx) {
    localStorage.setItem('anuncio-idx-' + anuncioId, String(idx));
  }

  function startCarousel(box) {
    const imgs = box.querySelectorAll('img');
    if (!imgs.length) return;
    const anuncioId = box.dataset.anuncioId;

    let current = leerIndiceGuardado(anuncioId) % imgs.length;
    imgs[current].classList.add('is-visible');
    guardarIndice(anuncioId, current);

    if (imgs.length < 2) return;

    setInterval(() => {
      imgs[current].classList.remove('is-visible');
      current = (current + 1) % imgs.length;
      imgs[current].classList.add('is-visible');
      guardarIndice(anuncioId, current);
    }, 6000);
  }

  function renderColumns(anuncios) {
    // Usa los MISMOS anuncios que el home (posicion = 'derecha'), pero
    // se muestran del lado izquierdo dentro del dashboard.
    const anunciosHome = anuncios.filter(a => a.posicion === 'derecha');

    if (anunciosHome.length) {
      const col = document.createElement('div');
      col.className = 'anuncio-izq';
      col.innerHTML = buildColumn(anunciosHome);
      document.body.appendChild(col);
    }

    document.querySelectorAll('.ad-box').forEach(startCarousel);

    // En celular no hay espacio para la columna fija, así que en su
    // lugar usamos una burbuja flotante que aparece de rato en rato.
    if (window.innerWidth <= 1300) {
      const todasLasImagenes = anunciosHome.flatMap(a => a.imagenes);
      iniciarBurbujaFlotante(todasLasImagenes);
    }
  }

  function cargarAnuncios() {
    fetch('/api/anuncios')
      .then(res => res.json())
      .then(anuncios => renderColumns(anuncios))
      .catch(() => {
        // Si falla la petición (ej. sin conexión), simplemente no se muestran anuncios.
      });
  }

  /* =====================================================================
     BURBUJA FLOTANTE: solo en celular (donde no cabe la columna fija).
     Aparece de rato en rato mostrando un negocio a la vez, sin bloquear
     nada — el usuario puede ignorarla (se desvanece sola), cerrarla con
     la X, o tocarla para ver el anuncio completo en el modal de siempre.
     ===================================================================== */
  function iniciarBurbujaFlotante(imagenes) {
    if (!imagenes.length) return;

    // Guardamos en qué negocio íbamos en localStorage, para que si el
    // usuario cambia de página (o recarga), la rotación siga donde se
    // quedó, en vez de reiniciar siempre desde el primero.
    let indice = 0;
    try {
      indice = parseInt(localStorage.getItem('burbujaAnuncioIndice') || '0', 10) || 0;
    } catch (e) {
      // Si localStorage no está disponible (ej. modo incógnito estricto),
      // simplemente empezamos desde el principio.
    }

    function mostrarBurbuja() {
      // Si el usuario ya cerró una burbuja hace poco, o hay una activa,
      // no amontonamos otra encima.
      if (document.getElementById('burbujaAnuncio')) return;

      const img = imagenes[indice % imagenes.length];
      indice++;

      try {
        localStorage.setItem('burbujaAnuncioIndice', indice);
      } catch (e) {
        // Sin problema si no se puede guardar; solo no recordará la
        // posición entre páginas, pero seguirá funcionando igual.
      }

      const burbuja = document.createElement('div');
      burbuja.id = 'burbujaAnuncio';
      burbuja.className = 'burbuja-anuncio';
      burbuja.innerHTML = `
        <button type="button" class="burbuja-anuncio-cerrar" aria-label="Cerrar">&times;</button>
        <img src="${img.imagen}" alt="Negocio destacado">
        <div class="burbuja-anuncio-texto">
          <small>Negocio destacado</small>
          <strong>${(img.nombre_negocio || 'Conoce este negocio').replace(/</g, '&lt;')}</strong>
        </div>
      `;
      document.body.appendChild(burbuja);

      requestAnimationFrame(() => burbuja.classList.add('mostrar'));

      function quitarBurbuja() {
        burbuja.classList.remove('mostrar');
        setTimeout(() => burbuja.remove(), 300);
      }

      const autoOcultar = setTimeout(quitarBurbuja, 15000);

      burbuja.querySelector('.burbuja-anuncio-cerrar').addEventListener('click', function (e) {
        e.stopPropagation();
        clearTimeout(autoOcultar);
        quitarBurbuja();
      });

      burbuja.addEventListener('click', function () {
        clearTimeout(autoOcultar);
        quitarBurbuja();

        // Reutiliza el mismo modal grande que ya existe.
        const modal = document.getElementById('modalAnuncio');
        document.getElementById('modalAnuncioImg').src = img.imagen;

        const nombreEl = document.getElementById('modalAnuncioNombre');
        nombreEl.textContent = img.nombre_negocio || '';
        nombreEl.style.display = img.nombre_negocio ? 'block' : 'none';

        const esloganEl = document.getElementById('modalAnuncioEslogan');
        esloganEl.textContent = img.eslogan || '';
        esloganEl.style.display = img.eslogan ? 'block' : 'none';

        const botonLink = document.getElementById('modalAnuncioBotonLink');
        if (img.link_externo) {
          botonLink.href = img.link_externo;
          botonLink.style.display = 'inline-flex';
        } else {
          botonLink.style.display = 'none';
        }

        const botonUbicacion = document.getElementById('modalAnuncioBotonUbicacion');
        if (img.link_ubicacion) {
          botonUbicacion.href = img.link_ubicacion;
          botonUbicacion.style.display = 'inline-flex';
        } else {
          botonUbicacion.style.display = 'none';
        }

        modal.classList.add('activo');
      });
    }

    // La primera, a los 10 segundos de entrar; luego, cada 90 segundos
    // (así, en una sesión de pocos minutos, alcanzan a rotar varios negocios).
    setTimeout(mostrarBurbuja, 10000);
    setInterval(mostrarBurbuja, 90000);
  }

  /* =====================================================================
     MODAL: ver anuncio en grande al tocarlo, con nombre, eslogan y
     botones de "Visitar página" / "Cómo llegar" (según lo que el negocio
     haya llenado en su plan).
     El propio widget crea el recuadro (no hace falta agregarlo a cada
     página), y usa "delegación de eventos" para detectar clics en
     imágenes que se insertan después (como estas, vía fetch).
     ===================================================================== */
  function crearModalAnuncio() {
    if (document.getElementById('modalAnuncio')) return; // ya existe, no duplicar

    const modal = document.createElement('div');
    modal.id = 'modalAnuncio';
    modal.className = 'modal-anuncio-overlay';
    modal.innerHTML = `
      <button type="button" class="modal-anuncio-cerrar" id="modalAnuncioCerrarBtn">&times;</button>
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
    `;
    document.body.appendChild(modal);

    function cerrar() {
      modal.classList.remove('activo');
    }

    document.getElementById('modalAnuncioCerrarBtn').addEventListener('click', cerrar);

    document.addEventListener('click', function (e) {
      const img = e.target.closest('.ad-box img');
      if (img) {
        document.getElementById('modalAnuncioImg').src = img.src;

        const nombreEl = document.getElementById('modalAnuncioNombre');
        const nombre = img.dataset.nombre || '';
        nombreEl.textContent = nombre;
        nombreEl.style.display = nombre ? 'block' : 'none';

        const esloganEl = document.getElementById('modalAnuncioEslogan');
        const eslogan = img.dataset.eslogan || '';
        esloganEl.textContent = eslogan;
        esloganEl.style.display = eslogan ? 'block' : 'none';

        const botonLink = document.getElementById('modalAnuncioBotonLink');
        if (img.dataset.link) {
          botonLink.href = img.dataset.link;
          botonLink.style.display = 'inline-flex';
        } else {
          botonLink.style.display = 'none';
        }

        const botonUbicacion = document.getElementById('modalAnuncioBotonUbicacion');
        if (img.dataset.ubicacion) {
          botonUbicacion.href = img.dataset.ubicacion;
          botonUbicacion.style.display = 'inline-flex';
        } else {
          botonUbicacion.style.display = 'none';
        }

        modal.classList.add('activo');
        return;
      }
      if (e.target === modal) {
        cerrar();
      }
    });

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') cerrar();
    });
  }

  document.addEventListener('DOMContentLoaded', function () {
    crearModalAnuncio();
    cargarAnuncios();
  });
})();
/* =====================================================================
   ANUNCIOS LATERALES - inserta las columnas "Negocios destacados de tu
   municipio" (izquierda y derecha), igual que en index.html, en
   cualquier página del dashboard de usuario.

   Ahora jala los datos reales de tu base de datos vía GET /api/anuncios.
   ===================================================================== */

(function () {
  function buildAdBox(anuncio) {
    const imgsHtml = anuncio.imagenes
      .map((img, idx) => `<img src="${img.imagen}"
        alt="Negocio destacado"
        data-nombre="${(img.nombre_negocio || '').replace(/"/g, '&quot;')}"
        data-eslogan="${(img.eslogan || '').replace(/"/g, '&quot;')}"
        data-link="${img.link_externo || ''}"
        data-ubicacion="${img.link_ubicacion || ''}">`)
      .join('');
    return `<div class="ad-box" data-anuncio-id="${anuncio.id}">${imgsHtml}</div>`;
  }

  function buildColumn(anuncios) {
    const titulo = `
      <div class="ad-titulo">
        <h5>NEGOCIOS DESTACADOS</h5>
        <div class="linea-titulo">
          <span></span>
          <small>de tu municipio</small>
          <span></span>
        </div>
      </div>`;
    return titulo + anuncios.map(buildAdBox).join('');
  }

  // Recuerda, entre una página y otra, en qué foto se quedó cada anuncio
  // (así, aunque la gente navegue rápido entre páginas, con el tiempo
  // se alcanzan a ver TODAS las fotos de cada negocio, no solo la primera).
  function leerIndiceGuardado(anuncioId) {
    const val = parseInt(localStorage.getItem('anuncio-idx-' + anuncioId), 10);
    return isNaN(val) ? 0 : val;
  }
  function guardarIndice(anuncioId, idx) {
    localStorage.setItem('anuncio-idx-' + anuncioId, String(idx));
  }

  function startCarousel(box) {
    const imgs = box.querySelectorAll('img');
    if (!imgs.length) return;
    const anuncioId = box.dataset.anuncioId;

    let current = leerIndiceGuardado(anuncioId) % imgs.length;
    imgs[current].classList.add('is-visible');
    guardarIndice(anuncioId, current);

    if (imgs.length < 2) return;

    setInterval(() => {
      imgs[current].classList.remove('is-visible');
      current = (current + 1) % imgs.length;
      imgs[current].classList.add('is-visible');
      guardarIndice(anuncioId, current);
    }, 6000);
  }

  function renderColumns(anuncios) {
    // Usa los MISMOS anuncios que el home (posicion = 'derecha'), pero
    // se muestran del lado izquierdo dentro del dashboard.
    const anunciosHome = anuncios.filter(a => a.posicion === 'derecha');

    if (anunciosHome.length) {
      const col = document.createElement('div');
      col.className = 'anuncio-izq';
      col.innerHTML = buildColumn(anunciosHome);
      document.body.appendChild(col);
    }

    document.querySelectorAll('.ad-box').forEach(startCarousel);

    // En celular no hay espacio para la columna fija, así que en su
    // lugar usamos una burbuja flotante que aparece de rato en rato.
    if (window.innerWidth <= 1300) {
      const todasLasImagenes = anunciosHome.flatMap(a => a.imagenes);
      iniciarBurbujaFlotante(todasLasImagenes);
    }
  }

  function cargarAnuncios() {
    fetch('/api/anuncios')
      .then(res => res.json())
      .then(anuncios => renderColumns(anuncios))
      .catch(() => {
        // Si falla la petición (ej. sin conexión), simplemente no se muestran anuncios.
      });
  }

  /* =====================================================================
     BURBUJA FLOTANTE: solo en celular (donde no cabe la columna fija).
     Aparece de rato en rato mostrando un negocio a la vez, sin bloquear
     nada — el usuario puede ignorarla (se desvanece sola), cerrarla con
     la X, o tocarla para ver el anuncio completo en el modal de siempre.
     ===================================================================== */
  function iniciarBurbujaFlotante(imagenes) {
    if (!imagenes.length) return;

    // Guardamos en qué negocio íbamos en localStorage, para que si el
    // usuario cambia de página (o recarga), la rotación siga donde se
    // quedó, en vez de reiniciar siempre desde el primero.
    let indice = 0;
    try {
      indice = parseInt(localStorage.getItem('burbujaAnuncioIndice') || '0', 10) || 0;
    } catch (e) {
      // Si localStorage no está disponible (ej. modo incógnito estricto),
      // simplemente empezamos desde el principio.
    }

    function mostrarBurbuja() {
      // Si el usuario ya cerró una burbuja hace poco, o hay una activa,
      // no amontonamos otra encima.
      if (document.getElementById('burbujaAnuncio')) return;

      const img = imagenes[indice % imagenes.length];
      indice++;

      try {
        localStorage.setItem('burbujaAnuncioIndice', indice);
      } catch (e) {
        // Sin problema si no se puede guardar; solo no recordará la
        // posición entre páginas, pero seguirá funcionando igual.
      }

      const burbuja = document.createElement('div');
      burbuja.id = 'burbujaAnuncio';
      burbuja.className = 'burbuja-anuncio';
      burbuja.innerHTML = `
        <button type="button" class="burbuja-anuncio-cerrar" aria-label="Cerrar">&times;</button>
        <img src="${img.imagen}" alt="Negocio destacado">
        <div class="burbuja-anuncio-texto">
          <small>Negocio destacado</small>
          <strong>${(img.nombre_negocio || 'Conoce este negocio').replace(/</g, '&lt;')}</strong>
        </div>
      `;
      document.body.appendChild(burbuja);

      requestAnimationFrame(() => burbuja.classList.add('mostrar'));

      function quitarBurbuja() {
        burbuja.classList.remove('mostrar');
        setTimeout(() => burbuja.remove(), 300);
      }

      const autoOcultar = setTimeout(quitarBurbuja, 15000);

      burbuja.querySelector('.burbuja-anuncio-cerrar').addEventListener('click', function (e) {
        e.stopPropagation();
        clearTimeout(autoOcultar);
        quitarBurbuja();
      });

      burbuja.addEventListener('click', function () {
        clearTimeout(autoOcultar);
        quitarBurbuja();

        // Reutiliza el mismo modal grande que ya existe.
        const modal = document.getElementById('modalAnuncio');
        document.getElementById('modalAnuncioImg').src = img.imagen;

        const nombreEl = document.getElementById('modalAnuncioNombre');
        nombreEl.textContent = img.nombre_negocio || '';
        nombreEl.style.display = img.nombre_negocio ? 'block' : 'none';

        const esloganEl = document.getElementById('modalAnuncioEslogan');
        esloganEl.textContent = img.eslogan || '';
        esloganEl.style.display = img.eslogan ? 'block' : 'none';

        const botonLink = document.getElementById('modalAnuncioBotonLink');
        if (img.link_externo) {
          botonLink.href = img.link_externo;
          botonLink.style.display = 'inline-flex';
        } else {
          botonLink.style.display = 'none';
        }

        const botonUbicacion = document.getElementById('modalAnuncioBotonUbicacion');
        if (img.link_ubicacion) {
          botonUbicacion.href = img.link_ubicacion;
          botonUbicacion.style.display = 'inline-flex';
        } else {
          botonUbicacion.style.display = 'none';
        }

        modal.classList.add('activo');
      });
    }

    // La primera, a los 10 segundos; luego, cada 90 segundos.
    // OJO: en vez de solo usar setTimeout (que se reinicia cada vez que
    // cambias de página), guardamos en localStorage LA HORA EXACTA en la
    // que debe aparecer la siguiente. Así, aunque el usuario esté
    // navegando activamente entre varias páginas, la burbuja aparece a
    // tiempo en la página que esté viendo en ese momento — no espera a
    // que se quede quieto 10 segundos en cada página nueva.
    const INTERVALO_MS = 90000;
    const PRIMERA_ESPERA_MS = 10000;

    function programarSiguienteAparicion() {
      let proximaVez;
      try {
        proximaVez = parseInt(localStorage.getItem('burbujaProximaVez') || '0', 10);
      } catch (e) {
        proximaVez = 0;
      }

      const ahora = Date.now();

      if (!proximaVez) {
        // Primera vez que se calcula esto en todo el sitio.
        proximaVez = ahora + PRIMERA_ESPERA_MS;
        try { localStorage.setItem('burbujaProximaVez', proximaVez); } catch (e) {}
      }

      const espera = Math.max(0, proximaVez - ahora);
      setTimeout(function () {
        mostrarBurbuja();

        // Programa la siguiente, 90 segundos a partir de AHORA.
        try { localStorage.setItem('burbujaProximaVez', Date.now() + INTERVALO_MS); } catch (e) {}
        programarSiguienteAparicion();
      }, espera);
    }

    programarSiguienteAparicion();
  }

  /* =====================================================================
     MODAL: ver anuncio en grande al tocarlo, con nombre, eslogan y
     botones de "Visitar página" / "Cómo llegar" (según lo que el negocio
     haya llenado en su plan).
     El propio widget crea el recuadro (no hace falta agregarlo a cada
     página), y usa "delegación de eventos" para detectar clics en
     imágenes que se insertan después (como estas, vía fetch).
     ===================================================================== */
  function crearModalAnuncio() {
    if (document.getElementById('modalAnuncio')) return; // ya existe, no duplicar

    const modal = document.createElement('div');
    modal.id = 'modalAnuncio';
    modal.className = 'modal-anuncio-overlay';
    modal.innerHTML = `
      <button type="button" class="modal-anuncio-cerrar" id="modalAnuncioCerrarBtn">&times;</button>
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
    `;
    document.body.appendChild(modal);

    function cerrar() {
      modal.classList.remove('activo');
    }

    document.getElementById('modalAnuncioCerrarBtn').addEventListener('click', cerrar);

    document.addEventListener('click', function (e) {
      const img = e.target.closest('.ad-box img');
      if (img) {
        document.getElementById('modalAnuncioImg').src = img.src;

        const nombreEl = document.getElementById('modalAnuncioNombre');
        const nombre = img.dataset.nombre || '';
        nombreEl.textContent = nombre;
        nombreEl.style.display = nombre ? 'block' : 'none';

        const esloganEl = document.getElementById('modalAnuncioEslogan');
        const eslogan = img.dataset.eslogan || '';
        esloganEl.textContent = eslogan;
        esloganEl.style.display = eslogan ? 'block' : 'none';

        const botonLink = document.getElementById('modalAnuncioBotonLink');
        if (img.dataset.link) {
          botonLink.href = img.dataset.link;
          botonLink.style.display = 'inline-flex';
        } else {
          botonLink.style.display = 'none';
        }

        const botonUbicacion = document.getElementById('modalAnuncioBotonUbicacion');
        if (img.dataset.ubicacion) {
          botonUbicacion.href = img.dataset.ubicacion;
          botonUbicacion.style.display = 'inline-flex';
        } else {
          botonUbicacion.style.display = 'none';
        }

        modal.classList.add('activo');
        return;
      }
      if (e.target === modal) {
        cerrar();
      }
    });

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') cerrar();
    });
  }

  document.addEventListener('DOMContentLoaded', function () {
    crearModalAnuncio();
    cargarAnuncios();
  });
})();
