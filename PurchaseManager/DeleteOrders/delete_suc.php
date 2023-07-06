<!DOCTYPE html>
<html>

<head>
    <title>Home - Yummy Restaurant Group Limited</title>
    <link rel="stylesheet" type="text/css" href="style_Delete.css">
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
        <h2>Order record with ID has been successfully deleted.</h2>
    </main>
    <footer>
        <p>&copy; 2023 Yummy Restaurant Group Limited</p>
    </footer>
</body>

</html>