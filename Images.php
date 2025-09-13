<?php
include './config/dbconn.php';

if (isset($_GET['id'])) {
    $itemID = preg_replace('/[^A-Za-z0-9]/', '', $_GET['id']);

    $imagePath = __DIR__ . "/assets/products/items/$itemID.png";

    if (file_exists($imagePath)) {
        $imageInfo = getimagesize($imagePath);
        header('Content-Type: ' . $imageInfo['mime']);
        readfile($imagePath);
    } else {
        header('Content-Type: image/png');
        readfile(__DIR__ . '/assets/products/default.png');
    }
} else {
    echo 'No ItemID provided.';
}
