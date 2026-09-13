<?php
session_start();
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("location: ../public/login.php");
    exit;
}

require_once '../includes/db.php';

if (!isset($_GET['id'])) {
    echo "Product ID not provided.";
    exit;
}

$product_id = intval($_GET['id']);

$stmt = $mysqli->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Product not found.";
    exit;
}

$product = $result->fetch_assoc();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    $stmt = $mysqli->prepare("UPDATE products SET name = ?, description = ?, price = ? WHERE id = ?");
    $stmt->bind_param("ssdi", $name, $description, $price, $product_id);
    
    if ($stmt->execute()) {
        echo "Product updated successfully.";
    } else {
        echo "Error updating product: " . $stmt->error;
    }

    $stmt->close();
}
?>

<?php include '../includes/header.php'; ?>

<h2>Edit Product</h2>
<form action="" method="post">
    <label for="name">Name:</label><br>
    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($product['name']); ?>"><br>
    
    <label for="description">Description:</label><br>
    <textarea id="description" name="description"><?php echo htmlspecialchars($product['description']); ?></textarea><br>
    
    <label for="price">Price:</label><br>
    <input type="number" id="price" name="price" value="<?php echo htmlspecialchars($product['price']); ?>"><br>
    
    <input type="submit" value="Update Product">
</form>

<?php include '../includes/footer.php'; ?>

<?php
$mysqli->close();
?>
