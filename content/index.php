<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories & Products</title>
    <link rel="stylesheet" href="./styles/screen.css">
    <?php include './config/dbconn.php'; ?>
    <link rel="icon" type="image/x-icon" href="./assets/products/logo.png">
</head>

<body>

    <div class="container">
        <div class="menu-section">
            <div class="category-container">
                <button class="category-btn" data-category="ALL" onclick="fetchProducts('ALL')">All</button>

                <?php
                $categoriesToCheck = [''];

                $sql = "
                SELECT dbo.d1(CategoryCode) AS CategoryCode, dbo.d1(Description) AS Description
                FROM CTGM
                ORDER BY CASE dbo.d1(Description)
                    WHEN 'Meal 1' THEN 1
                    WHEN 'Meal 2' THEN 2
                    WHEN 'Meal 3' THEN 3
                    WHEN 'Meal 4' THEN 4
                    WHEN 'Single Order' THEN 5
                    WHEN 'Drinks' THEN 6
                    WHEN 'Others' THEN 7
                    ELSE 99
                END
                ";
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($categories as $category) {
                    $categoryCode = $category['CategoryCode'];

                    // Check only selected categories for subcategories
                    if (in_array($categoryCode, $categoriesToCheck)) {
                        $subCatSql = "SELECT COUNT(*) FROM CTGS WHERE dbo.d1(CategoryCode) = ?";
                        $subCatStmt = $conn->prepare($subCatSql);
                        $subCatStmt->execute([$categoryCode]);
                        $subCatCount = $subCatStmt->fetchColumn();

                        // Skip this category if it has no subcategories
                        if ($subCatCount == 0) {
                            continue;
                        }
                    }

                    echo "<button class='category-btn' data-category='" . htmlspecialchars($categoryCode) . "' 
                        onclick='fetchSubcategories(\"" . htmlspecialchars($categoryCode) . "\"); fetchProducts(\"" . htmlspecialchars($categoryCode) . "\")'>" .
                        htmlspecialchars($category['Description']) .
                        "</button>";
                }
                ?>
            </div>

            <div class="subcategory-container" id="subcategory-container"></div>

            <!-- Products Section -->
            <div class="products-container" id="products">
                <h2>Select a category to view products.</h2>
            </div>
        </div>

        <div class="cart-section">
            <div class="cart-items" id="cart-items">
                <p>No items in cart.</p>
            </div>
            <div class="cart-summary">
                <div class="order-type">
                    <label>
                        <input type="radio" name="orderType" value="Dine In" checked> Dine In
                    </label>
                    <label>
                        <input type="radio" name="orderType" value="Take Out"> Take Out
                    </label>
                </div>
                <p>Grand Total: <span id="grandtotal">0.00</span></p>
                <button class="place-order-btn" onclick="placeOrder()">Place Order</button>
            </div>
        </div>
    </div>


    <div id="sleep-screen" class="sleep-overlay" onclick="wakeScreen()">
        <div class="sleep-container">
            <h1>ORDER HERE</h1>
            <p>Click anywhere to proceed</p>
        </div>
    </div>


    <!-- MODAL -->
    <div id="custom-alert" class="modal">
        <div class="modal-content">
            <p id="alert-message">Your cart is empty. Please add items before placing an order.</p>
            <button onclick="closeModal()">OK</button>
        </div>
    </div>

    <style>
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            align-items: center;
            justify-content: center;
        }


        .modal-content {
            background: white;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            width: 300px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            animation: fadeIn 0.3s ease-in-out;
        }

        .modal-content p {
            font-size: 18px;
            margin-bottom: 15px;
        }

        .modal-content button {
            padding: 14px 40px;
            background-color: #f58220;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .modal-content button:hover {
            background-color: black;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }
    </style>

    <script src="./js/fetchProducts.js"></script>
    <script src="./js/sleepScreen.js"></script>
    <script src="./js/placeOrder.js"></script>
    <script src="./js/showModal.js"></script>

</body>

</html>