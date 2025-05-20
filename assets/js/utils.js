/**
 * Shared utilities for the e-commerce application
 */

// Constants
const API_URL = 'https://api.daaif.net/products';

/**
 * Renders a star rating as HTML using Font Awesome icons
 * @param {number} rating - Rating from 0-5
 * @returns {string} HTML representation of stars
 */
function renderStars(rating) {
    const full = Math.floor(rating);
    const half = rating % 1 >= 0.5;
    const empty = 5 - full - (half ? 1 : 0);
    return (
        '<i class="fas fa-star text-warning"></i>'.repeat(full) +
        (half ? '<i class="fas fa-star-half-alt text-warning"></i>' : '') +
        '<i class="far fa-star text-warning"></i>'.repeat(empty)
    );
}

/**
 * Fetches a product by ID
 * @param {number|string} id - Product ID
 * @returns {Promise<Object|null>} Product object or null if not found
 */
async function fetchProduct(id) {
    try {
        const res = await fetch(`${API_URL}/${id}`);
        if (!res.ok) throw new Error(`Product ${id} not found`);
        return await res.json();
    } catch (e) {
        console.error(`Error fetching product ${id}:`, e);
        return null;
    }
}

/**
 * Truncates text to specified length and adds ellipsis if needed
 * @param {string} text - Text to truncate
 * @param {number} maxLength - Maximum length before truncation
 * @returns {string} Truncated text
 */
function truncateText(text, maxLength) {
    return text.length > maxLength
        ? text.slice(0, maxLength) + '…'
        : text;
}

/**
 * Adds a product to wishlist
 * @param {HTMLElement} wishBtn - The wishlist button element
 * @param {number|string} productId - Product ID
 * @returns {Promise<void>}
 */
async function toggleWishlist(wishBtn, productId) {
    try {
        const response = await fetch('add_to_wishlist.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `product_id=${encodeURIComponent(productId)}`
        });
        
        const data = await response.json();
        
        if (!data.success) {
            alert(data.message || 'An error occurred.');
            return;
        }
        
        alert(data.message);
        
        if (data.status === 'added') {
            // Product added: show "danger" look
            wishBtn.classList.remove('btn-outline-danger');
            wishBtn.classList.add('btn-danger');
        } else if (data.status === 'removed') {
            // Product removed: revert to outline look
            wishBtn.classList.remove('btn-danger');
            wishBtn.classList.add('btn-outline-danger');
        }
    } catch (err) {
        console.error('Wishlist error:', err);
        alert('Failed to update wishlist.');
    }
}

/**
 * Adds a product to cart
 * @param {number|string} productId - Product ID
 * @param {number} quantity - Quantity to add
 * @returns {Promise<boolean>} Success status
 */
async function addToCart(productId, quantity = 1) {
    try {
        const formData = new FormData();
        formData.append('id', productId);
        formData.append('qty', quantity);

        const response = await fetch('add_to_cart.php', {
            method: 'POST',
            body: formData
        });
        
        const data = await response.json();
        
        if (data.success) {
            alert('Product added to cart!');
            return true;
        } else {
            alert('Failed to add to cart.');
            return false;
        }
    } catch (err) {
        console.error('Add to cart error:', err);
        alert('Failed to add to cart.');
        return false;
    }
}

/**
 * Shows or hides the loading overlay
 * @param {boolean} show - Whether to show (true) or hide (false) the overlay
 * @param {number} delay - Optional delay before hiding (in ms)
 */
function toggleLoading(show, delay = 0) {
    const loadingOverlay = document.getElementById('loadingOverlay');
    
    if (show) {
        loadingOverlay.classList.remove('d-none');
    } else {
        if (delay > 0) {
            setTimeout(() => loadingOverlay.classList.add('d-none'), delay);
        } else {
            loadingOverlay.classList.add('d-none');
        }
    }
}

// Export all functions
export {
    API_URL,
    renderStars,
    fetchProduct,
    truncateText,
    toggleWishlist,
    addToCart,
    toggleLoading
};