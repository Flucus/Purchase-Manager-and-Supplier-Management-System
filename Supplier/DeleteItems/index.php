<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <title>Delete Item - Yummy Restaurant Group Limited</title>
  <link rel="stylesheet" type="text/css" href="style_deleteItem.css">
  <script>
    function confirmDelete() {
      return confirm("Are you sure you want to delete this item?");
    }
  </script>
</head>

<body>
  <header>
    <h1>Yummy Restaurant Group Limited</h1>
    <nav>
      <ul>
        <li><a href="//127.0.0.1/Supplier/index.php">Home</a></li>
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
    <h2>Delete Item</h2>
    <?php
    include 'mysqli_conn.php';

    $supplierID = $_SESSION['username'];

    $sql = "SELECT itemID, itemName, ImageFile, itemDescription, stockItemQty, price FROM Item WHERE supplierID = '$supplierID'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
      echo "<table>";
      echo "<tr>
          <th>Item ID</th>
          <th>Item Name</th>
          <th>Image File</th>
          <th>Item Description</th>
          <th>Stock Item Quantity</th>
          <th>Price</th>
        </tr>";

      while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['itemID'] . "</td>";
        echo "<td>" . $row['itemName'] . "</td>";
        echo "<td>" . $row['ImageFile'] . "</td>";
        echo "<td>" . $row['itemDescription'] . "</td>";
        echo "<td>" . $row['stockItemQty'] . "</td>";
        echo "<td>" . $row['price'] . "</td>";
        echo "</tr>";
      }

      echo "</table>";

      echo "<form method='POST' action='delete_item.php' onsubmit='return confirmDelete();'>";
      echo "<label for='itemID'>Item ID:</label>";
      echo "<select id='itemID' name='itemID' required>";
      mysqli_data_seek($result, 0);
      while ($row = mysqli_fetch_assoc($result)) {
        echo "<option value='" . $row['itemID'] . "'>" . $row['itemID'] . " - " . $row['itemName'] . "</option>";
      }
      echo "</select><br><br>";
      echo "<input type='submit' value='Delete Item'>";
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