import { API_URL, renderStars, truncateText, toggleWishlist, addToCart, toggleLoading } from './utils.js';

document.addEventListener('DOMContentLoaded', () => {
    const LIMIT = 12;
    let currentPage = 1;
    let totalPages = 1;
    let currentProducts = [];

    const productList = document.getElementById('productList');
    const pagination = document.getElementById('pagination');

    // Modal elements
    const productModalEl = document.getElementById('productModal');
    const bsModal = new bootstrap.Modal(productModalEl);
    const modalTitle = productModalEl.querySelector('#productModalLabel');
    const carouselInner = productModalEl.querySelector('.carousel-inner');
    const modalPrice = document.getElementById('modalPrice');
    const modalStock = document.getElementById('modalStock');
    const modalTags = document.getElementById('modalTags');
    const modalQuantity = document.getElementById('modalQuantity');
    const addToCartBtn = document.getElementById('addToCartBtn');

    // Sidebar toggle
    document.getElementById('sidebarCollapse').addEventListener('click', function() {
        document.getElementById('sidebar').classList.toggle('active');
        document.getElementById('content-wrapper').classList.toggle('sidebar-active');
    });

    // Close sidebar when clicking outside on mobile
    document.addEventListener('click', function(event) {
        const sidebar = document.getElementById('sidebar');
        const sidebarCollapse = document.getElementById('sidebarCollapse');
        const contentWrapper = document.getElementById('content-wrapper');

        if (window.innerWidth <= 768 &&
            !sidebar.contains(event.target) &&
            !sidebarCollapse.contains(event.target) &&
            sidebar.classList.contains('active')) {
            sidebar.classList.remove('active');
            contentWrapper.classList.remove('sidebar-active');
        }
    });

    /**
     * Loads and displays products for the specified page
     * @param {number} page - Page number to load
     */
    async function loadProducts(page = 1) {
        const skip = (page - 1) * LIMIT;
        const url = `${API_URL}?limit=${LIMIT}&skip=${skip}&delay=1000`;

        toggleLoading(true);
        productList.innerHTML = '';

        try {
            const res = await fetch(url);
            const data = await res.json();
            currentProducts = data.products;
            totalPages = Math.ceil(data.total / LIMIT) || 1;

            currentProducts.forEach(product => {
                const col = document.createElement('div');
                col.className = 'col-sm-6 col-md-4 mb-4';

                const shortDescription = truncateText(product.description, 50);
                const starsHtml = renderStars(product.rating);

                col.innerHTML = `
          <div class="card h-100 product-card position-relative">
            <div class="position-absolute top-0 end-0 p-2">
              <button id="wish-${product.id}" data-product-id="${product.id}" class="btn btn-sm ${wishlistIds.some(el => el == product.id) ? 'btn-danger' : 'btn-outline-danger'} rounded-circle">
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
              <button
                class="btn btn-primary mt-auto view-details-btn"
                type="button"
              >
                View Details
              </button>
            </div>
          </div>
        `;
                // Attach wishlist handler with unique ID to avoid conflicts
                const wishBtn = col.querySelector(`#wish-${product.id}`);
                wishBtn.addEventListener('click', () => {
                    toggleWishlist(wishBtn, wishBtn.dataset.productId);
                });

                // Bind click after element exists
                const btn = col.querySelector('.view-details-btn');
                btn.addEventListener('click', () => openModal(product));

                productList.append(col);
            });

            renderPagination();
        } catch (err) {
            console.error('Error loading products:', err);
            productList.innerHTML = `
        <div class="d-flex justify-content-center align-items-center text-center w-100" style="height:300px;">
          <div class="alert alert-danger mb-0">
            Failed to load products. Please try again later.
          </div>
        </div>
      `;
        } finally {
            toggleLoading(false, 500);
        }
    }

    /**
     * Opens the product detail modal
     * @param {Object} product - Product data
     */
    function openModal(product) {
        // Title
        modalTitle.textContent = product.title;

        // Carousel
        carouselInner.innerHTML = product.images.map((src, idx) => `
        <div class="carousel-item${idx === 0 ? ' active' : ''}">
            <img src="${src}" class="d-block w-100 object-fit-cover" alt="Image ${idx + 1}" style="height: 250px;">
        </div>
    `).join('');

        // Price & Stock
        if (product.stock > 0) {
            modalStock.textContent = `Stock: ${product.stock}`;
            modalStock.classList.remove('bg-danger');
            modalStock.classList.add('bg-success');
        } else {
            modalStock.textContent = `Out of Stock`;
            modalStock.classList.remove('bg-success');
            modalStock.classList.add('bg-danger');
        }
        modalPrice.textContent = `${product.price} $`;

        // Tags
        modalTags.innerHTML = product.tags
            ? product.tags.map(tag => `<span class="badge rounded-pill bg-secondary me-1">${tag}</span>`).join('')
            : '<span class="text-muted">No tags</span>';

        // Reset quantity
        modalQuantity.value = 1;

        // Add to cart action
        addToCartBtn.onclick = () => {
            const qty = Number(modalQuantity.value);
            addToCart(product.id, qty);
            bsModal.hide();
        };

        bsModal.show();
    }

    /**
     * Renders pagination controls
     */
    function renderPagination() {
        pagination.innerHTML = `
      <li class="page-item ${currentPage===1?'disabled':''}">
        <a class="page-link" href="#" data-page="${currentPage-1}">&laquo;</a>
      </li>
    `;

        let start = Math.max(1, currentPage - 2);
        let end = Math.min(totalPages, start + 4);
        if (end - start < 4) start = Math.max(1, end - 4);

        for (let i = start; i <= end; i++) {
            pagination.innerHTML += `
        <li class="page-item ${i===currentPage?'active':''}">
          <a class="page-link" href="#" data-page="${i}">${i}</a>
        </li>
      `;
        }

        pagination.innerHTML += `
      <li class="page-item ${currentPage===totalPages?'disabled':''}">
        <a class="page-link" href="#" data-page="${currentPage+1}">&raquo;</a>
      </li>
    `;

        pagination.querySelectorAll('a.page-link').forEach(link => {
            link.addEventListener('click', e => {
                e.preventDefault();
                const pg = Number(link.dataset.page);
                if (pg>=1 && pg<=totalPages && pg!==currentPage) {
                    currentPage = pg;
                    loadProducts(pg);
                }
            });
        });
    }

    // Initialize the product listing
    loadProducts();
});