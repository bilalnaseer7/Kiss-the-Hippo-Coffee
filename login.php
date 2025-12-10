<?php
session_start();
$error = "";
$loginSuccess = false;
$loggedInUsername = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $path = "/home/zs2720/databases";
    $db = new SQLite3($path . "/users.db");

    // Create users table if it doesn't exist yet
    $db->exec("CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT NOT NULL,
        password TEXT NOT NULL
    )");

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if ($username === "" || $password === "") {
        $error = "Username or password cannot be empty.";
    } else {
        $sql = "SELECT * FROM users WHERE username = :u AND password = :p";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':u', $username, SQLITE3_TEXT);
        $stmt->bindValue(':p', $password, SQLITE3_TEXT);
        $result = $stmt->execute();

        $row = $result->fetchArray(SQLITE3_ASSOC);

        if ($row) {
            $_SESSION['username'] = $row['username'];
            $loginSuccess = true;
            $loggedInUsername = $row['username'];
        } else {
            $error = "Wrong username or password.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login | Kiss the Hippo Coffee</title>
<link rel="stylesheet" href="styles.css">

<style>
body {
    font-family: Arial, sans-serif;
    background-color: #F5F1EB;
    color: #1a1a1a;
    margin: 0;
    padding: 0;
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
}
.login-container {
    width: 400px;
    background: white;
    margin: 120px auto;
    padding: 40px;
    border: 1px solid #ddd;
}
.confirmation-container {
    max-width: 500px;
    background-color: #ffffff;
    padding: 60px 40px;
    text-align: center;
    border: 1px solid #e0e0e0;
}
.confirmation-icon {
    font-size: 48px;
    margin-bottom: 20px;
}
.confirmation-title {
    font-family: Georgia, serif;
    font-size: 28px;
    margin: 0 0 20px 0;
}
.confirmation-message {
    font-size: 15px;
    color: #666;
    line-height: 1.8;
    margin: 0 0 15px 0;
}
.confirmation-username {
    font-family: Georgia, serif;
    font-size: 16px;
    background-color: #F5F1EB;
    padding: 15px;
    margin: 25px 0;
    border: 1px solid #e0e0e0;
}
.home-link {
    display: inline-block;
    margin-top: 30px;
    padding: 15px 40px;
    background-color: #1a1a1a;
    color: #ffffff;
    text-decoration: none;
    font-size: 14px;
    letter-spacing: 1px;
}
.home-link:hover {
    background-color: #333;
}
.footer-text {
    font-size: 12px;
    color: #999;
    margin-top: 40px;
}
.login-title {
    font-family: Georgia, serif;
    font-size: 28px;
    text-align: center;
    margin-bottom: 20px;
}
.login-input {
    width: 100%;
    padding: 12px;
    margin: 10px 0;
    border: 1px solid #aaa;
    font-size: 16px;
    box-sizing: border-box;
}
.login-btn {
    width: 100%;
    padding: 12px;
    background: #1a1a1a;
    color: white;
    border: none;
    cursor: pointer;
    margin-top: 10px;
}
.login-btn:hover {
    background: #333;
}
.error {
    color: red;
    text-align: center;
}
.register-link {
    text-align: center;
    margin-top: 15px;
}
</style>
</head>
<body>

<?php if ($loginSuccess == true) { ?>
    <!-- Show confirmation page after successful login -->
    <div class="confirmation-container">
        <div class="confirmation-icon">☕</div>
        <h1 class="confirmation-title">You Are Logged In!</h1>
        
        <p class="confirmation-message">
            Welcome back to Kiss the Hippo Coffee.<br>
            You have successfully logged in to your account.
        </p>
        
        <div class="confirmation-username">
            Welcome, <?php echo htmlspecialchars($loggedInUsername); ?>!
        </div>

        <a href="index.html" class="home-link">RETURN TO HOME</a>

        <p class="footer-text">
            Kiss the Hippo Coffee | Boldly Brewed Since 2020
        </p>
    </div>

<?php } else { ?>
    <!-- Show login form -->
    <div class="login-container">

        <h2 class="login-title">Login</h2>

        <?php if (!empty($error)) { echo "<p class='error'>$error</p>"; } ?>

        <form action="login.php" method="POST">
            <input class="login-input" type="text" name="username" placeholder="Username" required>
            <input class="login-input" type="password" name="password" placeholder="Password" required>
            <button class="login-btn" type="submit">Login</button>
        </form>

        <div class="register-link">
            <p>Don't have an account? <a href="register.php">Register here</a></p>
        </div>

    </div>
<?php } ?>

</body>
</html>
