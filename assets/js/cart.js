// Retorna el carrito desde localStorage (o array vacío si no existe)
function getCart() {
  return JSON.parse(localStorage.getItem("cart") || "[]");
}

// Guarda el carrito en localStorage
function saveCart(items) {
  localStorage.setItem("cart", JSON.stringify(items));
}

/**
 * Agrega un producto al carrito.
 * @param {number} id        ID del producto
 * @param {string} name      Nombre del producto
 * @param {string} image     Ruta/base64 de la imagen
 * @param {number} quantity  Cantidad a añadir (por defecto 1)
 */
function addProductToCart(id, name, image, quantity = 1) {
  const cart = getCart();
  const index = cart.findIndex(item => item.id === id);

  if (index >= 0) {
    // Si ya existe, aumentar cantidad
    cart[index].quantity += quantity;
  } else {
    // Crear nuevo objeto con todos los datos
    cart.push({ id, name, image, quantity });
  }
  saveCart(cart);
}

/**
 * Renderiza el carrito en la página cart.php
 */
function renderCart() {
  const container = document.getElementById("cartContainer");
  if (!container) return;

  const cart = getCart();
  if (cart.length === 0) {
    container.innerHTML = "<p>El carrito está vacío.</p>";
    return;
  }

  let html = `
    <table>
      <tr>
        <th>Imagen</th>
        <th>Producto</th>
        <th>Cantidad</th>
        <th>Acciones</th>
      </tr>
  `;

  cart.forEach((item, idx) => {
    html += `
      <tr>
        <td>
          <img src="${item.image}" alt="${item.name}" style="width:50px; height:auto;">
        </td>
        <td>${item.name}</td>
        <td>${item.quantity}</td>
        <td>
          <button onclick="removeItem(${idx})">Eliminar</button>
        </td>
      </tr>
    `;
  });

  html += "</table>";
  container.innerHTML = html;
}

/**
 * Elimina un producto del carrito según su índice.
 * @param {number} index Índice del item en el array de carrito
 */
function removeItem(index) {
  const cart = getCart();
  cart.splice(index, 1);
  saveCart(cart);
  renderCart();
}
