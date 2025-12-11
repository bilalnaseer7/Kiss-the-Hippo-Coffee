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

// Initialize variables
$email = isset($_POST['email']) ? $_POST['email'] : "";
$date = date("m/d/Y");
$time = date("h:i A");

//var 
$current = false;
$showForm = true;

// Only process if POST data exists
if (isset($_POST['email']) && $_POST['email'] !== "") {
    $showForm = false;
    
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
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact us for Franchise Opportunities</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;            
            background-color: #F5F1EB;
            color: #1a1a1a;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
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

        .franchise-form {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-top: 30px;
        }

        .franchise-input {
            width: 100%;
            padding: 15px;
            font-family: Georgia, serif;
            font-size: 15px;
            border: 1px solid #ccc;
            background-color: #fff;
            color: #1a1a1a;
            box-sizing: border-box;
            text-align: center;
        }

        .franchise-input:focus {
            outline: none;
            border-color: #8B7355;
        }

        .franchise-submit {
            width: 100%;
            padding: 15px;
            background-color: #1a1a1a;
            color: #ffffff;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 14px;
            letter-spacing: 1px;
            border: none;
            cursor: pointer;
        }

        .franchise-submit:hover {
            background-color: #333;
        }

        .form-note {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
            color: #999;
            margin: 20px 0 0 0;
        }
    </style>
</head>
<body>

<div class="franchise-container">

<?php if ($showForm == true) { ?>
    <!-- Show franchise inquiry form -->
    <div class="icon">☕</div>
    <h1 class="title">Franchise Opportunities</h1>
    <p class="message">
        Interested in opening a Kiss the Hippo Coffee location?<br>
        Submit your email below and our team will get in touch with you.
    </p>
    
    <form class="franchise-form" action="franchise.php" method="POST">
        <input type="email" name="email" class="franchise-input" placeholder="Enter your email address" required>
        <input type="submit" value="SUBMIT INQUIRY" class="franchise-submit">
    </form>
    
    <p class="form-note">We'll contact you soon about franchise opportunities.</p>
    
    <a href="index.html" class="home-link">RETURN TO HOME</a>

<?php } elseif ($current == true) { ?>
    <!-- Already submitted -->
    <div class="icon">⚠️</div>
    <h1 class="title">Already Submitted Inquiry</h1>
    <p class="message">
        Hippo has already received your franchise inquiry.<br>
        We will be in touch soon! 
    </p>

    <div class="email-box"><?php echo htmlspecialchars($email); ?></div>
    <a class="home-link" href="index.html">GO BACK</a>

<?php } else { ?>
    <!-- Successfully submitted -->
    <div class="icon">☕️</div>
    <h1 class="title">Thank You</h1>
    <p class="message">
        We have received your franchise inquiry.<br><br>
        <strong>Our team will get in touch with you soon.</strong>
    </p>

    <div class="email-box"><?php echo htmlspecialchars($email); ?></div>
    <div class="date-info">
        Submitted on <?php echo $date; ?> at <?php echo $time; ?> 
    </div>

    <a class="home-link" href="index.html">RETURN HOME</a>
<?php } ?>

<p class="footer-text">
    Kiss the Hippo Coffee | Boldly Brewed Since 2020
</p>

</div>

</body>
</html>
