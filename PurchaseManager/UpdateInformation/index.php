<!DOCTYPE html>
<html>

<head>
	<title>Update Purchase Manager's Information - Yummy Restaurant Group Limited</title>
	<link rel="stylesheet" type="text/css" href="style_Update.css">
	<script>
		function confirmUpdate() {
			if (confirm("Are you sure you want to modify the record?")) {
				return true;
			} else {
				return false;
			}
		}
	</script>
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
		<h2>Update Purchase Manager's Information</h2>
		<?php
		include 'mysqli_conn.php';
		if (isset($_SESSION['username'])) {
			$username = $_SESSION['username'];

			$sql = "SELECT * FROM PurchaseManager WHERE purchaseManagerID = '$username'";
			$result = mysqli_query($conn, $sql);

			if (mysqli_num_rows($result) > 0) {
				echo "<table>";
				echo "<tr>
                    <th>Purchase Manager ID</th>
                    <th>Password</th>
                    <th>Manager Name</th>
                    <th>Contact Number</th>
                    <th>Warehouse Address</th>
                </tr>";

				$row = mysqli_fetch_assoc($result);
				echo "<tr>";
				echo "<td>" . $row['purchaseManagerID'] . "</td>";
				echo "<td>" . $row['password'] . "</td>";
				echo "<td>" . $row['managerName'] . "</td>";
				echo "<td>" . $row['contactNumber'] . "</td>";
				echo "<td>" . $row['warehouseAddress'] . "</td>";
				echo "</tr>";

				echo "</table>";

				echo "<form action='update_record.php' method='POST' onsubmit='return confirmUpdate();'>";
				echo "<label for='password'>Password:</label>";
				echo "<input type='text' id='password' name='password' value='" . $row['password'] . "' required><br><br>";
				echo "<label for='contactNumber'>Contact Number:</label>";
				echo "<input type='text' id='contactNumber' name='contactNumber' value='" . $row['contactNumber'] . "' required><br><br>";
				echo "<label for='warehouseAddress'>Warehouse Address:</label>";
				echo "<input type='text' id='warehouseAddress' name='warehouseAddress' value='" . $row['warehouseAddress'] . "' required><br><br>";
				echo "<input type='submit' value='Modify Record'>";
				echo "</form>";
			} else {
				echo "No purchase manager record found.";
			}
		} else {
			echo "User is not logged in.";
		}

		mysqli_close($conn);
		?>
	</main>
	<footer>
		<p>&copy; 2023 Yummy Restaurant Group Limited</p>
	</footer>
</body>

</html>