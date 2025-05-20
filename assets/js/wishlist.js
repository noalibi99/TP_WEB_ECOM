import { fetchProduct, renderStars, truncateText, toggleWishlist, addToCart, toggleLoading } from './utils.js';

const container = document.getElementById('wishlistContainer');
const emptyMsg = document.getElementById('emptyMessage');

/**
 * Renders a product card in the wishlist
 * @param {Object} product - Product data
 */
function renderCard(product) {
    const shortDescription = truncateText(product.description, 100);
    const starsHtml = renderStars(product.rating?.rate || 0);
    const col = document.createElement('div');
    col.className = 'col';

    col.innerHTML = `
        <div class="card h-100 product-card position-relative">
          <div class="position-absolute top-0 end-0 p-2">
            <button id="wish-${product.id}" data-product-id="${product.id}"
              class="btn btn-sm ${wishlistIds.includes(product.id) ? 'btn-danger' : 'btn-outline-danger'} rounded-circle">
              <i class="fas fa-heart"></i>
            </button>
          </div>
          <img src="${product.thumbnail}"
               class="card-img-top"
               alt="${product.title}"
               loading="lazy">
          <div class="card-body d-flex flex-column">
            <h5 class="card-title">${product.title}</h5>
            <div class="d-flex justify-content-between align-items-center mb-2">
              <p class="text-muted mb-0">${product.category}</p>
              <div>${starsHtml}</div>
            </div>
            <p class="card-text flex-grow-1 small">${shortDescription}</p>
            <h6 class="fw-bold">${product.price} $</h6>
            <button class="btn btn-primary mt-auto add-to-cart-btn" data-id="${product.id}">
              <i class="fa fa-cart-plus"></i> Add to Cart
            </button>
          </div>
        </div>
      `;
    container.appendChild(col);
    
    // Attach wishlist handler with unique ID to avoid conflicts
    const wishBtn = col.querySelector(`#wish-${product.id}`);
    wishBtn.addEventListener('click', () => {
        toggleWishlist(wishBtn, wishBtn.dataset.productId);
    });
    
    // Attach handler for Add to Cart
    col.querySelector('.add-to-cart-btn').addEventListener('click', () => {
        addToCart(product.id);
    });
}

/**
 * Loads and displays wishlist products
 */
async function loadWishlist() {
    try {
        if (!wishlistIds.length) {
            emptyMsg.classList.remove('d-none');
            return;
        }

        toggleLoading(true);

        const productPromises = wishlistIds.map(id => fetchProduct(id));
        const results = await Promise.all(productPromises);
        const validProducts = results.filter(p => p !== null);

        if (!validProducts.length) {
            emptyMsg.classList.remove('d-none');
            return;
        }

        validProducts.forEach(renderCard);
    } catch (e) {
        console.error('Error loading wishlist:', e);
        container.innerHTML = `
            <div class="col-12">
                <div class="alert alert-danger">
                    Failed to load wishlist products. Please try again later.
                </div>
            </div>`;
    } finally {
        toggleLoading(false, 500);
    }
}

// Initialize the wishlist
document.addEventListener('DOMContentLoaded', loadWishlist);