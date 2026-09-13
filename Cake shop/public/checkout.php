<?php
session_start();
require_once '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_SESSION['cart_items']) && !empty($_SESSION['cart_items'])) {
        $cart_items = $_SESSION['cart_items'];
        $user_id = $_SESSION['id'];
        $total_price = array_sum(array_column($cart_items, 'price'));

        $stmt = $mysqli->prepare("INSERT INTO orders (user_id, total_price) VALUES (?, ?)");
        $stmt->bind_param("id", $user_id, $total_price);

        if ($stmt->execute()) {
            $order_id = $stmt->insert_id;

            foreach ($cart_items as $item) {
                $product_id = $item['id'];
                $quantity = 1;
                $stmt_item = $mysqli->prepare("INSERT INTO order_items (order_id, product_id, quantity) VALUES (?, ?, ?)");
                $stmt_item->bind_param("iii", $order_id, $product_id, $quantity);
                $stmt_item->execute();
            }

            $_SESSION['cart_items'] = array();
            header("location: view_order.php?id=" . $order_id);
            exit();
        } else {
            echo "Error: " . $mysqli->error;
        }

        $stmt->close();
    } else {
        echo "Error: Cart items are not set.";
    }
}

$mysqli->close();
?>

<?php include '../includes/header.php'; ?>

<div style="max-width: 600px; margin: auto; padding: 20px; background-color: #f8f9fa; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
    <h2 style="text-align: center; color: #d4a373;">Checkout</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div style="text-align: center; margin-top: 20px;">
            <input type="submit" value="Place Order" style="padding: 10px 20px; background-color: #ccd5ae; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; font-weight: bold;">
        </div>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
