<?php
// login.php
session_start();

// Simple hardcoded credentials (for Hydra brute force). Add better creds later
$valid_users = ["admin", "john_doe", "root"];
$valid_passwords = ["admin", "john_doen't", "root"];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    if (in_array($user, $valid_users, TRUE) && in_array($pass, $valid_passwords, TRUE)) {
        // Store login flag in session
        $_SESSION['authenticated'] = true;
        $_SESSION['user'] = $user; // used for admin page auth later
        $redirect = $_SESSION['intended_destination'] ?? 'index.php';
        unset($_SESSION['intended_destination']);
        header("Location: $redirect");
        exit;
    } 
    else {
        $error = "INVALID LOGIN";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Login</title>
        <link rel="stylesheet" href="./css/mdb.min.css">
        <link rel="stylesheet" href="./css/global.css">
        <link rel="stylesheet" href="./css/login.css">
        <script src="./js/mdb.umd.min.js" defer></script>
    </head>

<body>
    <div class="flex-parent">
        <h2>Jericho Water Co. Login</h2>
        <form method="POST">

            <!-- TODO: make the input fields horizontally wider so the website doesn't look so old. -->
            <!-- Compare against the mdb examples - you'll see what I mean. -->

            <!-- username input -->
            <div data-mdb-input-init class="form-outline mb-4">
                <input type="text" id="login-username-input" name="username" class="form-control" />
                <label class="form-label" for="login-username-input">Username</label>
            </div>

            <!-- password input -->
            <div data-mdb-input-init class="form-outline mb-4">
                <input type="password" id="login-password-input" name="password" class="form-control" />
                <label class="form-label" for="login-password-input">Password</label>
            </div>

            <!-- Submit button -->
            <button type="submit" data-mdb-ripple-init class="btn btn-primary btn-block mb-4">Sign in</button>
        </form>
    </div>

    <?php
    if (isset($error)) echo "<p class='error'>$error</p>";
    // Note the conspicuous lack of failed login limits or password lockouts...
    ?>
    </body>
</html>