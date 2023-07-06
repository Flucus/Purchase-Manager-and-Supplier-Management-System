<?php
include 'mysqli_conn.php';

function sanitizeInput($input)
{
    global $conn;
    $input = mysqli_real_escape_string($conn, $input);
    $input = htmlspecialchars($input);
    return $input;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $orderID = sanitizeInput($_POST['order-id']);

    $sqlDeleteOrderItem = "DELETE FROM OrdersItem WHERE orderID = '$orderID'";
    if (mysqli_query($conn, $sqlDeleteOrderItem)) {
        $sqlDeleteOrder = "DELETE FROM Orders WHERE orderID = '$orderID'";
        if (mysqli_query($conn, $sqlDeleteOrder)) {
            header("Location: delete_suc.php");
            exit();
        } else {
            echo "Error deleting order record: " . mysqli_error($conn);
        }
    } else {
        echo "Error deleting order item records: " . mysqli_error($conn);
    }
}
?>