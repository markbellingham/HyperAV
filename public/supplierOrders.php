<?php
require_once ("../includes/session.php");
require_once ("../includes/db_connection.php");
require_once ("../includes/functions.php");

$page_title = 'Supplier Orders | HyperAV';
include ("../includes/layouts/header.php");

if (!isset($_SESSION['staff'])) {
	redirect_to("index.php");
}

// Gets the cart from the SESSION or initialises it if it is not there
if (isset($_SESSION['stockcart'])) {
	$stockcart = $_SESSION['stockcart'];
} else {
	$stockcart = array();
}

// Create SQL statement from the $cart array
if (count($stockcart) > 0) {
	// First get the productIDs from the array
	$cart_keys = array_keys($stockcart);
	$query = 'SELECT * FROM hyperav_products WHERE ';
	// Build up a SELECT  statement from all the items in the array
	for ($i = 0; $i < count($stockcart); $i++) {
		if ($i != 0) {
			$query .= ' OR ';
		}
		$query .= 'prModelNo = "' . $cart_keys[$i] . '"';
	}
} else {
	// If the cart had to be created by this page, a message informs the user
	echo '<p>Your Stock Order basket is empty</p>';
	include ("../includes/layouts/footer.php");
	exit();
}	

// Send the query to the database
$results = @mysqli_query($connection, $query);
$num_rows = mysqli_num_rows($results);

// Display the results (if any)
if ($results) {
	if ($num_rows > 0) {
		$grandTotal = 0;
		//$cart_quantity = array_values($stockcart);
		echo '<p><h3>Your current order</h3></p>';
		echo '<table border="1"><tr><td></td><td><b>Name</b></td><td><b>Price per item</b></td><td><b>Quantity</b></td><td><b>Total per item</b></td><td></td><td></td></tr>';
		while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
			$i = $row['prModelNo'];
			echo '<tr><td><img src="images/' . $row['prName'] . '.jpg" id="product_images"></td>
				<td><a href="selected_product.php?prModelNo=' . $row['prModelNo'] . '">' . $row['prName'] . '</a></td>
				<td>&pound' . $row['prPrice'] . '</td>
				<form action="updateSupplierOrder.php" method="POST">
				<td><input type="number" name="quantity" value="' . $stockcart[$i] . '" style="width: 3em;" min="0"></td>';
				$totalPerItem = (($row['prPrice']) * (int)($stockcart[$i]));
			echo '<td>&pound' . number_format($totalPerItem,2) . '</td>
				<td><input type="hidden" name="prModelNo" value="' . $row['prModelNo'] . '">
				<input type="submit" value="Update"></form></td>';
			echo '<td><form action="delete_from_supplier_order.php" method="POST">
				<input type="hidden" name="prModelNo" value="' . $row['prModelNo'] . '">
				<input type="submit" value="Delete"></form></td></tr>';
				$grandTotal += $totalPerItem;
		}
		echo '<tfoot><td colspan="4">Total</td><td>&pound' . number_format($grandTotal,2) . '</td><td colspan="2"><a href="confirmStockOrder.php">Confirm Order</a></td></tfoot>';
		echo '</table>';
	}
}
?>

<div style="margin-left: 700px">

<?php
if (isset($_SESSION['cuEmail']) && !isset($_SESSION['staff'])) {
	echo '<p><b>Please choose a location for pickup or delivery</b></p>';
	$query2 = "SELECT locationID, loName FROM hyperav_location ORDER BY loName ASC";
	$results2 = @mysqli_query($connection, $query2);
	$num_rows2 = mysqli_num_rows($results2);
	if($results2) {
		if($num_rows2 > 0) {?>
			<select name="location">
				<option>Select Location</option>
				<?php while($option = mysqli_fetch_array($results2, MYSQLI_ASSOC)) { 
					if ($option['locationID'] == $_SESSION['location']) { ?>
						<option selected><?php echo $option['loName']; ?></option> <?php
					} else { ?>
						<option><?php echo $option['loName']; ?></option> <?php
					}
				} ?>
			</select><?php
		}
	}
}
?>

</div>


<?php
	include ("../includes/layouts/footer.php");
?>