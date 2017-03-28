<?php
require_once ("../includes/session.php");
require_once ("../includes/db_connection.php");
require_once ("../includes/functions.php");

$page_title = 'Confirm Order | HyperAV';
include ("../includes/layouts/header.php");

if (!isset($_SESSION['staff'])) {
	redirect_to("index.php");
}

// Gets the cart from the SESSION
if (isset($_SESSION['stockcart'])) {
	$stockcart = $_SESSION['stockcart'];
} else {
	// If the staff somehow got here without making an order, they are redirected to the products page
	redirect_to("supplierOrders.php");
}

// Create SQL statement from the $cart array
if (count($stockcart) > 0) {
	// First get the productIDs from the array
	$cart_keys = array_keys($stockcart);
	$query = 'SELECT * FROM hyperAV_products pr JOIN hyperAV_stock st ON pr.prModelNo = st.prModelNo JOIN hyperAV_stockorderdetails sod ON st.stockID = sod.stockID JOIN hyperAV_supplier su ON sod.supplierID = su.supplierID WHERE ';
	// Build up a SELECT  statement from all the items in the array
	for ($i = 0; $i < count($stockcart); $i++) {
		if ($i != 0) {
			$query .= ' OR ';
		}
		$query .= 'pr.prModelNo = "' . $cart_keys[$i] . '"';
	}
} else {
	echo 'Your basket is empty';
} ?>



<form action="confirmStockOrderSubmit.php" method="POST">

	<p><b>Please choose a location where the stock should be delivered to</b></p>

	<div style="float: left, ">
		<?php
		// Shows a drop down box with all the locations to choose from and the 
		// location where the staff member placing the order is automatically selected
		$query2 = "SELECT locationID, loName FROM hyperAV_location ORDER BY loName ASC";
		$results2 = @mysqli_query($connection, $query2);
		$num_rows2 = mysqli_num_rows($results2);
		if($results2) {
			if($num_rows2 > 0) { ?>
				<select name="location">
					<option>Select Location</option>
					<?php while($option = mysqli_fetch_array($results2, MYSQLI_ASSOC)) { 
						if ($option['locationID'] == $_SESSION['location']) { ?>
							<option selected><?php echo $option['loName']; ?></option><?php
						} else { ?>
							<option><?php echo $option['loName']; ?></option>
				<?php 	} 
				} ?>
				</select><?php
			}
		} ?>
	</div>

	<div id="show_cart">
		<?php
		// Send the query to the database
		$results = @mysqli_query($connection, $query);
		$num_rows = mysqli_num_rows($results);

		//echo $query;

		// Display the results (if any)
		if ($results) {
			if ($num_rows > 0) {
				$grandTotal = 0;
				$cart_quantity = array_values($stockcart);
				echo '<p><h3>Confirm your order</h3></p>';
				echo '<div style="float: left; margin-left: 10px;">';
				echo '<table border="1"><tr><th></th><th><b>Name</b></th><th><b>Supplier</b></th><th><b>Price per item</b></th><th><b>Quantity</b></th><th><b>Total per item</b></th></tr>';
				while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
					$i = $row['prModelNo'];
					echo '<tr><td><img src="images/' . $row['prName'] . '.jpg" id="product_images"></td>
						<td><a href="selected_product.php?prModelNo=' . $row['prModelNo'] . '">' . $row['prName'] . '</a></td>
						<td>' . $row['suName'] . '</td>
						<td>&pound' . $row['prPrice'] . '</td>
						<td>' . $stockcart[$i] . '</td>';
						// Calculate the total cost of each item when multiplied by the quantity
						$totalPerItem = (($row['prPrice']) * (int)($stockcart[$i]));
					echo '<td>&pound' . number_format($totalPerItem,2) . '</td></tr>';
						$grandTotal += $totalPerItem;
						
				}
				echo '<tfoot><td colspan="5"><b>Total</td><td>&pound' . number_format($grandTotal,2) . '</b></td></tfoot>';
				$_SESSION['stOrderTotal'] = $grandTotal;
				
				echo '</table>';
				echo '</div>';
			}
		} ?>
	</div> <!-- ends show_cart -->

	<div style="clear: both">
		<br/>
		<p><input type="submit" value="Confirm Order"></p>
	</div>

</form>


<?php
mysqli_free_result($results);
mysqli_free_result($results2);
mysqli_close($connection);
?>


<?php
	include ("../includes/layouts/footer.php");
?>