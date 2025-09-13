<?php
include '../config/dbconn.php';

header('Content-Type: application/json');

if (!isset($_GET['category'])) {
    echo json_encode(["error" => "Category not provided"]);
    exit;
}

$category = $_GET['category'];
$subcategory = isset($_GET['subcategory']) ? $_GET['subcategory'] : null;

try {
    if ($category === "ALL") {
        $sql = "SELECT 'True' AS ReturnItem, 
            dbo.d1(ItemID) AS ItemID, 
            dbo.d1(ItemDesc) AS ItemDesc, 
            dbo.d1(Servings) AS Servings, 
            dbo.d1(isComboMeal) AS isComboMeal, 
            CAST(Price AS DECIMAL(10,2)) AS Price, 
            IsAvailable 
            FROM ITEM";
        $stmt = $conn->prepare($sql);
    } else {
        $sql = "SELECT 'True' AS ReturnItem, 
       dbo.d1(ItemID) AS ItemID, 
       dbo.d1(ItemDesc) AS ItemDesc, 
       dbo.d1(Servings) AS Servings, 
       dbo.d1(isComboMeal) AS isComboMeal, 
       CAST(Price AS DECIMAL(10,2)) AS Price, 
       IsAvailable 
FROM ITEM 
WHERE dbo.d1(MainCategory) = :category";

        if ($subcategory) {
            $sql .= " AND dbo.d1(SubCategory) = :subcategory";
        }

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':category', $category);
        if ($subcategory) {
            $stmt->bindParam(':subcategory', $subcategory);
        }
    }

    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($products);
} catch (PDOException $e) {
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
}
