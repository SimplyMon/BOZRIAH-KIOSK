<?php
include '../config/dbconn.php';

function checkAndAddColumns($conn)
{
    $columns = [
        "isFromKiosk" => "BIT DEFAULT 0",
        "WaitingNo" => "VARCHAR(5) DEFAULT '00001'",
        "isTakeout" => "BIT DEFAULT 0"
    ];

    foreach ($columns as $column => $type) {
        $checkQuery = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'OSEH' AND COLUMN_NAME = ?";
        $stmt = $conn->prepare($checkQuery);
        $stmt->execute([$column]);

        if ($stmt->rowCount() === 0) {
            $alterQuery = "ALTER TABLE OSEH ADD $column $type";
            $conn->exec($alterQuery);
        }
    }
}

checkAndAddColumns($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $osDate = date('Y-m-d H:i:s');
        $isFromKiosk = 1;

        $inputData = json_decode(file_get_contents("php://input"), true);
        $orderType = isset($inputData['orderType']) && $inputData['orderType'] === "Takeout" ? 1 : 0;

        $waitingNoQuery = "SELECT COALESCE(MAX(CAST(WaitingNo AS INT)), 0) + 1 FROM OSEH WHERE isFromKiosk = 1";
        $waitingNoStmt = $conn->query($waitingNoQuery);
        $waitingNo = $waitingNoStmt->fetchColumn();

        $waitingNo = str_pad($waitingNo, 5, '0', STR_PAD_LEFT);

        $osNumber = $waitingNo;

        $sql = "INSERT INTO OSEH (OSNumber, OSDate, isFromKiosk, isTakeout, WaitingNo, BranchCode, TableID, TerminalID, CashierID, HeadCount, EntryDate, isReady, isServed, ReadyDate, ClockSeqNo, PreparedBy)
                OUTPUT INSERTED.SeqNo
                VALUES (?, ?, ?, ?, ?, 0, 0, 0, 0, 0, ?, 0, 0, NULL, 0, 0)";

        $stmt = $conn->prepare($sql);
        $stmt->execute([$osNumber, $osDate, $isFromKiosk, $orderType, $waitingNo, $osDate]);
        $seqNo = $stmt->fetchColumn();

        echo json_encode(["success" => true, "seqNo" => $seqNo, "waitingNo" => $waitingNo]);
    } catch (Exception $e) {
        echo json_encode(["success" => false, "error" => $e->getMessage()]);
    }
}
