<?php
session_start();
if (!isset($_SESSION["cart"])) {
    $_SESSION["cart"] = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Shopping Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
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

        #topbar {
            background-color: white;
            border-bottom: 1px solid #dee2e6;
            padding: 10px 20px;
            position: sticky;
            top: 0;
            z-index: 999;
        }
    </style>
</head>
<body class="overflow-auto">
<div class="wrapper">
    <!-- Sidebar -->
    <?php include './partials/sidebar.php'; ?>

    <!-- Page Content -->
    <div class="content-wrapper" id="content-wrapper">
        <?php include './partials/topbar.php' ?>

        <div class="content">
            <h3>Your Shopping Cart</h3>
            <div id="cartContainer" class="table-responsive mt-4">
                <table class="table align-middle">
                    <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th style="width: 120px;">Quantity</th>
                        <th>Subtotal</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody id="cartItems">
                    <!-- Cart items loaded here -->
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="3" class="text-end fw-bold">Total:</td>
                        <td id="cartTotal" class="fw-bold fs-5">$0.00</td>
                        <td></td>
                    </tr>
                    </tfoot>
                </table>

                <div class="d-flex justify-content-end">
                    <button id="checkoutBtn" class="btn btn-success" disabled>Proceed to Checkout</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="loadingOverlay" class="position-fixed top-0 start-0 w-100 vh-100 d-flex justify-content-center align-items-center bg-white bg-opacity-75" style="z-index: 1050;">
    <div class="spinner-border text-primary" role="status" style="width: 4rem; height: 4rem;">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="/assets/js/cart.js"></script>
<script>
    var wishlistIds = <?php echo json_encode($_SESSION['wishlist']); ?>;
    const cartDict = <?php echo json_encode($_SESSION['cart']); ?>;
</script>
<script type="module" src="./assets/js/cart.js"></script>
</body>
</html>