/* =====================================================================
   DATOS DE EJEMPLO (MOCK) - Dashboard de Usuario
   Empleabilidad Zacapoaxtla

   Simula lo que vendría de tu base de datos MySQL (ver
   /DATABASE/empleabilidad_zacapoaxtla_FINAL.sql). Cuando conectes el
   backend real, reemplaza cada arreglo por el resultado de tu API.

   Este archivo comparte la MISMA estructura que
   /adminDash2/js/admin-data.js para que el admin y el usuario lean
   siempre el mismo modelo de datos (tablas: municipios, localidades,
   anuncios, anuncio_imagenes, etc).
   ===================================================================== */

const DASH_DB = {

  municipios: [
    { id: 1, nombre: "Zacapoaxtla" }
  ],

  localidades: [
    { id: 1, municipio_id: 1, nombre: "Zacapoaxtla (cabecera municipal)" },
    { id: 2, municipio_id: 1, nombre: "Ahuacatlán" },
    { id: 9, municipio_id: 1, nombre: "La Cumbre" },
    { id: 11, municipio_id: 1, nombre: "San Juan Tahitic" }
  ],

  usuarioActual: {
    id: 2,
    nombre: "Miguel Ángel Pérez",
    localidad_id: 1
  },

  anuncios: [
    { id: 1, municipio_id: 1, posicion: "izquierda", orden: 1, estado: "activo" },
    { id: 2, municipio_id: 1, posicion: "izquierda", orden: 2, estado: "activo" },
    { id: 3, municipio_id: 1, posicion: "izquierda", orden: 3, estado: "activo" },
    { id: 4, municipio_id: 1, posicion: "derecha", orden: 1, estado: "activo" },
    { id: 5, municipio_id: 1, posicion: "derecha", orden: 2, estado: "activo" },
    { id: 6, municipio_id: 1, posicion: "derecha", orden: 3, estado: "activo" }
  ],

  anuncio_imagenes: [
    { id: 1, anuncio_id: 1, imagen: "/assets/usuario/img/anuncios/anuncio1.png", orden: 1 },
    { id: 2, anuncio_id: 1, imagen: "/assets/usuario/img/anuncios/anuncio2.png", orden: 2 },

    { id: 3, anuncio_id: 2, imagen: "/assets/usuario/img/anuncios/anuncio3.png", orden: 1 },
    { id: 4, anuncio_id: 2, imagen: "/assets/usuario/img/anuncios/anuncio5.png", orden: 2 },

    { id: 5, anuncio_id: 3, imagen: "/assets/usuario/img/anuncios/anuncio6.png", orden: 1 },
    { id: 6, anuncio_id: 3, imagen: "/assets/usuario/img/anuncios/anuncio7.png", orden: 2 },

    { id: 7, anuncio_id: 4, imagen: "/assets/usuario/img/anuncios/anuncio7.png", orden: 1 },
    { id: 8, anuncio_id: 4, imagen: "/assets/usuario/img/anuncios/anuncio6.png", orden: 2 },

    { id: 9, anuncio_id: 5, imagen: "/assets/usuario/img/anuncios/anuncio3.png", orden: 1 },
    { id: 10, anuncio_id: 5, imagen: "/assets/usuario/img/anuncios/anuncio5.png", orden: 2 },

    { id: 11, anuncio_id: 6, imagen: "/assets/usuario/img/anuncios/anuncio1.png", orden: 1 },
    { id: 12, anuncio_id: 6, imagen: "/assets/usuario/img/anuncios/anuncio2.png", orden: 2 }
  ]
};

function dashGetMunicipioDeLocalidad(localidadId) {
  const loc = DASH_DB.localidades.find(l => l.id === localidadId) || {};
  return DASH_DB.municipios.find(m => m.id === loc.municipio_id) || {};
}

function dashGetImagenesAnuncio(anuncioId) {
  return DASH_DB.anuncio_imagenes
    .filter(i => i.anuncio_id === anuncioId)
    .sort((a, b) => a.orden - b.orden);
}

function dashGetAnunciosDestacados() {

  const municipioId = DASH_DB.usuarioActual.localidad_id
    ? dashGetMunicipioDeLocalidad(DASH_DB.usuarioActual.localidad_id).id
    : DASH_DB.municipios[0].id;

  return DASH_DB.anuncios
    .filter(a => a.municipio_id === municipioId && a.estado === "activo")
    .sort((a, b) =>
      a.posicion === b.posicion
        ? a.orden - b.orden
        : a.posicion.localeCompare(b.posicion)
    )
    .map(a => ({
      ...a,
      imagenes: dashGetImagenesAnuncio(a.id)
    }))
    .filter(a => a.imagenes.length > 0);
}
