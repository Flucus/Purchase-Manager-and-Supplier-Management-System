<?php
include 'mysqli_conn.php';

if (isset($_POST['itemID'])) {
  $itemID = $_POST['itemID'];

  $sql = "SELECT itemDescription, stockItemQty, ImageFile, price FROM Item WHERE itemID = '$itemID'";
  $result = mysqli_query($conn, $sql);

  if ($result && mysqli_num_rows($result) > 0) {
    $itemDetails = mysqli_fetch_assoc($result);

    echo json_encode($itemDetails);
  } else {
    echo json_encode(['error' => 'Item not found.']);
  }
} else {
  echo json_encode(['error' => 'Invalid request.']);
}

mysqli_close($conn);
