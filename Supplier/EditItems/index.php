<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>Edit Item - Yummy Restaurant Group Limited</title>
  <link rel="stylesheet" type="text/css" href="style_edit.css">
  <style>
    .item-image {
      height: 100px;
    }
  </style>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    $(document).ready(function() {
      // Function to load item details when a different item is selected
      $("#itemID").change(function() {
        var selectedItemID = $(this).val();

        // Clear the textboxes before loading new data
        $("#itemDescription").val("");
        $("#stockItemQty").val("");
        $("#price").val("");

        $.ajax({
          url: "get_item_details.php", // Replace with the actual PHP file to fetch item details
          method: "POST",
          data: {
            itemID: selectedItemID
          },
          dataType: "json",
          success: function(response) {
            $("#itemDescription").val(response.itemDescription);
            $("#stockItemQty").val(response.stockItemQty);
            $("#price").val(response.price);
          },
          error: function() {
            alert("Error fetching item details.");
          }
        });
      });
    });
  </script>
</head>

<body>
  <header>
    <h1>Yummy Restaurant Group Limited</h1>
    <nav>
      <ul>
        <<li><a href="//127.0.0.1/Supplier/index.php">Home</a></li>
          <li><a href="//127.0.0.1/Supplier/InsertItems">Insert Items</a></li>
          <li><a href="//localhost/Supplier/EditItems">Edit Items</a></li>
          <li><a href="//127.0.0.1/Supplier/GemerateReport">Generate Report</a></li>
          <li><a href="//127.0.0.1/Supplier/DeleteItems">Delete Item</a></li>
          <li><a href="//127.0.0.1/Login/index.php">Logout</a></li>
      </ul>
    </nav>
    <?php
    session_start();

    if (isset($_SESSION['username'])) {
      $username = $_SESSION['username'];
      echo "<p class='username-label'>Logged in as: $username</p>";
    }
    ?>
  </header>
  <main>
    <h2>Edit Item</h2>
    <?php
    include 'mysqli_conn.php';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $itemID = $_POST['itemID'];
      $itemDescription = $_POST['itemDescription'];
      $stockItemQty = $_POST['stockItemQty'];
      $price = $_POST['price'];

      $sql = "UPDATE Item SET itemDescription = '$itemDescription', stockItemQty = '$stockItemQty', price = '$price' WHERE itemID = '$itemID'";
      $result = mysqli_query($conn, $sql);

      if ($result) {
        if (!empty($_FILES['itemImage']['name'])) {
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
        }

        echo "Item details have been successfully updated.";
      } else {
        echo "Error updating item details: " . mysqli_error($conn);
      }
    }

    $supplierID = $_SESSION['username'];

    $sql = "SELECT itemID, itemDescription, stockItemQty, ImageFile, price FROM Item WHERE supplierID = '$supplierID'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
      echo "<table>";
      echo "<tr>
    <th>Item ID</th>
    <th>Item Description</th>
    <th>Stock Item Quantity</th>
    <th>Image File</th>
    <th>Price</th>
  </tr>";

      while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['itemID'] . "</td>";
        echo "<td>" . $row['itemDescription'] . "</td>";
        echo "<td>" . $row['stockItemQty'] . "</td>";
        echo "<td><img src='/image/" . $row['ImageFile'] . "' alt='Item Image' class='item-image'></td>";
        echo "<td>" . $row['price'] . "</td>";
        echo "</tr>";
      }

      echo "</table>";

      echo "<form method='POST' action='edit_item.php' enctype='multipart/form-data'>";
      echo "<label for='itemID'>Item ID:</label>";
      echo "<select id='itemID' name='itemID' required>";
      mysqli_data_seek($result, 0);
      while ($row = mysqli_fetch_assoc($result)) {
        echo "<option value='" . $row['itemID'] . "'>" . $row['itemID'] . "</option>";
      }
      echo "</select><br><br>";

      mysqli_data_seek($result, 0);
      $firstItem = mysqli_fetch_assoc($result);

      echo "<label for='itemDescription'>Item Description:</label>";
      echo "<input type='text' id='itemDescription' name='itemDescription' value='" . $firstItem['itemDescription'] . "'><br>";

      echo "<label for='stockItemQty'>Stock Item Quantity:</label>";
      echo "<input type='number' id='stockItemQty' name='stockItemQty' value='" . $firstItem['stockItemQty'] . "'><br>";

      echo "<label for='itemImage'>Item Image:</label>";
      echo "<input type='file' id='itemImage' name='itemImage' accept='image/*'><br>";

      echo "<label for='price'>Price:</label>";
      echo "<input type='number' id='price' name='price' step='0.01' value='" . $firstItem['price'] . "'><br>";

      echo "<input type='submit' value='Edit'>";
      echo "</form>";
    } else {
      echo "No item records found.";
    }

    mysqli_close($conn);
    ?>
  </main>
  <footer>
    &copy; 2023 Yummy Restaurant Group Limited
  </footer>
</body>

</html>