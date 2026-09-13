<?php
session_start();
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("location: ../public/login.php");
    exit;
}

require_once '../includes/db.php';

// Handle delete request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_order'])) {
    $order_id = $_POST['order_id'];

    // Start a transaction
    $mysqli->begin_transaction();

    try {
        // Delete related entries in order_items first
        $stmt = $mysqli->prepare("DELETE FROM order_items WHERE order_id = ?");
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $stmt->close();

        // Now delete the order
        $stmt = $mysqli->prepare("DELETE FROM orders WHERE id = ?");
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $stmt->close();

        // Commit the transaction
        $mysqli->commit();
        $delete_success = "Order deleted successfully.";
    } catch (mysqli_sql_exception $exception) {
        // Rollback the transaction in case of an error
        $mysqli->rollback();
        $delete_error = "Something went wrong. Please try again later.";
    }
}

// Fetch all orders and their items from the database
$query = "
    SELECT 
        orders.id AS order_id, 
        orders.user_id, 
        orders.total_price, 
        orders.created_at AS order_date, 
        GROUP_CONCAT(order_items.product_id) AS product_ids, 
        GROUP_CONCAT(order_items.quantity) AS quantities
    FROM orders
    LEFT JOIN order_items ON orders.id = order_items.order_id
    GROUP BY orders.id";
$result = $mysqli->query($query);
$orders = $result->fetch_all(MYSQLI_ASSOC);

$mysqli->close();
?>

<?php include '../includes/header.php'; ?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Manage Orders</h2>
    
    <?php if (!empty($delete_success)): ?>
        <div class="alert alert-success"><?php echo $delete_success; ?></div>
    <?php elseif (!empty($delete_error)): ?>
        <div class="alert alert-danger"><?php echo $delete_error; ?></div>
    <?php endif; ?>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>User ID</th>
                <th>Product IDs</th>
                <th>Quantities</th>
                <th>Total Price</th>
                <th>Order Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?php echo $order['order_id']; ?></td>
                    <td><?php echo $order['user_id']; ?></td>
                    <td><?php echo isset($order['product_ids']) ? $order['product_ids'] : 'N/A'; ?></td>
                    <td><?php echo isset($order['quantities']) ? $order['quantities'] : 'N/A'; ?></td>
                    <td>RS:<?php echo $order['total_price']; ?></td>
                    <td><?php echo $order['order_date']; ?></td>
                    <td>
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" style="display: inline;">
                            <input type="hidden" name="order_id" value="<?php echo $order['order_id']; ?>">
                            <input type="submit" name="delete_order" value="Delete" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this order?');">
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include '../includes/footer.php'; ?>
