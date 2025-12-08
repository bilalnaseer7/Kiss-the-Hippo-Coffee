<?php
// Set timezone to New York
date_default_timezone_set("America/New_York");

// Get email from POST
$email = $_POST['email'];

// Get current date and time
$date = date("m/d/Y");
$time = date("h:i A");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscription Confirmed | Kiss the Hippo Coffee</title>
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
            margin: 0 auto;
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
            font-weight: normal;
            margin: 0 0 20px 0;
            color: #1a1a1a;
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
            color: #1a1a1a;
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
            font-family: Arial, sans-serif;
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
    
    <p class="footer-text">
        Kiss the Hippo Coffee | Boldly Brewed Since 2020
    </p>
</div>

</body>
</html>
