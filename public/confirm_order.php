<?php
	require_once ("../includes/session.php");
	require_once ("../includes/db_connection.php");
	require_once ("../includes/functions.php");
	require_once ("../includes/db_functions.php");

	$page_title = 'Confirm Order | HyperAV';
	include ("../includes/layouts/header.php");

	// If the user somehow got here without being logged in, they are redirected to the login page
	if(!isset($_SESSION['cuEmail']) && !isset($_SESSION['staff'])) {
		redirect_to("login_page.php");
	}

	get_SESSION_value_or_redirect("cart", "products.php");


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

	}


	// Send the query to the database
	$results = @mysqli_query($connection, $query);
	$num_rows = mysqli_num_rows($results);
?>
	<!--Display the results (if any) in a form to submit the order to the database-->
	<form action="confirm_order_submit.php" method="POST">
<?php
	if ($results) {
		if ($num_rows > 0) {
			$grandTotal = 0;
?>
			<p><h3>Confirm your order</h3></p>
			<div style="float: left; margin-left: 10px;">
			<table class="results"><tr><th></th><th><b>Name</b></th><th><b>Price per item</b></th><th><b>Quantity</b></th><th><b>Total per item</b></th></tr>
<?php
			while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
				$ID = $row['prModelNo'];
?>
				<tr><td><img src=<?php echo "images/" . $row['prName'] . ".jpg"?> id="product_images"></td>
					<td><a href="selected_product.php?prModelNo="<?php echo $row['prModelNo'] . '">' . $row['prName'] . '</a></td>
					<td>&pound' . $row['prPrice'] . '</td>
					<td>' . $cart[$ID] . '</td>';
				// Calculate the total cost of each item when multiplied by the quantity
				$totalPerItem = (($row['prPrice']) * (int)$cart[$ID]);
				echo '<td>&pound' . number_format($totalPerItem,2) . '</td></tr>';
				$grandTotal += $totalPerItem;
			}
			echo '<tfoot><td colspan="4"><b>Total</td><td><b>&pound' . number_format($grandTotal,2) . '</b></td></tfoot>';
			$_SESSION['grandTotal'] = $grandTotal;
?>
			</table>
			</div>
<?php
			mysqli_free_result($results);
		}
	}
	// Shows the customer information or asks the staff member for the email address,
	// then when the staff member clicks on the confirm button, the customer's address is shown for checking.
?>
	<div style="float: left; margin-left: 50px; >
<?php
	if (isset($_SESSION['cuEmail'])) {
		// Get the customer information from the database
		$query1 = 'SELECT * FROM hyperAV_customer WHERE cuEmail = "' . $_SESSION['cuEmail'] . '"';
		$results1 = @mysqli_query($connection, $query1);
		$num_rows1 = mysqli_num_rows($results1);

		// Get the results (if any)
		if ($results1) {
			if ($num_rows1 > 0) {
?>
				<p><b>Are your details correct?</b></p>
<?php
				while ($row = mysqli_fetch_array($results1, MYSQLI_ASSOC)) {
					$customerID = $row['customerID'];
?>
					<p><?php echo $row['cuFName'] . ' ' . $row['cuLName'] ?></p>
					<p><?php echo $row['cuAddress1'] ?></p>
					<p><?php echo $row['cuAddress2'] ?></p>
					<p><?php echo $row['cuTown'] ?></p>
					<p><?php echo $row['cuPostcode'] ?></p>
<?php				}
			} else {
?>
				<p>We couldn\'t find your details, please check your email address</p>
				<p>Please confirm the customer\'s email address:</p>
				<input type="email" name="cuEmail" required>
<?php
			}

			mysqli_free_result($results1);
		} else {
			// If there wasa a problem with the database query itself, we end up here.
?>
			<h3 class="error">System Error</h3>
			<p class="error">Product data could not be retrieved.</p>
<?php
			//DEBUGGING echo '<p class="error">'.mysqli_error($connection).'</p>';
			//DEBUGGING echo '<p class="error">Query:'. $query . '</p>';
		}
	} else if (isset($_SESSION['staff']) && !isset($_SESSION['cuEmail'])) {
?>
		<p>Please confirm the customer\'s email address:</p>
		<input type="email" name="cuEmail" required>
<?php
	}

	/* If the customer is making the order, provide a dropdown box
	 so that they can choose a location for pickup or delivery
	 If a staff member is placing the order on behalf of the customer,
	 The location is taken from the location the staff member works at. */
?>
	<br />
<?php
	if (isset($_SESSION['cuEmail']) && !isset($_SESSION['staff'])) {
?>
		<p><b>Please choose a location for pickup or delivery</b></p>
<?php
		dropdown_box("loName", "hyperAV_location");
	}

?>
		<p><b>Please select payment type</b></p>
		<select name="payment">
			<option>Credit Card</option>
			<option>Debit Card</option>
			<option>Cash</option>
		</select>

		</div>

		<div style="clear: both">
			<p><input type="submit" value="Confirm Order"></p>
		</div>
	</form>