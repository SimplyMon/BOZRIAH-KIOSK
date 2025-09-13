<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Summary</title>
    <link rel="stylesheet" href="../styles/order_summary.css">
</head>


<body>
    <div class="summary-container">
        <div class="header">
            <div class="logo"><img src="../assets/products/logo.png" alt=""></div>
            <p>Order Summary</p>
        </div>

        <div class="store-info">
            <h2>BOZRIAH</h2>
            <p href="#">AIC Burgundy Empire Tower, Sapphire Rd, Ortigas Center, Pasig, Metro Manila</p>
        </div>

        <div class="customer-info">
            <p>Date: <span id="order-date"></span></p>
            <p style="font-weight: bold; margin-top: 10px"><span id="order-type"></span></p>
        </div>

        <div class="order-items-container">
            <table class="order-table">
                <thead>
                    <tr>
                        <th>PRODUCT NAME</th>
                        <th>PRICE</th>
                        <th>QUANTITY</th>
                        <th>TOTAL PRICE</th>
                    </tr>
                </thead>
                <tbody id="order-items"></tbody>
            </table>
        </div>

        <p class="grand-total">GRAND TOTAL: P <span id="grand-total">0</span></p>

        <div class="btn-container">
            <button class="btn back-btn" onclick="goBack()">Back</button>
            <button class="btn confirm-btn" onclick="confirmOrder()">Confirm</button>

        </div>
    </div>

    <script src="../js/order_summary.js"></script>

</body>

</html>