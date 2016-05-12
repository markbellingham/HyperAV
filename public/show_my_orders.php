<?php
	require_once ("../includes/session.php");
	require_once ("../includes/db_connection.php");
	require_once ("../includes/functions.php");

	$page_title = 'My Orders | HyperAV';
	include ("../includes/layouts/header.php");
?>

<!-- <h3>Here are all your previous orders <?php echo $_SESSION['first_name'] . ' ' . $_SESSION['last_name'] ?></h3>
 -->
<?php

// Check if staff or customer is logged in.
// If staff, ask for the customer's email.
// If neither staff nor customer is logged in, redirect to the index page
if (!isset($_SESSION['customerID']) && (isset($_SESSION['staff'])) && (!isset($_POST['cuEmail']))) {
	echo '<form action="show_my_orders.php" method="POST">';
	echo 'Please input the customer\'s email';
	echo '<input type="email" name="cuEmail" required>';
	echo '<input type="submit" name="submit" value="Submit">';
	echo '</form>';
	include ("../includes/layouts/footer.php");
	exit;
} else if (!isset($_SESSION['customerID']) && (!isset($_SESSION['staff']))) {
	redirect_to("index.php");
}

// Get the customer's email either from the SESSION or from POST
if (isset($_SESSION['cuEmail'])) {
	$cuEmail = $_SESSION['cuEmail'];
} else if (isset($_POST['cuEmail'])) {
	$cuEmail = $_POST['cuEmail'];
}

// Need to join several tables in order to get the type of information that would be useful to the customer
$query = 'SELECT cu.cuFName, cu.cuLName, o.orderID, prName, prPrice, odQuantity, orDate, orTotal, orDeliverDate, orPaymentMethod 
	FROM hyperav_orders o 
	JOIN hyperav_orderdetails od ON o.orderID = od.orderID 
	JOIN hyperav_stock st ON od.stockID = st.stockID 
	JOIN hyperav_products pr ON st.prModelNo = pr.prModelNo 
	JOIN hyperav_customer cu ON o.customerID = cu.customerID 
	WHERE cu.cuEmail = "' . $cuEmail . '" 
	ORDER BY o.orDate';

// echo $query;

$results = @mysqli_query($connection, $query);
$num_rows = mysqli_num_rows($results);

if ($results) {
	if ($num_rows > 0) {
		$i = 0;	$j = 0;	// Initialise
		while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {

			if ($j == 0) {
				// Say whose orders are being retrieved
				echo '<h3>Here are the previous orders for ' . $row['cuFName'] . ' ' . $row['cuLName'] . '</h3>';
				$j++;
			}

			// If the order ID has changed, close the previous table if it exists and create some space
			if ($i != $row['orderID']) {
				echo '</table class="sortable"><br/><br/><br/>';

				// Data is shown in two tables. The first shows information that is the same for the whole order
				echo '<table border="1"><tr>
					<th>Order Number: ' . $row['orderID'] . '</th>
					<th>Order Date: ' . $row['orDate'] . '</th>
					<th>Payment Method: ' . $row['orPaymentMethod'] . '</th>
					<th style="text-align: right"><b>Order Total: £' . $row['orTotal'] . '</b></th></tr></table><br/>';

				// The second table shows details of each individual item on that order - first create some headings
				echo '<table style="border: 1px solid black"><tr style="font-weight: bold"><td></td>
					<th>Product Name</th>
					<th>Price</th>
					<th>Quantity</th>
					<th>Total Per Item</th>
					<th>Delivery Date</th></tr>';
			}
			// Now show each individual item.
			$totalPerItem = $row['prPrice'] * $row['odQuantity'];
			
			echo '<tr><td><img src="images/' . $row['prName'] . '.jpg" id="product_images"></td>
				<td>' . $row['prName'] . '</td>
				<td>£' . $row['prPrice'] . '</td>
				<td>' . $row['odQuantity'] . '</td>
				<td>£' . number_format($totalPerItem, 2) . '</td>
				<td>' . $row['orDeliverDate'] . '</td></tr>';

			$i = $row['orderID'];
		}
	} else {
		echo '<p><center>There are no orders for this customer</center></p>';
	}
}


	mysqli_free_result($results);
	mysqli_close($connection);

	include ("../includes/layouts/footer.php");
?>