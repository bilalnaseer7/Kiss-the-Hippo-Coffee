<?php
// Connect to database
$path = "/home/bn2168/databases";
$db = new SQLite3($path . "/users.db");

// Create subscriptions table if it doesn't exist yet
$db->exec("CREATE TABLE IF NOT EXISTS subscriptions (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    email TEXT,
    date TEXT,
    time TEXT
)");

// Set timezone for date/time
date_default_timezone_set("America/New_York");

// Get email from form
$email = $_POST['email'];
$date = date("m/d/Y");
$time = date("h:i A");

// Check if this email is already in the database
$check = $db->prepare("SELECT email FROM subscriptions WHERE email = :email");
$check->bindValue(":email", $email, SQLITE3_TEXT);
$result = $check->execute();

if ($result->fetchArray()) {
    // Email already exists
    $already = true;
} else {
    // Email is new, so add it to database
    $already = false;

    $stmt = $db->prepare("INSERT INTO subscriptions (email, date, time) VALUES (:email, :date, :time)");
    $stmt->bindValue(":email", $email, SQLITE3_TEXT);
    $stmt->bindValue(":date", $date, SQLITE3_TEXT);
    $stmt->bindValue(":time", $time, SQLITE3_TEXT);
    $stmt->execute();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscription Status | Kiss the Hippo Coffee</title>
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
        .confirmation-email {
            font-family: Georgia, serif;
            font-size: 16px;
            background-color: #F5F1EB;
            padding: 15px;
            margin: 25px 0;
            border: 1px solid #e0e0e0;
        }
        .confirmation-date {
            font-size: 12px;
            color: #999;
            margin: 20px 0;
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
    </style>
</head>
<body>

<div class="confirmation-container">

    <?php if ($already == true) { ?>

        <div class="confirmation-icon">⚠️</div>
        <h1 class="confirmation-title">Already Subscribed</h1>

        <p class="confirmation-message">
            The email below is already subscribed.<br>
            Please enter a new email address.
        </p>

        <div class="confirmation-email">
            <?php echo $email; ?>
        </div>

        <a href="index.html" class="home-link">TRY AGAIN</a>

    <?php } else { ?>

        <div class="confirmation-icon">☕</div>
        <h1 class="confirmation-title">Welcome to the Hippo Club!</h1>
        
        <p class="confirmation-message">
            Thank you for subscribing to our newsletter.<br>
            You'll receive the latest updates, exclusive offers, and brewing tips.
        </p>
        
        <div class="confirmation-email">
            <?php echo $email; ?>
        </div>
        
        <p class="confirmation-date">
            Subscribed on <?php echo $date; ?> at <?php echo $time; ?>
        </p>

        <a href="index.html" class="home-link">RETURN TO HOME</a>

    <?php } ?>

    <p class="footer-text">
        Kiss the Hippo Coffee | Boldly Brewed Since 2020
    </p>

</div>

</body>
</html>
