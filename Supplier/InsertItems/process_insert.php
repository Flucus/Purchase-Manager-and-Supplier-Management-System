<?php
include 'mysqli_conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $supplierID = $_POST['supplierID'];
    $itemID = $_POST['itemID'];
    $itemName = $_POST['itemName'];
    $itemDescription = $_POST['itemDescription'];
    $stockItemQuantity = $_POST['stockItemQuantity'];
    $price = $_POST['price'];

    $imageFile = $_FILES['itemImage']['name'];
    $imageTmp = $_FILES['itemImage']['tmp_name'];

    $fileExt = pathinfo($imageFile, PATHINFO_EXTENSION);

    $newFileName = $itemID . '.' . $fileExt;

    $targetPath = $_SERVER['DOCUMENT_ROOT'] . "/image/" . $newFileName;

    if (move_uploaded_file($imageTmp, $targetPath)) {
        $sql = "INSERT INTO Item (supplierID, itemID, itemName, itemDescription, stockItemQty, price, ImageFile) VALUES ('$supplierID', '$itemID', '$itemName', '$itemDescription', '$stockItemQuantity', '$price', '$newFileName')";

        if (mysqli_query($conn, $sql)) {
            header("Location: insert_suc.php");
            exit();
        } else {
            echo "Error inserting item details: " . mysqli_error($conn);
        }
    } else {
        echo "Error uploading image.";
    }

    mysqli_close($conn);
}
