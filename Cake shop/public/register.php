<?php
session_start();
require_once '../includes/db.php';

$username = $password = $email = "";
$username_err = $password_err = $email_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter a username.";
    } else {
        $username = trim($_POST["username"]);
    }

    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";
    } else {
        $password = trim($_POST["password"]);
    }

    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter an email.";
    } else {
        $email = trim($_POST["email"]);
    }

    if (empty($username_err) && empty($password_err) && empty($email_err)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        if ($stmt = $mysqli->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)")) {
            $stmt->bind_param("sss", $username, $hashed_password, $email);

            if ($stmt->execute()) {
                header("location: login.php");
            } else {
                echo "Something went wrong. Please try again later.";
            }

            $stmt->close();
        }
    }
    $mysqli->close();
}
?>

<?php include '../includes/header.php'; ?>

<div class="register-container">
    <h2>Register</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="register-form">
        <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
            <label>Username</label>
            <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>" class="form-control" required>
            <span class="help-block"><?php echo $username_err; ?></span>
        </div>
        <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
            <span class="help-block"><?php echo $password_err; ?></span>
        </div>
        <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
            <label>Email</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" class="form-control" required>
            <span class="help-block"><?php echo $email_err; ?></span>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Register">
        </div>
        <p>Already have an account? <a href="login.php">Login here</a>.</p>
    </form>
</div>

<?php include '../includes/footer.php'; ?>
