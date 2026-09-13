<?php
session_start();
require_once '../includes/db.php';

// Check if 'id' is set in the GET request
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $order_id = intval($_GET['id']);

    // Prepare the query for order details
    $order_stmt = $mysqli->prepare("SELECT * FROM orders WHERE id = ?");
    $order_stmt->bind_param("i", $order_id);
    $order_stmt->execute();
    $order_result = $order_stmt->get_result();
    $order = $order_result->fetch_assoc();

    if ($order) {
        // Prepare the query for order items
        $items_stmt = $mysqli->prepare("
            SELECT products.name, products.price, order_items.quantity 
            FROM order_items 
            JOIN products ON order_items.product_id = products.id 
            WHERE order_items.order_id = ?
        ");
        $items_stmt->bind_param("i", $order_id);
        $items_stmt->execute();
        $items_result = $items_stmt->get_result();
    } else {
        echo "Order not found.";
        exit;
    }
} else {
    echo "Invalid order ID.";
    exit;
}
?>

<?php include '../includes/header.php'; ?>
<h2>Order Details</h2>
<p>Order ID: <?php echo htmlspecialchars($order['id']); ?></p>
<p>Total Price: $<?php echo htmlspecialchars($order['total_price']); ?></p>
<p>Placed At: <?php echo htmlspecialchars($order['created_at']); ?></p>

<h3>Items</h3>
<?php while ($item = $items_result->fetch_assoc()): ?>
    <div class="order-item">
        <p><?php echo htmlspecialchars($item['name']); ?></p>
        <p>Quantity: <?php echo htmlspecialchars($item['quantity']); ?></p>
        <p>Price: $<?php echo htmlspecialchars($item['price']); ?></p>
    </div>
<?php endwhile; ?>

<?php include '../includes/footer.php'; ?>
