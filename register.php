<?php
session_start();


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
       
        $sql = "INSERT INTO users (username, password) VALUES (:u, :p)";
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':u', $username, SQLITE3_TEXT);
        $stmt->bindValue(':p', $password, SQLITE3_TEXT);
        $stmt->execute();

     
        $_SESSION['username'] = $username;

        header("Location: index.html");
        exit;
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
            background-color: #F5F1EB;
            font-family: Arial, sans-serif;
        }
        .form-container {
            max-width: 380px;
            margin: 80px auto;
            padding: 30px;
            background-color: #fff;
            border: 1px solid #ddd;
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

</body>
</html>

