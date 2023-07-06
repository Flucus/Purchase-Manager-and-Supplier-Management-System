<?php
include 'mysqli_conn.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $itemID = $_POST['itemID'];
    $itemDescription = $_POST['itemDescription'];
    $stockItemQty = $_POST['stockItemQty'];
    $price = $_POST['price'];

    $sql = "SELECT ImageFile FROM Item WHERE itemID = '$itemID'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    $currentImageFile = $row['ImageFile'];

    if (!empty($_FILES['itemImage']['name'])) {
        // Delete the current image file if it exists
        if (!empty($currentImageFile)) {
            $imagePath = $_SERVER['DOCUMENT_ROOT'] . "/image/" . $currentImageFile;
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $imageFile = $_FILES['itemImage']['name'];
        $imageTmp = $_FILES['itemImage']['tmp_name'];

        $fileExt = pathinfo($imageFile, PATHINFO_EXTENSION);

        $newFileName = $itemID . '.' . $fileExt;

        $targetPath = $_SERVER['DOCUMENT_ROOT'] . "/image/" . $newFileName;

        if (move_uploaded_file($imageTmp, $targetPath)) {
            echo "Image uploaded successfully.";
        } else {
            echo "Error uploading image.";
        }
    } else {
        // If no new image is uploaded, retain the current image file name
        $newFileName = $currentImageFile;
    }

    $sql = "UPDATE Item SET itemDescription = '$itemDescription', stockItemQty = '$stockItemQty', price = '$price', ImageFile = '$newFileName' WHERE itemID = '$itemID'";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        header("Location: edit_suc.php");
        exit();
    } else {
        echo "Error updating item details: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
