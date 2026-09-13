<?php
session_start();
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
    header("location: ../public/login.php");
    exit;
}

require_once '../includes/db.php';

if (isset($_GET['id'])) {
    $product_id = intval($_GET['id']);

    $stmt = $mysqli->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);

    if ($stmt->execute()) {
        header("location: catalog.php");
        exit;
    } else {
        echo "Something went wrong. Please try again later.";
    }

    $stmt->close();
} else {
    echo "Product ID not provided.";
}

$mysqli->close();
?>
