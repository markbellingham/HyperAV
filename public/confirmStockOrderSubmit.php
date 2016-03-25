<?php
require_once ("../includes/session.php");
require_once ("../includes/db_connection.php");
require_once ("../includes/functions.php");

$page_title = 'Order confirmed | HyperAV';
include ("../includes/layouts/header.php");

if (!isset($_SESSION['staff'])) {
	redirect_to("index.php");
}

// If the user somehow got here without being logged in, they are redirected to the login page
if(!isset($_SESSION['staff'])) 
{
	redirect_to("login_page.php");
}

// Gets the cart from the SESSION
if (isset($_SESSION['stockcart'])) {
	$cart = $_SESSION['stockcart'];
} else {
	// If the user somehow got here without making an order, they are redirected to the products page
	redirect_to("supplierProducts.php");
}

	// If the customer created the order, their email is present in the SESSION.
	// If a staff member created the order, they get the email address from the customer.
	// Then this page is redirected back so that the staff member can verify the address.
	if (isset($_SESSION['staff']) && !isset($_SESSION['stEmail']))
	{
		if (isset($_POST['stEmail']) && !empty($_POST['stEmail'])) 
		{
			$_SESSION['stEmail'] = $_POST['stEmail'];
			redirect_to("confirmStockOrder.php");
		}
		else 
		{
			echo '<p>You forgot to enter the email address</p>';
			echo '<p><a href="confirmStockOrder.php">Click here to go back</a></p>';
			exit;
		}
	}
	else
	{
	$email = $_SESSION['stEmail'];
	}

// Get the total
$stQuantity = $_SESSION['totalQuantity'];



// Get the staff ID from the database
/*$query1 = 'SELECT supplierID FROM hyperav_supplier WHERE cuEmail = "' . $email . '"';
$results1 = @mysqli_query($connection, $query1);
$num_rows1 = mysqli_num_rows($results1);
if ($results1) {
	if ($num_rows1 == 1) {
		while ($row = mysqli_fetch_array($results1, MYSQLI_ASSOC)) {
			$customerID = $row['customerID'];
		}
	} else {
		echo '<p>Error: There was a problem with the data</p>';
		exit;
	}
} else {
	echo '<p>Error: There was a problem with the database connection';
	//DEBUGGING	 echo '<p class="error">'.mysqli_error($connection).'</p>';
	//DEBUGGING	 echo '<p class="error">Query:'. $query . '</p>';
	exit;
}
*/
// Get the staff ID if it exists or give it a NULL value
if (isset($_SESSION['staff'])) {
	$staffID = $SESSION['staffID'];
} else {
	$staffID = NULL;
}

// INSERT INTO the orders table
// Prepare the insert statement
$query2 = mysqli_prepare($connection, "INSERT INTO hyperav_stock (stockID, prModelNo, locationID, stQuantity) VALUES (?, ?, ?, ?, ?, ?)");
if ($query2 === false) {
	trigger_error('Statement failed! ' . htmlspecialchars(mysqli_error($connection)), E_USER_ERROR);
}
// Bind the values
$bind = mysqli_stmt_bind_param($query2, "iissi", $stockID, $supplierID, $storDate, $StorDeliveryDate, $stQuantity);
if ($bind === false) {
	trigger_error('Bind param failed!', E_USER_ERROR);
}
// Execute the query
$exec = mysqli_stmt_execute($query2);
if ($exec === false) {
	trigger_error('Statement execute failed! ' . htmlspecialchars(mysqli_stmt_error($query2)), E_USER_ERROR);	
}

// STR_TO_DATE('$orDate', '%d-%m-%Y'), $orTotal, STR_TO_DATE('$orDate', '%d-%m-%Y'), '$orPaymentMethod', $customerID, $staffID

// if (mysqli_query($connection, $query2)) {
// 	echo 'insert into hyperav_orders successful';
// } else {
// 	echo '<p>insert into hyperav_orders not successful</p>';
// 	echo '<p>Query2: ' . $query2 . '</p>';
// 	echo '<p>' . mysqli_error($connection) . '</p>';
// }

// Get the automatically generated orderID from the last insert
$orderID = mysqli_insert_id($connection);

// Create arrays from the cart
$productIDs = array_keys($stockcart);
$quantities = array_values($stockcart);

// Get the selected location name
if (($_POST['location']) == "Select Location") {
	redirect_to("confirmStockOrder.php");
} else {
	$location = $_POST['location'];
}

// Get the locationID from the location name
$query3 = 'SELECT locationID from hyperav_location WHERE loName = "' . $location . '"';
$results3 = @mysqli_query($connection, $query3);
$num_rows3 = mysqli_num_rows($results3);
if ($results3) {
	if ($num_rows3 == 1) {
		while ($row = mysqli_fetch_array($results3, MYSQLI_ASSOC)) {
			$locationID = $row['locationID'];
		}
	}
}



// INSERT INTO the orderDetails table
for ($i = 0; $i < count($cart); $i++) {
	// First get the stockID that corresponds to each prModelNo and the location
	$query4 = 'SELECT stockID FROM hyperav_stock WHERE prModelNo = "' . $productIDs[$i] . '" AND locationID = "' . $locationID . '"';
	$results4 = @mysqli_query($connection, $query4);
	$num_rows4 = mysqli_num_rows($results4);
	if ($results4) {
		if ($num_rows4 == 1) {
			while ($row = mysqli_fetch_array($results4, MYSQLI_ASSOC)) {
				$stockID = $row['stockID'];
			}
		}
	}

/*	// Create an INSERT statement for each item in the cart
	$query5 = "INSERT INTO hyperav_orderdetails (orderID, stockID, odQuantity) VALUES ($orderID, $stockID, $quantities[$i])";
	if (mysqli_query($connection, $query5)) {
		echo 'insert into hyperav_orderdetails successful';
	} else {
		echo '<p>insert into hyperav_orderdetails not successful</p>';
		echo '<p>Query5: ' . $query5 . '</p>';
		echo '<p>' . mysqli_error($connection) . '</p>';
}
}
*/
// Provide confirmation message to the user
echo '<p>Your order has been successfully submitted</p>';
