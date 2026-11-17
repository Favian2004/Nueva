window.addEventListener("load", function () {
  const loader = document.getElementById("loader");
  if (loader) loader.classList.toggle("loader2");
});

// 🔔 NOTIFICACIONES
function showToast(message) {
  const toast = document.createElement("div");
  toast.className = "toast-custom";
  toast.textContent = message;

  document.body.appendChild(toast);

  setTimeout(() => toast.classList.add("show"), 100);

  setTimeout(() => {
    toast.classList.remove("show");
    setTimeout(() => toast.remove(), 300);
  }, 2500);
}

// ================= FAVORITOS =================
// Cada favorito se guarda con el id del servicio (así no se repite aunque cambie el
// título) y se abre con su enlace. Se guarda solo en este navegador (localStorage).
// Los textos se insertan con textContent y no con innerHTML porque los títulos los
// escriben los usuarios.

const btnFavorites = document.getElementById("btnFavorites");
const favBox = document.getElementById("favBox");
const favItems = document.getElementById("favItems");
const favCount = document.getElementById("favCount");
const clearFavorites = document.getElementById("clearFavorites");

const favItemsMobile = document.getElementById("favItemsMobile");
const favCountMobile = document.getElementById("favCountMobile");
const clearFavoritesMobile = document.getElementById("clearFavoritesMobile");

let favoritos = [];

try {
  const guardados = JSON.parse(localStorage.getItem("favoritos") || "[]");
  // Se descartan los favoritos de la versión anterior (no traían id ni enlace).
  favoritos = Array.isArray(guardados) ? guardados.filter(f => f && f.id) : [];
} catch (e) {
  favoritos = [];
}

function guardarFavoritos() {
  try { localStorage.setItem("favoritos", JSON.stringify(favoritos)); } catch (e) { /* sin almacenamiento */ }
}

function esFavorito(id) {
  return favoritos.some(f => String(f.id) === String(id));
}

function celda(contenido) {
  const td = document.createElement("td");
  td.appendChild(contenido);
  return td;
}

function filaFavorito(item) {
  const tr = document.createElement("tr");

  const img = document.createElement("img");
  img.src = item.img || "";
  img.width = 50;
  img.alt = "";
  tr.appendChild(celda(img));

  const enlace = document.createElement("a");
  enlace.href = "/servicio/" + encodeURIComponent(item.id);
  enlace.textContent = item.name;
  enlace.style.fontWeight = "600";
  tr.appendChild(celda(enlace));

  const desc = document.createElement("span");
  desc.textContent = item.description || "";
  const tdDesc = celda(desc);
  tdDesc.style.maxWidth = "200px";
  tr.appendChild(tdDesc);

  const quitar = document.createElement("button");
  quitar.type = "button";
  quitar.className = "btn btn-sm btn-danger btn-remove";
  quitar.textContent = "X";
  quitar.title = "Quitar de favoritos";
  quitar.addEventListener("click", () => quitarFavorito(item.id));
  tr.appendChild(celda(quitar));

  return tr;
}

function renderFavorites() {
  // Escritorio y celular usan la misma tabla de filas
  if (favItems) {
    favItems.replaceChildren();
    if (!favoritos.length) {
      const tr = document.createElement("tr");
      const td = document.createElement("td");
      td.colSpan = 4;
      td.textContent = "Aún no tienes favoritos.";
      td.className = "text-center text-muted";
      tr.appendChild(td);
      favItems.appendChild(tr);
    } else {
      favoritos.forEach(item => favItems.appendChild(filaFavorito(item)));
    }
  }

  if (favItemsMobile) {
    favItemsMobile.replaceChildren();
    if (!favoritos.length) {
      favItemsMobile.textContent = "No tienes favoritos aún.";
    } else {
      const tabla = document.createElement("table");
      tabla.className = "table";
      const cuerpo = document.createElement("tbody");
      favoritos.forEach(item => cuerpo.appendChild(filaFavorito(item)));
      tabla.appendChild(cuerpo);
      favItemsMobile.appendChild(tabla);
    }
  }

  if (favCount) favCount.textContent = favoritos.length;
  if (favCountMobile) favCountMobile.textContent = favoritos.length;

  // Corazones de las tarjetas: rellenos si ya es favorito
  document.querySelectorAll(".product .btn-fav").forEach(btn => {
    const id = btn.closest(".product")?.dataset.id;
    const activo = !!id && esFavorito(id);
    btn.classList.toggle("activo", activo);
    btn.setAttribute("aria-pressed", activo ? "true" : "false");
    btn.title = activo ? "Quitar de favoritos" : "Agregar a favoritos";
    const icono = btn.querySelector("i");
    if (icono) icono.className = activo ? "bi bi-heart-fill" : "bi bi-heart";
  });

  guardarFavoritos();
}

function quitarFavorito(id) {
  favoritos = favoritos.filter(f => String(f.id) !== String(id));
  renderFavorites();
}

// Corazón de cada tarjeta: agrega o quita
document.querySelectorAll(".product .btn-fav").forEach(btn => {
  btn.addEventListener("click", () => {
    const product = btn.closest(".product");
    if (!product || !product.dataset.id) return;

    const id = product.dataset.id;
    if (esFavorito(id)) {
      quitarFavorito(id);
      showToast("Quitado de favoritos");
      return;
    }

    const imgEl = product.querySelector("img");
    const textoEl = product.querySelector(".product-text");
    favoritos.push({
      id,
      name: product.dataset.titulo || "Servicio",
      img: imgEl ? imgEl.src : "",
      description: textoEl ? textoEl.textContent.trim() : "",
    });
    renderFavorites();
    showToast("Agregado a favoritos ❤️");
  });
});

// Abrir / cerrar la cajita de favoritos (escritorio)
if (btnFavorites && favBox) {
  btnFavorites.addEventListener("click", e => {
    e.preventDefault();
    favBox.style.display = favBox.style.display === "none" ? "block" : "none";
  });

  // Se cierra al tocar fuera de ella
  document.addEventListener("click", e => {
    if (favBox.style.display !== "none" && !favBox.contains(e.target) && !btnFavorites.contains(e.target)) {
      favBox.style.display = "none";
    }
  });
}

function vaciarFavoritos() {
  favoritos = [];
  renderFavorites();
}

if (clearFavorites) clearFavorites.addEventListener("click", vaciarFavoritos);
if (clearFavoritesMobile) clearFavoritesMobile.addEventListener("click", vaciarFavoritos);

renderFavorites();
