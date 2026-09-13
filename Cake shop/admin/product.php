<?php
session_start();
require_once '../includes/db.php';

// Get the product ID from the URL
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch the product details from the database
$product_query = $mysqli->prepare("SELECT * FROM products WHERE id = ?");
$product_query->bind_param("i", $product_id);
$product_query->execute();
$product = $product_query->get_result()->fetch_assoc();

// Close the prepared statement and the database connection
$product_query->close();
$mysqli->close();
?>

<?php include '../includes/header.php'; ?>

<div class="product-details" style="max-width: 800px; margin: 0 auto; padding: 20px; background-color: #e9edc9; border: 1px solid #faedcd; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
    <h2 style="text-align: center; color: #d4a373;"><?php echo htmlspecialchars($product['name']); ?></h2>
    <img src="../public/<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" style="width: 100%; height: auto; border-radius: 8px; margin-bottom: 20px;">
    <p style="text-align: center; color: #6c757d;">Price: RS:<?php echo htmlspecialchars($product['price']); ?></p>
    <p style="text-align: center; color: #6c757d;"><?php echo htmlspecialchars($product['description']); ?></p>
</div>

<?php include '../includes/footer.php'; ?>
