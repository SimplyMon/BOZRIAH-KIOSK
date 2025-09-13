<?php
include './config/dbconn.php';

$category = $_GET['category'] ?? 'ALL';

if ($category === 'ALL') {
    $sql = "SELECT ItemID, ItemDesc, Price FROM DSVA";
} else {
    $sql = "SELECT ItemID, ItemDesc, Price FROM DSVA WHERE CategoryCode = ?";
}

$stmt = $conn->prepare($sql);

if ($category !== 'ALL') {
    $stmt->execute([$category]);
} else {
    $stmt->execute();
}

$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($products as &$product) {
    $product['ImagePath'] = "http://localhost/product-images/" . $product['ItemID'] . ".jpg";
}

header('Content-Type: application/json');
echo json_encode($products);
?>



<script>
    function fetchProducts(categoryCode) {
        fetch("content/fetch_product.php?category=" + categoryCode)
            .then((response) => response.json())
            .then((data) => {
                let productContainer = document.getElementById("products");
                productContainer.innerHTML = "";

                if (data.length === 0) {
                    productContainer.innerHTML = "<h2>No products found.</h2>";
                    return;
                }

                data.forEach((product) => {
                    let productCard = `
          <div class="product-card">
            <img src="${product.ImagePath}" alt="Product Image" class="product-image" onerror="this.onerror=null; this.src='./assets/products/default.jpg';">
            <div class="product-title">${product.ItemDesc}</div>
            <div class="product-price">₱${product.Price}</div>
            <button class="add-btn" onclick="addToCart('${product.ItemID}', '${product.ItemDesc}', ${product.Price})">ADD</button>
          </div>
        `;
                    productContainer.innerHTML += productCard;
                });

                // Set active category button
                setActiveCategory(categoryCode);
            })
            .catch((error) => console.error("Error fetching products:", error));
    }
</script>




<!-- mklink /D C:\xampp\htdocs\bozriah-POS\assets\products \\brian-pc\Shared\POSItemImage\ItemImage\
 -->

 <!--  -->