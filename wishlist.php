<?php
session_start();
if (!isset($_SESSION['wishlist'])) {
    $_SESSION['wishlist'] = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>My Wishlist</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
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
    <?php include 'partials/sidebar.php'; ?>

    <div class="content-wrapper" id="content-wrapper">
        <?php include 'partials/topbar.php'; ?>

        <div class="content">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3>My Wishlist</h3>
            </div>

            <div id="wishlistContainer" class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4">
                <!-- cards will be injected here -->
            </div>

            <!-- Pagination -->
            <nav>
                <ul id="pagination" class="pagination justify-content-center mt-4">

                </ul>
            </nav>

            <div id="emptyMessage" class="alert alert-info text-center mt-4 d-none">
                Your wishlist is empty.
            </div>
        </div>
    </div>
</div>



<!-- Loading overlay (optional) -->
<div id="loadingOverlay" class="position-fixed top-0 start-0 w-100 vh-100 d-flex justify-content-center align-items-center bg-white bg-opacity-75 d-none" style="z-index:1050;">
    <div class="spinner-border text-primary" role="status" style="width:4rem; height:4rem;">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>

<div id="loadingOverlay" class="position-fixed d-none top-0 start-0 w-100 vh-100 d-flex justify-content-center align-items-center bg-white bg-opacity-75" style="z-index: 1050;">
    <div class="spinner-border text-primary" role="status" style="width: 4rem; height: 4rem;">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>

<!-- Bootstrap JS bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    var wishlistIds = <?php echo json_encode($_SESSION['wishlist'], JSON_NUMERIC_CHECK); ?>;
</script>
<script type="module" src="assets/js/wishlist.js"></script>
</body>
</html>