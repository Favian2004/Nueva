/* =====================================================================
   ANUNCIOS LATERALES - inserta las columnas "Negocios destacados de tu
   municipio" (izquierda y derecha), igual que en index.html, en
   cualquier página del dashboard de usuario.

   Ahora jala los datos reales de tu base de datos vía GET /api/anuncios.
   ===================================================================== */

(function () {
  function buildAdBox(anuncio) {
    const imgsHtml = anuncio.imagenes
      .map((img, idx) => `<img src="${img.imagen}" alt="Negocio destacado" class="${idx === 0 ? 'is-visible' : ''}">`)
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

  function startCarousel(box) {
    const imgs = box.querySelectorAll('img');
    if (imgs.length < 2) return;
    let current = 0;
    setInterval(() => {
      imgs[current].classList.remove('is-visible');
      current = (current + 1) % imgs.length;
      imgs[current].classList.add('is-visible');
    }, 2800);
  }

  function renderColumns(anuncios) {
    const izquierda = anuncios.filter(a => a.posicion === 'izquierda');
    const derecha = anuncios.filter(a => a.posicion === 'derecha');

    if (izquierda.length) {
      const colIzq = document.createElement('div');
      colIzq.className = 'anuncio-izq';
      colIzq.innerHTML = buildColumn(izquierda);
      document.body.appendChild(colIzq);
    }

    if (derecha.length) {
      const colDer = document.createElement('div');
      colDer.className = 'anuncio-der';
      colDer.innerHTML = buildColumn(derecha);
      document.body.appendChild(colDer);
    }

    document.querySelectorAll('.ad-box').forEach(startCarousel);
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
     MODAL: ver anuncio en grande al tocarlo
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
      <img id="modalAnuncioImg" src="" alt="Anuncio">
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
