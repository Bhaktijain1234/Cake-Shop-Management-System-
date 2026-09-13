<?php
session_start();
require_once '../includes/db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST["product_id"];
    $user_id = $_SESSION["id"];
    $rating = $_POST["rating"];
    $comment = $_POST["comment"];

    if (!empty($rating) && !empty($comment)) {
        $stmt = $mysqli->prepare("INSERT INTO reviews (product_id, user_id, rating, comment) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iiis", $product_id, $user_id, $rating, $comment);

        if ($stmt->execute()) {
            header("location: ../public/product.php?id=" . $product_id);
        } else {
            echo "Something went wrong. Please try again later.";
        }

        $stmt->close();
    }
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit a Review</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #fefae0;
            color: #d4a373;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .review-container {
            background-color: #e9edc9;
            border: 1px solid #faedcd;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 500px;
            width: 100%;
        }
        .review-container h2 {
            text-align: center;
            color: #d4a373;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #d4a373;
        }
        .form-control {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
            border: 1px solid #d4a373;
            border-radius: 4px;
        }
        .form-control:focus {
            border-color: #faedcd;
            outline: none;
        }
        .btn {
            width: 100%;
            padding: 10px;
            background-color: #ccd5ae;
            border: none;
            border-radius: 4px;
            color: #d4a373;
            font-size: 16px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #faedcd;
        }
        .help-block {
            color: red;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
    <div class="review-container">
        <h2>Submit a Review</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label for="rating">Rating</label>
                <select name="rating" id="rating" class="form-control">
                    <option value="1">1 - Very Poor</option>
                    <option value="2">2 - Poor</option>
                    <option value="3">3 - Average</option>
                    <option value="4">4 - Good</option>
                    <option value="5">5 - Excellent</option>
                </select>
            </div>
            <div class="form-group">
                <label for="comment">Comment</label>
                <textarea name="comment" id="comment" class="form-control" rows="5"></textarea>
            </div>
            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($_GET['product_id']); ?>">
            <div class="form-group">
                <input type="submit" class="btn" value="Submit Review">
            </div>
        </form>
    </div>
</body>
</html>
