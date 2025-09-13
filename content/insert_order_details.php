<?php
include '../config/dbconn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $data = json_decode(file_get_contents("php://input"), true);
        $osehSeqNo = $data['osehSeqNo'];
        $cart = $data['cart'];

        foreach ($cart as $item) {
            $sql = "INSERT INTO OSED (OSEHSeqNo, ItemID, DrinkItemCode, Quantity, Amount, DiscCode, DiscPercent, DiscAmount)
                    VALUES (?, ?, 0, ?, ?, 0, 0, 0)";

            $stmt = $conn->prepare($sql);
            $stmt->execute([$osehSeqNo, $item['id'], $item['quantity'], $item['price'] * $item['quantity']]);
        }

        echo json_encode(["success" => true]);
    } catch (Exception $e) {
        echo json_encode(["success" => false, "error" => $e->getMessage()]);
    }
}
