<!-- Sidebar -->
<nav id="sidebar">
    <div class="sidebar-header text-center">
        <h5>Shop Categories</h5>
    </div>

    <div class="px-3">
        <div class="list-group mb-4">
            <a href="index.php" class="list-group-item list-group-item-action">
                <i class="fas fa-home me-2"></i> Home
            </a>
            <a href="cart.php" class="list-group-item list-group-item-action">
                <i class="fas fa-shopping-cart me-2"></i> Cart
            </a>
            <a href="wishlist.php" class="list-group-item list-group-item-action">
                <i class="fas fa-heart me-2"></i> Wishlist
            </a>
        </div>

        <h6 class="px-3 mt-4 mb-3">Search & Filter</h6>

        <form class="search-form">
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Search products..." aria-label="Search">
                <button class="btn btn-outline-secondary" type="submit">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>

        <div class="mb-3">
            <label for="priceRange" class="form-label">Price Range</label>
            <input type="range" class="form-range" id="priceRange">
            <div class="d-flex justify-content-between">
                <small>$0</small>
                <small>$1000</small>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Categories</label>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="cat1">
                <label class="form-check-label" for="cat1">
                    Electronics
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="cat2">
                <label class="form-check-label" for="cat2">
                    Clothing
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="cat3">
                <label class="form-check-label" for="cat3">
                    Home & Kitchen
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="cat4">
                <label class="form-check-label" for="cat4">
                    Books
                </label>
            </div>
        </div>

        <button class="btn btn-primary btn-sm w-100 mb-4">Apply Filters</button>
    </div>
</nav>