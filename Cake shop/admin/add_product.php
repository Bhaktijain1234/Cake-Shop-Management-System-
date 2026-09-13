<?php
session_start();
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("location: ../public/login.php");
    exit;
}

require_once '../includes/db.php';

$name = $description = $price = $image = "";
$name_err = $description_err = $price_err = $image_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty(trim($_POST["name"]))) {
        $name_err = "Please enter a product name.";
    } else {
        $name = trim($_POST["name"]);
    }

    if (empty(trim($_POST["description"]))) {
        $description_err = "Please enter a product description.";
    } else {
        $description = trim($_POST["description"]);
    }

    if (empty(trim($_POST["price"]))) {
        $price_err = "Please enter a price.";
    } elseif (!is_numeric(trim($_POST["price"]))) {
        $price_err = "Price must be a number.";
    } else {
        $price = trim($_POST["price"]);
    }

    if ($_FILES["image"]["error"] == 0) {
        $target_dir = "../public/images/";
        $image = $target_dir . basename($_FILES["image"]["name"]);
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $image)) {
            $image = 'images/' . basename($_FILES["image"]["name"]);
        } else {
            $image_err = "There was an error uploading the file.";
        }
    } else {
        $image_err = "Please upload an image.";
    }

    if (empty($name_err) && empty($description_err) && empty($price_err) && empty($image_err)) {
        $stmt = $mysqli->prepare("INSERT INTO products (name, description, price, image) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssds", $name, $description, $price, $image);

        if ($stmt->execute()) {
            header("location: index.php");
            exit;
        } else {
            echo "Something went wrong. Please try again later.";
        }

        $stmt->close();
    }

    $mysqli->close();
}
?>

<?php include '../includes/header.php'; ?>

<div class="container mt-5">
    <h2 class="text-center mb-4">Add Product</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
        <div class="form-group mb-3">
            <label for="name">Product Name</label>
            <input type="text" name="name" id="name" class="form-control <?php echo (!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
            <span class="invalid-feedback"><?php echo $name_err; ?></span>
        </div>
        <div class="form-group mb-3">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control <?php echo (!empty($description_err)) ? 'is-invalid' : ''; ?>"><?php echo $description; ?></textarea>
            <span class="invalid-feedback"><?php echo $description_err; ?></span>
        </div>
        <div class="form-group mb-3">
            <label for="price">Price</label>
            <input type="text" name="price" id="price" class="form-control <?php echo (!empty($price_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $price; ?>">
            <span class="invalid-feedback"><?php echo $price_err; ?></span>
        </div>
        <div class="form-group mb-3">
            <label for="image">Image</label>
            <input type="file" name="image" id="image" class="form-control <?php echo (!empty($image_err)) ? 'is-invalid' : ''; ?>">
            <span class="invalid-feedback"><?php echo $image_err; ?></span>
        </div>
        <div class="text-center">
            <input type="submit" class="btn btn-primary" value="Submit">
        </div>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
