<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bakery Management System</title>
    <link rel="stylesheet" href="../includes/css/style.css">
</head>
<body>
<header>
    <h1>Bakery Management System</h1>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="catalog.php">Product Catalog</a></li>
            <?php if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== true): ?>
                <li><a href="cart.php">Shopping Cart</a></li>
                <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
                    <li><a href="orders.php">My Orders</a></li>
                    <li><a href="logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="login.php">Login</a></li>
                    <li><a href="register.php">Register</a></li>
                <?php endif; ?>
            <?php endif; ?>
            <?php if (isset($_SESSION['admin']) && $_SESSION['admin'] === true): ?>
                <li><a href="../admin/index.php">Admin Panel</a></li>
                <li><a href="logout.php">Logout</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>
<main>
