document.addEventListener('DOMContentLoaded', () => {
  // --- 1. FILTRADO EN TIEMPO REAL ---
  const searchInput = document.querySelector('.search-bar input');
  const productCards = document.querySelectorAll('.products-table td');

  if (searchInput) {
    searchInput.addEventListener('input', (e) => {
      const term = e.target.value.toLowerCase().trim();

      productCards.forEach((cell) => {
        const title = cell.querySelector('.product-title')?.textContent.toLowerCase() || '';
        const desc = cell.querySelector('.product-desc')?.textContent.toLowerCase() || '';

        // Si coincide con el título o la descripción, se muestra; si no, se oculta
        if (title.includes(term) || desc.includes(term)) {
          cell.style.visibility = 'visible';
          cell.style.opacity = '1';
        } else {
          cell.style.visibility = 'hidden';
          cell.style.opacity = '0';
        }
      });
    });
  }

  // --- 2. CONTADOR DE CARRITO INTERACTIVO ---
  const cartBadge = document.querySelector('.cart-badge');
  let cartCount = 0;

  // Insertar un botón dinámico en cada tarjeta para no modificar el HTML a mano
  productCards.forEach((cell) => {
    const card = cell.querySelector('.product-card');
    if (card) {
      const btn = document.createElement('button');
      btn.textContent = 'Agregar al carrito';
      btn.className = 'btn-add-cart';

      btn.addEventListener('click', () => {
        cartCount++;
        cartBadge.textContent = cartCount;

        // Feedback visual temporal
        btn.textContent = '¡Agregado!';
        btn.style.backgroundColor = '#28a745';
        setTimeout(() => {
          btn.textContent = 'Agregar al carrito';
          btn.style.backgroundColor = '';
        }, 800);
      });

      card.appendChild(btn);
    }
  });
});