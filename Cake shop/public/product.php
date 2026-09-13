<?php
session_start();
require_once '../includes/db.php';

$product_id = $_GET['id'];
$product_query = $mysqli->query("SELECT * FROM products WHERE id = $product_id");
$product = $product_query->fetch_assoc();

$reviews_query = $mysqli->query("SELECT reviews.rating, reviews.comment, users.username FROM reviews JOIN users ON reviews.user_id = users.id WHERE product_id = $product_id");
?>

<?php include '../includes/header.php'; ?>

<div class="product-details" style="max-width: 800px; margin: 0 auto; padding: 20px; background-color: #e9edc9; border: 1px solid #faedcd; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
    <h2 style="text-align: center; color: #d4a373;"><?php echo $product['name']; ?></h2>
    <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" style="width: 100%; height: auto; border-radius: 8px; margin-bottom: 20px;">
    <p style="text-align: center; color: #6c757d;">Price: RS:<?php echo $product['price']; ?></p>
    <p style="text-align: center; color: #6c757d;"><?php echo $product['description']; ?></p>
    
    <form action="cart.php" method="post" style="text-align: center;">
        <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
        <input type="submit" value="Add to Cart" style="padding: 10px 20px; background-color: #ccd5ae; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-size: 16px; font-weight: bold;">
    </form>

    <div class="reviews" style="margin-top: 20px;">
        <h3 style="text-align: center; color: #d4a373;">Reviews</h3>
        <?php while ($review = $reviews_query->fetch_assoc()): ?>
            <div class="review" style="padding: 10px; background-color: #fefae0; border-radius: 8px; margin-bottom: 10px;">
                <h4 style="margin: 0; color: #6c757d;"><?php echo $review['username']; ?> (Rating: <?php echo $review['rating']; ?>/5)</h4>
                <p style="margin: 5px 0; color: #6c757d;"><?php echo $review['comment']; ?></p>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
