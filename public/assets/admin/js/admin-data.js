/* =====================================================================
   DATOS DE EJEMPLO (MOCK) - Panel de Administración
   Empleabilidad Zacapoaxtla

   Simula lo que vendría de tu base de datos MySQL. Cuando conectes el
   backend real, reemplaza cada arreglo por el resultado de tu API.
   Busca los comentarios "TODO: conectar a la API" en cada página.
   ===================================================================== */

const DB = {

  municipios: [
    { id: 1, nombre: "Zacapoaxtla" }
  ],

  localidades: [
    { id: 1, municipio_id: 1, nombre: "Zacapoaxtla (cabecera municipal)" },
    { id: 2, municipio_id: 1, nombre: "Ahuacatlán" },
    { id: 3, municipio_id: 1, nombre: "Atacpan" },
    { id: 4, municipio_id: 1, nombre: "Comaltepec" },
    { id: 5, municipio_id: 1, nombre: "Xalacapan" },
    { id: 6, municipio_id: 1, nombre: "Tatzecuala" },
    { id: 7, municipio_id: 1, nombre: "Tatoxcac" },
    { id: 8, municipio_id: 1, nombre: "Oxtempan" },
    { id: 9, municipio_id: 1, nombre: "Las Lomas" },
    { id: 10, municipio_id: 1, nombre: "La Libertad" },
    { id: 11, municipio_id: 1, nombre: "San Juan Tahitic" },
    { id: 12, municipio_id: 1, nombre: "El Molino" },
    { id: 13, municipio_id: 1, nombre: "Xaltetela" },
    { id: 14, municipio_id: 1, nombre: "Xochitepec" },
    { id: 15, municipio_id: 1, nombre: "Ixticpan" },
    { id: 16, municipio_id: 1, nombre: "Nexticapan" },
    { id: 17, municipio_id: 1, nombre: "Xilita" },
    { id: 18, municipio_id: 1, nombre: "Tlaltehui" },
    { id: 19, municipio_id: 1, nombre: "Ahuacatán" },
    { id: 20, municipio_id: 1, nombre: "Cuacuilco" },
    { id: 21, municipio_id: 1, nombre: "El Progreso" },
    { id: 22, municipio_id: 1, nombre: "Las Delicias" },
    { id: 23, municipio_id: 1, nombre: "La Cumbre" },
    { id: 24, municipio_id: 1, nombre: "Los Manantiales" },
    { id: 25, municipio_id: 1, nombre: "La Unión" },
    { id: 26, municipio_id: 1, nombre: "Buenavista" },
    { id: 27, municipio_id: 1, nombre: "El Mirador" },
    { id: 28, municipio_id: 1, nombre: "El Paraíso" },
    { id: 29, municipio_id: 1, nombre: "El Carmen" },
    { id: 30, municipio_id: 1, nombre: "La Mesa" },
    { id: 31, municipio_id: 1, nombre: "El Rosario" },
    { id: 32, municipio_id: 1, nombre: "El Fresno" },
    { id: 33, municipio_id: 1, nombre: "La Aurora" },
    { id: 34, municipio_id: 1, nombre: "El Potrero" },
    { id: 35, municipio_id: 1, nombre: "Tepetitán" },
    { id: 36, municipio_id: 1, nombre: "El Carrizal" },
    { id: 37, municipio_id: 1, nombre: "La Providencia" },
    { id: 38, municipio_id: 1, nombre: "Rancho Nuevo" },
    { id: 39, municipio_id: 1, nombre: "El Arenal" },
    { id: 40, municipio_id: 1, nombre: "Las Palmas" },
    { id: 41, municipio_id: 1, nombre: "El Refugio" },
    { id: 42, municipio_id: 1, nombre: "San Miguel Tenextepec" },
    { id: 43, municipio_id: 1, nombre: "Tres Cruces" },
    { id: 44, municipio_id: 1, nombre: "Plan de Guadalupe" },
    { id: 45, municipio_id: 1, nombre: "La Concepción" },
    { id: 46, municipio_id: 1, nombre: "Agua Blanca" },
    { id: 47, municipio_id: 1, nombre: "Cuauhtémoc" },
    { id: 48, municipio_id: 1, nombre: "El Pedregal" },
    { id: 49, municipio_id: 1, nombre: "El Encinal" },
    { id: 50, municipio_id: 1, nombre: "La Joya" }

  ],

  usuarios: [
    { id: 1, nombre: "Admin Principal", email: "admin@empleabilidadzacapoaxtla.com", telefono: "2331000000", localidad_id: 1, rol: "admin", modo_activo: "trabajador", estado: "activo", motivo_suspension: null, verificacion_estado: "aprobado", created_at: "2026-01-01" },
    { id: 2, nombre: "Miguel Ángel Pérez", email: "miguel.perez@mail.com", telefono: "2331001122", localidad_id: 1, rol: "usuario", modo_activo: "trabajador", estado: "activo", motivo_suspension: null, verificacion_estado: "aprobado", created_at: "2026-05-02" },
    { id: 3, nombre: "Rosa Isela Martínez", email: "rosa.martinez@mail.com", telefono: "2331002233", localidad_id: 2, rol: "usuario", modo_activo: "empleador", estado: "activo", motivo_suspension: null, verificacion_estado: "pendiente", created_at: "2026-05-14" },
    { id: 4, nombre: "Juan Carlos Vázquez", email: "jc.vazquez@mail.com", telefono: "2331003344", localidad_id: 11, rol: "usuario", modo_activo: "trabajador", estado: "activo", motivo_suspension: null, verificacion_estado: "pendiente", created_at: "2026-06-01" },
    { id: 5, nombre: "Fabricación y Aceros del Norte", email: "contacto@acerosnorte.com", telefono: "2331004455", localidad_id: 1, rol: "usuario", modo_activo: "empleador", estado: "activo", motivo_suspension: null, verificacion_estado: "aprobado", created_at: "2026-06-10" },
    { id: 6, nombre: "Lucía Fernanda Torres", email: "lucia.torres@mail.com", telefono: "2331005566", localidad_id: 9, rol: "usuario", modo_activo: "trabajador", estado: "suspendido", motivo_suspension: "Publicó contenido falso en una vacante y recibió varios reportes.", verificacion_estado: "rechazado", created_at: "2026-06-15" }
  ],

  documentos_verificacion: [
    { id: 1, usuario_id: 3, tipo_documento: "ine", archivo: "https://i.imgur.com/2Y4tYCH.png", estado: "pendiente" },
    { id: 2, usuario_id: 3, tipo_documento: "selfie", archivo: "https://i.imgur.com/2Y4tYCH.png", estado: "pendiente" },
    { id: 3, usuario_id: 4, tipo_documento: "ine", archivo: "https://i.imgur.com/2Y4tYCH.png", estado: "pendiente" },
    { id: 4, usuario_id: 4, tipo_documento: "selfie", archivo: "https://i.imgur.com/2Y4tYCH.png", estado: "pendiente" }
  ],

  categorias: [
    { id: 1, nombre: "Construcción" },
    { id: 2, nombre: "Hogar y limpieza" },
    { id: 3, nombre: "Talleres y mecánica" },
    { id: 4, nombre: "Educación y clases" }
  ],

  subcategorias: [
    { id: 1, categoria_id: 1, nombre: "Albañilería" },
    { id: 2, categoria_id: 1, nombre: "Plomería" },
    { id: 3, categoria_id: 2, nombre: "Limpieza del hogar" },
    { id: 4, categoria_id: 3, nombre: "Mecánica automotriz" },
    { id: 5, categoria_id: 4, nombre: "Clases particulares" }
  ],

  servicios: [
    { id: 1, usuario_id: 2, categoria_id: 1, subcategoria_id: 1, titulo: "Albañil con experiencia", precio: 350, estado: "activo", created_at: "2026-06-02" },
    { id: 2, usuario_id: 4, categoria_id: 3, subcategoria_id: 4, titulo: "Mecánico a domicilio", precio: 250, estado: "activo", created_at: "2026-06-20" },
    { id: 3, usuario_id: 6, categoria_id: 2, subcategoria_id: 3, titulo: "Limpieza de casas y oficinas", precio: 200, estado: "inactivo", created_at: "2026-06-22" }
  ],

  contrataciones: [
    { id: 1, servicio_id: 1, contratante_id: 3, trabajador_id: 2, estado: "finalizado", fecha_inicio: "2026-06-05", fecha_fin: "2026-06-06" },
    { id: 2, servicio_id: 2, contratante_id: 5, trabajador_id: 4, estado: "aceptado", fecha_inicio: "2026-07-10", fecha_fin: null }
  ],

  vacantes: [
    { id: 1, empleador_id: 5, titulo: "Se buscan 3 albañiles", publicante: "Aceros del Norte", ubicacion: "Zacapoaxtla centro", trabajadores_requeridos: 3, tipo_pago: "Pago semanal", salario: "$1,800 - $2,200", experiencia: "1 año mínimo", contrato: "Temporal", fecha_limite: "2026-07-25", estado: "activa", created_at: "2026-07-01" },
    { id: 2, empleador_id: 5, titulo: "Ayudante general de bodega", publicante: "Aceros del Norte", ubicacion: "San Juan Tahitic", trabajadores_requeridos: 1, tipo_pago: "Pago mensual", salario: "$6,500", experiencia: "Sin experiencia", contrato: "Fijo", fecha_limite: "2026-07-20", estado: "cerrada", created_at: "2026-06-15" },
    { id: 3, empleador_id: 2, titulo: "Chofer con licencia tipo C", publicante: "Miguel Pérez", ubicacion: "Zacapoaxtla", trabajadores_requeridos: 1, tipo_pago: "Pago quincenal", salario: "$3,200", experiencia: "2 años mínimo", contrato: "Eventual", fecha_limite: "2026-06-30", estado: "vencida", created_at: "2026-06-01" }
  ],

  postulaciones: [
    { id: 1, vacante_id: 1, postulante_id: 4, estado: "pendiente", created_at: "2026-07-02" },
    { id: 2, vacante_id: 1, postulante_id: 6, estado: "rechazado", created_at: "2026-07-03" },
    { id: 3, vacante_id: 2, postulante_id: 3, estado: "contratado", created_at: "2026-06-16" }
  ],

  anuncios: [
    { id: 1, creado_por: 1, municipio_id: 1, posicion: "izquierda", orden: 1, estado: "activo" },
    { id: 2, creado_por: 1, municipio_id: 1, posicion: "izquierda", orden: 2, estado: "activo" },
    { id: 3, creado_por: 1, municipio_id: 1, posicion: "izquierda", orden: 3, estado: "inactivo" },
    { id: 4, creado_por: 1, municipio_id: 1, posicion: "derecha",   orden: 1, estado: "activo" },
    { id: 5, creado_por: 1, municipio_id: 1, posicion: "derecha",   orden: 2, estado: "activo" },
    { id: 6, creado_por: 1, municipio_id: 1, posicion: "derecha",   orden: 3, estado: "inactivo" }
  ],

  anuncio_imagenes: [
    { id: 1, anuncio_id: 1, imagen: "https://i.imgur.com/8yF3fpN.png", orden: 1 },
    { id: 2, anuncio_id: 1, imagen: "https://i.imgur.com/8yF3fpN.png", orden: 2 },
    { id: 3, anuncio_id: 2, imagen: "https://i.imgur.com/8yF3fpN.png", orden: 1 },
    { id: 4, anuncio_id: 4, imagen: "https://i.imgur.com/8yF3fpN.png", orden: 1 },
    { id: 5, anuncio_id: 4, imagen: "https://i.imgur.com/8yF3fpN.png", orden: 2 },
    { id: 6, anuncio_id: 4, imagen: "https://i.imgur.com/8yF3fpN.png", orden: 3 },
    { id: 7, anuncio_id: 5, imagen: "https://i.imgur.com/8yF3fpN.png", orden: 1 }
  ],

  reportes: [
    { id: 1, usuario_reporta_id: 3, usuario_reportado_id: 6, tipo_objeto: "usuario", objeto_id: null, motivo: "Perfil sospechoso / posible fraude", descripcion: "Me pidió un anticipo antes de realizar el trabajo y luego no contestó más.", estado: "pendiente", created_at: "2026-07-05" },
    { id: 2, usuario_reporta_id: 4, usuario_reportado_id: 2, tipo_objeto: "servicio", objeto_id: 1, motivo: "Información falsa en el anuncio", descripcion: "El precio publicado no coincide con lo que cobró al llegar.", estado: "pendiente", created_at: "2026-07-08" },
    { id: 3, usuario_reporta_id: 2, usuario_reportado_id: 5, tipo_objeto: "vacante", objeto_id: 2, motivo: "Vacante duplicada", descripcion: null, estado: "resuelto", created_at: "2026-06-18" }
  ]
};

function getUsuario(id) { return DB.usuarios.find(u => u.id === id); }
function getLocalidad(id) { return DB.localidades.find(l => l.id === id) || {}; }
function getMunicipioDeLocalidad(localidadId) {
  const loc = getLocalidad(localidadId);
  return DB.municipios.find(m => m.id === loc.municipio_id) || {};
}
function getMunicipio(id) { return DB.municipios.find(m => m.id === id) || {}; }
function getCategoria(id) { return DB.categorias.find(c => c.id === id) || {}; }
function getSubcategoria(id) { return DB.subcategorias.find(s => s.id === id) || {}; }
function getVacante(id) { return DB.vacantes.find(v => v.id === id); }
function getImagenesAnuncio(anuncioId) { return DB.anuncio_imagenes.filter(i => i.anuncio_id === anuncioId).sort((a,b) => a.orden - b.orden); }
