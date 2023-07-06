<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<title>Insert Items Information - Yummy Restaurant Group Limited</title>
	<link rel="stylesheet" type="text/css" href="style_insert.css">
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
		<form method="POST" action="process_insert.php" enctype="multipart/form-data">
			<label for="supplierID"><span>Supplier ID:</span></label>
			<?php
			include 'mysqli_conn.php';
			$supplierID = $_SESSION['username'];

			echo "<input type='text' id='supplierID' name='supplierID' value='$supplierID' required readonly><br><br>";

			$sql = "SELECT MAX(itemID) AS maxItemID FROM Item";
			$result = mysqli_query($conn, $sql);
			$row = mysqli_fetch_assoc($result);
			$maxItemID = $row['maxItemID'];

			$newItemID = $maxItemID + 1;

			echo "<label for='itemID'><span>Item ID:</span></label>";
			echo "<input type='text' id='itemID' name='itemID' value='$newItemID' required readonly><br><br>";

			mysqli_close($conn);
			?>

			<label for="itemName"><span>Item Name:</span></label>
			<input type="text" id="itemName" name="itemName" required><br><br>

			<label for="itemImage"><span>Item Image:</span></label>
			<input type="file" id="itemImage" name="itemImage" accept="image/*" required><br><br>

			<label for="itemDescription"><span>Item Description:</span></label>
			<input type="text" id="itemDescription" name="itemDescription" rows="5" cols="50" required></textarea><br><br>

			<label for="stockItemQuantity"><span>Stock Item Quantity:</span></label>
			<input type="number" id="stockItemQuantity" name="stockItemQuantity" required><br><br>

			<label for="price"><span>Price:</span></label>
			<input type="number" id="price" name="price" step="0.01" required><br><br>

			<button type="submit" onclick="return confirmDelete()">Insert Item</button>
			<br><br><br>
		</form>

		<script>
			const itemNameInput = document.getElementById('itemName');
			const itemSelectionList = document.getElementById('itemSelectionList');

			itemNameInput.addEventListener('input', function() {
				const newItemName = itemNameInput.value;

				const existingOption = Array.from(itemSelectionList.options).find(option => option.value === newItemName);

				if (!existingOption) {
					const newOption = document.createElement('option');
					newOption.value = newItemName;
					newOption.textContent = newItemName;
					itemSelectionList.appendChild(newOption);
				}
			});

			function confirmDelete() {
				return confirm("Are you sure you want to Insert this item?");
			}
		</script>
	</main>
	<footer>
		<p>© 2023 Yummy Restaurant Group Limited</p>
	</footer>
</body>

</html>