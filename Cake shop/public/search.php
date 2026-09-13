<?php
session_start();
require_once '../includes/db.php';

$search_query = '';
$products = array();

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['query'])) {
    $search_query = $_GET['query'];
    $stmt = $mysqli->prepare("SELECT * FROM products WHERE name LIKE ? OR description LIKE ?");
    $param = "%" . $search_query . "%";
    $stmt->bind_param("ss", $param, $param);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }

    $stmt->close();
}

$mysqli->close();
?>

<?php include '../includes/header.php'; ?>
<h2>Search Results for "<?php echo $search_query; ?>"</h2>
<div class="products">
    <?php foreach ($products as $product): ?>
        <div class="product">
            <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
            <h3><?php echo $product['name']; ?></h3>
            <p><?php echo $product['description']; ?></p>
            <p>$<?php echo $product['price']; ?></p>
            <a href="product.php?id=<?php echo $product['id']; ?>">View Product</a>
        </div>
    <?php endforeach; ?>
</div>
<?php include '../includes/footer.php'; ?>
