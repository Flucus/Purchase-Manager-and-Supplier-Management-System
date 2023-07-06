<?php
session_start();
require_once 'mysqli_conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $query_p = "SELECT * FROM purchasemanager WHERE purchaseManagerID = '$username' AND password = '$password'";
    $query_s = "SELECT * FROM supplier WHERE supplierID = '$username' AND password = '$password'";
    $result_p = mysqli_query($conn, $query_p);
    $result_s = mysqli_query($conn, $query_s);

    if (mysqli_num_rows($result_p) == 1) {
        $_SESSION['userType'] = 'purchasemanager';
        $_SESSION['username'] = $username;
        mysqli_free_result($result_p);
        mysqli_close($conn);
        header("Location: //127.0.0.1/PurchaseManager/index.php");
        exit();
    } else if (mysqli_num_rows($result_s) == 1) {
        $_SESSION['userType'] = 'supplier';
        $_SESSION['username'] = $username;
        mysqli_free_result($result_s);
        mysqli_close($conn);
        header("Location: //127.0.0.1/Supplier/index.php");
        exit();
    } else {
        $error_message = "Invalid username or password!";
        mysqli_free_result($result_p);
        mysqli_free_result($result_s);
        mysqli_close($conn);
        header("Location: index.php?error=" . urlencode($error_message));
        exit();
    }
}
?>