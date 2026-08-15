"use strict";

// ====== SIDEBAR TOGGLE ======
Array.from(document.getElementsByClassName("jb-aside-mobile-toggle")).forEach(function(e) {
  e.addEventListener("click", function(e) {
    var icon = e.currentTarget.getElementsByClassName("mdi")[0];
    document.documentElement.classList.toggle("has-aside-mobile-expanded");
    icon.classList.toggle("mdi-forwardburger");
    icon.classList.toggle("mdi-backburger");
  });
});

Array.from(document.getElementsByClassName("jb-navbar-menu-toggle")).forEach(function(e) {
  e.addEventListener("click", function(e) {
    var icon = e.currentTarget.getElementsByClassName("mdi")[0];
    document.getElementById(e.currentTarget.getAttribute("data-target")).classList.toggle("is-active");
    icon.classList.toggle("mdi-dots-vertical");
    icon.classList.toggle("mdi-close");
  });
});

Array.from(document.getElementsByClassName("has-dropdown-icon")).forEach(function(e) {
  e.addEventListener("click", function(e) {
    var icon = e.currentTarget.getElementsByClassName("mdi")[0];
    e.currentTarget.parentNode.classList.toggle("is-active");
    if(icon){ icon.classList.toggle("mdi-plus"); icon.classList.toggle("mdi-minus"); }
  });
});

// ====== CARRITO & FAVORITOS ======
var cart = JSON.parse(localStorage.getItem('cart') || '[]');
var favs = JSON.parse(localStorage.getItem('favs') || '[]');

function saveCart(){ localStorage.setItem('cart', JSON.stringify(cart)); }
function saveFavs(){ localStorage.setItem('favs', JSON.stringify(favs)); }

function renderCart(){
  var tbody = document.getElementById('cartItems');
  var totalEl = document.getElementById('total');
  var countEl = document.getElementById('cartCount');
  if(!tbody) return;
  tbody.innerHTML = '';
  var total = 0;
  if(cart.length === 0){
    tbody.innerHTML = '<tr><td colspan="4" class="cart-empty-msg">Tu carrito está vacío</td></tr>';
  } else {
    cart.forEach(function(item, i){
      total += item.price;
      tbody.innerHTML += '<tr>' +
        '<td><img src="' + item.img + '" alt="" style="width:40px;height:40px;object-fit:cover;border-radius:6px"></td>' +
        '<td>' + item.name + '</td>' +
        '<td>$' + item.price + '</td>' +
        '<td><button onclick="removeCart(' + i + ')" style="border:none;background:none;color:#e63946;cursor:pointer;font-size:16px">×</button></td>' +
      '</tr>';
    });
  }
  if(totalEl) totalEl.textContent = total;
  if(countEl) countEl.textContent = cart.length;
}

function renderFavs(){
  var tbody = document.getElementById('favItems');
  var countEl = document.getElementById('favCount');
  if(!tbody) return;
  tbody.innerHTML = '';
  if(favs.length === 0){
    tbody.innerHTML = '<tr><td colspan="4" class="cart-empty-msg">Sin favoritos aún</td></tr>';
  } else {
    favs.forEach(function(item, i){
      tbody.innerHTML += '<tr>' +
        '<td><img src="' + item.img + '" alt="" style="width:40px;height:40px;object-fit:cover;border-radius:6px"></td>' +
        '<td>' + item.name + '</td>' +
        '<td>' + item.desc + '</td>' +
        '<td><button onclick="removeFav(' + i + ')" style="border:none;background:none;color:#e63946;cursor:pointer;font-size:16px">×</button></td>' +
      '</tr>';
    });
  }
  if(countEl) countEl.textContent = favs.length;
}

window.removeCart = function(i){ cart.splice(i,1); saveCart(); renderCart(); };
window.removeFav  = function(i){ favs.splice(i,1); saveFavs(); renderFavs(); updateFavButtons(); };

// ====== ELEMENTOS DEL CARRITO Y FAVORITOS ======
var btnCart = document.getElementById('btnCart');
var cartBox = document.getElementById('cartBox');

var btnFav = document.getElementById('btnFav');
var favBox = document.getElementById('favBox');

// Si la función no existe, evita errores
if (typeof updateFavButtons !== "function") {
  function updateFavButtons() {}
}



if (btnCart && cartBox) {
  btnCart.addEventListener('click', function (e) {
    e.preventDefault();
    e.stopPropagation();

    cartBox.classList.toggle('is-open');

    if (favBox) {
      favBox.classList.remove('is-open');
    }
  });
}

if (btnFav && favBox) {
  btnFav.addEventListener('click', function (e) {
    e.preventDefault();
    e.stopPropagation();

    favBox.classList.toggle('is-open');

    if (cartBox) {
      cartBox.classList.remove('is-open');
    }
  });
}

document.addEventListener('click', function (e) {

  if (
    cartBox &&
    btnCart &&
    !cartBox.contains(e.target) &&
    !btnCart.contains(e.target)
  ) {
    cartBox.classList.remove('is-open');
  }

  if (
    favBox &&
    btnFav &&
    !favBox.contains(e.target) &&
    !btnFav.contains(e.target)
  ) {
    favBox.classList.remove('is-open');
  }

});

var emptyCart = document.getElementById('emptyCart');
var clearFavs = document.getElementById('clearFavorites');
if(emptyCart)  emptyCart.addEventListener('click',  function(){ cart=[]; saveCart();  renderCart(); });
if(clearFavs)  clearFavs.addEventListener('click', function(){ favs=[]; saveFavs();  renderFavs(); updateFavButtons(); });

var checkout = document.getElementById('checkout');
if(checkout) checkout.addEventListener('click', function(){
  if(cart.length === 0){ alert('Tu carrito está vacío'); return; }
  alert('¡Gracias! Tu solicitud de ' + cart.length + ' servicio(s) fue enviada.');
  cart=[]; saveCart(); renderCart();
  if(cartBox) cartBox.classList.remove('is-open');
});



// ====== FILTRO SERVICIOS (verEmpleos + index) ======
var searchInput = document.querySelector('.filter-item input[type="text"]');
if(searchInput){
  searchInput.addEventListener('input', function(){
    var q = this.value.toLowerCase();
    document.querySelectorAll('.servicio-card, .product').forEach(function(card){
      var text = card.textContent.toLowerCase();
      card.closest('.column, .product') && (card.closest('.column') || card).style && (
        (card.closest('.column') || card).style.display = text.includes(q) ? '' : 'none'
      );
    });
  });
}

// Init
renderCart();
renderFavs();

