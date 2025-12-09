<?php
session_start();

$path = "/home/zs2720/databases";
$db = new SQLite3($path . "/users.db");


$db->exec("CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL
)");

$username = trim($_POST['username']);
$password = trim($_POST['password']);


if ($username === "" || $password === "") {
    echo "Username or password cannot be empty.<br>";
    echo "<a href='register.html'>Try again</a>";
    exit;
}


$check = $db->prepare("SELECT * FROM users WHERE username = :u");
$check->bindValue(':u', $username, SQLITE3_TEXT);
$exists = $check->execute()->fetchArray(SQLITE3_ASSOC);

if ($exists) {
    echo "Username already exists.<br>";
    echo "<a href='register.html'>Try again</a>";
    exit;
}


$sql = "INSERT INTO users (username, password) VALUES (:u, :p)";
$stmt = $db->prepare($sql);
$stmt->bindValue(':u', $username, SQLITE3_TEXT);
$stmt->bindValue(':p', $password, SQLITE3_TEXT);
$stmt->execute();


$_SESSION['username'] = $username;


header("Location: index.php");
exit;
?>
