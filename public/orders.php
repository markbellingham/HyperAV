<?php
require_once ("../includes/session.php");
require_once ("../includes/db_connection.php");
require_once ("../includes/functions.php");

$page_title = 'My Orders | HyperAV';
include ("../includes/layouts/header.php");

// Gets the cart from the SESSION or initialises it if it is not there
if (isset($_SESSION['cart'])) {
	$cart = $_SESSION['cart'];
} else {
	$cart = array();
}

//print_r($_SESSION['cart']);

// Create SQL statement from the $cart array
if (count($cart) > 0) {
	// First get the productIDs from the array
	$cart_keys = array_keys($cart);
	$query = 'SELECT * FROM hyperAV_products WHERE ';
	// Build up a SELECT  statement from all the items in the array
	for ($i = 0; $i < count($cart); $i++) {
		if ($i != 0) {
			$query .= ' OR ';
		}
		$query .= 'prModelNo = "' . $cart_keys[$i] . '"';
	}
} else {
	// If the cart had to be created by this page, a message informs the user
	echo '<p>Your shopping basket is empty</p>';
	include ("../includes/layouts/footer.php");
	exit();
}

// Send the query to the database
$results = @mysqli_query($connection, $query);
$num_rows = mysqli_num_rows($results);

/* Display the results (if any) in a table with update and delete buttons so that the user
 can change quantites or remove an item from their cart. */
if ($results) {
	if ($num_rows > 0) {
		$grandTotal = 0;
		echo '<div id="main">';
		echo '<p><h3>Here are your current orders</h3></p>';
		echo '<table><tr><th></th><th><b>Name</b></th><th><b>Price per item</b></th><th><b>Quantity</b></th><th><b>Total per item</b></th><th></th><th></th></tr>';
		while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
			$ID = $row['prModelNo'];
			echo '<tr><td><img src="images/' . $row['prName'] . '.jpg" id="product_images"></td>
				<td><a href="selected_product.php?prModelNo=' . $row['prModelNo'] . '">' . $row['prName'] . '</a></td>
				<td>&pound' . $row['prPrice'] . '</td>
				<form action="update_order.php" method="POST">
				<td><input type="number" name="quantity" value="' . $cart[$ID] . '" style="width: 3em;" min="1"></td>';
				// Calculate the cost for each item multiplied by the quantity ordered
				$totalPerItem = round((($row['prPrice']) * (int)$cart[$ID]),2);
			echo '<td>&pound' . number_format($totalPerItem,2) . '</td>
				<td><input type="hidden" name="prModelNo" value="' . $row['prModelNo'] . '">
				<input type="submit" value="Update"></td></form>';
			echo '<form action="delete_item_from_order.php" method="POST">
				<td><input type="hidden" name="prModelNo" value="' . $row['prModelNo'] . '">
				<input type="submit" value="Delete"></td></form></tr>';
				$grandTotal += $totalPerItem; // Sum up the items on the list
		}
		echo '<tfoot><td colspan="4"><b>Total</b></td><td><b>&pound' . number_format($grandTotal,2) . '</b></td><td colspan="2"><b><a href="confirm_order.php">Checkout</a></b></td></tfoot>';
		echo '</table>';
		echo '<div>';
	}
}

mysqli_free_result($results);
mysqli_close($connection);

include ("../includes/layouts/footer.php");
?>