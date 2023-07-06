<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generate Report - Yummy Restaurant Group Limited</title>
    <link rel="stylesheet" href="style_report.css">
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
        <h2>Generate Report</h2>
        <table>
            <thead>
                <tr>
                    <th>Item ID</th>
                    <th>Item Name</th>
                    <th>Item Image</th>
                    <th>Total Quantity</th>
                    <th>Total Sales Amount ($)</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include 'mysqli_conn.php';

                if (isset($_SESSION['username'])) {
                    $supplierID = $_SESSION['username'];

                    $sql = "SELECT Item.itemID, Item.itemName, Item.ImageFile, SUM(OrdersItem.orderQty) AS totalQuantity, SUM(Item.price * OrdersItem.orderQty) AS totalSalesAmount 
                        FROM Item 
                        INNER JOIN OrdersItem ON Item.itemID = OrdersItem.itemID 
                        INNER JOIN Orders ON OrdersItem.orderID = Orders.orderID 
                        WHERE Item.supplierID = '$supplierID'
                        GROUP BY Item.itemID";

                    $result = mysqli_query($conn, $sql);

                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<tr>";
                        echo "<td>" . $row['itemID'] . "</td>";
                        echo "<td>" . $row['itemName'] . "</td>";
                        echo "<td><img src='//127.0.0.1/image/" . $row['ImageFile'] . "' alt='" . $row['itemName'] . "' width='100' height='100'></td>";
                        echo "<td>" . $row['totalQuantity'] . "</td>";
                        echo "<td>" . $row['totalSalesAmount'] . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>No data available. Please log in.</td></tr>";
                }

                mysqli_close($conn);
                ?>
            </tbody>
        </table>
    </main>

    <footer>
        <p>&copy; 2023 Yummy Restaurant Group Limited</p>
    </footer>
</body>

</html>