<?php
	require_once ("../includes/session.php");
	require_once ("../includes/db_connection.php");
	require_once ("../includes/functions.php");

	$page_title = 'Order confirmed | HyperAV';
	include ("../includes/layouts/header.php");

	// If the user somehow got here without being logged 
	// in as a staff member, redirect them to the index page
	$staffID = get_SESSION_value_or_redirect("staff", "index.php");

	// Gets the cart from the SESSION
	$stockcart = get_SESSION_value_or_redirect("stockcart", "supplierProducts.php");


	// Get the total
	$stOrderTotal = $_SESSION['stOrderTotal'];

	// Get the date
	$storDate = date('Y-m-d');

	// Delivery date not yet known
	$storDeliveryDate = NULL;

	// Create array of the product IDs
	$productIDs = array_keys($stockcart);



	// INSERT INTO the stockOrderDetails table
	for ($i = 0; $i < count($stockcart); $i++) {

		$ID = $productIDs[$i]; 			// Store the product id in a variable to use later
		$stQuantity = $stockcart[$ID];	// Get the cart quantity for each product in the cart

		$query = 'SELECT * 
			FROM hyperAV_products pr 
			JOIN hyperAV_stock st ON pr.prModelNo = st.prModelNo 
			JOIN hyperAV_stockorderdetails sod ON st.stockID = sod.stockID 
			JOIN hyperAV_supplier su ON sod.supplierID = su.supplierID 
			WHERE pr.prModelNo = "' . $ID . '"';
		$results = @mysqli_query($connection, $query);
		$num_rows = mysqli_num_rows($results);

		// echo '<p>Query: ' . $query . '</p>';

		if ($results) {
			if ($num_rows > 0) {
				while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
					$stockID = $row['stockID'];
					$supplierID = $row['supplierID'];

					// Prepare the insert statement
					$query2 = mysqli_prepare($connection, "INSERT INTO hyperAV_stockorderdetails (stockID, supplierID, stOrderDate, stDeliveryDate, stOrderQuantity) VALUES (?, ?, ?, ?, ?)");
					if ($query2 === false) {
						trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($connection)), E_USER_ERROR);
					}
					// Bind the values
					$bind = mysqli_stmt_bind_param($query2, "iissi", $stockID, $supplierID, $storDate, $storDeliveryDate, $stQuantity);
					if ($bind === false) {
						trigger_error('Bind param failed!', E_USER_ERROR);
					}
					// Execute the query
					$exec = mysqli_stmt_execute($query2);
					if ($exec === false) {
						trigger_error('Statement execute failed! ' . htmlspecialchars(mysqli_stmt_error($query2)), E_USER_ERROR);	
					}
				}

				// Delete the cart from the SESSION
				unset($_SESSION['stockcart']);
			}
		}
	}

	mysqli_free_result($results);
	mysqli_close($connection);


	// Provide confirmation message to the user
	echo '<p>Your order has been successfully submitted</p>';
?>