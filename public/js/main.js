window.addEventListener("load", function () {
  document.getElementById("loader").classList.toggle("loader2");
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




//RENDER INICIAL
renderCart();


// ELEMENTOS FAVORITOS DESKTOP ------------------------------------------------------------------------
const btnFavorites = document.getElementById("btnFavorites");
const favBox = document.getElementById("favBox");
const favItems = document.getElementById("favItems");
const favCount = document.getElementById("favCount");
const clearFavorites = document.getElementById("clearFavorites");

// ELEMENTOS FAVORITOS MOBILE
const favItemsMobile = document.getElementById("favItemsMobile");
const favCountMobile = document.getElementById("favCountMobile");
const clearFavoritesMobile = document.getElementById("clearFavoritesMobile");

let favoritos = [];

// CARGAR FAVORITOS GUARDADOS
const favoritosGuardados = localStorage.getItem("favoritos");
if (favoritosGuardados) {
  favoritos = JSON.parse(favoritosGuardados);
  renderFavorites();
}

// MOSTRAR / OCULTAR DROPDOWN DESKTOP
if (btnFavorites) {
  btnFavorites.addEventListener("click", () => {
    favBox.style.display = favBox.style.display === "none" ? "block" : "none";
  });
}

// AGREGAR FAVORITO
document.querySelectorAll(".btn-fav").forEach(btn => {
  btn.addEventListener("click", e => {
    const product = e.target.closest(".product");
    const name = product.querySelector("h4").textContent;
    const img = product.querySelector("img").src;
    const description = product.querySelector(".product-text").textContent;

    // Evitar duplicados
    if (!favoritos.some(f => f.name === name)) {
      favoritos.push({ name, img, description });
      renderFavorites();
      showToast(`"${name}" agregado a favoritos ❤️`);
    }
  });
});

// RENDER FAVORITOS (desktop + mobile)
function renderFavorites() {
  // Desktop
  if (favItems) {
    favItems.innerHTML = "";
    favoritos.forEach((item, index) => {
      favItems.innerHTML += `
        <tr>
          <td><img src="${item.img}" width="50"></td>
          <td>${item.name}</td>
          <td style="max-width: 200px;">${item.description}</td>
          <td>
            <button class="btn btn-sm btn-success btn-cart" onclick="addToCartFromFav(${index})">✓</button>
            <button class="btn btn-sm btn-danger btn-remove" onclick="removeFavorite(${index})">X</button>
          </td>
        </tr>
      `;
    });
  }

  // Mobile
  if (favItemsMobile) {
    if (favoritos.length === 0) {
      favItemsMobile.innerHTML = "No tienes favoritos aún.";
    } else {
      let html = `<table class="table">
        <thead>
          <tr>
            <th>Img</th>
            <th>Servicio</th>
            <th>Descripción</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>`;
      favoritos.forEach((item, index) => {
        html += `
          <tr>
            <td><img src="${item.img}" width="50"></td>
            <td>${item.name}</td>
            <td>${item.description}</td>
            <td>
              <button class="btn btn-sm btn-success btn-cart" onclick="addToCartFromFav(${index})">✓</button>
              <button class="btn btn-sm btn-danger btn-remove" onclick="removeFavorite(${index})">X</button>
            </td>
          </tr>
        `;
      });
      html += `</tbody></table>`;
      favItemsMobile.innerHTML = html;
    }
  }

  // Actualizar contadores
  if (favCount) favCount.textContent = favoritos.length;
  if (favCountMobile) favCountMobile.textContent = favoritos.length;

  // Guardar en localStorage
  localStorage.setItem("favoritos", JSON.stringify(favoritos));
}

// ELIMINAR FAVORITO
function removeFavorite(index) {
  favoritos.splice(index, 1);
  renderFavorites();
}

// VACIAR FAVORITOS
if (clearFavorites) clearFavorites.addEventListener("click", () => {
  favoritos = [];
  renderFavorites();
});

if (clearFavoritesMobile) clearFavoritesMobile.addEventListener("click", () => {
  favoritos = [];
  renderFavorites();
});



