<?php
session_start();
require_once '../includes/db.php';

// Fetch all products from the database
$query = "SELECT * FROM products";
$result = $mysqli->query($query);
$products = $result->fetch_all(MYSQLI_ASSOC);

// Close the database connection
$mysqli->close();
?>

<?php include '../includes/header.php'; ?>

<h2 style="text-align: center; color: #d4a373;">Product Catalog</h2>

<div class="products" style="display: flex; flex-wrap: wrap; justify-content: center; gap: 20px; padding: 20px;">
    <?php foreach ($products as $product): ?>
        <div class="product" style="border: 1px solid #faedcd; border-radius: 8px; padding: 20px; width: 300px; background-color: #e9edc9; text-align: center; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
            <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" style="width: 100%; height: auto; border-radius: 8px;">
            <h3 style="color: #d4a373;"><?php echo $product['name']; ?></h3>
            <p style="color: #6c757d;"><?php echo $product['description']; ?></p>
            <p style="font-weight: bold; color: #d4a373;">RS:<?php echo $product['price']; ?></p>
            <a href="product.php?id=<?php echo $product['id']; ?>" style="display: inline-block; padding: 10px 20px; background-color: #ccd5ae; color: #d4a373; text-decoration: none; border-radius: 4px; font-weight: bold;">View Product</a>
        </div>
    <?php endforeach; ?>
</div>

<?php include '../includes/footer.php'; ?>
