<nav>
    <ul>
        <li><a href="/public/index.php">Home</a></li>
        <li><a href="/public/cart.php">Cart</a></li>
        <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
            <li><a href="/public/logout.php">Logout</a></li>
            <li><a href="/public/view_order.php">My Orders</a></li>
        <?php else: ?>
            <li><a href="/public/login.php">Login</a></li>
            <li><a href="/public/register.php">Register</a></li>
        <?php endif; ?>
    </ul>
</nav>
