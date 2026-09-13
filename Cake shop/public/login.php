<?php
session_start();
require_once '../includes/db.php';

$username = $password = "";
$username_err = $password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty(trim($_POST["username"]))) {
        $username_err = "Please enter username.";
    } else {
        $username = trim($_POST["username"]);
    }

    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    if (empty($username_err) && empty($password_err)) {
        $role = $_POST['role'];
        if ($role == 'user') {
            $sql = "SELECT id, username, password FROM users WHERE username = ?";
        } else {
            $sql = "SELECT id, username, password FROM admins WHERE username = ?";
        }

        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("s", $param_username);
            $param_username = $username;

            if ($stmt->execute()) {
                $stmt->store_result();
                if ($stmt->num_rows == 1) {
                    $stmt->bind_result($id, $username, $hashed_password);
                    if ($stmt->fetch()) {
                        if (password_verify($password, $hashed_password)) {
                            session_regenerate_id();
                            if ($role == 'user') {
                                $_SESSION["loggedin"] = true;
                                $_SESSION["id"] = $id;
                                $_SESSION["username"] = $username;
                            } else {
                                $_SESSION["admin"] = true;
                                $_SESSION["id"] = $id;
                                $_SESSION["username"] = $username;
                            }
                            header("location: index.php");
                        } else {
                            $password_err = "The password you entered was not valid.";
                        }
                    }
                } else {
                    $username_err = "No account found with that username.";
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
        $stmt->close();
    }
    $mysqli->close();
}
?>

<?php include '../includes/header.php'; ?>

<div class="login-container">
    <h2>Login</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="login-form">
        <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
            <label>Username</label>
            <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>" class="form-control">
            <span class="help-block"><?php echo $username_err; ?></span>
        </div>    
        <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
            <label>Password</label>
            <input type="password" name="password" class="form-control">
            <span class="help-block"><?php echo $password_err; ?></span>
        </div>
        <div class="form-group">
            <label>Login as</label>
            <select name="role" class="form-control">
                <option value="user">User</option>
                <option value="admin">Admin</option>
            </select>
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" value="Login">
        </div>
        <p>Don't have an account? <a href="register.php">Register here</a>.</p>
        
    </form>
</div>

<?php include '../includes/footer.php'; ?>
