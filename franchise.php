<?php
//connect to database
$path = "/home/ek4138/databases";
$db = new SQLite3($path . "/users.db");

//create table if table does not exist 
$db->exec("CREATE TABLE IF NOT EXISTS franchise (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    email TEXT,
    date TEXT,
    time TEXT
)");

date_default_timezone_set("America/New_York");

//get email from the form 
$email = $_POST['email'];
$date = date("m/d/Y");
$time = date("h:i A");

//var 
$current = false; 

//check for duplicates
$check = $db->prepare("SELECT email FROM franchise WHERE email = :email");
$check->bindValue(":email", $email, SQLITE3_TEXT);
$result = $check->execute();

//insert new 
if ($result->fetchArray()){
    $current = true;
}
else {
    $stmt = $db->prepare("INSERT INTO franchise (email, date, time) VALUES (:email, :date, :time)");
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
    <title>Contact us for Franchise Opprtunities</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;            
            background-color: #F5F1EB;
            color: #1a1a1a;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }

        .franchise-container {
            max-width: 500px;
            background-color: #ffffff;
            padding: 60px 40px;
            text-align: center;
            border: 1px solid #e0e0e0;
        }

        .icon {
            font-size: 50px;
            margin-bottom: 20px;
        }

        .title {
            font-family: Georgia, serif;
            font-size: 30px;
            margin: 0 0 20px 0;
        }

        .message {
            font-size: 20px;
            color: #666;
            line-height: 1.6;
            margin: 0 0 20px 0;
        }

        .email-box {
            font-family: Georgia, serif;
            font-size: 20px;
            background-color: #F5F1EB;
            padding: 20px;
            margin: 30px 0;
            border: 1px solid #e0e0e0;
        }

        .date-info {
            font-size: 15px;
            color: #888;
            margin-top: 25px;
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

<div class="franchise-container">

<?php 
    if ($current) {

        echo '
            <div class="icon">⚠️</div>
            <div class="title">Already Submitted Inquiry</div>
            <p class="message">
                Hippo has already received your franchise inquiry.<br>
                We will be in touch soon! 
            </p>

            <div class="email-box">' . $email . '</div>
            <a class="home-link" href="index.html">GO BACK</a>
        ';
    }
?>

<?php
if (!$current){
    
    echo '
        <div class="icon">☕️</div>
        <div class="title">Thank You</div>
        <p class="message">
            We have received your franchise inquiry.<br><br>
            <strong>Our team will get in touch with you soon.</strong>
        </p>

        <div class="email-box">' . $email . '</div>
        <div class="date-info">
            Submitted on ' . $date . ' at ' . $time .' 
        </div>

        <a class="home-link" href="index.html">RETURN HOME</a>

    '; 
}
?>

<p class="footer-text">
        Kiss the Hippo Coffee | Boldly Brewed Since 2020
</p>

</div>

</body>
</html>
