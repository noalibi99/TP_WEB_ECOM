import { API_URL, fetchProduct, toggleLoading } from './utils.js';

let cartProducts = [];

// Load products + quantities from cartDict
async function loadCartProducts() {
    try {
        toggleLoading(true);
        const ids = Object.keys(cartDict);
        if (!ids.length) {
            cartProducts = [];
            renderCart();
            return;
        }

        const products = await Promise.all(ids.map(fetchProduct));
        cartProducts = products
            .filter(p => p !== null)
            .map(p => ({ ...p, quantity: cartDict[p.id] || 1 }));

        renderCart();
    } catch (error) {
        console.error('Error loading cart products:', error);
    } finally {
        console.log("finished");
        toggleLoading(false, 500);
    }
}

function renderCart() {
    const tbody = document.getElementById('cartItems');
    tbody.innerHTML = '';

    if (cartProducts.length === 0) {
        tbody.innerHTML = `
                <tr>
                  <td colspan="5" class="text-center py-4">Your cart is empty.</td>
                </tr>`;
        document.getElementById('cartTotal').textContent = '$0.00';
        document.getElementById('checkoutBtn').disabled = true;
        return;
    }

    let total = 0;
    cartProducts.forEach(p => {
        const price = parseFloat(p.price);
        const quantity = parseInt(p.quantity);

        if (!isNaN(price) && !isNaN(quantity)) {
            const subtotal = price * quantity;
            total += subtotal;

            const tr = document.createElement('tr');
            tr.innerHTML = `
                    <td>
                      <div class="d-flex align-items-center">
                        <img src="${p.images?.[0] ?? 'placeholder.jpg'}"
                             alt="${p.title}"
                             style="width: 60px; height: 60px; object-fit: cover; margin-right: 10px;">
                        <span>${p.title}</span>
                      </div>
                    </td>
                    <td>$${price.toFixed(2)}</td>
                    <td>
                      <input type="number"
                             min="0"
                             class="form-control form-control-sm quantity-input"
                             data-id="${p.id}"
                             value="${quantity}"
                             style="width: 80px;">
                    </td>
                    <td>$${subtotal.toFixed(2)}</td>
                    <td>
                      <button class="btn btn-sm btn-danger remove-btn" data-id="${p.id}">
                        <i class="fa fa-trash"></i>
                      </button>
                    </td>
                `;
            tbody.appendChild(tr);
        }
    });

    document.getElementById('cartTotal').textContent = '$' + total.toFixed(2);
    document.getElementById('checkoutBtn').disabled = false;

    attachEventListeners();
}

function attachEventListeners() {
    // Quantity inputs
    document.querySelectorAll('.quantity-input').forEach(input => {
        input.onchange = e => {
            const id = e.target.dataset.id;
            let qty = parseInt(e.target.value, 10);
            if (isNaN(qty) || qty < 0) qty = 1;
            e.target.value = qty;

            const prod = cartProducts.find(x => x.id == id);
            if (prod) {
                prod.quantity = qty;
                if (qty === 0) {
                    cartProducts = cartProducts.filter(x => x.id != id);
                }
                persistCart();
                renderCart();
            }
        };
    });

    // Remove buttons (delete single product)
    document.querySelectorAll('.remove-btn').forEach(btn => {
        btn.onclick = async e => {
            const id = e.currentTarget.dataset.id;
            try {
                const res = await fetch('delete_from_cart.php', {
                    method: 'POST',  // or 'DELETE' if you want, but many browsers require special setup for DELETE + body
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id })
                });
                const data = await res.json();
                if (data.success) {
                    // Remove locally as well
                    cartProducts = cartProducts.filter(p => p.id != id);
                    renderCart();  // Or loadCartProducts() to refresh from server session cart
                } else {
                    console.error('Failed to delete product from cart');
                }
            } catch (err) {
                console.error('Error deleting product:', err);
            }
        };
    });
}

// POST updated { id: qty } map to update_cart.php
function persistCart() {
    const updated = {};
    cartProducts.forEach(p => {
        if (p.quantity > 0) updated[p.id] = p.quantity;
    });

    fetch('update_cart.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ cart: updated })
    }).catch(console.error);
}

document.addEventListener('DOMContentLoaded', loadCartProducts);