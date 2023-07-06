<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>View Order Records - Yummy Restaurant Group Limited</title>
  <link rel="stylesheet" href="style_Delete.css">
</head>

<body>
  <header>
    <h1>Yummy Restaurant Group Limited</h1>
    <nav>
      <ul>
        <li><a href="//127.0.0.1/PurchaseManager/index.php">Home</a></li>
        <li><a href="//127.0.0.1/PurchaseManager/MakeOrders">Make Orders</a></li>
        <li><a href="//127.0.0.1/PurchaseManager/OrderRecords">Order Records</a></li>
        <li><a href="//127.0.0.1/PurchaseManager/UpdateInformation">Update Information</a></li>
        <li><a href="//127.0.0.1/PurchaseManager/DeleteOrders">Delete Orders</a></li>
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
    <h2>Delete Order Records</h2>
    <?php
    require_once 'mysqli_conn.php';

    if (isset($_SESSION['username'])) {
      $username = $_SESSION['username'];
      $sql = "SELECT DISTINCT o.orderID, o.purchaseManagerID, o.orderDateTime, o.deliveryAddress, o.deliveryDate, i.itemName, i.price, oi.orderQty
            FROM Orders o
            INNER JOIN OrdersItem oi ON o.orderID = oi.orderID
            INNER JOIN Item i ON oi.itemID = i.itemID
            WHERE o.purchaseManagerID = '$username'";
      $result = mysqli_query($conn, $sql);

      if (mysqli_num_rows($result) > 0) {
        echo "<table>";
        echo "<tr>
                <th>Order ID</th>
                <th>Purchase Manager ID</th>
                <th>Order Date Time</th>
                <th>Delivery Address</th>
                <th>Delivery Date</th>
                <th>Item Name</th>
                <th>Item Price</th>
                <th>Order Quantity</th>
            </tr>";

        while ($row = mysqli_fetch_assoc($result)) {
          echo "<tr>";
          echo "<td>" . $row['orderID'] . "</td>";
          echo "<td>" . $row['purchaseManagerID'] . "</td>";
          echo "<td>" . $row['orderDateTime'] . "</td>";
          echo "<td>" . $row['deliveryAddress'] . "</td>";
          echo "<td>" . $row['deliveryDate'] . "</td>";
          echo "<td>" . $row['itemName'] . "</td>";
          echo "<td>" . $row['price'] . "</td>";
          echo "<td>" . $row['orderQty'] . "</td>";
          echo "</tr>";
        }

        echo "</table>";

        echo "<form method='POST' action='delete_order.php'>";
        echo "<label for='order-id'>Order ID:</label>";
        echo "<select id='order-id' name='order-id' required>";
        mysqli_data_seek($result, 0);
        $orderIDs = array();
        while ($row = mysqli_fetch_assoc($result)) {
          $orderID = $row['orderID'];
          if (!in_array($orderID, $orderIDs)) {
            echo "<option value='" . $orderID . "'>" . $orderID . "</option>";
            $orderIDs[] = $orderID;
          }
        }
        echo "</select><br><br>";
        echo "<input type='submit' value='Delete Order' onclick='return confirmDelete()'>";
        echo "</form>";
      } else {
        echo "No order records found.";
      }

      mysqli_close($conn);
    } else {
      echo "User is not logged in.";
    }
    ?>
  </main>
  <footer>
    <p>&copy; 2023 Yummy Restaurant Group Limited</p>
  </footer>

  <script>
    function confirmDelete() {
      var orderId = document.getElementById("order-id").value;
      var confirmation = confirm("Are you sure you want to delete Order ID: " + orderId + "?");

      if (confirmation) {
        showToast("Order deleted successfully!");
      }

      return confirmation;
    }

    function showToast(message) {
      var toast = document.createElement("div");
      toast.className = "toast-message";
      toast.textContent = message;

      document.body.appendChild(toast);

      setTimeout(function() {
        toast.parentNode.removeChild(toast);
      }, 3000);
    }
  </script>
</body>

</html>