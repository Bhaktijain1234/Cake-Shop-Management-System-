<?php
session_start();
require_once '../includes/db.php';

// Initialize cart session if not set
if (!isset($_SESSION['cart_items'])) {
    $_SESSION['cart_items'] = [];
}

// Handle adding items to the cart
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    $product_query = $mysqli->query("SELECT * FROM products WHERE id = $product_id");
    $product = $product_query->fetch_assoc();

    if ($product) {
        $cart_item = [
            'id' => $product['id'],
            'name' => $product['name'],
            'price' => $product['price'],
            'image' => $product['image'],
            'description' => $product['description']
        ];

        $_SESSION['cart_items'][] = $cart_item;

        header("Location: index.php");
        exit();
    } else {
        echo "Invalid product";
    }
}

// Handle actions (add, remove)
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['action'])) {
    $action = $_GET['action'];
    $product_id = $_GET['id'];

    switch ($action) {
        case 'add':
            if (!in_array($product_id, array_column($_SESSION['cart_items'], 'id'))) {
                $product_query = $mysqli->query("SELECT * FROM products WHERE id = $product_id");
                $product = $product_query->fetch_assoc();

                if ($product) {
                    $cart_item = [
                        'id' => $product['id'],
                        'name' => $product['name'],
                        'price' => $product['price'],
                        'image' => $product['image'],
                        'description' => $product['description']
                    ];

                    $_SESSION['cart_items'][] = $cart_item;
                }
            }
            break;

        case 'remove':
            foreach ($_SESSION['cart_items'] as $key => $item) {
                if ($item['id'] == $product_id) {
                    unset($_SESSION['cart_items'][$key]);
                }
            }
            $_SESSION['cart_items'] = array_values($_SESSION['cart_items']); // Reindex the array
            break;
    }
}

// Get cart items from session
$cart_items = $_SESSION['cart_items'];
?>

<?php include '../includes/header.php'; ?>

<div style="max-width: 800px; margin: 0 auto; padding: 20px; background-color: #e9edc9; border: 1px solid #faedcd; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
    <h2 style="text-align: center; color: #d4a373;">Shopping Cart</h2>
    <div class="cart" style="display: flex; flex-direction: column; gap: 20px;">
        <?php foreach ($cart_items as $item): ?>
            <div class="cart-item" style="display: flex; gap: 20px; align-items: center; padding: 10px; background-color: #fefae0; border-radius: 8px; box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);">
                <img src="<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>" style="width: 100px; height: auto; border-radius: 8px;">
                <div style="flex-grow: 1;">
                    <h3 style="margin: 0; color: #d4a373;"><?php echo $item['name']; ?></h3>
                    <p style="color: #6c757d;"><?php echo $item['description']; ?></p>
                    <p style="color: #6c757d;">RS:<?php echo $item['price']; ?></p>
                </div>
                <a href="cart.php?action=remove&id=<?php echo $item['id']; ?>" style="padding: 10px 20px; background-color: #ccd5ae; color: #d4a373; text-decoration: none; border-radius: 4px; font-weight: bold; text-align: center;">Remove</a>
            </div>
        <?php endforeach; ?>
    </div>
    <p style="text-align: right; font-weight: bold; margin-top: 20px; color: #d4a373;">Total: RS:<?php echo array_sum(array_column($cart_items, 'price')); ?></p>
    <div style="text-align: center; margin-top: 20px;">
        <a href="checkout.php" style="padding: 10px 20px; background-color: #ccd5ae; color: #d4a373; text-decoration: none; border-radius: 4px; font-weight: bold;">Proceed to Checkout</a>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
