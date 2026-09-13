<?php
session_start();
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("location: ../public/login.php");
    exit;
}
?>

<?php include '../includes/header.php'; ?>

<div class="container">
    <h2 class="page-title">Admin Panel</h2>
    <p>Welcome to the admin panel. Use the navigation links to manage products and orders.</p>

    <ul class="admin-nav">
        <li><a href="add_product.php">Add Product</a></li>
        
        <li><a href="manage_orders.php">Manage Orders</a></li>
    </ul>
</div>

<?php include '../includes/footer.php'; ?>
