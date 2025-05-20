<?php $cartCount = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0; ?>
<!-- Top Navigation Bar -->
<nav id="topbar" class="navbar position-fixed top-0 start-0 end-0 z-1030 bg-white border-bottom">
    <div class="container-fluid px-3">
        <div class="d-flex align-items-center">
            <button type="button" id="sidebarCollapse" class="btn btn-light d-md-none me-3">
                <i class="fas fa-bars"></i>
            </button>
            <a href="index.php" class="logo text-decoration-none">
                <i class="fas fa-shopping-bag me-2"></i>ShopName
            </a>
        </div>

        <div class="d-flex">
            <form class="d-none d-md-flex me-3">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Search..." aria-label="Search">
                    <button class="btn btn-outline-secondary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>

            <a href="cart.php" class="btn btn-outline-secondary position-relative me-2">
                <i class="fas fa-shopping-cart"></i>
                <span id="cart-badge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    <?php echo $cartCount; ?>
                </span>
            </a>

            <div class="dropdown d-inline-block">
                <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user me-1"></i> Account
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#">My Profile</a></li>
                    <li><a class="dropdown-item" href="#">Settings</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="#">Logout</a></li>
                </ul>
            </div>
        </div>
    </div>
</nav>