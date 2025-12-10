<?php
session_start();
$error = "";
$registrationSuccess = false;
$registeredUsername = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $path = "/home/zs2720/databases";
    $db = new SQLite3($path . "/users.db");

    
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
        // Check if username already exists in database
        $check = $db->prepare("SELECT username FROM users WHERE username = :username");
        $check->bindValue(":username", $username, SQLITE3_TEXT);
        $result = $check->execute();
        
        if ($result->fetchArray()) {
            // Username already exists
            $error = "This username is already registered. Please try with a different username.";
        } else {
            // Username is new, so add it to database
            $sql = "INSERT INTO users (username, password) VALUES (:u, :p)";
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':u', $username, SQLITE3_TEXT);
            $stmt->bindValue(':p', $password, SQLITE3_TEXT);
            $stmt->execute();

            $_SESSION['username'] = $username;
            $registrationSuccess = true;
            $registeredUsername = $username;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Kiss the Hippo Coffee</title>
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
        .form-container {
            max-width: 380px;
            margin: 80px auto;
            padding: 30px;
            background-color: #fff;
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
        h2 {
            text-align: center;
            font-family: Georgia, serif;
            margin-bottom: 25px;
        }
        input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #1a1a1a;
            color: white;
            cursor: pointer;
            border: none;
        }
        .switch-link {
            text-align: center;
            margin-top: 15px;
        }
        .error-msg {
            color: red;
            text-align: center;
            margin-bottom: 10px;
            font-size: 14px;
        }
    </style>
</head>
<body>

<?php if ($registrationSuccess == true) { ?>
    <!-- Show confirmation page after successful registration -->
    <div class="confirmation-container">
        <div class="confirmation-icon">☕</div>
        <h1 class="confirmation-title">Thank You for Registering!</h1>
        
        <p class="confirmation-message">
            Thank you for registering with Kiss the Hippo Coffee.<br>
            Your account has been successfully created.
        </p>
        
        <div class="confirmation-username">
            Welcome, <?php echo htmlspecialchars($registeredUsername); ?>!
        </div>

        <a href="index.html" class="home-link">RETURN TO HOME</a>
        <a href="login.php" class="home-link" style="margin-left: 10px; background-color: #666;">LOGIN NOW</a>

        <p class="footer-text">
            Kiss the Hippo Coffee | Boldly Brewed Since 2020
        </p>
    </div>

<?php } else { ?>
    <!-- Show registration form -->
    <div class="form-container">
        <h2>Create an Account</h2>

        <?php if (!empty($error)) : ?>
            <p class="error-msg"><?php echo $error; ?></p>
        <?php endif; ?>

        <form action="register.php" method="POST">
            <input type="text" name="username" placeholder="Enter username" required>

            <input type="password" name="password" placeholder="Enter password" required>

            <button type="submit">Register</button>
        </form>

        <p class="switch-link">
            Already have an account? <a href="login.php">Login here</a>
        </p>
    </div>
<?php } ?>

</body>
</html>
