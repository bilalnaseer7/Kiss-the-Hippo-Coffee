<?php
// Set timezone to New York
date_default_timezone_set("America/New_York");

// Get form data from POST - matching the HTML form field names
$customerName = $_POST['customer_name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$address = $_POST['address'];
$address2 = $_POST['address2'];
$city = $_POST['city'];
$state = $_POST['state'];
$zipcode = $_POST['zipcode'];
$country = $_POST['country'];
$cardNumber = $_POST['card_number'];
$cardName = $_POST['card_name'];
$expiryDate = $_POST['expiry_date'];
$cvv = $_POST['cvv'];
$cartData = $_POST['cart_data'];
$subtotal = $_POST['subtotal'];
$shipping = $_POST['shipping'];
$tax = $_POST['tax'];
$total = $_POST['total'];

// Generate order number
$orderNumber = rand(100000, 999999);

// Get current date and time
$date = date("m/d/Y");
$time = date("h:i A");

// Mask card number - show only last 4 digits
$lastFour = substr($cardNumber, -4);

// Decode cart data from JSON
$cartItems = json_decode($cartData, true);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Receipt | Kiss the Hippo Coffee</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #F5F1EB;
            color: #1a1a1a;
            margin: 0;
            padding: 40px;
        }
        .receipt-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 40px;
            border: 1px solid #ddd;
        }
        .receipt-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #1a1a1a;
        }
        .receipt-header h1 {
            font-family: Georgia, serif;
            font-size: 28px;
            margin: 0 0 10px 0;
        }
        .receipt-header p {
            margin: 5px 0;
            color: #666;
        }
        .receipt-section {
            margin-bottom: 25px;
        }
        .receipt-section h3 {
            font-size: 16px;
            margin: 0 0 10px 0;
            color: #1a1a1a;
            border-bottom: 1px solid #e0e0e0;
            padding-bottom: 5px;
        }
        .receipt-section p {
            margin: 5px 0;
            font-size: 14px;
        }
        .receipt-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        .receipt-item-name {
            flex: 1;
        }
        .receipt-item-price {
            text-align: right;
        }
        .receipt-totals {
            margin-top: 20px;
            padding-top: 15px;
            border-top: 2px solid #1a1a1a;
        }
        .receipt-totals p {
            display: flex;
            justify-content: space-between;
            margin: 8px 0;
        }
        .total-row {
            font-weight: bold;
            font-size: 18px;
        }
        .receipt-footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
            color: #666;
            font-size: 12px;
        }
        .print-btn {
            display: block;
            width: 200px;
            margin: 30px auto;
            padding: 15px 30px;
            background-color: #1a1a1a;
            color: #ffffff;
            font-family: Arial, sans-serif;
            font-size: 14px;
            border: none;
            cursor: pointer;
            text-align: center;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 15px;
            color: #666;
            text-decoration: underline;
        }
        @media print {
            .print-btn, .back-link {
                display: none;
            }
        }
    </style>
</head>
<body>

<div class="receipt-container">
    
    <div class="receipt-header">
        <h1>KISS THE HIPPO</h1>
        <p>Order Confirmation</p>
        <p>Order #: <?php echo $orderNumber; ?></p>
        <p>Date: <?php echo $date; ?> at <?php echo $time; ?></p>
    </div>

    <div class="receipt-section">
        <h3>Customer Information</h3>
        <p><strong>Name:</strong> <?php echo $customerName; ?></p>
        <p><strong>Email:</strong> <?php echo $email; ?></p>
        <p><strong>Phone:</strong> <?php echo $phone; ?></p>
    </div>

    <div class="receipt-section">
        <h3>Shipping Address</h3>
        <p><?php echo $address; ?></p>
        <?php if ($address2 != "") { ?>
            <p><?php echo $address2; ?></p>
        <?php } ?>
        <p><?php echo $city; ?>, <?php echo $state; ?> <?php echo $zipcode; ?></p>
        <p><?php echo $country; ?></p>
    </div>

    <div class="receipt-section">
        <h3>Payment Method</h3>
        <p>Card ending in: ****<?php echo $lastFour; ?></p>
    </div>

    <div class="receipt-section">
        <h3>Order Items</h3>
        <?php
        if ($cartItems) {
            for ($i = 0; $i < count($cartItems); $i++) {
                $item = $cartItems[$i];
                $itemName = $item['name'];
                $itemQty = $item['quantity'];
                $itemPrice = $item['price'];
                $itemTotal = $itemPrice * $itemQty;
                
                echo "<div class='receipt-item'>";
                echo "<div class='receipt-item-name'>";
                echo "<strong>" . $itemName . "</strong><br>";
                echo "<small>Qty: " . $itemQty;
                
                if (isset($item['bagSize'])) {
                    echo " | Size: " . $item['bagSize'];
                }
                if (isset($item['grind'])) {
                    echo " | Grind: " . $item['grind'];
                }
                
                echo "</small>";
                echo "</div>";
                echo "<div class='receipt-item-price'>$" . number_format($itemTotal, 2) . "</div>";
                echo "</div>";
            }
        }
        ?>
    </div>

    <div class="receipt-totals">
        <p><span>Subtotal:</span><span>$<?php echo $subtotal; ?></span></p>
        <p><span>Shipping:</span><span>$<?php echo $shipping; ?></span></p>
        <p><span>Tax (8%):</span><span>$<?php echo $tax; ?></span></p>
        <p class="total-row"><span>Total:</span><span>$<?php echo $total; ?> USD</span></p>
    </div>

    <div class="receipt-footer">
        <p>Thank you for your order!</p>
        <p>Kiss the Hippo Coffee | Boldly Brewed Since 2020</p>
        <p>Questions? Contact us at hello@kissthehippo.com</p>
    </div>

</div>

<button class="print-btn" onclick="window.print()">PRINT RECEIPT</button>
<a href="index.html" class="back-link">Return to Home</a>

<script>
    // Clear the cart after successful order
    localStorage.removeItem('cart');
</script>

</body>
</html>
