<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>View Order Records - Yummy Restaurant Group Limited</title>
  <link rel="stylesheet" href="style_record.css">
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
    <h2>View Order Records</h2>

    <?php
    include 'mysqli_conn.php';
    $sortColumn1 = $_GET['sortColumn1'] ?? 'orderID'; // Default sorting column: Order ID
    $sortColumn2 = $_GET['sortColumn2'] ?? 'itemName'; // Default sorting column: Item Name
    $sortDirection1 = $_GET['sortDirection1'] ?? 'ASC'; // Default sorting direction: Ascending
    $sortDirection2 = $_GET['sortDirection2'] ?? 'ASC'; // Default sorting direction: Ascending

    $sql = "SELECT o.orderID, s.supplierID, s.companyName, s.contactName, s.contactNumber, o.orderDateTime, o.deliveryAddress, o.deliveryDate, i.itemID, i.ImageFile, i.itemName, oi.orderQty, (oi.orderQty * i.price) AS totalAmount
FROM Orders o
INNER JOIN PurchaseManager pm ON o.purchaseManagerID = pm.purchaseManagerID
INNER JOIN OrdersItem oi ON o.orderID = oi.orderID
INNER JOIN Item i ON oi.itemID = i.itemID
INNER JOIN Supplier s ON i.supplierID = s.supplierID
ORDER BY $sortColumn1 $sortDirection1, $sortColumn2 $sortDirection2";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
      echo "<table>";
      echo "<tr>
<th><a href='?sortColumn1=orderID&sortDirection1=" . ($sortColumn1 === 'orderID' && $sortDirection1 === 'ASC' ? 'DESC' : 'ASC') . "'>Order ID</a></th>
<th><a href='?sortColumn1=supplierID&sortDirection1=" . ($sortColumn1 === 'supplierID' && $sortDirection1 === 'ASC' ? 'DESC' : 'ASC') . "'>Supplier ID</a></th>
<th><a href='?sortColumn1=companyName&sortDirection1=" . ($sortColumn1 === 'companyName' && $sortDirection1 === 'ASC' ? 'DESC' : 'ASC') . "'>Supplier's Company Name</a></th>
<th><a href='?sortColumn1=contactName&sortDirection1=" . ($sortColumn1 === 'contactName' && $sortDirection1 === 'ASC' ? 'DESC' : 'ASC') . "'>Supplier's Contact Name</a></th>
<th><a href='?sortColumn1=contactNumber&sortDirection1=" . ($sortColumn1 === 'contactNumber' && $sortDirection1 === 'ASC' ? 'DESC' : 'ASC') . "'>Supplier's Contact Number</a></th>
<th><a href='?sortColumn1=orderDateTime&sortDirection1=" . ($sortColumn1 === 'orderDateTime' && $sortDirection1 === 'ASC' ? 'DESC' : 'ASC') . "'>Order Date & Time</a></th>
<th><a href='?sortColumn1=deliveryAddress&sortDirection1=" . ($sortColumn1 === 'deliveryAddress' && $sortDirection1 === 'ASC' ? 'DESC' : 'ASC') . "'>Delivery Address</a></th>
<th><a href='?sortColumn1=deliveryDate&sortDirection1=" . ($sortColumn1 === 'deliveryDate' && $sortDirection1 === 'ASC' ? 'DESC' : 'ASC') . "'>Delivery Date</a></th>
<th><a href='?sortColumn1=itemID&sortDirection1=" . ($sortColumn1 === 'itemID' && $sortDirection1 === 'ASC' ? 'DESC' : 'ASC') . "'>Item ID</a></th>
<th>Item Image</th>
<th><a href='?sortColumn1=itemName&sortDirection1=" . ($sortColumn1 === 'itemName' && $sortDirection1 === 'ASC' ? 'DESC' : 'ASC') . "'>Item Name</a></th>
<th><a href='?sortColumn1=orderQty&sortDirection1=" . ($sortColumn1 === 'orderQty' && $sortDirection1 === 'ASC' ? 'DESC' : 'ASC') . "'>Order Quantity</a></th>
<th><a href='?sortColumn1=totalAmount&sortDirection1=" . ($sortColumn1 === 'totalAmount' && $sortDirection1 === 'ASC' ? 'DESC' : 'ASC') . "'>Total Order Amount</a></th>
</tr>";

      while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row['orderID'] . "</td>";
        echo "<td>" . $row['supplierID'] . "</td>";
        echo "<td>" . $row['companyName'] . "</td>";
        echo "<td>" . $row['contactName'] . "</td>";
        echo "<td>" . $row['contactNumber'] . "</td>";
        echo "<td>" . $row['orderDateTime'] . "</td>";
        echo "<td>" . $row['deliveryAddress'] . "</td>";
        echo "<td>" . $row['deliveryDate'] . "</td>";
        echo "<td>" . $row['itemID'] . "</td>";
        echo "<td><img src='//127.0.0.1/image/" . $row['ImageFile'] . "' alt='Item Image'></td>";
        echo "<td>" . $row['itemName'] . "</td>";
        echo "<td>" . $row['orderQty'] . "</td>";
        echo "<td>" . ($row['totalAmount'] * 0.9) . "</td>"; // Calculate discounted price (assuming 10% discount)
        echo "</tr>";
      }

      echo "</table>";
    } else {
      echo "No order records found.";
    }

    mysqli_close($conn);
    ?>

  </main>
  <footer>
    <p>&copy; 2023 Yummy Restaurant Group Limited</p>
  </footer>
</body>

</html>