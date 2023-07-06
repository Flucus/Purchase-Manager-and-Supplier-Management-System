<?php
include_once("mysqli_conn.php");

$itemIDMapping = array();
$getItemsQuery = "SELECT itemName, itemID FROM Item";
$result = mysqli_query($conn, $getItemsQuery) or die("Error: " . mysqli_error($conn));
while ($row = mysqli_fetch_assoc($result)) {
    $itemName = $row['itemName'];
    $itemID = $row['itemID'];
    $itemIDMapping[$itemName] = $itemID;
}

$orderID = ""; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $purchaseManagerID = $_POST['purchaseManagerID'];
    $deliveryAddress = $_POST['deliveryAddress'];
    $deliveryDate = $_POST['deliveryDate'];
    $selectedItems = $_POST['itemID'];
    $quantities = $_POST['quantity'];

    $getLastOrderIDQuery = "SELECT orderID FROM Orders ORDER BY orderID DESC LIMIT 1";
    $result = mysqli_query($conn, $getLastOrderIDQuery) or die("Error: " . mysqli_error($conn));
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $lastOrderID = $row['orderID'];
        $orderID = $lastOrderID + 1;
    } else {
        $orderID = 1;
    }

    $insertOrderQuery = "INSERT INTO Orders (orderID, purchaseManagerID, deliveryAddress, deliveryDate) VALUES ('$orderID', '$purchaseManagerID', '$deliveryAddress', '$deliveryDate')";
    mysqli_query($conn, $insertOrderQuery) or die("Error: " . mysqli_error($conn));

    foreach ($selectedItems as $key => $selectedItem) {
        if (array_key_exists($key, $quantities)) {
            $itemID = $selectedItem;
            $quantity = $quantities[$key];

            $getItemDetailsQuery = "SELECT price, stockItemQty FROM Item WHERE itemID = '$itemID'";
            $result = mysqli_query($conn, $getItemDetailsQuery) or die("Error: " . mysqli_error($conn));
            $row = mysqli_fetch_assoc($result);
            $itemPrice = $row['price'];
            $stockItemQty = $row['stockItemQty'];

            $totalPrice = $itemPrice * $quantity;

            $insertOrderItemQuery = "INSERT INTO OrdersItem (orderID, itemID, orderQty, itemPrice) VALUES ('$orderID', '$itemID', '$quantity', '$totalPrice')";
            mysqli_query($conn, $insertOrderItemQuery) or die("Error: " . mysqli_error($conn));

            $updatedStockQty = $stockItemQty - $quantity;
            $updateStockQtyQuery = "UPDATE Item SET stockItemQty = '$updatedStockQty' WHERE itemID = '$itemID'";
            mysqli_query($conn, $updateStockQtyQuery) or die("Error: " . mysqli_error($conn));
        }
    }

    mysqli_close($conn);

    header("Location: order_suc.php");
    exit();
}
?>