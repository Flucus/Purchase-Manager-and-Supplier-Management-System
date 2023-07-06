<!DOCTYPE html>
<html>

<head>
    <title>Home - Yummy Restaurant Group Limited</title>
    <link rel="stylesheet" type="text/css" href="style_deleteItem.css">
</head>

<body>
    <header>
        <h1>Yummy Restaurant Group Limited</h1>
        <nav>
            <ul>
                <li><a href="//127.0.0.1/Supplier/index.php">Home</a></li>
                <li><a href="//127.0.0.1/Supplier/InsertItems">Insert Items</a></li>
                <li><a href="//127.0.0.1/Supplier/EditItems">Edit Items</a></li>
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
        <h2> Cannot delete the item because it has existing related orders.</h2>
    </main>
    <footer>
        <p>&copy; 2023 Yummy Restaurant Group Limited</p>
    </footer>
</body>

</html>