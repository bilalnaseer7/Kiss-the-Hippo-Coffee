<?php
session_start();


if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $path = "/home/zs2720/databases";
    $db = new SQLite3($path . "/users.db");

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
            header("Location: index.html");
            exit;
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
<title>Login | Kiss the Hippo Coffee</title>
<link rel="stylesheet" href="styles.css">

<style>
body {
    background-color: #F5F1EB;
    font-family: Arial, sans-serif;
}
.login-container {
    width: 400px;
    background: white;
    margin: 120px auto;
    padding: 40px;
    border: 1px solid #ddd;
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

</body>
</html>
