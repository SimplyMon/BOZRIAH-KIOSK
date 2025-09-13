<?php
include '../config/dbconn.php';

header('Content-Type: application/json');

if (!isset($_GET['category'])) {
    echo json_encode(["error" => "Category code is missing"]);
    exit;
}

$categoryCode = $_GET['category'];

try {
    $sql = "SELECT dbo.d1(SubCategoryCode) AS SubCategoryCode, dbo.d1(Description) AS Description 
            FROM CTGS 
            WHERE dbo.d1(CategoryCode) = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$categoryCode]);
    $subcategories = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($subcategories);
} catch (PDOException $e) {
    echo json_encode(["error" => "Database error: " . $e->getMessage()]);
}
