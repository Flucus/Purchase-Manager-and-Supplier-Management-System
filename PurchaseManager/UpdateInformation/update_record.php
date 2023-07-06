<?php
session_start();
include 'mysqli_conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $purchaseManagerID = $_SESSION['username'];
    $password = $_POST['password'];
    $managerName = $_SESSION['managerName'];
    $contactNumber = $_POST['contactNumber'];
    $warehouseAddress = $_POST['warehouseAddress'];

    $sql = "UPDATE PurchaseManager SET password = '$password', contactNumber = '$contactNumber', warehouseAddress = '$warehouseAddress' WHERE purchaseManagerID = '$purchaseManagerID'";

    if (mysqli_query($conn, $sql)) {
        header("Location: updata_suc.php");
        exit();
    } else {
        echo "Error updating purchase manager record: " . mysqli_error($conn);
    }

    mysqli_close($conn);
}
