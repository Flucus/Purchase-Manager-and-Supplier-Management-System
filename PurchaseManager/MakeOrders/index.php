<?php
session_start();
// Database connection
include_once("mysqli_conn.php");

// Retrieve item IDs and their corresponding values from the Item table
$itemIDMapping = array();
$getItemsQuery = "SELECT itemName, itemID FROM Item";
$result = mysqli_query($conn, $getItemsQuery) or die("Error: " . mysqli_error($conn));
while ($row = mysqli_fetch_assoc($result)) {
	$itemName = $row['itemName'];
	$itemID = $row['itemID'];
	$itemIDMapping[$itemName] = $itemID;
}

// Get the last orderID from the Orders table
$getLastOrderIDQuery = "SELECT orderID FROM Orders ORDER BY orderID DESC LIMIT 1";
$result = mysqli_query($conn, $getLastOrderIDQuery) or die("Error: " . mysqli_error($conn));
if (mysqli_num_rows($result) > 0) {
	$row = mysqli_fetch_assoc($result);
	$lastOrderID = $row['orderID'];
	$orderID = $lastOrderID + 1;
} else {
	// If there are no existing orders, start with orderID 1
	$orderID = 1;
}

// Retrieve manager names and IDs from the PurchaseManager table
$getManagersQuery = "SELECT purchaseManagerID, managerName, warehouseAddress FROM PurchaseManager";
$managerResult = mysqli_query($conn, $getManagersQuery) or die("Error: " . mysqli_error($conn));
$managers = mysqli_fetch_all($managerResult, MYSQLI_ASSOC);

// Retrieve the logged-in user's ID and delivery address from the session
$loggedInUserID = "";
$managerName = "";
$deliveryAddress = "";
if (isset($_SESSION['username'])) {
	$loggedInUserID = $_SESSION['username'];

	// Find the manager's name and delivery address based on the logged-in user's ID
	foreach ($managers as $manager) {
		if ($manager['purchaseManagerID'] === $loggedInUserID) {
			$managerName = $manager['managerName'];
			$deliveryAddress = $manager['warehouseAddress'];
			break;
		}
	}
}

// Redirect to the login page if the user is not logged in
if (empty($loggedInUserID)) {
	header("Location: /Login/index.php");
	exit();
}
?>

<!DOCTYPE html>
<html>

<head>
	<title>Make Orders - Yummy Restaurant Group Limited</title>
	<link rel="stylesheet" type="text/css" href="style_make.css">
	<style></style>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<script>
		$(document).ready(function() {
			$('input[type="checkbox"]').on('change', function() {
				var quantityField = $(this).closest('label').next('input[type="number"]');
				quantityField.prop('disabled', !$(this).is(':checked'));
				calculateTotalAmount();
			});

			$('input[type="number"]').on('input', function() {
				calculateTotalAmount();
			});

			function calculateTotalAmount() {
				var totalAmount = 0;
				$('input[type="checkbox"]:checked').each(function() {
					var quantity = $(this).closest('label').next('input[type="number"]').val();
					var price = parseFloat($(this).data('price'));
					totalAmount += quantity * price;
				});
				$('#totalAmount').text(totalAmount.toFixed(2));
			}

			function calculateDiscount() {
				var totalAmount = parseFloat($('#totalAmount').text());
				var discountURL = "http://127.0.0.1:8011/api/discountCalculator?TotalOrderAmount=" + totalAmount;
				$.get(discountURL, function(response) {
					var discountRate = response.DiscountRate;
					var newOrderAmount = totalAmount * (1 - discountRate);

					$('#discountRate').text(discountRate);
					$('#newOrderAmount').text(newOrderAmount.toFixed(2));
				});
			}

			$('form').submit(function(e) {
				e.preventDefault();
				var confirmToast = confirm("Are you sure you want to submit the order?");
				if (confirmToast) {
					$(this).unbind('submit').submit();
				}
			});

			$('#calculateBtn').click(function() {
				calculateDiscount();
			});

			calculateTotalAmount();
		});
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
		if (!empty($loggedInUserID)) {
			echo "<p class='username-label'>Logged in as: $loggedInUserID</p>";
		}
		?>
	</header>
	<main>
		<h2>Make Orders</h2>

		<form action="process_order.php" method="POST">

			<label for="orderID">Order ID:</label>
			<input type="text" id="orderID" name="orderID" value="<?php echo $orderID; ?>" readonly>

			<label for="purchaseManagerID">Purchase Manager ID:</label>
			<input type="text" id="purchaseManagerID" name="purchaseManagerID" value="<?php echo $loggedInUserID; ?>" readonly>

			<label for="managerName">Manager Name:</label>
			<input type="text" id="managerName" name="managerName" value="<?php echo $managerName; ?>" readonly>

			<label for="deliveryAddress">Delivery Address:</label>
			<input type="text" id="deliveryAddress" name="deliveryAddress" value="<?php echo $deliveryAddress; ?>" readonly>

			<label for="deliveryDate">Delivery Date:</label>
			<input type="date" id="deliveryDate" name="deliveryDate" required>

			<label for="itemID">Item ID:</label>
			<fieldset>
				<?php
				$getItemsQuery = "SELECT itemName, itemID, ImageFile, price FROM Item";
				$result = mysqli_query($conn, $getItemsQuery) or die("Error: " . mysqli_error($conn));
				while ($row = mysqli_fetch_assoc($result)) {
					$itemID = $row['itemID'];
					$itemName = $row['itemName'];
					$imageFile = $row['ImageFile'];
					$price = $row['price'];
					echo '<label>';
					echo '<input type="checkbox" name="itemID[]" value="' . $itemID . '" data-price="' . $price . '">';
					echo '<span>Item ID: ' . $itemID . '</span>';
					echo '<img class="checkbox-img" src="//127.0.0.1/image/' . $imageFile . '" alt="' . $itemName . '">';
					echo $itemName;
					echo '</label>';
					echo '<input type="number" name="quantity[]" placeholder="Quantity" disabled>';
				}
				?>
			</fieldset>

			<label for="totalAmount">Total Order Amount:</label>
			<span id="totalAmount">0.00</span>

			<button type="button" id="calculateBtn">Calculate</button>

			<label for="discountRate">Discount Rate:</label>
			<span id="discountRate"></span>

			<label for="newOrderAmount">New Order Amount:</label>
			<span id="newOrderAmount"></span>

			<input type="submit" value="Submit">
		</form>
	</main>
	<footer class="footer">
		<p>© 2023 Yummy Restaurant Group Limited</p>
	</footer>
</body>

</html>