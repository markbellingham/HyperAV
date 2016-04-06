<?php
	require_once ("../includes/session.php");
	require_once ("../includes/db_connection.php");
	require_once ("../includes/functions.php");

	$page_title = 'My Orders | HyperAV';
	include ("../includes/layouts/header.php");

if (isset($_SESSION['customerID']) && (!isset($_SESSION['staff']))) { ?>

<h3>Here are all your previous orders <?php echo $_SESSION['first_name'] . ' ' . $_SESSION['last_name'] ?></h3>

<?php 
} else if (isset($_SESSION['staff'])) { ?>
<h3>Here you can see the customer orders</h3>
<?php
}

// Check if staff or customer is logged in.
// If staff, ask for the customer's email. If customer, get the email from the SESSION
// If neither staff nor customer is logged in, redirect to the index page
if (!isset($_SESSION['customerID']) && (isset($_SESSION['staff']))) {
	echo 'Please input the customer\'s email ';
	echo '<input type="email" name="cuEmail" required> ';
	echo '<input type="submit" name="submit" value="Submit">';
	include ("../includes/layouts/footer.php");
	exit;
} else if (!isset($_SESSION['customerID']) && (!isset($_SESSION['staff']))) {
	redirect_to("index.php");
} else {
	$cuEmail = $_SESSION['cuEmail'];
}

// Need to join several tables in order to get the type of information that would be useful to the customer
$query = 'SELECT o.orderID, prName, prPrice, odQuantity, orDate, orTotal, orDeliverDate, orPaymentMethod FROM hyperav_orders o JOIN hyperav_orderdetails od ON o.orderID = od.orderID JOIN hyperav_stock st ON od.stockID = st.stockID JOIN hyperav_products pr ON st.prModelNo = pr.prModelNo WHERE o.customerID = ' . $_SESSION['customerID'] . ' ORDER BY o.orDate';
$results = @mysqli_query($connection, $query);
$num_rows = mysqli_num_rows($results);

if ($results) {
	if ($num_rows > 0) {
		$i = 0;		// Initialise
		while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
			// If the order ID has changed, close the previous table if it exists and create some space
			if ($i != $row['orderID']) {
				echo '</table><br/><br/><br/>';

				// Data is shown in two tables. The first shows information that is the same for the whole order
				echo '<table border="1"><tr>
					<td>Order Number: ' . $row['orderID'] . '</td>
					<td>Order Date: ' . $row['orDate'] . '</td>
					<td>Payment Method: ' . $row['orPaymentMethod'] . '</td>
					<td><b>Order Total: ' . $row['orTotal'] . '</b></td></tr></table><br/>';

				// The second table shows details of each individual item on that order - first create some headings
				echo '<table style="border: 1px solid black"><tr style="font-weight: bold"><td></td>
					<td>Product Name</td>
					<td>Price</td>
					<td>Quantity</td>
					<td>Total Per Item</td>
					<td>Delivery Date</td></tr>';
			}
			// Now show each individual item.
			$totalPerItem = $row['prPrice'] * $row['odQuantity'];
			echo '<tr><td><img src="images/' . $row['prName'] . '.jpg" id="product_images"></td><td>' . $row['prName'] . '</td><td>' . $row['prPrice'] . '</td><td>' . $row['odQuantity'] . '</td><td>' . $totalPerItem . '</td><td>' . $row['orDeliverDate'] . '</td></tr>';			
			$i = $row['orderID'];
		}
	}
}


	mysqli_free_result($results);
	mysqli_close($connection);

	include ("../includes/layouts/footer.php");
?>