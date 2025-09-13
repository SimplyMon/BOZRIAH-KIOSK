<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Successful</title>
    <link rel="stylesheet" href="../styles/success.css">
</head>

<body>

    <div class="overlay">
        <div class="success-modal">
            <div class="header">
                <div class="logo"><img src="../assets/products/logo.png" alt=""></div>
                <p>Order Successful</p>
            </div>

            <img src="../assets/products/success.png" alt="Success" class="success-image">
            <p>Your <strong>Order Number</strong> is: <span class="waiting-number" id="waiting-number"></span></p>

            <div class="payment-info">
                <div>
                    <p><strong>Payment Detail:</strong></p>
                    <p>Amount to Pay</p>
                </div>
                <div>
                    <p class="grand-total">P <span id="grand-total">0</span></p>
                </div>
            </div>

            <button class="btn" onclick="goHome()">Confirm</button>
        </div>
    </div>

    <script src="../js/success.js"></script>

</body>


</html>