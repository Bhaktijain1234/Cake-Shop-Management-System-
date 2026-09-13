<?php
session_start();
require_once '../includes/db.php';

$order_id = $_GET['id'];
$order_query = $mysqli->query("SELECT * FROM orders WHERE id = $order_id");
$order = $order_query->fetch_assoc();

$order_items_query = $mysqli->query("SELECT products.name, products.price, order_items.quantity FROM order_items JOIN products ON order_items.product_id = products.id WHERE order_id = $order_id");

?>

<?php include '../includes/header.php'; ?>
<div style="max-width: 600px; margin: auto; padding: 20px; background-color: #f8f9fa; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
    <h2 style="text-align: center; color: #d4a373;">Order Details</h2>
    <p><strong>Order ID:</strong> <?php echo $order['id']; ?></p>
    <p><strong>Total Price:</strong> RS:<?php echo $order['total_price']; ?></p>
    <p><strong>Placed At:</strong> <?php echo $order['created_at']; ?></p>

    <h3 style="color: #d4a373;">Items</h3>
    <?php while ($item = $order_items_query->fetch_assoc()): ?>
        <div class="order-item" style="border-bottom: 1px solid #ddd; padding: 10px 0;">
            <p><strong><?php echo $item['name']; ?></strong></p>
            <p>Quantity: <?php echo $item['quantity']; ?></p>
            <p>Price: RS:<?php echo $item['price']; ?></p>
        </div>
    <?php endwhile; ?>
</div>

<?php include '../includes/footer.php'; ?>
