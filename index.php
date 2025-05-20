<?php
session_start();
if(!isset($_SESSION["cart"])){
    $_SESSION['cart'] = [];
}
if(!isset($_SESSION["wishlist"])){
    $_SESSION["wishlist"] = [];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ecommerce Site</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            overflow-x: hidden;
        }

        #sidebar {
            width: 250px;
            min-height: 100vh;
            transition: all 0.3s;
            background-color: #f8f9fa;
            border-right: 1px solid #dee2e6;
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            z-index: 1000;
            overflow-y: auto;
        }

        #sidebar.active {
            margin-left: -250px;
        }

        .wrapper {
            display: flex;
            width: 100%;
            align-items: stretch;
        }

        .content-wrapper {
            width: 100%;
            padding-left: 250px;
            padding-top: 60px;
            transition: all 0.3s;
        }

        .content {
            padding: 20px;
        }

        .sidebar-header {
            padding: 20px;
        }

        @media (max-width: 768px) {
            #sidebar {
                margin-left: -250px;
            }
            #sidebar.active {
                margin-left: 0;
            }
            .content-wrapper {
                padding-left: 0;
            }
            .content-wrapper.sidebar-active {
                padding-left: 250px;
            }
        }

        .product-card {
            transition: transform 0.3s;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        #topbar {
            background-color: white;
            border-bottom: 1px solid #dee2e6;
            padding: 10px 20px;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: #333;
            text-decoration: none;
        }

        .search-form {
            margin-bottom: 20px;
        }
    </style>
</head>
<body class="overflow-auto">
<div class="wrapper">
    <!-- Sidebar -->
    <?php include 'partials/sidebar.php' ?>

    <!-- Page Content -->
    <div class="content-wrapper" id="content-wrapper">
        <?php include 'partials/topbar.php' ?>

        <!-- Main Content Container -->
        <div class="content">
            <!-- Page Title -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3>Featured Products</h3>
                <div class="dropdown">
                    <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Sort by
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#">Price: Low to High</a></li>
                        <li><a class="dropdown-item" href="#">Price: High to Low</a></li>
                        <li><a class="dropdown-item" href="#">Newest First</a></li>
                        <li><a class="dropdown-item" href="#">Most Popular</a></li>
                    </ul>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4" id="productList">
                <!-- Products will be loaded dynamically -->
            </div>

            <!-- Pagination -->
            <nav>
                <ul id="pagination" class="pagination justify-content-center mt-4">

                </ul>
            </nav>

        </div>
    </div>
</div>

<!-- Product Details Modal -->
<div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="productModalLabel">Product Title</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <!-- Carousel -->
                <div id="productCarousel" class="carousel slide mb-4" data-bs-ride="carousel" style="height: 250px;">
                    <div class="carousel-inner h-100" id="carouselInner"></div>

                    <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                </div>

                <!-- Details -->
                <div class="row">
                    <div class="col-md-6">
                        <h6>Price:</h6>
                        <p id="modalPrice" class="fw-bold fs-4"></p>
                    </div>
                    <div class="col-md-6  mt-4">
                        <span id="modalStock" class="badge rounded-pill fs-6 px-3 py-2">Stock :</span>
                    </div>
                </div>

                <div class="mb-3">
                    <h6>Tags:</h6>
                    <div id="modalTags"></div>
                </div>

                <div class="input-group mb-3" style="max-width: 140px;">
                    <span class="input-group-text">Qty</span>
                    <input type="number" id="modalQuantity" class="form-control" min="1" value="1">
                </div>
            </div>

            <div class="modal-footer">
                <button id="addToCartBtn" type="button" class="btn btn-primary">Add to Cart</button>
            </div>

        </div>
    </div>
</div>


<div id="loadingOverlay" class="position-fixed top-0 start-0 w-100 vh-100 d-flex justify-content-center align-items-center bg-white bg-opacity-75" style="z-index: 1050;">
    <div class="spinner-border text-primary" role="status" style="width: 4rem; height: 4rem;">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>

<!-- Bootstrap JS and Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    var wishlistIds = <?php echo json_encode($_SESSION['wishlist']); ?>;
    var cartIds = <?php echo json_encode($_SESSION['cart']); ?>;
</script>
<script type="module" src="assets/js/index.js"></script>
</body>
</html>