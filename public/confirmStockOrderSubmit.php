<?php
require_once ("../includes/session.php");
require_once ("../includes/db_connection.php");
require_once ("../includes/functions.php");

$page_title = 'Order confirmed | HyperAV';
include ("../includes/layouts/header.php");

// If the user somehow got here without being logged 
// in as a staff member, redirect them to the index page
if (!isset($_SESSION['staff'])) {
	redirect_to("index.php");
} else {
	$staffID = $_SESSION['staffID'];
}

// Gets the cart from the SESSION
if (isset($_SESSION['stockcart'])) {
	$stockcart = $_SESSION['stockcart'];
} else {
	// If the user somehow got here without making an order, they are redirected to the products page
	redirect_to("supplierProducts.php");
}



// Get the selected location name from the form
// if (($_POST['location']) == "Select Location") {
// 	redirect_to("confirmStockOrder.php");
// } else {
// 	$location = $_POST['location'];
// 	// Get the locationID from the location name
// 	$query3 = 'SELECT locationID from hyperav_location WHERE loName = "' . $location . '"';
// 	$results3 = @mysqli_query($connection, $query3);
// 	$num_rows3 = mysqli_num_rows($results3);
// 	if ($results3) {
// 		if ($num_rows3 == 1) {
// 			while ($row = mysqli_fetch_array($results3, MYSQLI_ASSOC)) {
// 				$locationID = $row['locationID'];
// 			}
// 		}
// 	}
// }

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

	$query = 'SELECT * FROM hyperav_products pr JOIN hyperav_stock st ON pr.prModelNo = st.prModelNo JOIN hyperav_stockorderdetails sod ON st.stockID = sod.stockID JOIN hyperav_supplier su ON sod.supplierID = su.supplierID WHERE pr.prModelNo = "' . $ID . '"';
	$results = @mysqli_query($connection, $query);
	$num_rows = mysqli_num_rows($results);

	// echo '<p>Query: ' . $query . '</p>';

	if ($results) {
		if ($num_rows > 0) {
			while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
				$stockID = $row['stockID'];
				$supplierID = $row['supplierID'];

				// Prepare the insert statement
				$query2 = mysqli_prepare($connection, "INSERT INTO hyperav_stockorderdetails (stockID, supplierID, stOrderDate, stDeliveryDate, stOrderQuantity) VALUES (?, ?, ?, ?, ?)");
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


// Provide confirmation message to the user
echo '<p>Your order has been successfully submitted</p>';
