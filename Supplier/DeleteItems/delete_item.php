<?php
include 'mysqli_conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $itemID = mysqli_real_escape_string($conn, $_POST['itemID']);

    $sqlCheckRelatedOrders = "SELECT COUNT(*) AS totalOrders FROM OrdersItem WHERE itemID = '$itemID'";
    $result = mysqli_query($conn, $sqlCheckRelatedOrders);
    $row = mysqli_fetch_assoc($result);
    $totalOrders = $row['totalOrders'];

    if ($totalOrders > 0) {
        header("Location: delete_existing.php");
        exit();
    } else {
        $sqlGetImageFilename = "SELECT itemID FROM Item WHERE itemID = '$itemID'";
        $result = mysqli_query($conn, $sqlGetImageFilename);
        $row = mysqli_fetch_assoc($result);
        $imageFilename = $row['itemID'];

        $sqlDeleteItem = "DELETE FROM Item WHERE itemID = '$itemID'";

        if (mysqli_query($conn, $sqlDeleteItem)) {

            $targetPath = $_SERVER['DOCUMENT_ROOT'] . "/image/" . $imageFilename;
            if (file_exists($targetPath)) {
                unlink($targetPath); 
            }

            header("Location: delete_suc.php");
            exit();
        } else {
            echo "Error deleting item record: " . mysqli_error($conn);
        }
    }
}
